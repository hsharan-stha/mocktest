<?php

namespace App\Http\Controllers;

use App\Models\QuestionTypeSubSection;
use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeSubSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $questionTypeId = $request->query('question_type_id');
        
        if ($questionTypeId) {
            $subSections = QuestionTypeSubSection::where('question_type_id', $questionTypeId)
                ->with('questionType')
                ->orderBy('order')
                ->get();
            $questionType = QuestionType::findOrFail($questionTypeId);
        } else {
            $subSections = QuestionTypeSubSection::with('questionType')
                ->orderBy('question_type_id')
                ->orderBy('order')
                ->get();
            $questionType = null;
        }
        
        return view('question-type-sub-sections.index', compact('subSections', 'questionType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $questionTypeId = $request->query('question_type_id');
        $questionTypes = QuestionType::orderBy('order')->get();
        
        return view('question-type-sub-sections.create', compact('questionTypes', 'questionTypeId'));
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
            'question_type_id' => 'required|exists:question_types,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'count' => 'nullable|integer|min:0',
        ]);

        QuestionTypeSubSection::create($request->all());

        return redirect()->route('question-type-sub-sections.index', ['question_type_id' => $request->question_type_id])
            ->with('success', 'Sub-section created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionTypeSubSection  $questionTypeSubSection
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionTypeSubSection $questionTypeSubSection)
    {
        $questionTypeSubSection->load('questionType', 'pages');
        return view('question-type-sub-sections.show', compact('questionTypeSubSection'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionTypeSubSection  $questionTypeSubSection
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionTypeSubSection $questionTypeSubSection)
    {
        $questionTypes = QuestionType::orderBy('order')->get();
        return view('question-type-sub-sections.edit', compact('questionTypeSubSection', 'questionTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionTypeSubSection  $questionTypeSubSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionTypeSubSection $questionTypeSubSection)
    {
        $request->validate([
            'question_type_id' => 'required|exists:question_types,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'count' => 'nullable|integer|min:0',
        ]);

        $questionTypeSubSection->update($request->all());

        return redirect()->route('question-type-sub-sections.index', ['question_type_id' => $request->question_type_id])
            ->with('success', 'Sub-section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionTypeSubSection  $questionTypeSubSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionTypeSubSection $questionTypeSubSection)
    {
        $questionTypeId = $questionTypeSubSection->question_type_id;
        $questionTypeSubSection->delete();

        return redirect()->route('question-type-sub-sections.index', ['question_type_id' => $questionTypeId])
            ->with('success', 'Sub-section deleted successfully.');
    }
}
