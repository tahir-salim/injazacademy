<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;

    protected $guarded = [];
    public const PENDING = 'pending';
    public const COMPLETED = 'completed';

    public function historable()
    {
        return $this->morphTo();
    }

    public function program()
    {
        return $this->belongsTo(Program::class)->withTrashed();
    }

    public function scopeEnrollmentHistory($query){
        return $query->where('historable_type', Enrollment::class);
    }

    public function scopeDiscussionHistory($query){
        return $query->where('historable_type', Discussion::class);
    }

    public function scopeUnRead($query){
        return $query->where('checked', false);
    }

    public function scopeRead($query){
        return $query->where('checked', true);
    }

    public function scopeCheckHistorable($query)
    {
        $query->whereHasMorph('historable' , [Discussion::class, Enrollment::class]);
    }


}
