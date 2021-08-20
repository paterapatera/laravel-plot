<?php

declare(strict_types=1);

namespace App\Logging;

use Monolog\Processor\UidProcessor;
use Illuminate\Support\Facades\Auth;

class ExLogger
{
    /**
     * ロガーの拡張
     * 
     * ログのextraにuidとuser_idを追加
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor(new UidProcessor());
            $handler->pushProcessor(function (array $record): array {
                $record['extra']['user_id'] = Auth::id() ?? '-';
                return $record;
            });
        }
    }
}
