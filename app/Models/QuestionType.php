<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'count',
        'description',
        'order',
        'typecode',
        'timer',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class, 'type_id');
    }

    public function subSections()
    {
        return $this->hasMany(QuestionTypeSubSection::class, 'question_type_id')->orderBy('order');
    }
}
