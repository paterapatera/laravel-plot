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
        // コマンド実行時は$_SERVERが存在しないのでハイフンを表示
        $ipExists = array_key_exists('REMOTE_ADDR', $_SERVER);
        $record['extra']['hash_ip'] = $ipExists ? md5($_SERVER['REMOTE_ADDR']) : '-';

        return $record;
    }
}
