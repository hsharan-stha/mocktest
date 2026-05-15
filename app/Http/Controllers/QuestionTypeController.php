<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questionTypes = QuestionType::orderBy('order')->get();
        return view('question-types.index', compact('questionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question-types.create');
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
        ]);

        QuestionType::create($request->all());

        return redirect()->route('question-types.index')
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
        return view('question-types.edit', compact('questionType'));
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
        ]);

        $questionType->update($request->all());

        return redirect()->route('question-types.index')
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

        return redirect()->route('question-types.index')
            ->with('success', 'Question type deleted successfully.');
    }
}
