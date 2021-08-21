<?php

declare(strict_types=1);

namespace App\Console\Listeners;

use App\Logging\Channel;
use App\Logging\Loggers\ExLogger;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CommandStartLogger
{
    /**
     * リスナーインスタンス作成
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * イベント受診時の処理
     *
     * @param  CommandStarting  $event
     * @return void
     */
    public function handle(CommandStarting $event)
    {
        Log::channel(Channel::COMMAND)
            ->info($event->command . ' コマンド開始 ', ['input' => $event->input]);
    }
}
