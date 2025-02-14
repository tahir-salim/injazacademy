<?php

namespace App\Jobs;

use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishWorkshop implements ShouldQueue
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
        Program::where(function($q){
            $q->where([
                ['is_workshop' , '=' , true],
                ['is_live' , '=' , true],
                ['status' , '=' , Program::PUBLISHED],
                ['live_date_time_end' , '<' , Carbon::now()]
            ])
            ->orWhere('live_date_time' , '>' , Carbon::now());
        })->update(['is_live' => false]);
    }
}
