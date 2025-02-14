<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    const CACHE_KEY = 'categories-cached-';
    const CACHE_TAGS = ['tags'];

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCachedCategories($page = 1, $limit = 10, $paginate = true)
    {
        $this->removeCache();
        return Cache::tags(self::CACHE_TAGS)->remember(self::CACHE_KEY . $page . ($paginate?'-' . $limit:''), now()->addDays(2), function () use($limit, $paginate) {
            return $this->getCategories($limit, $paginate);
        });
    }

    public function removeCache()
    {
        Cache::tags(self::CACHE_TAGS)->flush();
    }

    public function getCategories($limit, $paginate)
    {
        $query = $this->category
            ->active()
            ->withCount('programs')
            ->orderBy('programs_count', 'desc');
        if($paginate){
            return $query->paginate($limit);
        }
        return $query->limit($limit)->get();
    }
}
