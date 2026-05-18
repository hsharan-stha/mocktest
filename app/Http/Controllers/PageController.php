<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Page;
use App\Models\QuestionType;
use App\Models\QuestionTypeSubSection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;



class PageController extends Controller
{
    public function index(Book $book)
    {
        $pages = $book->pages()->with('questionType')->orderBy('pageno')->get();
        $questionTypes = QuestionType::orderBy('order')->get();
        $categories = Category::orderBy('name')->get();

        return view('pages.index', compact('book', 'pages', 'questionTypes', 'categories'));
    }


    public function edit(Page $page)
    {
        $book = $page->book;
        $questionTypes = QuestionType::orderBy('order')->get();
        $categories = Category::orderBy('name')->get();
        
        // Get previous and next pages for navigation
        $previousPage = $book->pages()
            ->where('pageno', '<', $page->pageno)
            ->orderBy('pageno', 'desc')
            ->first();
        
        $nextPage = $book->pages()
            ->where('pageno', '>', $page->pageno)
            ->orderBy('pageno', 'asc')
            ->first();
        
        return view('pages.edit', compact('page', 'book', 'questionTypes', 'categories', 'previousPage', 'nextPage'));
    }

    public function update(Request $request, Page $page)
    {
            $request->validate([
                'question' => 'required|string',
                'option1' => 'required|string',
                'option2' => 'required|string',
                'option3' => 'required|string',
                'option4' => 'required|string',
                'correct_answer' => 'required|in:1,2,3,4',
                'type_id' => 'nullable|exists:question_types,id',
                'sub_section_id' => 'nullable|exists:question_type_sub_sections,id',
                'page_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'page_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            ]);

        $book = $page->book;
        $bookName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $book->name);
        $destinationPath = public_path('images/' . $bookName);
        
        // Ensure directories exist
        if (!file_exists($destinationPath . '/pages')) {
            mkdir($destinationPath . '/pages', 0755, true);
        }
        if (!file_exists($destinationPath . '/audio')) {
            mkdir($destinationPath . '/audio', 0755, true);
        }

        // Update basic fields
        $page->question = $request->question;
        $page->option1 = $request->option1;
        $page->option2 = $request->option2;
        $page->option3 = $request->option3;
        $page->option4 = $request->option4;
        $page->correct_answer = $request->correct_answer;
        $page->type_id = $request->type_id;
        $page->sub_section_id = $request->sub_section_id;

