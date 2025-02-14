<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const NUMBER = 'NUMBER';
    const TEXT = 'TEXT';
    const LONGTEXT = 'LONGTEXT';

    public static function STATES()
    {
        return [
            static::NUMBER => ucwords(strtolower(str_replace('_', ' ', static::NUMBER))),
            static::TEXT => ucwords(strtolower(str_replace('_', ' ', static::TEXT))),
            static::LONGTEXT => ucwords(strtolower(str_replace('_', ' ', static::LONGTEXT))),
        ];
    }

    public function scopeShowInApp($query)
    {
        return $query->where('show_in_app', true);
    }

    public function scopeAndroidStore($query)
    {
        return $query->where('name', 'ANDROID_STORE_LINK');
    }

    public function scopeAppStore($query)
    {
        return $query->where('name', 'IOS_STORE_LINK');
    }
}
