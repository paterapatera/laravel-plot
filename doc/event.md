# イベント作成

## イベント作成コマンド

1. [app/Providers/EventServiceProvider.php](../app/Providers/EventServiceProvider.php) の `$listen` に作りたいイベントやリスナーを書き込む
1. 以下のコマンドを実行

```sh
sail artisan event:generate
```
