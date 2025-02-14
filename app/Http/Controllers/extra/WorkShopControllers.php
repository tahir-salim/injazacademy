<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Program;

class WorkShopControllers extends Controller
{
    public function workshop()
    {
        $workshop = Program::where('is_workshop', true)->get();
        return $this->formatResponse('success', 'category-get', $workshop, 200);
    }
    public function upcomingWorkshop()
    {
        $workshop = Program::where('is_workshop', true)
            ->whereDate('created_at', '>', now())
            ->paginate(5);
        return $this->formatResponse('success', 'upcomming-workshop-get', $workshop, 200);
    }
}
