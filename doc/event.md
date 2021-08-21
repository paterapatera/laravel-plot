# イベント作成

## イベント作成コマンド

1. [app/Providers/EventServiceProvider.php](../app/Providers/EventServiceProvider.php) の `$listen` に作りたいイベントやリスナーを書き込む

```php
// app/Providers/EventServiceProvider.php

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Console\Events\CommandStarting::class => [ // イベント
            \App\Console\Listeners\CommandStartLogger::class   // リスナー
        ],
        \Illuminate\Console\Events\CommandFinished::class => [
            \App\Console\Listeners\CommandFinishLogger::class
        ],
        \App\Http\Events\ActionStarting::class => [
            \App\Http\Listeners\ActionStartLogger::class
        ],
        \App\Http\Events\ActionFinished::class => [
            \App\Http\Listeners\ActionFinishLogger::class
        ],
    ];
```

2. 以下のコマンドを実行

```sh
sail artisan event:generate
```
