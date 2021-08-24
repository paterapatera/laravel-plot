# 認証システム

```
jetstreamはだめだった  
ログイン画面がブラックボックスで、複数の認証が作りづらい  
Breezeで作るのがいいかも
```

## モデルとコントローラとルーターの作成
- [app/Http/Controllers/Admin/AuthController.php](../app/Http/Controllers/Admin/AuthController.php)
- [app/Models/Admin.php](../app/Models/Admin.php)
- [routes/admin.php](../routes/admin.php)

## マイグレーション追加

- [database/migrations/2021_08_23_200000_create_admins_table.php](../database/migrations/2021_08_23_200000_create_admins_table.php)
- [database/migrations/2021_08_23_200001_add_admin_two_factor_columns_to_users_table.php](../database/migrations/2021_08_23_200001_add_admin_two_factor_columns_to_users_table.php)

## Viewの追加

- [resources/views/admin/auth/*](../resources/views/admin/auth)

## 設定ファイルの修正

### 認証モデルとガードの追加

[config/auth.php](../config/auth.php)

```php
    'guards' => [
        ...
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        ...
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        ...
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
```

### 認証失敗時のリダイレクト処理追加

[app/Http/Middleware/Authenticate.php](../app/Http/Middleware/Authenticate.php)

```php
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // URLがadminから始まっている場合
            if (strncmp($request->path(), 'admin', 5) === 0) {
                return 'admin/login';
            }
            ...
        }
    }
```

### ルートファイルの登録

[app/Providers/RouteServiceProvider.php](../app/Providers/RouteServiceProvider.php)

```php
    public function boot()
    {
        ...
        $this->routes(function () {
            ...
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
        });
    }
```
