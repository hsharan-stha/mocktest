<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [  'book_id', 
    'name', 
    'title', 
    'pageno', 
    'page_image', 
    'page_html',
    'question',
    'option1',
    'option2',
    'option3',
    'option4',
    'correct_answer',
    'type_id',
    'sub_section_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'type_id');
    }

    public function subSection()
    {
        return $this->belongsTo(QuestionTypeSubSection::class, 'sub_section_id');
    }
}
