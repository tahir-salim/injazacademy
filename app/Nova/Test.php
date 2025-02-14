<?php

namespace App\Nova;

use App\Nova\Actions\CreateEvaluation;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Titasgailius\SearchRelations\SearchesRelations;

class Test extends Resource
{
    use SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Test::class;

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
        'title',
    ];

    public static $searchRelations = [
        'program' => ['title'],
    ];

    public static function label()
    {
        return 'Evaluation';
    }
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

            Text::make('', function () {
                $url = config('app.url');
                $testId = $this->id;
                return "
                    <div style='display: flex;gap: 10px;justify-content: flex-end;'>
                        <div class='edit-button-main' style='display: flex;align-items: center;justify-content: flex-end;color: #b3b9bf;'><a style='color:#ffffff;' class='btn btn-default btn-primary' href='{$url}/dashboard/test-creator/{$testId}?is_duplicate=true' class='product-edit-button'>Duplicate</a></div>
                        <div class='edit-button-main' style='display: flex;align-items: center;justify-content: flex-end;color: #b3b9bf;'><a style='color:#ffffff;' class='btn btn-default btn-primary' href='{$url}/dashboard/test-creator/{$testId}' class='product-edit-button'>Edit</a></div>
                    </div>
                ";
            })->asHtml()->onlyOnDetail(),

            // ID::make(__('ID'), 'id')->sortable(),

            Text::make('Title', 'title')
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Program')->sortable(),

            Text::make('Total Marks')->sortable(),
            Text::make('Passing Marks', 'passing_criteria')->sortable(),

            Text::make('', function () {
                $url = config('app.url');
                $testId = $this->id;
                return "<div class='edit-button-main' style='display: flex;align-items: center;justify-content: flex-end;color: #b3b9bf;'><a style='color:#b3b9bf;' href='{$url}/dashboard/test-creator/{$testId}' class='product-edit-button'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" aria-labelledby=\"edit\" role=\"presentation\" class=\"fill-current\"><path d=\"M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z\"></path></svg></a></div>";
            })->asHtml()->onlyOnIndex(),

            // Text::make('Sub Title', 'sub_title')
            //     ->rules('required'),
            // Trix::make('Body', 'body')
            //     ->rules('required'),

            (new Tabs('Tabs', [
                'Test Questions' => [
                    HasMany::make('Test Questions', 'testQuestions', TestQuestion::class),
                ],
                // 'Programs' => [
                //     HasMany::make('Programs', 'programs', Program::class),
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
        // return [];
        return [
            // new AttachProgram,
            CreateEvaluation::make()->standalone()->withoutConfirmation(),
        ];
    }
}
