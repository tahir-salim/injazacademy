<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'title',
        'type',
        'url',
        'data',
        'status',
        'duration',
        'language',
        'chapter_id',
    ];

    const PUBLISHED = 'PUBLISHED';
    const DRAFT = 'DRAFT';
    public static function CONTENT_STATUS()
    {
        return [
            static::PUBLISHED => ucwords(strtolower(str_replace('_', ' ', static::PUBLISHED))),
            static::DRAFT => ucwords(strtolower(str_replace('_', ' ', static::DRAFT))),
        ];
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id')->withTrashed();
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id')->withTrashed();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, WatchContent::class);
    }
}
