<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Logging\Channel;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

trait LoggableTrait
{
    /** @var \Psr\Log\LoggerInterface|null */
    protected $logger;

    /**
     * ロガーを取得
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function log(): LoggerInterface
    {
        if (is_null($this->logger)) {
            $this->logger = Log::stack([Channel::COMMAND, Channel::NOTIFICATION]);
        }

        return $this->logger;
    }
}
