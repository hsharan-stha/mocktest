<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

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
        'category_id',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class, 'type_id');
    }

    public function subSections()
    {
        return $this->hasMany(QuestionTypeSubSection::class, 'question_type_id')->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
