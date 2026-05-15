<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'examinee_name',
        'examinee_email',
        'examinee_phone',
        'examinee_notes',
        'started_at',
        'completed_at',
        'total_questions',
        'correct_answers',
        'incorrect_answers',
        'score_percentage',
        'time_spent_by_type',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent_by_type' => 'array',
        'score_percentage' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
