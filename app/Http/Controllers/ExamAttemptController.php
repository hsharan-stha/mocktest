<?php

namespace App\Http\Controllers;

use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use Illuminate\Http\Request;

class ExamAttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamAttempt::with(['user', 'book', 'answers'])
            ->orderBy('created_at', 'desc');

        // Filter by book if provided
        if ($request->has('book_id') && $request->book_id) {
            $query->where('book_id', $request->book_id);
        }

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Search by examinee name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('examinee_name', 'like', "%{$search}%")
                  ->orWhere('examinee_email', 'like', "%{$search}%");
            });
        }

        $examAttempts = $query->paginate(20);

        return view('exam-attempts.index', compact('examAttempts'));
    }

    public function show(ExamAttempt $examAttempt)
    {
        $examAttempt->load(['user', 'book', 'answers.page', 'answers.questionType', 'answers.subSection']);
        
        // Group answers by question type
        $answersByType = $examAttempt->answers->groupBy('question_type_id');
        
        return view('exam-attempts.show', compact('examAttempt', 'answersByType'));
    }
}
