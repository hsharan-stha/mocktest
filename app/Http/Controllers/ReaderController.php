<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PurchaseDetail;
use App\Models\QuestionType;
use App\Models\QuestionTypeSubSection;
use App\Models\Book;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ReaderController extends Controller
{

public function index(Request $request, $book_id)
{
    $userId = Auth::id();

    // --- Paid & quota check + atomic decrement on every refresh ---
    // If quantity is already 0 (or no paid purchase), $updated = 0 and we abort.
    $updated = PurchaseDetail::query()
        ->where('book_id', $book_id)
        ->where('user_id', $userId)
        ->whereHas('purchase', fn($q) => $q->where('is_paid', true))
        ->where('quantity', '>', 0)        // guard to never go negative
        ->decrement('quantity', 1);        // single-SQL atomic update

    if ($updated === 0) {
        abort(403, 'Access denied. Your reading quota is finished or payment not found.');
    }

    // Optional: remaining for UI display
    $remaining = PurchaseDetail::query()
        ->where('book_id', $book_id)
        ->where('user_id', $userId)
        ->whereHas('purchase', fn($q) => $q->where('is_paid', true))
        ->value('quantity');

    // --- Fetch questions grouped by question type ---
    $questionTypes = QuestionType::orderBy('order')->get();
    
    $questionsByType = [];
    $allPages = [];
    $questionIndex = 0;
    
    foreach ($questionTypes as $type) {
        // Get all sub-sections for this question type
        $subSections = QuestionTypeSubSection::where('question_type_id', $type->id)
            ->orderBy('order')
            ->get();
        
        // Process pages grouped by sub-section
        $allShuffledPages = [];
        
        // If there are sub-sections, use sub-section counts
        if ($subSections->isNotEmpty()) {
            // Process pages with sub-sections - limit to sub-section count
            foreach ($subSections as $subSection) {
                // Limit query to sub-section count (for NAT Test: only fetch count specified)
                $query = Page::where('book_id', $book_id)
                    ->where('type_id', $type->id)
                    ->where('sub_section_id', $subSection->id)
                    ->inRandomOrder();
                
                // Limit to sub-section count if specified
                if (($subSection->count ?? 0) > 0) {
                    $query->limit($subSection->count);
                }
                
                $subSectionPages = $query->get();
                
                if ($subSectionPages->isNotEmpty()) {
                    // For NAT Test: Use the limited questions directly (one page per sub-section)
                    $shuffledPages = $this->shufflePageOptions($subSectionPages->all(), $type, $questionIndex);
                    $allShuffledPages[] = [
                        'sub_section' => $subSection,
                        'pages' => $shuffledPages,
                    ];
                    $allPages = array_merge($allPages, $shuffledPages);
                }
            }
            
            // Process pages without sub-sections - use question type count
            $pagesWithoutSubSection = Page::where('book_id', $book_id)
                ->where('type_id', $type->id)
                ->whereNull('sub_section_id')
                ->inRandomOrder()
                ->get();
            
            if ($pagesWithoutSubSection->isNotEmpty()) {
                // Chunk by question type count
                $chunkSize = ($type->count ?? 0) > 0 ? $type->count : count($pagesWithoutSubSection);
                $chunks = $pagesWithoutSubSection->chunk($chunkSize);
                
                foreach ($chunks as $chunk) {
                    $shuffledPages = $this->shufflePageOptions($chunk->all(), $type, $questionIndex);
                    $allShuffledPages[] = [
                        'sub_section' => null,
                        'pages' => $shuffledPages,
                    ];
                    $allPages = array_merge($allPages, $shuffledPages);
                }
            }
        } else {
            // No sub-sections - use question type count as primary
            // For SSW: Limit to question type count, don't chunk
            $query = Page::where('book_id', $book_id)
                ->where('type_id', $type->id)
                ->inRandomOrder();
            
            // Limit to question type count if specified
            if (($type->count ?? 0) > 0) {
                $query->limit($type->count);
            }
            
            $typePages = $query->get();
            
            if ($typePages->isNotEmpty()) {
                // For SSW: Don't chunk, just use the limited questions directly
                $shuffledPages = $this->shufflePageOptions($typePages->all(), $type, $questionIndex);
                $allShuffledPages[] = [
                    'sub_section' => null,
                    'pages' => $shuffledPages,
                ];
                $allPages = array_merge($allPages, $shuffledPages);
            }
        }
        
        if (!empty($allShuffledPages)) {
            $questionsByType[] = [
                'type' => $type,
                'sub_sections' => $allShuffledPages,
                'pages' => collect($allShuffledPages)->pluck('pages')->flatten(),
            ];
        }
    }
    
    if (empty($allPages)) {
        abort(404, 'No pages found for this book.');
    }
    
    // Convert to collection for compatibility
    $pages = collect($allPages);

    // --- Load/merge persisted reader session (DB-backed) ---
    $sessionKey = "session_{$userId}_{$book_id}";
    // If your table is actually named `reader-sessions`, keep it. Otherwise prefer `reader_sessions`.
    $record = DB::table('reader-sessions')
        ->where('session_key', $sessionKey)
        ->first();

    $sessionData = [
        'bookId'      => $book_id,
        'currentPage' => 1,
        'bookmarks'   => [],
    ];

    if ($record) {
        $decoded = json_decode($record->session_data, true);
        if (is_array($decoded)) {
            $sessionData = array_merge($sessionData, $decoded);
        }
    }

    // Get book and category
    $book = Book::with('category')->findOrFail($book_id);
    $categoryName = $book->category ? strtolower($book->category->name) : 'ssw';
    
    // Determine which view to use based on category
    $viewName = 'reader';
    if (strpos($categoryName, 'nat') !== false || strpos($categoryName, 'nat test') !== false) {
        $viewName = 'reader';
    }
    
    // Render the appropriate Blade view
    return view($viewName, compact('pages', 'book_id', 'sessionData', 'remaining', 'questionsByType', 'book', 'categoryName'));
}

    public function saveSession(Request $request)
    {
        $request->validate([
            'bookId' => 'required|integer',
            'currentPage' => 'required|integer',
            'bookmarks' => 'nullable|array',
        ]);

        $userId = Auth::id();
        $bookId = $request->bookId;

        $sessionKey = "session_{$userId}_{$bookId}";

        $sessionData = [
            'bookId' => $bookId,
            'currentPage' => $request->currentPage,
            'bookmarks' => $request->bookmarks ?? [],
        ];

        DB::table('reader-sessions')->updateOrInsert(
            ['session_key' => $sessionKey],
            ['session_data' => json_encode($sessionData)]
        );

        return response()->json([
            'message' => 'Session saved successfully',
        ]);
    }

    private function shufflePageOptions($pages, $type, &$questionIndex)
    {
        return collect($pages)->map(function ($page) use (&$questionIndex, $type) {
            // Get all non-empty options
            $options = [];
            
            if (!empty($page->option1)) {
                $options[] = ['text' => $page->option1, 'original' => 1];
            }
            if (!empty($page->option2)) {
                $options[] = ['text' => $page->option2, 'original' => 2];
            }
            if (!empty($page->option3)) {
                $options[] = ['text' => $page->option3, 'original' => 3];
            }
            if (!empty($page->option4)) {
                $options[] = ['text' => $page->option4, 'original' => 4];
            }
            
            // Shuffle options
            shuffle($options);
            
            // Find the new position of the correct answer
            $newCorrectAnswer = null;
            foreach ($options as $index => $option) {
                if ($option['original'] == $page->correct_answer) {
                    $newCorrectAnswer = $index + 1;
                    break;
                }
            }
            
            // Create a new object with shuffled options
            $shuffledPage = clone $page;
            $shuffledPage->shuffled_option1 = $options[0]['text'] ?? '';
            $shuffledPage->shuffled_option2 = $options[1]['text'] ?? '';
            $shuffledPage->shuffled_option3 = $options[2]['text'] ?? '';
            $shuffledPage->shuffled_option4 = $options[3]['text'] ?? '';
            $shuffledPage->shuffled_correct_answer = $newCorrectAnswer ?? $page->correct_answer;
            $shuffledPage->question_index = $questionIndex++;
            $shuffledPage->question_type_name = $type->type;
            
            return $shuffledPage;
        })->all();
    }

    public function startExam(Request $request)
    {
        $request->validate([
            'examinee_name' => 'required|string|max:255',
            'examinee_email' => 'required|email|max:255',
            'examinee_phone' => 'nullable|string|max:255',
            'examinee_notes' => 'nullable|string',
            'book_id' => 'required|exists:books,id',
        ]);

        $examAttempt = ExamAttempt::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'examinee_name' => $request->examinee_name,
            'examinee_email' => $request->examinee_email,
            'examinee_phone' => $request->examinee_phone,
            'examinee_notes' => $request->examinee_notes,
            'started_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'exam_attempt_id' => $examAttempt->id,
        ]);
    }

    public function submitExam(Request $request)
    {
        $request->validate([
            'exam_attempt_id' => 'required|exists:exam_attempts,id',
            'answers' => 'required|array',
            'total_questions' => 'required|integer',
            'correct_answers' => 'required|integer',
            'incorrect_answers' => 'required|integer',
            'score_percentage' => 'required|numeric',
            'time_spent_by_type' => 'nullable|array',
        ]);

        $examAttempt = ExamAttempt::where('id', $request->exam_attempt_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Update exam attempt with results
        $examAttempt->update([
            'completed_at' => now(),
            'total_questions' => $request->total_questions,
            'correct_answers' => $request->correct_answers,
            'incorrect_answers' => $request->incorrect_answers,
            'score_percentage' => $request->score_percentage,
            'time_spent_by_type' => $request->time_spent_by_type,
        ]);

        // Save all answers
        foreach ($request->answers as $answerData) {
            // Recalculate is_correct on backend to ensure accuracy (handle integer/string comparison)
            $selectedAnswer = $answerData['selected_answer'] ? (string)$answerData['selected_answer'] : null;
            $correctAnswer = (string)$answerData['correct_answer'];
            $isCorrect = $selectedAnswer && $selectedAnswer === $correctAnswer;
            
            ExamAnswer::create([
                'exam_attempt_id' => $examAttempt->id,
                'page_id' => $answerData['page_id'],
                'question_type_id' => $answerData['question_type_id'] ?? null,
                'sub_section_id' => $answerData['sub_section_id'] ?? null,
                'question_text' => $answerData['question_text'],
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
                'option1' => $answerData['option1'] ?? null,
                'option2' => $answerData['option2'] ?? null,
                'option3' => $answerData['option3'] ?? null,
                'option4' => $answerData['option4'] ?? null,
                'question_number' => $answerData['question_number'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Exam submitted successfully',
        ]);
    }
}