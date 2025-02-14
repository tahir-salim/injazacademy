<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function tags(Request $request)
    {
        return $this->formatResponse(
            'success',
            'tag-get',
            $this->tagService->getCachedTags()
        );
    }
}
