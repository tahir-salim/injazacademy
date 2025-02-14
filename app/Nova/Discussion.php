<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Discussion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Discussion::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title(){
        return 'Discussion | ' . optional($this->user)->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'body'
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
            BelongsTo::make('User', 'user', $this->is_mentor?Mentor::class: Student::class),
            Boolean::make('Is Mentor' , 'is_mentor')
                ->showOnIndex(!in_array($request->viaResource, ['students','mentors'])),

            BelongsTo::make('Enrollment', 'enrollment', Enrollment::class),
            BelongsTo::make('Program', 'programs' , Program::class),

            Textarea::make('Body', 'body')
                ->rules('required', 'body', 'max:254')
                ->onlyOnIndex()
                ->onlyOnDetail(),


            // Text::make('Program'),

            BelongsTo::make('Reply', 'repliesParent', Discussion::class)
                ->showOnDetail()
                ->showOnIndex($request->viaResource && $request->viaResource != 'programs'),

            HasMany::make('Replies', 'replies', Discussion::class)
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if(!$request->viaResource || $request->viaResource == 'programs'){
            return $query->whereNull('reply_id');
        }
        return $query;
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
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
