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
        \App\Http\Events\ActionStarting::class => [
            \App\Http\Listeners\LogActionStart::class
        ],
        // \App\Http\Events\ActionFinished::class => [
        //     \App\Http\Listeners\LogActionFinish::class
        // ],
    ];

    /**
     * サブスクライバクラス
     *
     * @var array
     */
    protected $subscribe = [
        \App\Console\Commands\Listener::class,
        \App\Http\Controllers\Web\Listener::class,
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
