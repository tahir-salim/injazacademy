<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Like;
use App\Models\Task;
use App\Models\UserTaskView;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function __construct()
    {}

    public function uploadTaskFile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'file' => 'required',
            'program_id' => 'required',
            'project_name' => 'required',
            'project_submission_no' => 'required'
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }


        $enrollment = Enrollment::where('program_id', $request->program_id)->where('user_id', Auth::id())
        ->first();

        if(!$enrollment){
            return $this->formatResponse('error', 'could-not-upload-file');
        }

        $path = Storage::disk('s3')->put('files/tasks', new File($request->file));

        if ($path) {

            $task = new Task();
            $task->enrollment_id = $enrollment->id;
            $task->program_id = $request->program_id;
            $task->views_count = 0;
            $task->show_in_program = false;
            $task->task_image = $path;
            $task->file = $request->file('file')->getClientOriginalName();;
            $task->identifier_no = $request->project_submission_no;
            $task->save();

            if($enrollment->project_submission_no != $request->project_submission_no){
                $enrollment->project_submitted = true;
                $enrollment->project_name = $request->project_name;
                $enrollment->review_score = null;
                $enrollment->project_submission_no = $request->project_submission_no;
                $enrollment->save();
            }

        } else {
            return $this->formatResponse('error', 'could-not-upload-file');
        }

        return $this->formatResponse(
            'success',
            'task-inserted',
            $enrollment->loadMissing('user', 'task')->loadCount('projectLikes' , 'projectLikedByMe' , 'projectViews' , 'projectViewedByMe'),
            200
        );
    }

    public function markTask(Request $request, $task_id)
    {

        $validate = Validator::make($request->all(), [
            'marks' => 'required',
            'note' => ''
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $enrollment = Enrollment::withProgramTitle()
            ->find($task_id);
        if ($enrollment) {
            $enrollment->review_score = $request->marks;
            $enrollment->save();
            $enrollment->task()
                ->where('identifier_no',$enrollment->project_submission_no)
                ->update([
                    'review' => $request->note,
                    'score' => $enrollment->review_score
                ]);
        } else {
            return $this->formatResponse('error', 'task-does-not-exists');
        }
        return $this->formatResponse('success', 'task-assigned-marks');
    }

    public function taskAll(Request $request, $program_id)
    {
        $limit = $request->query('limit') ?: 10;
        $user_type = $request->query('user_type') ?? 0;

        if (!in_array($user_type, [0, 1])) {
            return $this->formatResponse(
                'error',
                'user-type-not-exists'
            );
        }

        $taskAll = null;
        if ($user_type == 0)
        {
            $taskAll = Enrollment::whereRelation('task', 'program_id', $program_id)
                // ->with('task', 'user','program:id,title,sub_title,program_image')
                ->withAppends()
                ->withCount('projectLikes', 'projectLikedByMe', 'projectViews', 'projectViewedByMe')
                ->orderBy('updated_at', 'desc')
                ->paginate($limit);
        }

        else if ($user_type == 1) {
            $taskPending = Enrollment::whereRelation('task', 'program_id', $program_id)
                ->whereNull('review_score')
                ->withAppends()
                // ->with('task', 'user', 'program:id,title,sub_title,program_image')
                ->orderBy('updated_at', 'desc')
                ->paginate($limit);

            $taskMarked = Enrollment::whereRelation('task', 'program_id', $program_id)
                // ->with('task', 'user', 'program:id,title,sub_title,program_image')
                ->withAppends()
                ->whereNotNull('review_score')
                ->orderBy('updated_at', 'desc')
                ->paginate($limit);
        }

        return $this->formatResponse(
            'success',
            'all-task-of-program-get',
            $user_type == 0 ? $taskAll : [
                'pending' => $taskPending,
                'marked' => $taskMarked,

            ],
            200
        );
    }

    public function likeProject(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'enrollment_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $likedProject = Like::where([
            ['likeable_id', '=', $request->enrollment_id],
            ['likeable_type', '=', Enrollment::class],
        ])->authUserLikes()->first();

        if ($likedProject) {
            if ($likedProject->is_like == true)
                $likedProject->is_like = false;
            else
                $likedProject->is_like = true;
            $likedProject->save();
            return $this->formatResponse('success', 'project-like-updated', $likedProject);
        }

        $likedProject = new Like();
        $likedProject->likeable_id = $request->enrollment_id;
        $likedProject->likeable_type = Enrollment::class;
        $likedProject->is_like = true;
        $likedProject->user_id = Auth::id();
        $likedProject->save();

        return $this->formatResponse('success', 'project-like-created', $likedProject);
    }

    public function viewProject(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'enrollment_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        // $this->enrollmentService->viewProject($request);
        $viewedProject = UserTaskView::where([
            ['enrollment_id', '=', $request->enrollment_id],
            ['user_id', '=', Auth::id()]
        ])->first();

        if (!$viewedProject) {
            $viewedProject = new UserTaskView();
            $viewedProject->enrollment_id = $request->enrollment_id;
            $viewedProject->user_id = Auth::id();
            $viewedProject->is_view = true;
            $viewedProject->save();

            return $this->formatResponse('success', 'project-viewed-successfully', $viewedProject);
        }

        $this->formatResponse('error', 'Project already viewed');
    }

    public function isIssueCertificate($enrollment)
    {
        return $enrollment->review_score >= 50 && !$enrollment->program->quiz_required && !$enrollment->is_certified;
    }
}
