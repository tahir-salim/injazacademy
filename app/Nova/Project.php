<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{

    public static function label()
    {
        return 'Tasks';
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Enrollment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'project_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'project_name',
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

            Text::make('Enrollment', function () {
                if ($this->id) {
                    $title = 'Enrollment | ' . optional($this->program)->title;
                    return "<a href='/dashboard/resources/enrollments/$this->id' class='no-underline dim text-primary font-bold'>
                        $title
                        </a>";
                }
                return '—';
            })
                ->asHtml(),

            BelongsTo::make('User', 'user', Student::class),

            Text::make('Task Name', 'project_name')
                ->rules('required'),
            // Number::make('Views Count', 'views_count')->exceptOnForms(),
            Number::make('Views Count', function () {
                return $this->projectViews()->count();
            }),
            Number::make('Review Score')
                ->rules('digits_between:0,100')
                ->max(100)
                ->min(0),

            Text::make('Mentor Note', function(){
                $task = $this->currentTasks()->first();
                if($task){
                    return $task->review;
                }
                return null;
            })
                ->onlyOnDetail(),

            (new Tabs('Tabs', [
                'Current Task Files' => [
                    HasMany::make('Task', 'currentTasks', Task::class),
                ],
                'Previous Task Files' => [
                    HasMany::make('Files', 'previousTasks', Task::class),
                ],
            ]))->defaultSearch(true),

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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('project_submitted', true);
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
