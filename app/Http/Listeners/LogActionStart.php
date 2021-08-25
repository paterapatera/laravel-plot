<?php

namespace App\Http\Listeners;

use App\Http\Events\ActionStarting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Monolog\Processor\IntrospectionProcessor;

class LogActionStart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActionStarting  $event
     * @return void
     */
    public function handle(ActionStarting $event)
    {
        Log::info(' アクション開始：' . $event->getRequest()->getRequestUri());
    }
}
