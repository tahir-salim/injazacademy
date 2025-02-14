<?php

namespace App\Nova;

use App\Models\Program;
use App\Models\User;
use App\Nova\Filters\CustomBooleanFilter;
use App\Nova\Filters\OrderBy;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;
use OptimistDigital\MultiselectField\Multiselect;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Workshops extends Resource
{
    use HasConditionalContainer;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Program::class;

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

            Hidden::make('is_workshop')->default(1),

            Image::make('Promo Image', 'program_image')
                ->disk('s3')
                ->path('images/programs')
                ->squared()
                ->maxWidth('200')
                ->creationRules('required')
                ->updateRules(function (NovaRequest $request) {
                    $model = $request->findModelOrFail();
                    return $model->program_image ? [] : ['required'];
                }),

            // BelongsTo::make('Mentor', 'mentor', Mentor::class)
            //     ->withMeta([
            //         'value' => optional($this->mentors()->first())->name,
            //         'belongsToId' => optional($this->mentors()->first())->id,
            //     ])
            //     ->exceptOnForms(),

            Text::make('Mentor', 'u.first_name' ,  function () {
                $url = config('app.url');
                $mentorId = optional($this->mainMentor()->first())->id;
                $mentorName = optional($this->mainMentor()->first())->name;
                return "
                   <a class='no-underline dim text-primary font-bold' href='{$url}/dashboard/resources/mentors/{$mentorId}'>{$mentorName}</a>
                ";
            })->asHtml()->exceptOnForms()->sortable()->hideOnExport(),

            Text::make('Title', 'title')
                ->rules('required')
                ->sortable(),

            // Text::make('Sub Title', 'sub_title')->onlyOnForms(),

            Textarea::make('Description', 'body')
                ->rules('required'),

            Boolean::make('Show Description In App', 'show_description')
                ->default(1)
                ->hideFromIndex(),

            Select::make('Program Preview In App', 'is_rtl')
                ->options([
                    0 => 'From Left To Right',
                    1 => 'From Right To Left',
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->default(0)
                ->hideFromIndex(),

            Select::make('Age Group', 'age_group')
                ->options(\App\Models\Program::ageGroups())
                ->displayUsingLabels()
                ->rules('required')
                ->hideFromIndex(),

            Multiselect::make('Languages', 'available_languages')
                ->options(Program::LANGUAGES())
                ->rules('required')
                ->saveAsJSON()
                ->placeholder('Choose Languages')
                ->hideFromIndex(),

            AttachMany::make('Main Mentors', 'mainMentor', Mentor::class)
                ->rules('required', 'max:1')
                ->fillUsing(function ($request, $model, $requestAttribute) {
                    return function () use ($model, $request, $requestAttribute) {
                        $memberIds = str_replace('[', '', $request->post($requestAttribute));
                        $memberIds = str_replace(']', '', $memberIds);
                        $memberIds = explode(',', $memberIds);

                        $members = [];
                        foreach ($memberIds as $memberId) {
                            $members[$memberId] = ['mentor_type' => \App\Models\User::MAIN];
                        }

                        $model->mentors()->wherePivot('mentor_type',\App\Models\User::MAIN)->sync($members);
                    };
                })
                ->showCounts()
                ->showPreview()
                ->onlyOnForms(),

            AttachMany::make('Tags', 'tags', Tag::class),

            Boolean::make('Is Live')
                ->sortable()
                ->onlyOnIndex(),

            // Boolean::make('Is Live')
            //     ->sortable()
            //     ->help('When is active, this means the workshop is now live')
            //     ->onlyOnDetail(),

            Stack::make('Is Live', [
                Boolean::make('Is Live')->withMeta(['textAlign' => 'left']),
                Line::make('', function () {
                    return $this->is_live ? 'Workshop is Live Now' : '';
                })
                ->asSmall()
                ->extraClasses('italic font-bold text-80'),
            ])
            ->hideOnExport()
            ->onlyOnDetail(),

            Text::make('Live Link', 'live_link')->rules('required')->hideFromIndex(),
            DateTime::make('Live Date Time', 'live_date_time')->rules('required')->hideFromIndex(),
            DateTime::make('Live Date Time End', 'live_date_time_end')->rules('required', 'after:live_date_time')->hideFromIndex(),

            Boolean::make('Status', 'status', function () {
                    return $this->status == \App\Models\Program::PUBLISHED;
                })
                ->sortable()
                ->exceptOnForms(),

            Select::make('Status', 'status')->options(\App\Models\Program::programStates())
                ->displayUsingLabels()
                ->default(\App\Models\Program::PUBLISHED)
                ->rules('required')
                ->onlyOnForms(),

            HasMany::make('Erollment', 'enrollment', Enrollment::class)->hideOnExport(),

            (new Tabs('Tabs', [

                'Mentors' => [
                    BelongsToMany::make('Program Mentors', 'mentors', Mentor::class)->fields(function () {
                        return [
                            Select::make('Mentor Type')->options(User::MENTOR_TYPES())->displayUsingLabels()
                                ->exceptOnForms(),
                            Hidden::make('Mentor Type')->default(User::ASSISTANT),
                        ];
                    }),
                ],
                'Category' => [
                    BelongsToMany::make('Categories', 'categories', Category::class),
                ],
                'Sponsors' => [
                    BelongsToMany::make('Sponsors', 'sponsors', Sponsor::class),
                ],
                'Tags' => [
                    BelongsToMany::make('Tags', 'tags', Tag::class),
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
        return [
            (new CustomBooleanFilter('Is Live', 'is_live', 'Yes', 'No')),
            (new CustomBooleanFilter('Status', 'status', 'Active', 'In Active', \App\Models\Program::PUBLISHED, \App\Models\Program::DRAFT)),
            (new OrderBy)
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
            (new DownloadExcel)->withHeadings()->allFields()->except('first_name', 'sub_title', 'project_required','project_description','is_rtl', 'quiz_required', 'promo_video','issue_certificate','task_required','task','deleted_at','generate_linkedin_certificate')
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('is_workshop', 1);
        $query->select('programs.*', 'u.first_name' );
        $query->leftJoin('program_mentors as pp', function($join) {
            $join->on('programs.id', '=', 'pp.program_id')
                 ->where('pp.mentor_type', '=', User::MAIN);
        });
        $query->leftJoin('users as u', 'pp.mentor_id', '=', 'u.id');
        return $query;
    }

    public static function relatableTags(NovaRequest $request, $query)
    {
        return $query->where('status', 1);
    }

    public static function relatableUsers(NovaRequest $request, $query)
    {
        return $query->where('role_id', User::ROLE_MENTOR);
    }
}
