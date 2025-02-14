<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class AddMentor extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */

    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function handle(ActionFields $fields, Collection $models)
    {

        $models->first()->mentors()->attach($fields->mentors , ['mentor_type' => $fields->type]);


        return Action::message('Mentor assigned successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {

        $users = \App\Models\User::isMentorUser()->get();
        $usersArray = [];

        foreach($users as $user)
        {
           $usersArray[$user->id] = $user->first_name;
        }

        return [

            Select::make('Mentors')->options(
                $usersArray
            )->default(function ($request) use($usersArray) {
                 return  array_key_first($usersArray);
             }),
             Select::make('Type')->options(
                \App\Models\User::MENTOR_TYPES()
            )->default('MAIN')
        ];
    }
}
