<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * 認証されていないときにユーザーがリダイレクトされるパスを取得
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // URLがadminから始まっている場合
            if (strncmp($request->path(), 'admin', 5) === 0) {
                return 'admin/login';
            }
            return route('login');
        }
    }
}
