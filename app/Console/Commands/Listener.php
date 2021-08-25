<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Logging\Channel;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Console\Events\CommandFinished;

class Listener
{
    /**
     * コマンドのリスナを登録
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            CommandStarting::class,
            fn (CommandStarting $event) => Log::channel(Channel::COMMAND)
                ->info($event->command . ' コマンド開始 ', ['input' => $event->input])
        );

        $events->listen(
            CommandFinished::class,
            fn (CommandFinished $event) => Log::channel(Channel::COMMAND)
                ->info($event->command . ' コマンド終了 ')
        );
    }
}
