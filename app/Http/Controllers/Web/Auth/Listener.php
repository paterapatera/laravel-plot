<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Web\Auth\Listeners;

class Listener
{
    /**
     * リスナーを登録
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    static public function subscribe($events)
    {
        Listeners\LogListener::subscribe($events);
    }
}
