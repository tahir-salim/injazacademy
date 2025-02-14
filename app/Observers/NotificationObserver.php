<?php

namespace App\Observers;

use App\Jobs\ProcessBatchNotification;
use App\Libraries\PushNotification;
use App\Models\Notification;
use App\Models\User;

class NotificationObserver
{
    // /**
    //  * Handle the Notification "creating" event.
    //  *
    //  * @param  \App\Models\Notification  $notification
    //  * @return void
    //  */

    //  public function creating(Notification $notification)
    //  {
    //      if($notif)
    //  }
    /**
     * Handle the Notification "created" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function created(Notification $notification)
    {

        $token = null;
        $topic = null;
        $registrationIds = null;
        if($notification->notification_to == Notification::ALL_USERS){
            $topic =  $notification->collapse_key;
        }elseif($notification->notification_to == Notification::SPECIFIC_USER && $notification->user_list)
        {
            $ids = array_map('intval', (explode("," ,   str_replace(']' , "" ,  str_replace('[' , "" ,  str_replace('"' , "" , $notification->user_list))))));
            $registrationIds = User::whereIn('id', $ids)->whereNotNull('fcm_token')->pluck('fcm_token','id')->all();
        }
        elseif($notification->notification_to == Notification::SPECIFIC_USER && ($notification->user)->fcm_token && ($notification->user)->is_notification){
            $token = $notification->user->fcm_token;
        }

        if($registrationIds)
        {
            ProcessBatchNotification::dispatch($notification, $registrationIds);
            return;
        }
        if ($token || $topic )
        {
            $data = null;
            if($notification->event){
                $data = ['event' => $notification->event];
                if($notification->event_id)
                    $data['event_id'] = (string)$notification->event_id;
            }
            PushNotification::Pusher(
                $notification->title,
                $notification->body,
                $token,
                $notification->notification_to == Notification::SPECIFIC_USER?$notification->user_id:null,
                $data,
                false,
                $topic,
                null,
                $notification->collapse_key,
                null,
                $registrationIds
            );
        }
    }

    /**
     * Handle the Notification "updated" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function updated(Notification $notification)
    {
        //
    }

    /**
     * Handle the Notification "deleted" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function deleted(Notification $notification)
    {
        //
    }

    /**
     * Handle the Notification "restored" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function restored(Notification $notification)
    {
        //
    }

    /**
     * Handle the Notification "force deleted" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function forceDeleted(Notification $notification)
    {
        //
    }
}
