<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Program;
use App\Models\Tag;
use App\Models\Test;
use App\Observers\CategoryObserver;
use App\Observers\ChapterObserver;
use App\Observers\DiscussionObserver;
use App\Observers\EnrollmentObserver;
use App\Observers\NotificationObserver;
use App\Observers\ProgramObserver;
use App\Observers\TagObserver;
use App\Observers\TestObserver;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        Notification::observe(NotificationObserver::class);
        Chapter::observe(ChapterObserver::class);
        Program::observe(ProgramObserver::class);
        Enrollment::observe(EnrollmentObserver::class);
        Discussion::observe(DiscussionObserver::class);
        Tag::observe(TagObserver::class);
        Category::observe(CategoryObserver::class);

        Nova::serving(function () {

            // MentorRequest::observe(MentorRequestObserver::class);
            Category::observe(CategoryObserver::class);
            Chapter::observe(ChapterObserver::class);
            Program::observe(ProgramObserver::class);
            Test::observe(TestObserver::class);
            // Notification::observe(NotificationObserver::class);

        });

    }
}
