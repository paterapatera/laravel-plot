<?php

declare(strict_types=1);

namespace App\Console\Listeners;

use App\Logging\Channel;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CommandFinishLogger
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
     * @param  CommandFinished  $event
     * @return void
     */
    public function handle(CommandFinished $event)
    {
        Log::channel(Channel::COMMAND)
            ->info($event->command . ' コマンド終了 ');
    }
}
