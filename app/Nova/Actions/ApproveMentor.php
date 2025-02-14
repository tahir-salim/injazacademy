<?php

namespace App\Nova\Actions;

use App\Mail\ApproveMentor as MailApproveMentor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ApproveMentor extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Approve Request';

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
         
            if ($model->status == 'REJECTED' || $model->status == 'APPROVED') {
                return Action::danger('Request Already Processed');
            }

            $model->status = 'APPROVED';
            $model->action_by = Auth::id();
            $model->save();
            $model->user->role_id = \App\Models\Role::MENTOR;
            $model->user->brief = $model->brief;
            $model->user->save();
            Mail::to($model->user->email)->send(new MailApproveMentor($model->user->first_name));

        }
        return Action::message('Mentor Approved!');
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
