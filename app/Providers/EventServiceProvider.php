<?php

namespace App\Providers;

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
        \Illuminate\Console\Events\CommandStarting::class => [
            \App\Console\Listeners\CommandStartLogger::class
        ],
        \Illuminate\Console\Events\CommandFinished::class => [
            \App\Console\Listeners\CommandFinishLogger::class
        ],
        \App\Http\Events\ActionStarting::class => [
            \App\Http\Listeners\ActionStartLogger::class
        ],
        \App\Http\Events\ActionFinished::class => [
            \App\Http\Listeners\ActionFinishLogger::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
