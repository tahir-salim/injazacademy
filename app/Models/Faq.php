<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Faq extends Model
{
    use HasFactory;


    const ALL =  'ALL';
    const MENTOR =  'MENTOR';
    const COURSE = 'COURSE';
    const WORKSHOP = 'WORKSHOP';
    const STUDENT = 'STUDENT';
    const ENROLLMENT = 'ENROLLMENT';

    public static function STATES()
    {
        return [
            static::ALL => ucwords(strtolower(str_replace('_', ' ', static::ALL))),
            static::MENTOR => ucwords(strtolower(str_replace('_', ' ', static::MENTOR))),
            static::COURSE => 'Program',
            static::WORKSHOP => ucwords(strtolower(str_replace('_', ' ', static::WORKSHOP))),
            static::STUDENT => ucwords(strtolower(str_replace('_', ' ', static::STUDENT))),
            static::ENROLLMENT => ucwords(strtolower(str_replace('_', ' ', static::ENROLLMENT))),
        ];
    }

    public function faqAnswered()
    {
        return $this->hasMany(FaqAnswered::class);
    }

    public function faqAnsweredByMe()
    {
        return $this->faqAnswered()->where('user_id', Auth::id());
    }




}
