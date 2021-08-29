<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // リセットメールの内容変更
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            // 管理者とユーザーでリンク先を変更
            $route = strncmp(Request::path(), 'admin', 5) === 0 ? 'admin.password.reset' : 'password.reset';
            $url = url(route($route, [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
            $count = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)->markdown('email.password-reset', ['actionUrl' => $url, 'count' => $count])
                ->subject('アカウントのパスワードリセットを受け付けました');
        });
    }
}
