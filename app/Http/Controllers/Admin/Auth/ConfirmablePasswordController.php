<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * アカウントの重要情報を変更する前のパスワード再確認する機能
 */
class ConfirmablePasswordController extends AbstractAdminController
{
    /**
     * パスワード確認フォーム
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        return view('admin.auth.confirm-password');
    }

    /**
     * パスワードチェック処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passowrdCheck(Request $request)
    {
        if (!$this->auth->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }
}
