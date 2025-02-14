<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\ProgramMentor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateAvgRatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $programId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($programId)
    {
        $this->programId = $programId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $program = Program::find($this->programId);
        if($program){
            $program->rating = $program->enrollment()->reviewed()->avg('review')?:0;
            $program->save();

            $mentors = ProgramMentor::where('program_id', $this->programId)
                ->with('mentor')
                ->get()
                ->map(function($item){
                    return $item->mentor;
                });

            foreach($mentors as $mentor){
                $programIds = $mentor->mentorPrograms()->pluck('program_id');
                $mentor->rating = Enrollment::reviewed()->whereIn('program_id',$programIds)
                    ->avg('review')?:0;
                $mentor->save();
            }
        }
    }
}
