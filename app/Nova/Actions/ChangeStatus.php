<?php

namespace App\Nova\Actions;

use App\Models\Program;
use Google\Service\Bigquery\Resource\Models;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class ChangeStatus extends Action
{
    use InteractsWithQueue, Queueable;
    public $status;
    public $program;

    public function __construct($id = null)
    {

        if($id)
        {
            $this->program = Program::find($id);
            $this->status = $this->program->status;
        }

    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // dd($this->program);
        if($models->first()->mentors()->exists() && $models->first()->chapter()->exists())
        {
            $models->first()->status = $fields->status;
            $models->first()->save();
            return Action::message("Status updated succesfully");
        }
        else{
            return Action::danger("Cannot publish program without mentor or sessions");
        }


    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        // dd($this->status);
        return [
            Select::make('Status')->options(\App\Models\Program::programStates()
            )->default($this->status)
        ];
    }
}
