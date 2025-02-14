<?php

namespace App\Nova\Traits;

use App\Models\User;
use App\Nova\Country;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;

trait UserTrait
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;
    // public static $talentid = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->name;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function commonFields()
    {
        return [

            // ID::make(__('ID'), 'id')->sortable(),

            Image::make('Avatar', 'avatar')
                ->disk('s3')
                ->path('images/users')
                ->squared()
                ->rules('mimes:jpg,png'),

            // Stack::make('Name', [
            //     Text::make('Name', 'name'),
            //     Text::make('Arabic Name', 'ar_name'),
            // ])
            //     ->onlyOnIndex()->sortable(),

            Text::make('Name', 'first_name' , function(){
                $name = null;
               $name = $this->name;
               if($this->ar_name)
                $name .= '/'.$this->ar_name;
            //    $this->name .'/'. $this->ar_name;

               return $name;
            })->onlyOnIndex()->sortable(),

            Text::make('First Name', 'first_name')
                ->rules('required')
                ->displayUsing(function ($value) {
                    return Str::title($value);
                })
                ->hideFromIndex(),

            Text::make('Middle Name', 'middle_name')
                ->displayUsing(function ($value) {
                    return Str::title($value);
                })
                ->hideFromIndex(),

            Text::make('Last Name', 'last_name')
                ->rules('required')
                ->displayUsing(function ($value) {
                    return Str::title($value);
                })
                ->hideFromIndex(),

            Text::make('Arabic Full Name', 'ar_name')
                ->rules('required')
                ->sortable()
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
                    return "Created : " . optional($this->updated_at)->diffForHumans();
                })->asSmall()->extraClasses('italic font-medium text-120'),
            ])->sortable(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            BelongsTo::make('Country', 'country', Country::class)->displayUsing(function ($country) {
                return $country->name . ' ( ' . $country->phone_code . ' ) ';
            })->hideFromIndex(),

            Number::make('Phone No', 'phone')
                ->sortable()
                ->rules('required', 'min:6', 'max:12')
                ->displayUsing(function () {
                    if ($this->phone) {
                        return ($this->country ? '(' . optional($this->country)->phone_code . ') ' : '') . $this->phone;
                    }

                    return '—';
                })
                ->help('Phone number without phone code')
                ->hideFromIndex(),

            Text::make('CPR / Passport', 'cpr')->onlyOnIndex()->sortable(),
            // Stack::make('CPR / Passport', [
            //     Text::make('CPR', 'cpr'),
            //     Line::make('Country', function () {
            //         return optional($this->country)->name;
            //     })->asSmall()->extraClasses('italic font-medium text-120'),
            // ])->onlyOnIndex(),

            Text::make('CPR / Passport', 'cpr')
                ->rules('required', 'min:5', 'max:10')
                ->hideFromIndex(),

            Date::make('Date Of Birth', 'dob')
                ->rules(
                    'after:' . Carbon::now()
                        ->subYears(99)
                        ->toDateString(),
                    'before:' . Carbon::now()
                        ->subYears(2)
                        ->toDateString(),
                    'required'
                )
                ->format('YYYY-MM-DD')
                ->hideFromIndex(),

            Select::make('Gender', 'gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ])
                ->rules('required')
                ->displayUsingLabels()
                ->hideFromIndex(),

            Boolean::make('Is Blocked', 'is_blocked')
                ->sortable()
                ->default(false),
        ];
    }
}
