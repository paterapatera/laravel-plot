<?php

declare(strict_types=1);

namespace App\Logging\Loggers;

use App\Logging\Processors\UserIdProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Formatter\JsonFormatter;

class ExLogger
{
    /**
     * ロガーの拡張
     * 
     * 出力フォーマットをJSON形式に変更
     * ログのextraにuidとuser_idを追加
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        // フォーマット形式変更
        $formatter = new JsonFormatter();

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            // ログファイル名のフォーマット変更
            // if ($handler instanceof RotatingFileHandler) {
            //     $handler->setFilenameFormat("{filename}-{date}", 'Y-m-d');
            // }

            // 項目追加
            $handler->pushProcessor(new UidProcessor());
            $handler->pushProcessor(new UserIdProcessor());
        }
    }
}
