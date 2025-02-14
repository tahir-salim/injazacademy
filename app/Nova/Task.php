<?php

namespace App\Nova;

use App\Nova\Actions\ReviewTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class Task extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Task::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'file';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            BelongsTo::make('Enrollment', 'enrollment', Enrollment::class),
            BelongsTo::make('Program', 'program', Program::class),
            // Text::make('Task' , 'file'),
            Text::make('Image' , 'task_image')->hideFromIndex(),

            Text::make('', function () {
                $file = Storage::disk('s3')->temporaryUrl($this->task_image, now()->addHour());
                return "<div class='edit-button-main' style='display: flex;align-items: center;justify-content: flex-end;color: #b3b9bf;'><a style='color:#ffffff;font-size:12px;' class='btn btn-default btn-danger' href='{$file}' class='product-edit-button'>View</a></div>";
            })->asHtml(),
            // Boolean::make('Is Reviewed', 'is_reviewed'),
            // Boolean::make('Show In Program', 'show_in_program'),
            // MorphMany::make('Like', 'likes', Like::class),
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
        return [
            new ReviewTasks(),
        ];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }
}
