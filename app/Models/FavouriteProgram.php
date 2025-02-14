<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteProgram extends Model
{

    use HasFactory;

    protected $fillable = ['user_id', 'program_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
