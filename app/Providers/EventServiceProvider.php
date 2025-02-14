<?php

namespace App\Providers;

use App\Models\Chapter;
use App\Models\MentorRequest;
use App\Models\Program;
use App\Observers\ChapterObserver;
use App\Observers\MentorRequestObserver;
use App\Observers\ProgramObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
     
        // MentorRequest::observe(MentorRequestObserver::class);
        // Chapter::observe(ChapterObserver::class);
        // Program::observe(ProgramObserver::class);

    }
}
