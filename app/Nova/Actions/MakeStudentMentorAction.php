<?php

namespace App\Nova\Actions;

use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class MakeStudentMentorAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Make Mentor';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach($models as $model){
            $model->role_id = Role::MENTOR;
            $model->company = $fields->company;
            $model->occupation = $fields->occupation;
            $model->experience = $fields->experience;
            $model->headline = $fields->headline;
            $model->brief = $fields->brief;
            if($fields->cpr_file){
                $model->cpr_file = Storage::disk('s3')->put('files/users', $fields->cpr_file);
            }
            if($fields->cv){
                $model->cv = Storage::disk('s3')->put('files/users', $fields->cv);
            }
            if($fields->bio){
                $model->bio = Storage::disk('s3')->put('files/users', $fields->bio);
            }
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Company', 'company')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Occupation', 'occupation')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Year Of Experience', 'experience')
                ->rules('required')
                ->hideFromIndex(),

            // Text::make('Headline', 'headline')
            //     ->rules('required')
            //     ->hideFromIndex(),

            Textarea::make('Brief', 'brief')
                ->rules('required')
                ->hideFromIndex(),

            // File::make('CPR Attachment','cpr_file'),

            // File::make('CV Attachment','cv'),

            // File::make('Bio Attachment','bio')
        ];
    }
}
