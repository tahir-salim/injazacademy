<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function programs()
    {
        return $this->belongsToMany(Program::class, ProgramCategory::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, UserInterest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status' , true);
    }
}
