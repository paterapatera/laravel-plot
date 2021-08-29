<?php

declare(strict_types=1);

namespace App\Logging\Processors;

use Monolog\Processor\ProcessorInterface;
use Illuminate\Support\Facades\Auth;

/**
 * ログにUserIDもしくはAdminIDを記録する
 */
class UserIdProcessor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(array $record): array
    {
        $adminId = Auth::guard('admin')->id();
        $record['extra']['user_id'] = Auth::id() ?? $adminId ? "(admin){$adminId}" : '-';

        return $record;
    }
}
