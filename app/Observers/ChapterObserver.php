<?php

namespace App\Observers;

use App\Models\Chapter;

class ChapterObserver
{
    /**
     * Handle the Chapter "created" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function created(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the Chapter "updated" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function updated(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the Chapter "deleted" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function deleted(Chapter $chapter)
    {
        $chapter->contents()->delete();
    }

    /**
     * Handle the Chapter "restored" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function restored(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the Chapter "force deleted" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function forceDeleted(Chapter $chapter)
    {
        //
    }
}
