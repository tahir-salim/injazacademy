<?php

namespace App\Observers;

use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\TaskHistory;
use App\Models\User;

class EnrollmentObserver
{


    /**
     * Handle the Enrollment "created" event.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return void
     */
    public function created(Enrollment $enrollment)
    {
        //
    }

    /**
     * Handle the Enrollment "updated" event.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return void
     */
    public function updated(Enrollment $enrollment)
    {
        //
    }

    public function updating(Enrollment $enrollment)
    {
        if($enrollment->isDirty('project_submitted'))
        {
            $taskHistory = new TaskHistory();
            $taskHistory->historable_id = $enrollment->id;
            $taskHistory->historable_type = Enrollment::class;
            $taskHistory->program_id = $enrollment->program_id;
            $taskHistory->project_name = $enrollment->project_name;
            $taskHistory->save();

            //Notification
            $programMentor = $enrollment->program->mainMentor()->first();
            $subMentors = $enrollment->program->mentors()->wherePivot('mentor_type', '!=' , User::MAIN)->pluck('users.id')->map(function($item){
                return (string)$item;
            });
            if($programMentor){
                $notify = new Notification();
                $notify->title = optional($enrollment->user)->name.' added Task';
                $notify->body = optional($enrollment->user)->name.' added a new task in your course '.$enrollment->program->title;
                $notify->user_id = $programMentor->id;
                $notify->related_id = $enrollment->user_id;
                $notify->notification_to = Notification::SPECIFIC_USER;
                $notify->collapse_key = 'project';
                $notify->event = Notification::TASK;
                $notify->event_id = $enrollment->program_id;
                $notify->user_list = $subMentors;
                $notify->save();
            }
        }

        if($enrollment->isDirty('review_score') && $enrollment->review_score)
        {
            // $enrollment->whereRelation('taskHistory', 'historable_id', $enrollment->id)->first();
            TaskHistory::where([
                ['historable_id' , '=' , $enrollment->id],
                ['historable_type' , '=' , Enrollment::class]
            ])->get()->each(function($taskHistory, $key){
                $taskHistory->checked = true;
                $taskHistory->save();
            });

            $mentor = $enrollment->program->mentors()->first();
            if($mentor){
                $notify = new Notification();
                $notify->title = $mentor->name.' marked your task';
                $notify->body = $mentor->name.' has marked your task';
                $notify->user_id = $enrollment->user_id;
                $notify->related_id = $mentor->id;
                $notify->notification_to = Notification::SPECIFIC_USER;
                $notify->collapse_key = 'project';
                $notify->event = Notification::TASK;
                $notify->event_id = $enrollment->program_id;
                $notify->save();
            }
        }
    }

    /**
     * Handle the Enrollment "deleted" event.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return void
     */
    public function deleted(Enrollment $enrollment)
    {
        //
    }

    /**
     * Handle the Enrollment "restored" event.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return void
     */
    public function restored(Enrollment $enrollment)
    {
        //
    }

    /**
     * Handle the Enrollment "force deleted" event.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return void
     */
    public function forceDeleted(Enrollment $enrollment)
    {
        //
    }
}
