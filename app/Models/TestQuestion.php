<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];

    const MCQ = 'MCQ';
    const TEXT = 'TEXT';

    public static function TYPES()
    {
        return [
            static::MCQ => ucwords(strtolower(str_replace('_', ' ', static::MCQ))),
            static::TEXT => ucwords(strtolower(str_replace('_', ' ', static::TEXT))),
        ];
    }

    protected $fillable = [
        'test_id',
        'question',
        'correct_answer',
        'order_number',
        
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function questionAnswers()
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id')->orderBy('order_number');
    }
    public function answer()
    {
        //return $this->hasOne(QuestionAnswer::class, 'order_number', 'correct_answer');
        return $this->questionAnswers()->whereIn('order_number', [$this->correct_answer])->first();
    }
}
