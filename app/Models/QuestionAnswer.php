<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'question_id',
        'answer',
        'order_number',
    ];

    public function testQuestion()
    {
        return $this->belongsTo(TestQuestion::class, 'question_id');
    }
    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, UserAnswer::class, 'answer_id', 'enrollment_id');
    }
    public function CorrectAnswer()
    {
        return $this->hasOne(TestQuestion::class, 'correct_answer');
    }
}
