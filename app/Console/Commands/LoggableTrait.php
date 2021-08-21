<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Logging\Channel;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

trait LoggableTrait
{
    /**
     * ロガーを取得
     *
     * @return LoggerInterface
     */
    public function log(): LoggerInterface
    {
        return Log::stack([Channel::COMMAND, Channel::NOTIFICATION]);
    }
}
