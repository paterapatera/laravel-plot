<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

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

        self::resetPasswordMailSetting();
        self::verifyEmailMailSetting();
    }

    /**
     * パスワードリセットのメール内容をカスタマイズ
     * 
     * 管理者とユーザーでリンク先を変更して、日本語のメール内容に変更
     *
     * @return void
     */
    static protected function resetPasswordMailSetting(): void
    {
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

    /**
     * メール確認のメール内容をカスタマイズ
     * 
     * 管理者とユーザーでリンク先を変更して、日本語のメール内容に変更
     *
     * @return void
     */
    static protected function verifyEmailMailSetting(): void
    {
        // 確認メールの内容変更
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            // 管理者とユーザーでリンク先を変更
            $route = strncmp(Request::path(), 'admin', 5) === 0 ? 'admin.verification.verify' : 'verification.verify';
            $url = URL::temporarySignedRoute(
                $route,
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return (new MailMessage)->markdown('email.verify-email', ['actionUrl' => $url])
                ->subject('メールアドレスを確認してください');
        });
    }
}
