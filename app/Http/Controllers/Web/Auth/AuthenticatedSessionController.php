<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * ログインフォーム
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        return view('web.auth.login');
    }

    /**
     * ログイン
     *
     * @param  \App\Http\Requests\Web\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * ログアウト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\StatefulGuard */
        $guard = Auth::guard('web');
        $guard->logout();

        // セッションから全てのデータを削除して再生成
        $request->session()->invalidate();
        // トークンの再生成
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
