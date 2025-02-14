<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Follower extends Model
{
    use HasFactory;

    public function followed()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }



    public function scopeWhereByUser($query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
