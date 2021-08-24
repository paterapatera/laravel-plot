<?php

declare(strict_types=1);

namespace App\Logging\Loggers;

use App\Logging\Processors\UserIdProcessor;
use Monolog\Processor\UidProcessor;
use App\Logging\Processors\HashIpProcessor;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\ProcessableHandlerInterface;

class ExLogger
{
    /**
     * ロガーの拡張
     * 
     * 出力フォーマットをJSON形式に変更
     * ログのextraにuidとuser_idとhash_ipを追加
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        // フォーマット形式変更
        $formatter = new JsonFormatter();

        foreach ($logger->getHandlers() as $handler) {
            // ログファイル名のフォーマット変更
            // if ($handler instanceof RotatingFileHandler) {
            //     $handler->setFilenameFormat("{filename}-{date}", 'Y-m-d');
            // }

            if ($handler instanceof FormattableHandlerInterface) {
                $handler->setFormatter($formatter);
            }

            if ($handler instanceof ProcessableHandlerInterface) {
                // 項目追加
                $handler->pushProcessor(new UidProcessor());
                $handler->pushProcessor(new UserIdProcessor());
                $handler->pushProcessor(new HashIpProcessor());
            }
        }
    }
}
