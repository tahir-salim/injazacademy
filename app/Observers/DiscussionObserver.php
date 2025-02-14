<?php

namespace App\Observers;

use App\Models\Discussion;
use App\Models\Notification;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;

class DiscussionObserver
{

    /**
     * Handle the Discussion "created" event.
     *
     * @param  \App\Models\Discussion  $discussion
     * @return void
     */
    public function created(Discussion $discussion)
    {
        $parentReply = $discussion->repliesParent;
        if($parentReply){
            if($parentReply->is_mentor && !$discussion->is_mentor){

                TaskHistory::updateOrCreate(
                    ['historable_id' => $discussion->id, 'historable_type' => Discussion::class],
                    ['program_id' => $discussion->program_id, 'checked' => false]
                );
                $notify = new Notification();
                $notify->title = $discussion->user->name.' replied to your post';
                $notify->body = $discussion->user->name.' replied to your post in your course '.$discussion->programs->title;
                $notify->user_id = $parentReply->user->id;
                $notify->related_id = $discussion->user_id;
                $notify->notification_to = Notification::SPECIFIC_USER;
                $notify->collapse_key = 'discussion';
                $notify->event = Notification::DISCUSSION;
                $notify->event_id = $parentReply->id;
                $notify->save();
            }

            if(!$parentReply->is_mentor && $discussion->is_mentor){
                $notify = new Notification();
                $notify->title = $discussion->user->name.' replied to your post';
                $notify->body = $discussion->user->name.' replied to your post in your course '.$discussion->programs->title;
                $notify->user_id = $parentReply->user->id;
                $notify->related_id = $discussion->user_id;
                $notify->notification_to = Notification::SPECIFIC_USER;
                $notify->collapse_key = 'discussion';
                $notify->event = Notification::DISCUSSION;
                $notify->event_id = $parentReply->id;
                $notify->save();
            }
        }
    }

    /**
     * Handle the Discussion "updated" event.
     *
     * @param  \App\Models\Discussion  $discussion
     * @return void
     */
    public function updated(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "deleted" event.
     *
     * @param  \App\Models\Discussion  $discussion
     * @return void
     */
    public function deleted(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "restored" event.
     *
     * @param  \App\Models\Discussion  $discussion
     * @return void
     */
    public function restored(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "force deleted" event.
     *
     * @param  \App\Models\Discussion  $discussion
     * @return void
     */
    public function forceDeleted(Discussion $discussion)
    {
        //
    }
}
