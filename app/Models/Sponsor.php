<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;
    public function Programs()
    {
        return $this->belongsToMany(Program::class, ProgramSponsor::class);
    }
}
