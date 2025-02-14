<?php

namespace App\Providers;

use App\Models\Enrollment;
use App\Models\QuestionAnswer;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Policies\EnrollmentPolicy;
use App\Policies\QuestionAnswerPolicy;
use App\Policies\TestPolicy;
use App\Policies\TestQuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Like' => 'App\Policies\LikePolicy',
        'App\Models\MentorRequest' => 'App\Policies\MentorRequestPolicy',
        Test::class => TestPolicy::class,
        TestQuestion::class => TestQuestionPolicy::class,
        QuestionAnswer::class => QuestionAnswerPolicy::class,
        'App\Models\Program' => 'App\Policies\ProgramPolicy',
        'App\Models\Task' => 'App\Policies\TaskPolicy',
        Enrollment::class => EnrollmentPolicy::class,
        'App\Models\User' => 'App\Policies\StudentPolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
