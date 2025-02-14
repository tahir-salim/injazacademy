<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramMentor extends Model
{
    use HasFactory;
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const REJECTED = 'REJECTED';

    public static function STATES()
    {
        return [
            static::PENDING => ucwords(strtolower(str_replace('_', ' ', static::PENDING))),
            static::APPROVED => ucwords(strtolower(str_replace('_', ' ', static::APPROVED))),
            static::REJECTED => ucwords(strtolower(str_replace('_', ' ', static::REJECTED))),
        ];
    }

    public function mentor()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
