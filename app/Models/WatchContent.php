<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class WatchContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_id',
        'time_spent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function content()
    {
        return $this->belongsTo(Content::class)->withTrashed();
    }

    public function program()
    {
        return $this->belongsTo(Program::class)->withTrashed();
    }

    public function scopeNotCompleted($query)
    {
        return $query->where('time_spent' , '<' , 100);
    }

    public function scopeCompleted($query)
    {
        return $query->where('time_spent' , '=' , 100);
    }
}