        // Handle image upload (only if new image is provided)
        if ($request->hasFile('page_image')) {
            // Delete old image if exists
            if ($page->page_image && file_exists(public_path($page->page_image))) {
                unlink(public_path($page->page_image));
            }
            
            $image = $request->file('page_image');
            $imageFilename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $image->getClientOriginalName());
            $image->move($destinationPath . '/pages', $imageFilename);
            $page->page_image = 'images/' . $bookName . '/pages/' . $imageFilename;
        }

        // Handle audio upload (only if new audio is provided)
        if ($request->hasFile('page_audio')) {
            // Delete old audio if exists
            if ($page->page_html && file_exists(public_path($page->page_html))) {
                unlink(public_path($page->page_html));
            }
            
            $audio = $request->file('page_audio');
            $audioFilename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $audio->getClientOriginalName());
            $audio->move($destinationPath . '/audio', $audioFilename);
            $page->page_html = 'images/' . $bookName . '/audio/' . $audioFilename;
        }

        // Handle image removal checkbox
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($page->page_image && file_exists(public_path($page->page_image))) {
                unlink(public_path($page->page_image));
            }
            $page->page_image = null;
        }

        // Handle audio removal checkbox
        if ($request->has('remove_audio') && $request->remove_audio == '1') {
            if ($page->page_html && file_exists(public_path($page->page_html))) {
                unlink(public_path($page->page_html));
            }
            $page->page_html = null;
        }

        $page->save();

        return redirect()->route('pages.edit', $page)->with('success', 'Question updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully.');
    }

    public function store(Request $request)
    {
        // Check if this is a question form submission or bulk page upload
        if ($request->has('question')) {
            // Handle single question form submission
            $request->validate([
                'book_id' => 'required|exists:books,id',
                'question' => 'required|string',
                'option1' => 'required|string',
                'option2' => 'required|string',
                'option3' => 'required|string',
                'option4' => 'required|string',
                'correct_answer' => 'required|in:1,2,3,4',
                'type_id' => 'nullable|exists:question_types,id',
                'sub_section_id' => 'nullable|exists:question_type_sub_sections,id',
                'page_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'page_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240', // 10MB max
            ]);

            $book = Book::findOrFail($request->book_id);
            $bookName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $book->name);
            $destinationPath = public_path('images/' . $bookName);
            
            // Ensure directories exist
            if (!file_exists($destinationPath . '/pages')) {
                mkdir($destinationPath . '/pages', 0755, true);
            }
            if (!file_exists($destinationPath . '/audio')) {
                mkdir($destinationPath . '/audio', 0755, true);
            }

            // Get next page number
            $maxPageNo = Page::where('book_id', $book->id)->max('pageno') ?? 0;
            $nextPageNo = $maxPageNo + 1;

            $pageData = [
                'book_id' => $book->id,
                'title' => 'Question ' . $nextPageNo,
                'pageno' => $nextPageNo,
                'question' => $request->question,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option3' => $request->option3,
                'option4' => $request->option4,
                'correct_answer' => $request->correct_answer,
                'type_id' => $request->type_id,
                'sub_section_id' => $request->sub_section_id,
            ];

            // Handle image upload
            if ($request->hasFile('page_image')) {
                $image = $request->file('page_image');
                $imageFilename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $image->getClientOriginalName());
                $image->move($destinationPath . '/pages', $imageFilename);
                $pageData['page_image'] = 'images/' . $bookName . '/pages/' . $imageFilename;
            }

            // Handle audio upload (store path in page_html for now, or create audio column)
            if ($request->hasFile('page_audio')) {
                $audio = $request->file('page_audio');
                $audioFilename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $audio->getClientOriginalName());
                $audio->move($destinationPath . '/audio', $audioFilename);
                $pageData['page_html'] = 'images/' . $bookName . '/audio/' . $audioFilename; // Using page_html for audio path
            }

            Page::create($pageData);

            return redirect()->back()->with('success', 'Question added successfully!');
        }

        // Handle bulk page image upload (existing functionality)
        $book = Book::findOrFail($request->book_id);
        $destinationPath = public_path('images/' . $book->name);

        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $pageImage) {
                $originalName = $pageImage->getClientOriginalName();
                $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
                $pageImage->move($destinationPath . '/pages', $filename);
                $imagePath = 'images/' . $book->name . '/pages/' . $filename;

                $book->pages()->create([
                    'page_image' => $imagePath,
                    'title' => '',
                    'pageno' => $baseName,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pages inserted successfully.');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'book_id' => 'required|exists:books,id',
        ]);

        $bookId = $request->book_id;

        // Load existing highest pageno for the book
        $maxPageNo = Page::where('book_id', $bookId)->max('pageno') ?? 0;
        $startPageNo = $maxPageNo + 1;
        $file = $request->file('excel_file');
        // dd($file);
        $data = app(Excel::class)->toCollection(null, $file);
       

        // Assuming the first sheet
        $dataRows = $data[0];

        foreach ($dataRows as $index => $row) {
            // Skip header row, assuming first row is header
            if ($index === 0)
                continue;

            // Assuming Excel columns:
            // 0 => question
            // 1 => option1
            // 2 => option2
            // 3 => option3
            // 4 => option4
            // 5 => correct_answer

            if (count($row) < 6) {
                continue; // Skip if incomplete row
            }

            Page::create([
                'book_id' => $bookId,
                'name' => null, // or set if you want
                'title' => 'Question ' . ($startPageNo + $index - 1), // example title
                'pageno' => $startPageNo + $index - 1,
                'page_image' => null,
                'page_html' => null,
                'question' => $row[0],
                'option1' => $row[1],
                'option2' => $row[2],
                'option3' => $row[3],
                'option4' => $row[4],
                'correct_answer' => $row[5],
            ]);
        }

        return redirect()->back()->with('success', 'Questions imported successfully!');
    }

    /**
     * Get sub-sections for a question type (AJAX endpoint)
     */
    public function getSubSections(Request $request)
    {
        $questionTypeId = $request->query('question_type_id');
        
        if (!$questionTypeId) {
            return response()->json([]);
        }

        $subSections = QuestionTypeSubSection::where('question_type_id', $questionTypeId)
            ->orderBy('order')
            ->get(['id', 'name', 'order']);

        return response()->json($subSections);
    }
}
