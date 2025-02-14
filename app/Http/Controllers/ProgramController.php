<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserInterest;
use App\Services\ProgramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    protected $programService;

    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    public function init(Request $request)
    {
        $limit = $request->query('limit') ?: 10;

        if (Auth::user()->isStudent()) {
            return $this->formatResponse('success', 'init', $this->programService->studentInit($limit));
        }

        return $this->formatResponse('success', 'init', $this->programService->mentorInit(5));
    }

    public function index(Request $request)
    {
        $is_workshop = $request->query('is_workshop') ?: 0;

        $programs = Program::whereCategory($request->query('category_id'))
            ->whereTagFilter($request->query('tag') ?: null)
            ->search($request->query('search'))
            ->when($is_workshop == 1, function ($q) {
                $q->publishedWorkshops();
            })
            ->when($is_workshop == 0, function ($q) {
                $q->publishedPrograms();
            })
            ->paginate($request->limit ?? 10);

        return $this->formatResponse(
            'success',
            'search-data',
            $programs
        );
    }

    public function explore(Request $request)
    {
        $trendingWorkshops = Program::publishedWorkshops()
            ->orderBy('live_date_time', 'asc')
            ->limit(10)
            ->get();

        $topCourses = Program::publishedPrograms()
            ->orderBy('enrollment_count', 'desc')
            ->orderBy('favourited_users_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return $this->formatResponse(
            'success',
            'search-data',
            [
                'trending-workshops' => $trendingWorkshops,
                'top-courses' => $topCourses,
            ]
        );
    }

    public function recommendedPrograms(Request $request)
    {
        $typeId = $request->query('type_id') ?: 'courses';
        $search = $request->query('search') ?? null;


        if (!in_array($typeId, ['workshops', 'courses'])) {
            return $this->formatResponse('error', 'type-not-exists');
        }

        $age = Carbon::parse(Auth::user()->dob)->age;
        $tagIds = UserInterest::where('user_id', Auth::id())->pluck('tag_id');

        $recommendedPrograms = Program::whereTags($tagIds)
            ->search($search)
            ->whereAge($age)
            ->whereDoesntHave('myEnrollment')
            ->when($typeId == 'workshops', function ($q) {
                $q->publishedWorkshops();
            })
            ->when($typeId == 'courses', function ($q) {
                $q->publishedPrograms();
            })
            ->latest()
            ->paginate($request->limit ?? 10);

        $recommendedPrograms = $recommendedPrograms->toArray();

        if(count($recommendedPrograms['data']) == 0 && !$search && (!$request->page || $request->page == 1))
        {
            $recommendedPrograms['data'] = Program::inRandomOrder()
                                            ->whereDoesntHave('myEnrollment')
                                            ->when($typeId == 'workshops', function ($q) {
                                                $q->publishedWorkshops();
                                            })
                                            ->when($typeId == 'courses', function ($q) {
                                                $q->publishedPrograms();
                                            })
                                            ->latest()
                                            ->limit(6)
                                            ->get()
                                            ->toArray();
        }

        return $this->formatResponse('success', $typeId == 'workshops' ? 'all-workshops-get' : 'all-courses-get', $recommendedPrograms, 200);
    }

    public function resumeCourses(Request $request)
    {
        $search = $request->query('search') ?? null;
        $programs = Program::whereHas('myEnrollment', function ($q) {
                $q->where('is_all_content_watched', false);
            })
            ->search($search)
            ->publishedPrograms()
            ->paginate($request->limit ?? 10);

        return $this->formatResponse('success', 'get-resume-courses', [
            'resumeCourses' => $programs,
        ], 200);
    }

    public function show(Request $request, $program_id)
    {

        // $platform_type = $request->query('platform_type');
        $userType = $request->query('user_type') ?? 0;
        $program_type = $request->query('program_type') ?? 0;


        // if($platform_type == 'android')
        // {
        //     $setting = Setting::androidStore()->first();
        //     return redirect()->away(optional($setting)->value ?? env('APP_URL'));
        // }
        // else if($platform_type == 'ios')
        // {
        //     $setting = Setting::appStore()->first();
        //     return redirect()->away(optional($setting)->value ?? env('APP_URL'));
        // }

        if (!in_array($userType, [0, 1])) {
            return $this->formatResponse('error', 'unknown user type');
        }

        $program = $this->programService->getProgramDetail($program_id);

        // $program = Program::when($program_type == 0, function ($q) {
        //     $q->with('chapter.contents');
        // })
        //     ->when($user_type == 0 && $program_type == 0, function ($q) {
        //         $q->with([
        //             'mentors' => function ($q) {
        //                 $q->isFollowing();
        //             },
        //             'watchHistory' => function ($q) {
        //                 $q->where('user_id', Auth::id());
        //             },
        //             'enrollment' => function ($q) {
        //                 $q->where('user_id', Auth::id());
        //             },
        //         ])
        //             ->withCount([
        //                 'enrollment as total_enrolled',
        //                 'myEnrollment as me_enrolled',
        //                 'studentProject',
        //                 'parentDiscussions as discussion_count',
        //                 'isFavourite as isFavourite'
        //             ]);
        //     })
        //     ->when($user_type == 0 && $program_type == 1, function ($q) {
        //         $q->withCount([
        //             'myEnrollment as me_enrolled',
        //             'isFavourite as isFavourite'
        //             ])
        //             ->with([
        //                 'mentors' => function ($q) {
        //                     $q->isFollowing();
        //                 },
        //             ]);
        //     })
        //     ->when($user_type == 1 || $program_type == 1, function ($q) {
        //         $q->withCount(['enrollment', 'parentDiscussions as discussion_count']);
        //     })
        //     ->find($program_id);

        if($userType == 0)
        {
            $program = $this->programService->addExtraAttributes($program);
        }

        if($program_type == 1)
            $program->append('remaining_time');


        return $this->formatResponse('success', 'program-data', [
            'program' => $program,
        ]);
    }

    public function shareProgram(Request $request, $program_id)
    {


        preg_match("/iPhone|Android|iPad|iPod|webOS/", $_SERVER['HTTP_USER_AGENT'], $matches);

        $os = current($matches);

        switch ($os)

        {
            case 'iPhone':
                $setting = Setting::appStore()->first();
                return redirect()->away(optional($setting)->value ?? env('APP_URL'));
                break;
            case 'Android':
                $setting = Setting::androidStore()->first();
                return redirect()->away(optional($setting)->value ?? env('APP_URL'));
                break;

        }



        abort(404, 'Not Found');
    }

}
