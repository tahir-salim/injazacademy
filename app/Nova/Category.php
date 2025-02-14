<?php

namespace App\Nova;

use App\Nova\Filters\CustomBooleanFilter;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Yna\NovaSwatches\Swatches;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

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
        'id', 'name', 'display_name'
    ];

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
            Text::make('Name', 'name')
                ->sortable()
                ->rules('required'),

            Text::make('Display Name', 'display_name')
                ->sortable()
                ->rules('required'),

            Swatches::make('Background Color','bg_color')
                ->colors('text-advanced')
                ->withProps([
                    'show-fallback' => true,
                    'fallback-type' => 'input',
                    'popover-to' => 'left',
                ])
                ->default('#b7b7b7')
                ->help('Pick any color except white')
                ->rules('required','not_in:#ffffff'),

            // Image::make('Image', 'category_image')
            //     ->disk('s3')
            //     ->path('images/categories')
            //     ->creationRules('required')
            //     ->updateRules('',function ($attribute, $value, $fail) use ($request) {
            //         $model = $this->resource->find($request->route('resourceId'));
            //         if (empty($value) && empty($model->$attribute)) {
            //             $fail(__(':Attribute is required.', ['attribute' => __($attribute)]));
            //         }
            //     })
            //     ->squared()
            //     ->maxWidth('200'),

            Boolean::make('Active', 'status')
                ->sortable()
                ->default(true),

            (new Tabs('Tabs', [
                'Programs' => [
                    BelongsToMany::make('Programs', 'programs', Program::class),
                ],
                // 'User Interest' => [
                //     BelongsToMany::make('User Interest', 'users', User::class),
                // ],
            ])),

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
            (new CustomBooleanFilter('Is Active', 'status', 'Active', 'In Active'))
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
        return [];
    }

}
