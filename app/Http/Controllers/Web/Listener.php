<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web;

class Listener
{
    /**
     * リスナーを登録
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        Web\Auth\Listener::subscribe($events);
    }
}
