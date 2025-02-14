<?php

namespace App\Nova;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\MultiselectField\Multiselect;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use DigitalCreative\ConditionalContainer\ConditionalContainer;


class Notification extends Resource
{

    use HasConditionalContainer;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Notification::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];
    public static $globallySearchable = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


    public function fields(Request $request)
    {

        $userArr = [];
            // change to collection later
            foreach(User::where([
                ['role_id' , '!=' , Role::ADMIN],
                ['fcm_token', '!=' , null]
            ])->get() as $user)
            {
                $userArr[(int)$user->id] = $user->name.($user->role_id == Role::STUDENT ? ' (Student)': ' (Mentor)') ;
            }

        // dd($userArr);

        return [
            // ID::make(__('ID'), 'id')->sortable(),
            Text::make('Title')
                ->sortable()
                ->rules('required'),
            Textarea::make('Body')
                ->hideFromIndex()
                ->rules('required'),
            // BelongsTo::make('Mentor' ,'user', Mentor::class)->hideWhenCreating(),
            Select::make('Notification To')->options(\App\Models\Notification::NOTIFICATION_TYPE())
                ->rules('required')
                ->sortable()
                ->displayUsingLabels(),
            // Select::make('Notification To')->options(\App\Models\Notification::STATES())
            // ->displayUsingLabels()
            // ->default(\App\Models\Notification::ALL_USERS)->onlyOnForms(),

            ConditionalContainer::make([
                Multiselect::make('Users', 'user_list')
                ->rules('required')
                ->options($userArr)
                ->placeholder('Choose Users to notify')
            ])->if("notification_to = 'SPECIFIC_USER' "),
            // BelongsTo::make('User' ,'user', User::class)->hideWhenCreating(),
            Hidden::make('Collapse Key' ,'collapse_key') ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                $model->collapse_key = 'AdminNotification';
            }),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
