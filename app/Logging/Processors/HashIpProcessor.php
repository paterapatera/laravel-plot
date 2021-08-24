<?php

declare(strict_types=1);

namespace App\Logging\Processors;

use Monolog\Processor\ProcessorInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * ログにハッシュ化したIPアドレスを記録する
 */
class HashIpProcessor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(array $record): array
    {
        $record['extra']['hash_ip'] = md5($_SERVER["REMOTE_ADDR"]);

        return $record;
    }
}
