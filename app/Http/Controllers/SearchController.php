<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->query('search');

        $tagIds = $request->query('tag');

        $programs = Program::when(isset($tagIds) && count($tagIds) > 0, function ($q) use ($tagIds) {
                $q->whereTags($tagIds);
            })
            ->publishedPrograms()
            ->search($search, true)
            ->withCount('chapter')
            ->latest()
            ->limit(5)
            ->get();

        $workshops = Program::when(isset($tagIds) && count($tagIds) > 0, function ($q) use ($tagIds) {
                $q->whereTags($tagIds);
            })
            ->publishedWorkshops()
            ->search($search,true)
            ->latest()
            ->limit(5)
            ->get();

        $mentors = User::search($search)
            ->isMentorUser()
            ->active()
            ->withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->limit(5)
            ->get();

        return $this->formatResponse(
            'success',
            'search-data',
            [
                'mentors' => $mentors,
                'programs' => $programs,
                'workshops' => $workshops,
            ]
        );
    }

}
