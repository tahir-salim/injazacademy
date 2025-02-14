<?php

namespace App\Observers;

use App\Models\Program;
use Carbon\Carbon;

class ProgramObserver
{

    public function creating(Program $program)
    {
        $program->calculateDuration();
        $program->calculateAge();
    }

    public function updating(Program $program)
    {
        $program->calculateDuration();
        $program->calculateAge();
    }

    /**
     * Handle the Program "created" event.
     *
     * @param  \App\Models\Program  $program
     * @return void
     */
    public function created(Program $program)
    {
    }

    /**
     * Handle the Program "updated" event.
     *
     * @param  \App\Models\Program  $program
     * @return void
     */
    public function updated(Program $program)
    {
        //
    }

    /**
     * Handle the Program "deleted" event.
     *
     * @param  \App\Models\Program  $program
     * @return void
     */
    public function deleted(Program $program)
    {
        foreach ($program->chapter as $chapter) {
            $chapter->contents()->delete();
            $chapter->delete();
        }

        $program->test()->delete();
    }

    /**
     * Handle the Program "restored" event.
     *
     * @param  \App\Models\Program  $program
     * @return void
     */
    public function restored(Program $program)
    {
        //
    }

    /**
     * Handle the Program "force deleted" event.
     *
     * @param  \App\Models\Program  $program
     * @return void
     */
    public function forceDeleted(Program $program)
    {
        //
    }
}
