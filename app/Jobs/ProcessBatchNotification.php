<?php

namespace App\Jobs;

use App\Libraries\PushNotification;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBatchNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $notification, $tokens;

    public function __construct($notification, $tokens)
    {
        $this->notification = $notification;
        $this->tokens = $tokens;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->tokens as $id => $token)
        {
            PushNotification::Pusher(
                $this->notification->title,
                $this->notification->body,
                $token,
                $this->notification->notification_to == Notification::SPECIFIC_USER?$id:null,
                null,
                false,
                null,
                null,
                $this->notification->collapse_key,
                null
            );
        }
    }
}
