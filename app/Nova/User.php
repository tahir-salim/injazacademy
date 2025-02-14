<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'email', 'last_name',
    ];
    public static $globallySearchable = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static function label()
    {
        if (Auth::user()->role_id = 1) {
            return 'Admin';
        }
        return 'User';
    }
    public function fields(Request $request)
    {
        return [
            // ID::make()->sortable(),

            Text::make('Name', function () {
                return $this->first_name . " " . optional($this)->last_name;
            })->exceptOnForms(),

            Text::make('First Name', 'first_name')
                ->sortable()
                ->rules('required', 'regex:/^[a-zA-Z]+$/u', 'max:255')
                ->help('Example Format : Admin')
                ->hideFromIndex(),

            Text::make('Last Name', 'last_name')
                ->sortable()
                ->rules('required', 'regex:/^[a-zA-Z]+$/u', 'max:255')
                ->help('Example Format : Admin')
                ->hideFromIndex(),

            Text::make('Email')
                ->sortable()
                ->onlyOnForms()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Stack::make('Email', [
                Text::make('Email'),
                Line::make('updated_at', function () {
                    return "Created: " . $this->updated_at->diffForHumans();
                })->asSmall()->extraClasses('italic font-medium text-120'),
            ]),

            BelongsTo::make('Role', 'role', Role::class)
                ->default(\App\Models\Role::ADMIN)
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'readonly' => true,
                ]]),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            Image::make('Avatar', 'avatar')
                ->disk('s3')
                ->path('images/users')
                ->squared()
                ->maxWidth('200')
                ->hideFromIndex(),

            Country::make('Country', 'country')
                ->hideFromIndex(),

            Text::make('Phone', 'phone')
                ->rules('required', 'digits:10')
                ->hideFromIndex(),

            Select::make('Gender', 'gender')
                ->options([
                    'MALE' => 'Male',
                    'FEMALE' => 'Female',
                ])->hideFromIndex(),

            Date::make('Date Of Birth', 'dob')
                ->rules('after:' . Carbon::now()
                        ->subYears(99)
                        ->toDateString(), 'before:' . Carbon::now()
                        ->subYears(1)
                        ->toDateString())
                ->format('YYYY-MM-DD')
                ->hideFromIndex(),
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

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
}
