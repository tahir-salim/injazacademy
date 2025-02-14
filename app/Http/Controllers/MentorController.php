<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Follower;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    public function mentorFollowers($user_id)
    {
        $follower = Follower::with('follower')
            ->whereByUser($user_id)
            ->withCount('follower')
            ->get();

        return ['status' => 'success', 'data' => $follower];
    }

    public function followMentor($user_id)
    {
        if (Follower::where('follower_id', Auth::id())
            ->whereByUser($user_id)
            ->count()
        ) {

            Follower::where('follower_id', Auth::id())
                ->whereByUser($user_id)
                ->delete();

            return $this->formatResponse('success', 'unfollow-successfully');
        } else {

            $createdId = Follower::insertGetId([
                "user_id" => $user_id,
                "follower_id" => Auth::id(),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

            $follow = [
                "user_id" => $user_id,
                "follower_id" => Auth::id(),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
                "id" => $createdId,
            ];

            return $this->formatResponse('success', 'follow-successfully', $follow);
        }
    }

    public function viewMentor($user_id)
    {
        $mentor = User::with(['mentorPrograms' => function ($q) {
            $q->addProgramExtraParam()
                ->addFavouriteParam()
                ->latest()
                ->courses()
                ->published();
        }])
            ->isFollowing()
            ->followersCount()
            ->where('id', $user_id)
            ->find($user_id);

        return $this->formatResponse('success', 'mentor-profile', $mentor);
    }

    public function viewStudentCoursesProjects(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $limit = $request->query('limit') ?? 10;

        $user = User::whereHas('enrollments', function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        })->first();

        $projectsSubmitted = Enrollment::has('task', '>=', 1)
            ->with('user', 'task', 'program:id,title,program_image')
            ->withCount('projectLikes', 'projectViews')
            ->whereRelation('user', 'id', $request->user_id)
            ->paginate($limit);
        $coursesCompleted = Enrollment::with(['program' => function ($q) {
            $q->with('mentors')
                ->withCount('enrollment', 'chapter');
        }])
            ->where('is_certified', true)
            ->whereRelation('user', 'id', $request->user_id)
            ->paginate($limit);

        return $this->formatResponse('success', 'student-profile', [
            'user_profile' => $user,
            'projects_submitted' => $projectsSubmitted,
            'courses_completed' => $coursesCompleted
        ]);
    }

    public function searchMentor(Request $request)
    {
        $results = User::search($request->query('search'))
            ->isMentorUser()
            ->active()
            ->isFollowing()
            ->paginate(10);

        return $this->formatResponse('success', 'searched-mentors', $results);
    }
}
