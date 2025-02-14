<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskHistoryController extends Controller
{
    public function getPendingTasks(Request $request)
    {
        $filterType = $request->query('task_type') ?? 2;
        $isHistory = $request->query('is_history') ?? 0;
        $taskStatus = $request->query('task_status') ?? TaskHistory::PENDING;
        $limit = $request->query('limit') ?? 20;

        // dd($taskStatus);

        // 2 means all
        if(!in_array($filterType , [0 , 1, 2]))
            $this->formatResponse('error' , 'type-does-not-exists');

        $programIds = Auth::user()->mentorPrograms()->pluck('programs.id');

        $tasks = TaskHistory::with(['program:id,title' , 'historable.user'])
            // ->whereHasMorph('historable' , [Discussion::class, Enrollment::class])
            ->checkHistorable()
            ->when($filterType == 0 , function($q){
                $q->enrollmentHistory();
            })
            ->when($filterType == 1 , function($q){
                $q->discussionHistory();
            })
            ->when($isHistory == 1 , function($q){
                $q->unRead();
            })
            ->when($taskStatus == TaskHistory::PENDING, function($q){
                $q->unRead();
            })
            ->when($taskStatus == TaskHistory::COMPLETED, function($q){
                $q->read();
            })
            ->whereIn('program_id' , $programIds)
            ->orderBy('checked' , 'asc')
            ->latest()
            ->paginate($limit);

        return $this->formatResponse('success' , 'task-pending' , $tasks);

    }

    public function viewPendingProject(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'enrollment_id' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        return $this->formatResponse('success' , 'pending-project' , Enrollment::with('user' , 'task' ,'program:id,title,program_image')->find($request->enrollment_id));
    }

    public function markPendingDiscussion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $pendingTask = TaskHistory::find($request->id);
        if($pendingTask)
        {
            $pendingTask->checked = true;
            $pendingTask->save();
        }

        return $this->formatResponse('success', 'discussion-marked');

    }
}
