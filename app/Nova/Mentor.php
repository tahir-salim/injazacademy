<?php

namespace App\Nova;

// use App\Enums\UserType;

use App\Models\User;
use App\Nova\Filters\CustomBooleanFilter;
use App\Nova\Actions\UnActivateMentor;
use Carbon\Carbon;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Traits\UserTrait;
use Laravel\Nova\Fields\File;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;


class Mentor extends Resource
{
    use UserTrait;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'email', 'last_name', 'middle_name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        $fields = [
            Hidden::make('Role','role_id')
                ->default(\App\Models\Role::MENTOR),
        ];

        $extraFields = [
            Hidden::make('Role','role_id')
                ->default(\App\Models\Role::MENTOR),

            Text::make('Company', 'company')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Occupation', 'occupation')
                ->rules('required')
                ->hideFromIndex(),

            // Text::make('Year Of Experience', 'experience')
            //     ->rules('required')
            //     ->hideFromIndex(),

            Textarea::make('Brief', 'brief')
                ->rules('required')
                ->hideFromIndex(),

            Boolean::make('Active')
                ->sortable()
                ->default(1),

            // File::make('CPR Attachment','cpr_file')
            //     ->disk('s3')
            //     ->path('files/users'),

            // File::make('CV Attachment','cv')
            //     ->disk('s3')
            //     ->path('files/users'),

            // File::make('Bio Attachment','bio')
            //     ->disk('s3')
            //     ->path('files/users'),

            Text::make('Rating')->sortable()->exceptOnForms(),

            (new Tabs('Tabs', [
                'Follower' => [
                    HasMany::make('Follower', 'followers', Follower::class),
                ],
                'Programs Mentoring' => [
                    BelongsToMany::make('Program', 'mentorPrograms', Program::class)->fields(function () {
                        return [
                            Select::make('Mentor Type')->options(User::MENTOR_TYPES())->displayUsingLabels(),
                        ];
                    }),
                ],
                'Discussions' => [
                    HasMany::make('Discussions','discussions',Discussion::class),
                ],
                // 'Application Request' => [
                //     HasOne::make('Mentor Request', 'mentorRequest', MentorRequest::class),
                // ],
            ])),
        ];

        return array_merge(
            $fields,
            $this->commonFields(),
            $extraFields
        );
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
            (new CustomBooleanFilter('Is Blocked', 'is_blocked', 'Blocked', 'Opened')),
            (new CustomBooleanFilter('Is Active', 'active', 'Active', 'In Active'))
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
            UnActivateMentor::make(),
            (new DownloadExcel)->withHeadings()->allFields()->except('experience')
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role_id', 2);
    }
}
