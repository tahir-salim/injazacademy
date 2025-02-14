<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        return $this->formatResponse(
            'success',
            'category-get',
            $this->categoryService->getCachedCategories($request->page?:1, $request->limit?:10)
        );
    }
}
