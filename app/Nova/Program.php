<?php

namespace App\Nova;

use App\Models\Program as ModelsProgram;
use App\Models\User;
use App\Nova\Actions\ChangeStatus;
use App\Nova\Filters\CustomBooleanFilter;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;
use OptimistDigital\MultiselectField\Multiselect;
use Titasgailius\SearchRelations\SearchesRelations;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;



class Program extends Resource
{
    use HasConditionalContainer, SearchesRelations;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelsProgram::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    // public static $with = ['mentor'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];

    public static $searchRelations = [
        'mainMentor' => ['first_name', 'email', 'last_name', 'middle_name'],
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

            Hidden::make('is_workshop')->default(0),

            Image::make('Promo Image', 'program_image')
                ->disk('s3')
                ->path('images/programs')
                ->squared()
                ->maxWidth('200')
                ->creationRules('required', 'mimes:jpg,png')
                ->updateRules(function (NovaRequest $request) {
                    $model = $request->findModelOrFail();
                    return $model->program_image ? [] : ['required', 'mimes:jpg,png'];
                }),

            // BelongsTo::make('Mentor', 'u.first_name', Mentor::class)
            //     ->withMeta([
            //         'value' => optional($this->mainMentor()->first())->name,
            //         'belongsToId' => optional($this->mainMentor()->first())->id,
            //     ])
            //     ->exceptOnForms(),

            Text::make('Mentor', 'u.first_name as mentor' ,  function () {
                $url = config('app.url');
                $mentorId = optional($this->mainMentor()->first())->id;
                $mentorName = optional($this->mainMentor()->first())->name;
                return "
                   <a class='no-underline dim text-primary font-bold' href='{$url}/dashboard/resources/mentors/{$mentorId}'>{$mentorName}</a>
                ";
            })->asHtml()->exceptOnForms()->sortable()->hideOnExport(),

            Stack::make('Title', [
                Text::make('Title', 'title'),
                Line::make('Rating', function () {
                    return $this->rating ? 'Rating: ' . $this->rating: '';
                })
                ->asSmall()
                ->extraClasses('italic font-bold text-80'),
            ])
                ->onlyOnIndex()->sortable()->hideOnExport(),

            Text::make('Title', 'title')->rules('required')
                ->hideFromIndex(),
            // Text::make('Sub Title', 'sub_title')->onlyOnForms(),

            Trix::make('Description', 'body')
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
                ->options(ModelsProgram::ageGroups())
                ->displayUsingLabels()
                ->rules('required')
                ->hideFromIndex(),

            // Trix::make('Task Description', 'project_description')->onlyOnForms(),

            Multiselect::make('Languages', 'available_languages')
                ->options(ModelsProgram::LANGUAGES())
                ->rules('required')
                ->saveAsJSON()
                ->placeholder('Choose Languages')
                ->hideFromIndex(),

            Text::make('Promo Video', 'promo_video')
                ->help('Please Enter vimeo Id.')
                ->hideFromIndex(),

            Select::make('Duration Hours', 'duration_hours')
                ->options(ModelsProgram::durationHours())
                ->rules('required')
                ->withMeta(['value' => $this->getHours()])
                ->onlyOnForms(),

            Select::make('Duration Minutes', 'duration_minutes')
                ->options(ModelsProgram::durationMinutes())
                ->rules('required')
                ->withMeta(['value' => $this->getMinuntes()])
                ->onlyOnForms(),

            Text::make('Duration', 'duration')
                ->rules('regex:/^\\d\\d?h\\s\\d\\d?m$/i')
                ->help(
                    'Example format 3h 6m '
                )
                ->onlyOnDetail(),

            // Rating
            Text::make('Rating')->onlyOnDetail(),

            Hidden::make('rating')->default(0),

            Stack::make('Required Task / Evaluation', [
                Boolean::make('Task Required', 'project_required'),
                Boolean::make('Evaluation Required', 'quiz_required'),
            ])
                ->onlyOnIndex()->hideOnExport(),

            Boolean::make('Task Required', 'project_required')
                ->default(true)
                ->hideFromIndex(),

            Boolean::make('Evaluation Required', 'quiz_required')
                ->onlyOnDetail(),

            AttachMany::make('Main Mentor', 'mainMentor', Mentor::class)
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

                        $model->mentors()->sync($members);
                    };
                })
                ->showCounts()
                ->onlyOnForms(),

            AttachMany::make('Tags', 'tags', Tag::class)
                ->showCounts(),

            Boolean::make('Allow Discussion', 'allow_discussion')->hideFromIndex(),

            Boolean::make('Status', 'status', function () {
                return $this->status == ModelsProgram::PUBLISHED;
            })
                ->exceptOnForms()
                ->sortable(),

            Select::make('Status', 'status')->options(ModelsProgram::programStates())
                ->displayUsingLabels()
                ->default(ModelsProgram::PUBLISHED)
                ->rules('required')
                ->onlyOnForms(),

            Text::make('Manage'  , function () {
                $url = config('app.url');
                $programId = $this->id;
                return "<div class='edit-button-main' style='display: flex;align-items: center;justify-content: flex-end;color: #b3b9bf;width: max-content;'><a style='color:#ffffff;font-size:12px;' class='btn btn-default btn-danger' href='{$url}/dashboard/content-manager/{$programId}' class='product-edit-button'>Manage Content</a></div>";
            })->asHtml(),

            (new Tabs('Tabs2', [
                'Enrollments' => [
                    HasMany::make('Erollment', 'enrollment', Enrollment::class),
                ],
                'Submitted Tasks' => [
                    HasMany::make('Enrollment', 'enrollment', Project::class),
                ],
                'Evaluation' => [
                    HasOne::make('Test'),
                ],
                'Discussions' => [
                    HasMany::make('Discussions', 'discussion', Discussion::class)
                ]
            ]))->defaultSearch(true),

            (new Tabs('Tabs1', [
                'Mentors' => [
                    BelongsToMany::make('Program Mentors', 'mentors', Mentor::class)->fields(function () {
                        return [
                            Select::make('Mentor Type')->options(User::MENTOR_TYPES())
                                ->displayUsingLabels()
                                ->exceptOnForms(),
                            Hidden::make('Mentor Type')->default(User::ASSISTANT),
                        ];
                    }),
                ],
                'Chapters' => [
                    HasMany::make('Chapters', 'chapters', Chapter::class),
                ],
                'Category' => [
                    BelongsToMany::make('Categories', 'categories', Category::class),
                ],
                'Tags' => [
                    BelongsToMany::make('Tags', 'tags', Tag::class),
                ],
                'Sponsors' => [
                    BelongsToMany::make('Sponsors', 'sponsors', Sponsor::class),
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
            (new CustomBooleanFilter('Status', 'status', 'Active', 'In Active', \App\Models\Program::PUBLISHED, \App\Models\Program::DRAFT)),
            (new CustomBooleanFilter('Task Required', 'project_required', 'Yes', 'No')),
            (new CustomBooleanFilter('Evaluation Required', 'quiz_required', 'Yes', 'No')),
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
            // (new AddMentor($request->resourceId))->onlyonDetail()
            (new ChangeStatus($request->resourceId))->onlyOnDetail(),
            (new DownloadExcel)->withHeadings()->allFields()->except('Manage' , 'sub_title')
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('is_workshop', 0);
        $query->select('programs.*', 'u.first_name' );
        // $query->leftJoin('program_mentors as pp', 'programs.id', '=', 'pp.program_id');
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

    // public function authorizedToDetach(NovaRequest $request, $model, $relationship)
    // {
    //     $mentors = ModelsProgram::find($this->id)->mentors()->count();

    //     if ($relationship == 'mentors' && $mentors == 1) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }
    // public function authorizedToAttachAny(NovaRequest $request, $model)
    // {
    //     // dd( $this->mentors()->count());

    //     if (get_class($model) == "App\Models\User" && $this->mentors()->count() >= 1) {
    //         return false;
    //     } else {
    //         return true;
    //     }

    // }

}
