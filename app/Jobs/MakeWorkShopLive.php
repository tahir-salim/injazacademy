<?php

namespace App\Jobs;

use App\Libraries\PushNotification;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakeWorkShopLive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $workshopsNowIds =  tap(Program::where([
            ['is_workshop' , '=' , true],
            ['is_live' , '=' , false],
            ['status' , '=' , Program::PUBLISHED],
            ['live_date_time' , '<' , Carbon::now()],
            ['live_date_time_end' , '>' , Carbon::now()]
        ]))->update(['is_live' => true])->pluck('id');

        if($workshopsNowIds->all())
        {
            $ids = implode(', ', $workshopsNowIds->all());
            
            PushNotification::Pusher(
                'Workshops Live Now',
                'New workshops have gone live just now',
                null,
                null,
                [
                    'programIds' => $ids
                ],
                false,
                'UpcomingWorkshops'
            );
        }
        
    }
}
