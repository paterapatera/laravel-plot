<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Auth\Listeners;

use Illuminate\Support\Facades\Log;

class LogListener
{
    /**
     * 認証ログに関するリスナーを登録
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    static public function subscribe($events)
    {
        $listen = [
            \Illuminate\Auth\Events\Registered::class
            => fn ($event) => Log::info('ユーザー作成成功', [
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\Attempting::class
            => fn ($event) => Log::info('認証中', [
                'guard' => $event->guard,
                'remember' => $event->remember,
            ]),
            \Illuminate\Auth\Events\Authenticated::class
            => fn ($event) => Log::info('自動ログイン', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\Login::class
            => fn ($event) => Log::info('ログイン', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
                'remember' => $event->remember,
            ]),
            \Illuminate\Auth\Events\Failed::class
            => fn ($event) => Log::info('認証失敗', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\Validated::class
            => fn ($event) => Log::info('認証成功', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\Verified::class
            => fn ($event) => Log::info('確認メール完了', [
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\Logout::class
            => fn ($event) => Log::info('ログアウト', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\CurrentDeviceLogout::class
            => fn ($event) => Log::info('現在のアプリケーションのみログアウト', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\OtherDeviceLogout::class
            => fn ($event) => Log::info('現在のデバイス以外をログアウト', [
                'guard' => $event->guard,
                'user_id' => optional($event->user)->id,
            ]),
            \Illuminate\Auth\Events\Lockout::class
            => fn () => Log::info('ユーザーロック'),
            \Illuminate\Auth\Events\PasswordReset::class
            => fn ($event) => Log::info('新しいパスワードに変更', [
                'user_id' => optional($event->user)->id,
            ]),
        ];
        collect($listen)->each(fn ($val, $key) => $events->listen($key, $val));
    }
}
