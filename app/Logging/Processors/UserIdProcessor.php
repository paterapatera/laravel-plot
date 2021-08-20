<?php

declare(strict_types=1);

namespace App\Logging\Processors;

use Monolog\Processor\ProcessorInterface;
use Illuminate\Support\Facades\Auth;

/**
 * ログにUserIDを記録する
 */
class UserIdProcessor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(array $record): array
    {
        $record['extra']['user_id'] = Auth::id() ?? '-';

        return $record;
    }
}
