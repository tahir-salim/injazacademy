<?php

namespace App\Nova;

use App\Http\Controllers\UserController;
use App\Nova\Actions\MakeStudentMentorAction;
use App\Nova\Filters\CustomBooleanFilter;
use App\Nova\Filters\StudentStatusFilter;
use App\Nova\Traits\UserTrait;
use Carbon\Carbon;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Student extends Resource
{
    // use UserTrait;

    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'email', 'last_name', 'middle_name' , 'ar_name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        // $area = UserController::getArea();

        $fields = [
            Hidden::make('Role', 'role_id')
                ->default(\App\Models\Role::STUDENT),

            Image::make('Avatar', 'avatar')
            ->disk('s3')
            ->path('images/users')
            ->squared()
            ->rules('mimes:jpg,png'),

            Text::make('Name', 'first_name' , function(){
               $name = null;
               $name = $this->name;
               if($this->ar_name)
                $name .= '/'.$this->ar_name;
            //    $this->name .'/'. $this->ar_name;

               return $name;
            })->onlyOnIndex()->sortable(),

            Text::make('CPR', 'cpr')->onlyOnIndex()->sortable(),

            Select::make('Gender', 'gender')
            ->options([
                'male' => 'Male',
                'female' => 'Female',
            ])
            ->rules('required')
            ->displayUsingLabels()->sortable(),



            // Stack::make('Name', [
            //     Text::make('Name', 'name'),
            //     Text::make('Arabic Name', 'ar_name'),
            // ])
            //     ->onlyOnIndex()->sortable(),

            Text::make('First Name', 'first_name')
                ->rules('required')
                ->displayUsing(function ($value) {
                    return Str::title($value);
                })
                ->hideFromIndex(),

            Text::make('Middle Name', 'middle_name')
                ->displayUsing(function ($value) {
                    return Str::title($value);
                })
                ->hideFromIndex(),

            Text::make('Last Name', 'last_name')
                ->rules('required')
                ->displayUsing(function ($value) {
                    return Str::title($value);
                })
                ->hideFromIndex(),

            Text::make('Arabic Full Name', 'ar_name')
                ->rules('required')
                ->sortable()
                ->hideFromIndex(),

            Text::make('Email')
                ->sortable()
                ->onlyOnForms()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Stack::make('Email', [
                Text::make('Email'),
                Line::make('updated_at', function () {
                    return "Created : " . optional($this->updated_at)->diffForHumans();
                })->asSmall()->extraClasses('italic font-medium text-120'),
            ])->sortable()->hideOnExport(),

            BelongsTo::make('Nationality', 'country', Country::class)->displayUsing(function ($country) {
                return $country->name;
            })->onlyOnIndex()->sortable()->hideOnExport(),

            Password::make('Password')
            ->hideOnExport()
            ->onlyOnForms()
            ->creationRules('required', 'string', 'min:6')
            ->updateRules('nullable', 'string', 'min:6'),

            BelongsTo::make('Country', 'country', Country::class)->displayUsing(function ($country) {
                return $country->name . ' ( ' . $country->phone_code . ' ) ';
            })->hideFromIndex(),

            Number::make('Phone No', 'phone')
                ->sortable()
                ->rules('required', 'min:6', 'max:12')
                ->displayUsing(function () {
                    if ($this->phone) {
                        return ($this->country ? '(' . optional($this->country)->phone_code . ') ' : '') . $this->phone;
                    }

                    return '—';
                })
                ->help('Phone number without phone code')
                ->hideFromIndex(),

            // Stack::make('CPR / Passport', [
            //     Text::make('CPR', 'cpr'),
            //     Line::make('Country', function () {
            //         return optional($this->country)->name;
            //     })->asSmall()->extraClasses('italic font-medium text-120'),
            // ])->onlyOnIndex(),

            Text::make('CPR / Passport', 'cpr')
                ->rules('required', 'min:5', 'max:10')
                ->hideFromIndex(),

            Date::make('Date Of Birth', 'dob')
                ->rules(
                    'after:' . Carbon::now()
                        ->subYears(99)
                        ->toDateString(),
                    'before:' . Carbon::now()
                        ->subYears(2)
                        ->toDateString(),
                    'required'
                )
                ->format('YYYY-MM-DD')
                ->hideFromIndex(),

            Text::make('Area', 'area_name')->onlyOnDetail(),
            Text::make('School', 'school_name')->onlyOnDetail(),
            Select::make('Gender', 'gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ])
                ->rules('required')
                ->displayUsingLabels()
                ->hideFromIndex(),

            Boolean::make('Active', function () {
                return $this->activeInApp;
            })
                ->exceptOnForms(),

            Boolean::make('Is Blocked', 'is_blocked')
                ->sortable()
                ->default(false)->hideFromIndex(),


        ];
        $extraFields = [



            Date::make('Last Activity At', 'last_activity_at')
                ->onlyOnDetail(),

            AttachMany::make('Interests', 'usertag', Tag::class),

            (new Tabs('Tabs', [
                'Enrollments' => [
                    HasMany::make('Enrollments', 'enrollments', Enrollment::class),
                ],
                'Favourite Programs' => [
                    BelongsToMany::make('Favourite Programs', 'programs', Program::class),
                ],
                'Discussions' => [
                    HasMany::make('Discussions','discussions',Discussion::class),
                ],
                'liked Tasks' => [
                    MorphedByMany::make('liked Tasks','likedTasksList',Project::class),
                ],
                'liked Discussions' => [
                    MorphedByMany::make('Liked Discussions','likedDiscussionsList', Discussion::class),
                ],
                // 'liked Tasks' => [
                //     HasMany::make('liked Tasks', 'likedTasks', Like::class),
                // ],
                // 'liked Discussions' => [
                //     HasMany::make('liked Discussions', 'likedDiscussions', Like::class),
                // ],
                'Interests' => [
                    BelongsToMany::make('Tags', 'userTag', Tag::class),
                ],
            ]))->defaultSearch(true),
        ];

        return array_merge(
            $fields,
            // $this->commonFields(),
            $extraFields
        );
    }
    // public function attachprograms(User $user, \App\Models\Program $program)
    // {
    //     return false;
    // }

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
            (new CustomBooleanFilter('Is Blocked', 'is_blocked', 'Blocked', 'Opened')),
            StudentStatusFilter::make()
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
            MakeStudentMentorAction::make(),
            (new DownloadExcel)->withHeadings()->allFields()
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role_id', 3);
    }

    public static function relatableTags(NovaRequest $request, $query)
    {
        return $query->where('status', 1);
    }
}
