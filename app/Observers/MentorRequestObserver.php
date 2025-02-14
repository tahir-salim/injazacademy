<?php

namespace App\Observers;

use App\Models\MentorRequest;
use Illuminate\Support\Facades\Auth;

class MentorRequestObserver
{


    public function creating(MentorRequest $mentorRequest)
    {
        $mentorRequest->action_by = Auth::id();
        $mentorRequest->save();
    }


    /**
     * Handle the MentorRequest "created" event.
     *
     * @param  \App\Models\MentorRequest  $mentorRequest
     * @return void
     */
    public function created(MentorRequest $mentorRequest)
    {
        
        //
    }

    /**
     * Handle the MentorRequest "updated" event.
     *
     * @param  \App\Models\MentorRequest  $mentorRequest
     * @return void
     */
    public function updated(MentorRequest $mentorRequest)
    {
        if ($mentorRequest->action_by != Auth()->id()) {
            $mentorRequest->action_by = Auth()->id();
            $mentorRequest->save();
        }
        //
    }

    /**
     * Handle the MentorRequest "deleted" event.
     *
     * @param  \App\Models\MentorRequest  $mentorRequest
     * @return void
     */
    public function deleted(MentorRequest $mentorRequest)
    {
        //
    }

    /**
     * Handle the MentorRequest "restored" event.
     *
     * @param  \App\Models\MentorRequest  $mentorRequest
     * @return void
     */
    public function restored(MentorRequest $mentorRequest)
    {
        //
    }

    /**
     * Handle the MentorRequest "force deleted" event.
     *
     * @param  \App\Models\MentorRequest  $mentorRequest
     * @return void
     */
    public function forceDeleted(MentorRequest $mentorRequest)
    {
        //
    }
}
