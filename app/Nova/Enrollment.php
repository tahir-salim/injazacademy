<?php

namespace App\Nova;

use App\Nova\Filters\CustomBooleanFilter;
use App\Nova\Filters\DateFrom;
use App\Nova\Filters\DateTo;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use AkkiIo\LaravelNovaSearch\LaravelNovaSearchable;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;


class Enrollment extends Resource
{
    use LaravelNovaSearchable;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Enrollment::class;

    // public static $with = ['user' , 'program'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'id';
    public function title()
    {
        return 'Enrollment | ' . ($this->user ? $this->user->name : '');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $searchRelations = [
        // 'user' => [ 'first_name', 'email', 'last_name', 'middle_name' ,'phone' , 'cpr'],
        'program' => ['title'],
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    /**
     * The relationship columns that should to be concatenated and searched.
     *
     * @var array
     */
    public static $searchRelationsConcatenation = [
        'user' => [
            ['first_name', 'middle_name' ,'last_name'],
            ['email'],
            ['ar_name'],
            // ['cpr'],
            ['c.phone_code','phone']
        ],
    ];

    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),

            Stack::make('Student' , 'u.first_name' ,[
                BelongsTo::make('Student Name', 'user', Student::class),
                // Line::make('Email', function () {
                //     return "Email : " . optional($this->user)->email;
                // })->asSmall()->extraClasses('italic font-medium text-120'),
                // Line::make('CPR', function () {
                //     return "CPR : " . optional($this->user)->cpr;
                // })->asSmall()->extraClasses('italic font-medium text-120'),
                // Line::make('Phone', function () {
                //     $phone = '—';
                //     if (optional($this->user)->phone) {
                //         $phone = (optional($this->user)->country ? '(' . optional(optional($this->user)->country)->phone_code . ') ' : '') . optional($this->user)->phone;
                //     }

                //     return "Phone : " . $phone;
                // })->asSmall()->extraClasses('italic font-medium text-120'),
            ])->exceptOnForms()->sortable(),

            Text::make('Phone', function () {
                $phone = '—';
                if (optional($this->user)->phone) {
                    $phone = (optional($this->user)->country ? '(' . optional(optional($this->user)->country)->phone_code . ') ' : '') . optional($this->user)->phone;
                }

                return $phone;
            })->exceptOnForms()->sortable(),

            BelongsTo::make('Student', 'user', Student::class)
                ->onlyOnForms()
                ->withoutTrashed()
                ->searchable()
                ->required(),

            Stack::make('Program / Workshops','p.title' ,[
                // BelongsTo::make('Program / Workshops', 'program', Program::class),

                Text::make('Program / Workshops', function () {
                    if ($this->program) {
                        $id = optional($this->program)->id;
                        $title =  optional($this->program)->title;
                        return optional($this->program)->is_workshop ? "<a href='/dashboard/resources/workshops/$id' class='no-underline dim text-primary font-bold'>
                        $title
                        </a>" : "<a href='/dashboard/resources/programs/$id' class='no-underline dim text-primary font-bold'>
                        $title
                        </a>";
                    }
                    return '—';
                })->asHtml(),
                // Line::make('type', function () {
                //     return optional($this->program)->is_workshop ? 'Workshop' : 'Program';
                // })->asSmall()->extraClasses('italic font-medium text-120'),
            ])
                ->onlyOnIndex()->sortable(),

            Text::make(optional($this->program)->is_workshop ? 'Workshop' : 'Program', 'program', function () {
                if ($this->program) {
                    $id = optional($this->program)->id;
                    $title = optional($this->program)->title;
                    return optional($this->program)->is_workshop ? "<a href='/dashboard/resources/workshops/$id' class='no-underline dim text-primary font-bold'>
                        $title
                        </a>" : "<a href='/dashboard/resources/programs/$id' class='no-underline dim text-primary font-bold'>
                        $title
                        </a>";
                }
                return '—';
            })
                ->asHtml()
                ->onlyOnDetail(),

            BelongsTo::make('Program / Workshops', 'program', Program::class)
                ->withoutTrashed()
                ->required()
                ->onlyOnForms(),

            // Stack::make('Start / End', 'started_date' , [
            //     Date::make('Started Date', 'started_date'),
            //     Date::make('Finished Date', 'finished_date'),
            // ])
            Date::make('Started Date', 'started_date')->onlyOnIndex()->sortable(),

            Hidden::make('Started Date', 'started_date')
                ->default(now())
                ->showOnUpdating(false)
                ->showOnCreating(),

            DateTime::make('Started Date', 'started_date')
            // ->format("YYYY-MM-DD")
                ->required()
                ->showOnCreating(false)
                ->hideFromIndex(),

            DateTime::make('Finished Date', 'finished_date')
                ->rules('after_or_equal:started_date')
            // ->format("YYYY-MM-DD")
                ->onlyOnDetail(),

            Number::make('Evaluation', 'test_score')->onlyOnDetail(),
            Number::make('Task Score', 'review_score')->onlyOnDetail(),

            Boolean::make('Completed', 'is_certified')
                ->sortable()
                ->exceptOnForms(),

            Text::make('Certificate', function () {
                if ($this->certificate_url && $this->is_certified) {
                    $file = Storage::disk('s3')->temporaryUrl($this->certificate_url, now()->addHour());
                    return "<div class='edit-button-main' style='display: flex;align-items: center;justify-content: start;color: #b3b9bf;'><a style='color:#ffffff;font-size:12px;' class='btn btn-default btn-danger' href='{$file}' class='product-edit-button' target='_blank'>View</a></div>";
                }
                return '—';
            })
                ->asHtml()
                ->onlyOnDetail(),

            // Select::make('Status', 'status')
            //     ->options(\App\Models\Enrollment::STATUSES())
            //     ->displayUsingLabels()
            //     ->sortable()
            //     ->showOnCreating(false),

            Hidden::make('status')
                ->showOnCreating()
                ->showOnUpdating(false)
                ->default(\App\Models\Enrollment::ACTIVE),

            Boolean::make('Task Submitted', 'project_submitted')->onlyOnDetail(),

            // Text::make('All Watched', 'is_all_content_watched')->exceptOnForms(),

            DateTime::make('Certification Date', 'certification_date')
                ->rules('after_or_equal:started_date')
                ->onlyOnDetail(),

            (new Tabs('Tabs', [
                'Discussions' => [
                    HasMany::make('Discussions', 'dicussions', Discussion::class),
                ],
                'Tasks' => [
                    HasOne::make('Tasks', 'project', Project::class),
                ],
                // 'Answers' => [
                //     BelongsToMany::make('Answers', 'questionAnswers', QuestionAnswer::class),
                // ],
            ]))->defaultSearch(true),

            // BelongsToMany::make('Question Answers', 'questionAnswers', QuestionAnswer::class),

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
            (new CustomBooleanFilter('Is Completed', 'is_certified', 'Yes', 'No')),
            // (new CustomBooleanFilter(
            //     'Status',
            //     'status',
            //     ModelsEnrollment::STATUSES()[ModelsEnrollment::ACTIVE],
            //     ModelsEnrollment::STATUSES()[ModelsEnrollment::CANCELLED],
            //     ModelsEnrollment::ACTIVE,
            //     ModelsEnrollment::CANCELLED,
            //     // ModelsEnrollment::STATUSES()[ModelsEnrollment::COMPLETED],
            //     // ModelsEnrollment::COMPLETED,
            // )),
            (new DateFrom('Started From', 'started_date')),
            (new DateTo('Started To', 'started_date')),
            (new DateFrom('Finished From', 'finished_date')),
            (new DateTo('Finished To', 'finished_date')),
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
            // new CancelEnrollment,
            // new ActivateEnrollment,
            (new DownloadExcel)->withHeadings()->allFields()->except('certificate')
        ];
    }

    /**
     * Handle any post-validation processing.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidation(NovaRequest $request, $validator)
    {
        $userId = $request->post('user');
        $programId = $request->post('program');
        // $unique = Rule::unique('enrollments', 'user_id')->where(
        //     'program_id',
        //     $programId
        // );
        // if ($request->route('resourceId')) {
        //     $unique->ignore($request->route('resourceId'));
        // }

        // $uniqueValidator = Validator::make($request->all(), [
        //     'user_id' => [$unique],
        // ]);

        $isExists = \App\Models\Enrollment::where('user_id',$userId)
            ->where('program_id',$programId)
            ->when($request->route('resourceId'),function($query,$value){
                $query->where('id', '<>', $value);
            })
            ->count();

        if ($isExists) {
            $validator
                ->errors()
                ->add(
                    'user',
                    'This student is already taken on the below selected program or workshop.'
                );
            return;
        }
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->select('enrollments.*', 'u.first_name' ,'p.title' ,'c.phone_code');
        $query->leftJoin('users as u', 'enrollments.user_id', '=', 'u.id');
        $query->leftJoin('programs as p', 'enrollments.program_id', '=', 'p.id');
        $query->leftJoin('countries as c', 'u.country_id', '=', 'c.id');
        return $query;
    }

}
