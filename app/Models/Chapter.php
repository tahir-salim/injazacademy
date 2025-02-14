<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'title',
        'sub_title',
        'body',
        'order_number',
        'program_id',
        'promo_video',
        'status',
        'program_id',
    ];

    const PUBLISHED = 'published';
    const DRAFT = 'draft';
    public static function CHAPTER_STATUS()
    {
        return [
            static::PUBLISHED => ucwords(strtolower(str_replace('_', ' ', static::PUBLISHED))),
            static::DRAFT => ucwords(strtolower(str_replace('_', ' ', static::DRAFT))),
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id')->withTrashed();
    }
    public function contents()
    {
        return $this->hasMany(Content::class, 'chapter_id')->orderBy('order_number');
    }
}
