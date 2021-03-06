# ログまわりのカスタマイズ

## 変更内容

- ログを日次でローテーション
- コマンド、WEB、APIでファイルを分ける
- フォーマットをJSON形式に変更
- ログにUserID追加
- ログに処理単位のUID追加
- SQLエラーのログレベルをクリティカルに変更
- DB接続エラーのログレベルをアラートに変更
- SQLエラーのSQLのパラメータをマスク化
- ログの圧縮コマンド作成
- HTTPアクセス開始と終了時にログ出力追加
- エラー時メール送信ログチャンネル作成

## 変更したファイル

- [config/logging.php](../config/logging.php)
- [app/Exceptions/Handler.php](../app/Exceptions/Handler.php)
- [app/Exceptions/QueryMaskException.php](../app/Exceptions/QueryMaskException.php)
- [app/Logging/Loggers/ExLogger.php](../app/Logging/Loggers/ExLogger.php)
- [app/Logging/Processors/UserIdProcessor.php](../app/Logging/Processors/UserIdProcessor.php)
- [app/Console/Commands/LogArchive.php](../app/Console/Commands/LogArchive.php)
- [app/Console/Listeners/CommandListener.php](../app/Console/Listeners/CommandListener.php)
- [app/Console/Kernel.php](../app/Console/Kernel.php)
- [app/Http/Middleware/HttpBeforeAfterDispatch.php](../app/Http/Middleware/HttpBeforeAfterDispatch.php)
- [app/Http/Kernel.php](../app/Http/Kernel.php)
- [app/Providers/EventServiceProvider.php](../app/Providers/EventServiceProvider.php)
- [app/Http/Events/ActionStarting.php](../app/Http/Events/ActionStarting.php)
- [app/Http/Events/ActionFinished.php](../app/Http/Events/ActionFinished.php)
- [app/Http/Listeners/LogActionStart.php](../app/Http/Listeners/LogActionStart.php)
- [app/Http/Listeners/LogActionFinish.php](../app/Http/Listeners/LogActionFinish.php)
- [app/Logging/Handlers/MailHandler.php](../app/Logging/Handlers/MailHandler.php)

## ExLogger

ファイル名のフォーマットの変更やログの項目追加は、このファイルを編集することで可能。

```php
// app/Logging/Loggers/ExLogger.php

    public function __invoke($logger)
    {
        // フォーマット形式変更
        $formatter = new JsonFormatter();

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            // ログファイル名のフォーマット変更
            // if ($handler instanceof RotatingFileHandler) {
            //     $handler->setFilenameFormat("{filename}-{date}", 'Y-m-d');
            // }

            // 項目追加
            $handler->pushProcessor(new UidProcessor());
            $handler->pushProcessor(new UserIdProcessor());
        }
    }
```

## 項目追加のためのProcessorの作成

- `app/Logging/Processors/UserIdProcessor.php` をコピーして任意の名前に変更する
- `__invoke()` の内容を編集する
- `app/Logging/Loggers/ExLogger.php` で `$handler->pushProcessor(new <任意の名前>Processor());` を追加する

```php
// app/Logging/Processors/<任意の名前>Processor.php

    public function __invoke(array $record): array
    {
        $record['extra'][<任意の項目名>] = <出力内容>;

        return $record;
    }
```

## Handler

`exceptionMap` に追加することで既存の例外クラスを置き換えることができる

```php
// app/Exceptions/Handler.php

    /**
     * {@inheritDoc}
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        // 既存のExceptionを独自のExceptionに置換
        $this->exceptionMap = [
            QueryException::class => function (QueryException $e): QueryMaskException {
                return new QueryMaskException($e);
            },
        ];
    }
```

## ログの圧縮

Note: 事前にphpでZipArchiveが使えることを確認  

### 下記のコマンドにて圧縮可能
[app/Console/Commands/LogArchive.php](../app/Console/Commands/LogArchive.php)  
ログファイル名の変更時はこのコマンドの調整が必要

### 下記ファイルでスケジュール設定をする
[app/Console/Kernel.php](../app/Console/Kernel.php)

```php
// app/Console/Kernel.php

    protected function schedule(Schedule $schedule)
    {
        ...
        // 月初に先月のログファイルを圧縮
        $schedule->command('log:archive')->monthly()->withoutOverlapping();
    }
```

## HTTPアクセス開始と終了時にログ出力追加

### アクションの最初と最後にイベントを送信するミドルウェアを作成

```php
// app/Http/Middleware/HttpBeforeAfterDispatch.php

    public function handle(Request $request, Closure $next)
    {
        ActionStarting::dispatch($request);
        $response = $next($request);
        ActionFinished::dispatch($request);
        return $response;
    }
```

### ミドルウェアの登録

```php
// app/Http/Kernel.php

    protected $middleware = [
        ...
        \App\Http\Middleware\HttpBeforeAfterDispatch::class,
    ];
```
