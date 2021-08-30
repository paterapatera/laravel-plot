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
     * コントローラのアクション開始時のログ
     */
    public function handle(ActionStarting $event): void
    {
        Log::info('' . $event->getRequest()->method() . ' ' . $event->getRequest()->getRequestUri());
    }
}
