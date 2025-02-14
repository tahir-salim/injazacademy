<?php

namespace App\Nova;

use App\Nova\Actions\ApproveMentor;
use App\Nova\Actions\RejectMentorRequest;
use App\Nova\Filters\FilterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class MentorRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MentorRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'first_name';

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
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name', function ($q) {
                return $this->first_name . " " . optional($this)->last_name;
            })->exceptOnForms(),

            Text::make('Email')
                ->sortable()
                ->onlyOnForms()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Stack::make('Email', [
                Text::make('Email'),
                Line::make('updated_at', function () {
                    return "Created : " . $this->updated_at->diffForHumans();
                })->asSmall()->extraClasses('italic font-medium text-120'),
            ]),

            // BelongsTo::make('Action by', 'actionBy', User::class)
            //     ->nullable()
            //     ->hideWhenCreating()
            //     ->hideWhenUpdating(),

            Select::make('Status')
                ->options(\App\Models\MentorRequest::mentorStatus())
                ->displayUsingLabels(),

            // Boolean::make('Status')
            //     ->trueValue(\App\Models\MentorRequest::APPROVED)
            //     ->falseValue(\App\Models\MentorRequest::REJECTED),

            File::make('Resume', 'file')
                ->disk('s3')
                ->displayUsing(function () {
                    return 'CV';
                  })
                  ->download(function () {
                    return Storage::disk('s3')->download($this->file);
                  }),

            Textarea::make('Brief', 'brief'),
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
        return [
            new FilterRequest
        ];
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
        return [
            new ApproveMentor,
            new RejectMentorRequest,
        ];
    }

}
