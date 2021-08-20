# コマンド（バッチ）の作成

## 事前準備(初めて作成するときのみ)

### Cronの編集

```sh
crontab -e
```

### Cronに下記を追加

```sh
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## コマンド作成

```sh
sail artisan make:command <任意のファイル名>
```

## スケジュールの設定
[app/Console/Kernel.php](../app/Console/Kernel.php) にスケジュールを追加
`command()` には、作成したコマンドの `$signature` の値を書く
```php
// app/Console/Kernel.php

    protected function schedule(Schedule $schedule)
    {
        // 1時間ごとにhoge:hogeを起動、多重起動は防止
        $schedule->command('hoge:hoge')->hourly()->withoutOverlapping();
    }
```

[スケジュールの種類](https://readouble.com/laravel/8.x/ja/scheduling.html#schedule-frequency-options)
