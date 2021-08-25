<?php

namespace App\Http\Listeners;

use App\Http\Events\ActionFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogActionFinish
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
     * @param  ActionFinished  $event
     * @return void
     */
    public function handle(ActionFinished $event)
    {
        Log::info('アクション終了');
    }
}
