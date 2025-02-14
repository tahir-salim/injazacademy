<?php

namespace App\Observers;

use App\Models\Test;

class TestObserver
{
    /**
     * Handle the Test "created" event.
     *
     * @param  \App\Models\Test  $test
     * @return void
     */
    public function created(Test $test)
    {
        //
    }

    /**
     * Handle the Test "updated" event.
     *
     * @param  \App\Models\Test  $test
     * @return void
     */
    public function updated(Test $test)
    {
        //
    }

    /**
     * Handle the Test "deleted" event.
     *
     * @param  \App\Models\Test  $test
     * @return void
     */
    public function deleted(Test $test)
    {
        //
    }


    public function deleting(Test $test)
    {
        $test->program->quiz_required = false;
        $test->program->save();
    }

    /**
     * Handle the Test "restored" event.
     *
     * @param  \App\Models\Test  $test
     * @return void
     */
    public function restored(Test $test)
    {
        //
    }

    /**
     * Handle the Test "force deleted" event.
     *
     * @param  \App\Models\Test  $test
     * @return void
     */
    public function forceDeleted(Test $test)
    {
        //
    }
}
