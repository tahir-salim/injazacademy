<?php

namespace App\Nova;

use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Michielfb\Time\Time;

class Content extends Resource
{
    use HasConditionalContainer;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Content::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

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
        return [
            // ID::make(__('ID'), 'id')->sortable(),

            Text::make('Title', 'title')->rules('required'),

            Text::make('Order Number', 'order_number'),

            Select::make('Type', 'type')->options([
                'VIDEO' => 'VIDEO',
                'FILE' => 'FILE',
            ]),

            ConditionalContainer::make([
                File::make('Attachment', 'data')
                    ->disk('s3')
                    ->storeAs(function (Request $request) {
                        return $request->data->getClientOriginalName();
                    })
                    ->onlyOnForms(),
            ])
                ->if('type = "FILE"'),

            File::make('Attachment', 'data')
                ->disk('s3')
                ->onlyOnDetail(),

            ConditionalContainer::make([
                Text::make('Vimeo Id', 'url')
                    ->rules('required')
                    ->help('Please Enter vimeo id'),
            ])
                ->if('type = "VIDEO"'),

            // Select::make('Status')
            //     ->options(\App\Models\Content::CONTENT_STATUS())
            //     ->displayUsingLabels(),

            // Time::make('Duration', 'duration')
            //     ->format('HH:MM')
            //     ->help('Format HH : MM'),

            Text::make('Duration', 'duration')
                ->rules('regex:/^\\d\\d?h\\s\\d\\d?m$/i')
                ->help(
                    'Example format 3h 6m '
                )
                ->hideFromIndex(),

            // Select::make('Language', 'language')->options([
            //     'ENGLISH' => 'ENGLISH',
            //     'ARABIC' => 'ARABIC',

            // ]),
            // BelongsTo::make('Chapter', 'chapter', Chapter::class),
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

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
}
