<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedCategoryId = $request->query('category_id');

        if ($request->has('category_id')) {
            if ($selectedCategoryId === '') {
                session()->forget('question_types_category_id');
            } else {
                session(['question_types_category_id' => $selectedCategoryId]);
            }
        } elseif (session()->has('question_types_category_id')) {
            $selectedCategoryId = session('question_types_category_id');
        }

        $questionTypes = QuestionType::with('category')
            ->when($selectedCategoryId, function ($query) use ($selectedCategoryId) {
                $query->where('category_id', $selectedCategoryId);
            })
            ->orderBy('order')
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('question-types.index', compact('questionTypes', 'categories', 'selectedCategoryId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('question-types.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'count' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'typecode' => 'required|string|max:255|unique:question_types,typecode',
            'timer' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        QuestionType::create($request->all());

        $redirectParams = [];
        if (session()->has('question_types_category_id')) {
            $redirectParams['category_id'] = session('question_types_category_id');
        }

        return redirect()->route('question-types.index', $redirectParams)
            ->with('success', 'Question type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionType  $questionType
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionType $questionType)
    {
        return view('question-types.show', compact('questionType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionType  $questionType
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionType $questionType)
    {
        $categories = Category::orderBy('name')->get();
        return view('question-types.edit', compact('questionType', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionType  $questionType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionType $questionType)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'count' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'typecode' => 'required|string|max:255|unique:question_types,typecode,' . $questionType->id,
            'timer' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $questionType->update($request->all());

        $redirectParams = [];
        if (session()->has('question_types_category_id')) {
            $redirectParams['category_id'] = session('question_types_category_id');
        }

        return redirect()->route('question-types.index', $redirectParams)
            ->with('success', 'Question type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionType  $questionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionType $questionType)
    {
        $questionType->delete();

        $redirectParams = [];
        if (session()->has('question_types_category_id')) {
            $redirectParams['category_id'] = session('question_types_category_id');
        }

        return redirect()->route('question-types.index', $redirectParams)
            ->with('success', 'Question type deleted successfully.');
    }
}
