<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    const ALL_USERS =  'ALL_USERS';
    const SPECIFIC_USER = 'SPECIFIC_USER';
    const ALL_MENTORS = 'ALL_MENTORS';
    const ALL_STUDENTS = 'ALL_STUDENTS';
    const DISCUSSION = 'DISCUSSION';
    const TASK = 'TASK';
    const PROGRAM = 'PROGRAM';

    public static function STATES()
    {
        return [
            static::ALL_USERS => ucwords(strtolower(str_replace('_', ' ', static::ALL_USERS))),
            static::ALL_MENTORS => ucwords(strtolower(str_replace('_', ' ', static::ALL_MENTORS))),
            static::ALL_STUDENTS => ucwords(strtolower(str_replace('_', ' ', static::ALL_STUDENTS))),
        ];
    }

    public static function NOTIFICATION_TYPE(){
        return [
            static::ALL_USERS => ucwords(strtolower(str_replace('_', ' ', static::ALL_USERS ))),
            static::SPECIFIC_USER => ucwords(strtolower(str_replace('_', ' ', static::SPECIFIC_USER))),
            // static::ALL_MENTORS => ucwords(strtolower(str_replace('_', ' ', static::ALL_MENTORS))),
            // static::ALL_STUDENTS => ucwords(strtolower(str_replace('_', ' ', static::ALL_STUDENTS))),
        ];
    }

    protected $fillable = [
        'title', 'body', 'notification_to', 'user_id', 'event_id', 'event'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id')->withTrashed();
    }

    public function related()
    {
        return $this->belongsTo(\App\Models\User::class)->withTrashed();
    }
}
