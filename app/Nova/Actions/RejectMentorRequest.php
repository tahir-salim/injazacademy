<?php

namespace App\Nova\Actions;

use App\Mail\RejectMentor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class RejectMentorRequest extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = "Reject Request";

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            if ($model->status == 'APPROVED' || $model->status == 'REJECTED') {
                return Action::danger('Request Already Processed');
            } else {
                $model->status = 'REJECTED';
                $model->action_by = Auth::id();
                $model->save();
                Mail::to($model->user->email)->send(new RejectMentor($model->user->first_name));
            }
        }
        return Action::message('Mentor Rejected!');

    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
