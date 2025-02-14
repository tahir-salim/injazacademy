<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $guarded = [];


    protected $fillable = [
        'title',
        'sub_title',
        'body',
        'program_id',
        'total_marks',
        'passing_criteria'
    ];

    public function testQuestions()
    {
        return $this->hasMany(TestQuestion::class, 'test_id')->orderBy('order_number');
    }

    public function program()
    {
        return $this->belongsTo(Program::class)->withTrashed();
    }

}
