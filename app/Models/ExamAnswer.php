<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_attempt_id',
        'page_id',
        'question_type_id',
        'sub_section_id',
        'question_text',
        'selected_answer',
        'correct_answer',
        'is_correct',
        'option1',
        'option2',
        'option3',
        'option4',
        'question_number',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function subSection()
    {
        return $this->belongsTo(QuestionTypeSubSection::class, 'sub_section_id');
    }
}
