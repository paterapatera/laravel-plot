<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\View\View;
use \Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Auth\StatefulGuard;

class AuthController extends \App\Http\Controllers\Controller
{
    /**
     * ログインフォーム画面
     */
    public function showLoginForm(): View|ViewFactory
    {
        return view('admin.auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(Request $request): View|RedirectResponse
    {
        $credentials = $request->only(['email', 'password']);

        $guard = Auth::guard('admin');
        // メールとパスワードで一致するAdminがあるか
        if ($guard instanceof StatefulGuard && $guard->attempt($credentials)) {
            $redirect = 'admin/dashboard';
            Log::info('認証に成功しました', ['redirect' => $redirect]);
            return redirect($redirect);
        }

        Log::notice('認証に失敗しました');
        return back()->withErrors([
            'auth' => ['認証に失敗しました']
        ]);
    }
}
