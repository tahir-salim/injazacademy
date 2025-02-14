<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{
    use HasFactory;
    const TASK = "TASK";
    const DISCUSSION = "DISCUSSION";

    public function likeable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function scopeAuthUserLikes($query){
        $query->where('user_id', Auth::id());
    }

}
