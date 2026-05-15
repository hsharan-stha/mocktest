<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTypeSubSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type_id',
        'name',
        'order',
        'description',
        'count',
    ];

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'sub_section_id');
    }
}
