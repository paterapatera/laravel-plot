<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use App\Logging\Loggers\ExLogger;

return [

    /*
    | ---------------------------------------------------------------------------
    | デフォルトのログチャンネル
    | ---------------------------------------------------------------------------
    |
    | ログの書き込み時に使用されるデフォルトのログチャンネルを定義
    | 「channels」配列で定義しているものを指定
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | ログチャンネル
    |--------------------------------------------------------------------------
    |
    | 主に「stack」の「channels」の変更、追加や新しいチャンネルの定義をする
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['web'],
            'ignore_exceptions' => false,
        ],

        // ブラウザアクセスで使用されるログ設定
        'web' => [
            'driver' => 'daily',
            'path' => storage_path('logs/web-event.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 3, // 保管されるファイル数。それ以上になった場合、古いものから削除される
            'tap' => [ExLogger::class], // フォーマットやExtraにUIDとUserIDの追加
        ],

        // Commandで使用されるログ設定
        'command' => [
            'driver' => 'daily',
            'path' => storage_path('logs/command-event.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 3, // 保管されるファイル数。それ以上になった場合、古いものから削除される
            'tap' => [ExLogger::class], // フォーマットやExtraにUIDとUserIDの追加
        ],

        // APIで使用されるログ設定
        'api' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api-event.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 3, // 保管されるファイル数。それ以上になった場合、古いものから削除される
            'tap' => [ExLogger::class], // フォーマットやExtraにUIDとUserIDの追加
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];
