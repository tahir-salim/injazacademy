<?php

namespace App\Nova\Actions;

use App\Models\Program;
use App\Models\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;

class AttachProgram extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Assign To Program';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {

        if($models->count() > 1)
            return Action::danger('Please select only one test.');
            
        $program = Program::find($fields->program);
        $program->test()->associate($models->first());
        $program->save();

        return Action::message('Program assigned successfully.');
    }

    public function fields()
    {
        $programs = Program::all();
        $programsArray = [];

        foreach($programs as $program)
        {
           $programsArray[$program->id] = $program->title; 
        }
        
        return [
            
            Select::make('Program')->options(
                $programsArray
            )->default(function ($request) use($programsArray) {
                 return  array_key_first($programsArray);
             })
        ];
    }

}
