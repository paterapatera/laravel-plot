<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    private const GUARD_WEB = 'web';
    private const GUARD_ADMIN = 'admin';

    /**
     * 認証済みの場合はリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard(self::GUARD_ADMIN)->check() && $request->is('admin.*')) {
            return redirect(RouteServiceProvider::ADMIN_HOME);
        } elseif (Auth::guard(self::GUARD_WEB)->check() && !$request->is('admin.*')) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
