# 認証システム

```
jetstreamはだめだった  
ログイン画面がブラックボックスで、複数の認証が作りづらい  
Breezeで作るのがいいかも
```

```sh
seil composer require laravel/breeze --dev

sail artisan breeze:install

seil shell

npm install
npm run dev
php artisan migrate
```

# 追加・変更したファイル
- [app/Models/Admin.php](../app/Models/Admin.php)
- [app/Http/Controllers/Admin/Auth/](../app/Http/Controllers/Web/Auth)
- [resources/views/web/auth/](../resources/views/admin/auth)
- [routes/admin.php](../routes/admin.php)
- [app/Http/Requests/Web/Auth/LoginRequest.php](../app/Http/Requests/Admin/Auth/LoginRequest.php)
- [resources/views/dashboard.blade.php](../resources/views/admin/dashboard.blade.php)
- [app/Models/User.php](../app/Models/User.php)
- [app/Http/Controllers/Web/Auth/](../app/Http/Controllers/Web/Auth)
- [resources/views/web/auth/](../resources/views/web/auth)
- [routes/auth.php](../routes/auth.php)
- [app/Http/Requests/Web/Auth/LoginRequest.php](../app/Http/Requests/Web/Auth/LoginRequest.php)
- [app/Http/Middleware/RedirectIfAuthenticated.php](../app/Http/Middleware/RedirectIfAuthenticated.php)
- [app/Providers/RouteServiceProvider.php](../app/Providers/RouteServiceProvider.php)
- [app/Providers/AuthServiceProvider.php](../app/Providers/AuthServiceProvider.php)
- [resources/css/app.css](../resources/css/app.css)
- [resources/js/app.js](../resources/js/app.js)
- [resources/views/components/](../resources/views/components)
- [resources/views/components/layouts](../resources/views/components/layouts)
- [resources/views/dashboard.blade.php](../resources/views/dashboard.blade.php)
- [webpack.mix.js](../webpack.mix.js)

## ルートの追加

`as()` の追加でルート名にもプレフィックスを付ける
adminの追加

```php
// app/Providers/RouteServiceProvider.php

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->as('api.')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->as('admin.')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
        });
    }
```

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
strcmpを使ってるのは処理が早いため、`$request->is('admin*')`でも問題ないと思う

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

## Adminの場合の処理追加

- Controllerとviewのリダイレクトとルートの変更

```php
// routes/admin.php
// authをadminにして、verified追加、verifiedのルート先変更

Route::middleware(['auth:admin', 'verified:admin.verification.notice'])->group(function () {
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'passowrdCheck']);
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
});
```

```php
// app/Http/Middleware/RedirectIfAuthenticated.php
// ログイン画面など認証時には入れない画面のリダイレクト処理

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard(self::GUARD_ADMIN)->check() && $request->is('admin*')) {
            return redirect(RouteServiceProvider::ADMIN_HOME);
        } elseif (Auth::guard(self::GUARD_WEB)->check() && !$request->is('admin*')) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
```

```php
// app/Providers/AuthServiceProvider.php
// Admin時にURLを変更するように設定

    public function boot()
    {
        $this->registerPolicies();

        self::resetPasswordMailSetting();
        self::verifyEmailMailSetting();
    }
```


```php
// app/Models/Admin.php
// リセットパスワードと確認メールのインターフェース追加

class Admin extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    ...
```
