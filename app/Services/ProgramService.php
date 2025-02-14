<?php

namespace App\Services;

use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Setting;
use App\Models\TaskHistory;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class ProgramService
{
    protected $categoryService, $program, $setting;

    public function __construct(CategoryService $categoryService, Program $program, Setting $setting)
    {
        $this->categoryService = $categoryService;
        $this->program = $program;
        $this->setting = $setting;
    }

    public function studentInit($limit)
    {
        return [
            'courses' => Program::publishedPrograms()
                ->latest()
                ->limit($limit)
                ->get(),
            'workshops' => Program::publishedWorkshops()
                ->latest()
                ->limit($limit)
                ->get(),
            'categories' => $this->categoryService->getCachedCategories(1, 10, false),
            'mentors' => User::isMentorUser()
                ->active()
                ->limit(10)
                ->get(),
            'settings' => $this->setting->showInApp()->get()
        ];
    }

    public function mentorInit($limit)
    {
        $programIds = Auth::user()->mentorPrograms()->published()->pluck('programs.id');

        return [
            'program_count' => $programIds->count(),

            'projects_count' => TaskHistory::whereIn('program_id', $programIds)
                ->enrollmentHistory()
                ->unRead()
                ->count(),

            'discussions_count' => TaskHistory::whereIn('program_id', $programIds)
                // ->whereHasMorph('historable' , [Discussion::class, Enrollment::class])
                ->checkHistorable()
                ->discussionHistory()
                ->unRead()
                ->count(),

            'pending_tasks' => TaskHistory::with(['program:id,title', 'historable.user'])
                // ->whereHasMorph('historable' , [Discussion::class, Enrollment::class])
                ->checkHistorable()
                ->whereIn('program_id', $programIds)
                ->unRead()
                ->latest()
                ->paginate($limit),

            'auth_user' => Auth::user(),
            'settings' => $this->setting->showInApp()->get()
        ];
    }

    public function getProgramDetail($program_id, $program_type = 0, $user_type = 0)
    {
        return $this->program
            ->when($program_type == 0, function ($q) {
                $q->with(['chapter.contents' , 'sponsors']);
            })
            ->when($user_type == 0 && $program_type == 0, function ($q) {
                $q->with([
                    'watchHistory' => function ($q) {
                        $q->where('user_id', Auth::id());
                    },
                    'enrollment' => function ($q) {
                        $q->where('user_id', Auth::id());
                    },
                ])

                    ->withCount([
                        'enrollment',
                        // 'myEnrollment as me_enrolled',
                        // 'isFavourite as isFavourite',
                        'studentProject',
                        'parentDiscussions as discussion_count'
                    ])->withCommomAttributesCount();
            })
            ->when($user_type == 0 && $program_type == 1, function ($q) {
                $q->withCommomAttributesCount();
                // ->withCount([
                //     'myEnrollment as me_enrolled',
                //     'isFavourite as isFavourite'
                // ]);
            })
            ->when($user_type == 1 || $program_type == 1, function ($q) {
                $q->withCount(['enrollment', 'parentDiscussions as discussion_count']);
            })
            ->mainMentor()
            ->withTrashed()
            ->find($program_id);
    }


    public function addExtraAttributes($program)
    {

        if(!$program){
            return $program;
        }

        // logic here
        if (!isset($program->me_enrolled) || !$program->me_enrolled) {
            $program->show_enroll = 1;
            return $program;
        }

        $program->loadMissing('myEnrollment');
        $myEnrollment = $program->myEnrollment->first();

        if ($myEnrollment->is_certified) {
            $program->is_certified = 1;
            if (
                $program->me_enrolled
                && $program->allow_discussion
                )
                $program->show_write_post = 1;
            return $program;
        }

        if($program->deleted_at){
            return $program;
        }

        if (
            $program->me_enrolled
            && $program->project_required == 1
            && optional($myEnrollment)->is_all_content_watched == 1
            && !optional($myEnrollment)->project_pass
            && optional($myEnrollment)->project_submitted == 0
        )
            $program->show_submit_task = 1;
        if (
            $program->me_enrolled
            && $program->project_required == 1
            && !optional($myEnrollment)->project_pass
            && optional($myEnrollment)->project_submitted == 1
            && optional($myEnrollment)->review_score != null
        )
            $program->show_re_submit = 1;
        if (
            $program->me_enrolled
            && $program->project_required == 1
            && !optional($myEnrollment)->project_pass
            && optional($myEnrollment)->project_submitted == 1
            && optional($myEnrollment)->review_score == null
        )
            $program->show_pending_marking = 1;
        if (
            $program->me_enrolled
            && $program->quiz_required == 1
            && (($program->project_required == 1 && optional($myEnrollment)->project_pass) || $program->project_required == 0)
            && optional($myEnrollment)->is_all_content_watched == 1
        )
            $program->show_take_quiz = 1;
        if (
            $program->me_enrolled
            && $program->allow_discussion
            && optional($myEnrollment)
        )
            $program->show_write_post = 1;
        if (
            optional($myEnrollment)->is_all_content_watched
            && ($program->quiz_required == 0 || ($program->quiz_required == 1 && $program->test_pass))
            && ($program->project_required == 0 || ($program->project_required == 1 && optional($myEnrollment)->project_pass))
            && !(optional($myEnrollment)->is_certified)
        )
            $program->show_get_certificate = 1;

        return $program;
    }
}
