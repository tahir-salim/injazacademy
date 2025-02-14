<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_VERIFIED = 'VERIFIED';

    protected $fillable = [
        'email', 'token', 'user_id', 'status',
    ];

    public static function GET_STATUSES()
    {
        return [
            static::STATUS_PENDING => ucwords(strtolower(str_replace('_', ' ', static::STATUS_PENDING))),
            static::STATUS_VERIFIED => ucwords(strtolower(str_replace('_', ' ', static::STATUS_VERIFIED))),
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
