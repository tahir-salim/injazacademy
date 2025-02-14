<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Discussion extends Model
{
    use HasFactory;

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable')->where('is_like' , true);
    }

    public function taskHistory()
    {
        return $this->morphMany(TaskHistory::class, 'historable');
    }

    public function likedByMe()
    {
        return $this->likes()->where([
            ['user_id' , '=' , Auth::id()],
            ['is_like' , '=' , true]
        ]);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function replies()
    {
        return $this->hasMany(Discussion::class, 'reply_id');
    }

    public function repliesParent()
    {
        return $this->belongsTo(Discussion::class, 'reply_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function myDiscussion()
    {
        return $this->user()->where('id' , Auth::id());
    }

    public function programs()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('discussions.reply_id');
    }
    public function scopeLatestOrder($query)
    {
        return $query->orderBy('is_pinned', 'desc')
        ->orderBy('updated_at', 'desc');
    }

    public function scopeUserInfo($query){
        return $query->with('user')
            ->withCount('myDiscussion');
    }

    public function scopeAppendExtraAttributes($query){
        return $query->userInfo()
            ->withCount('replies', 'likes', 'likedByMe');
    }

    public function scopeWithProgramTitle($query)
    {
        return $query->with(['programs' => function($q){
            $q->select('programs.id','programs.title')
                ->withCount('isEnrolled as me_enrolled');
        }]);
    }
}
