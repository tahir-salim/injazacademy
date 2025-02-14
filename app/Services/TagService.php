<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class TagService {
    const TAG_CACHE_KEY = 'tags-cached';

    protected $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function getCachedTags(){
        return Cache::remember(self::TAG_CACHE_KEY, now()->addDays(7), function(){
            return $this->getTags();
        });
    }

    public function removeCache(){
        Cache::forget(self::TAG_CACHE_KEY);
    }

    public function getTags(){
        return $this->tag->active()->get();
    }
}
