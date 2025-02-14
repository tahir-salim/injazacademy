<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model
{
    use HasFactory;
    protected $fillable = ['action_by'];
    public $afterCommit = true;

    const APPROVED = 'APPROVED';
    const PENDING = 'PENDING';
    const REJECTED = 'REJECTED';

    public static function mentorStatus()
    {
        return [
            static::APPROVED => ucwords(strtolower(str_replace('_', ' ', static::APPROVED))),
            static::PENDING => ucwords(strtolower(str_replace('_', ' ', static::PENDING))),
            static::REJECTED => ucwords(strtolower(str_replace('_', ' ', static::REJECTED))),
        ];
    }
    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email')->withTrashed();
    }

}
