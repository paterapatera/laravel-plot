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
            \App\Console\Listeners\LogCommandStart::class   // リスナー
        ],
        \Illuminate\Console\Events\CommandFinished::class => [
            \App\Console\Listeners\LogCommandFinish::class
        ],
        \App\Http\Events\ActionStarting::class => [
            \App\Http\Listeners\LogActionStart::class
        ],
        \App\Http\Events\ActionFinished::class => [
            \App\Http\Listeners\LogActionFinish::class
        ],
    ];
```

2. 以下のコマンドを実行

```sh
sail artisan event:generate
```


### サブスクライバーの登録

下記のサブスクライバーを用意している

- [app/Http/Controllers/Web/Listener.php](../app/Http/Controllers/Web/Listener.php)
- [app/Http/Controllers/Web/Auth/Listener.php](../app/Http/Controllers/Web/Auth/Listener.php)
- [app/Http/Controllers/Web/Auth/Listeners/LogListener.php](../app/Http/Controllers/Web/Auth/Listeners/LogListener.php)

イベント処理などは `LogListener.php` を参考にするといい

イベントは数が多くなるので、ベース機能のイベントは `$listen`  
機能ごとのイベントは `$subscribe` で登録

```php
// app/Providers/EventServiceProvider.php

    protected $listen = [
        ...
        \App\Http\Events\ActionStarting::class => [
            \App\Http\Listeners\LogActionStart::class
        ],
        \App\Http\Events\ActionFinished::class => [
            \App\Http\Listeners\LogActionFinish::class
        ],
    ];

    protected $subscribe = [
        CommandListener::class,
    ];
```
