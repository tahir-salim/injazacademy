<?php

namespace App\Nova;

use App\Models\Discussion;
use App\Models\Enrollment as EnrollmentModel;
use App\Models\Task;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\MorphToMany;

class Like extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Like::class;
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

            BelongsTo::make('User', 'user', User::class),
            Text::make('Liked', function(){
                if($this->likeable_type == Discussion::class)
                    return Str::limit(optional($this->likeable)->body, 30);
                else if($this->likeable_type == EnrollmentModel::class)
                    return optional($this->likeable)->project_name;
            })->onlyOnIndex(),
            Text::make('Liked', function(){
                if($this->likeable_type == Discussion::class)
                    return optional($this->likeable)->body;
                else if($this->likeable_type == EnrollmentModel::class)
                    return optional($this->likeable)->project_name;;
            })->hideFromIndex(),
            Boolean::make('Like', 'is_like')
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
}
