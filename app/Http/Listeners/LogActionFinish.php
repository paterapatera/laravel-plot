<?php

namespace App\Http\Listeners;

use App\Http\Events\ActionFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogActionFinish
{
    /**
     * コントローラのアクション終了時のログ
     */
    public function handle(ActionFinished $event): void
    {
        Log::info('アクション終了');
    }
}
