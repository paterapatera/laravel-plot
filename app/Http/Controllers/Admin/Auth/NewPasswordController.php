<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

/**
 * パスワードを忘れた場合のリセットメールのリンクをクリックした時の機能
 */
class NewPasswordController extends AbstractAdminController
{
    /**
     * パスワード変更画面
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(Request $request)
    {
        return view('admin.auth.reset-password', ['request' => $request]);
    }

    /**
     * 新しいパスワードの変更処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ユーザーのパスワードリセット
        $status = $this->password->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin) use ($request) {
                // 成功した場合は、ユーザーのパスワードを更新
                $admin->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($admin));
            }
        );

        // パスワードがリセットされた場合は、ログイン画面にリダイレクト
        // エラーが発生した場合は、入力情報を保持したままエラーメッセージをつけて前画面にリダイレクト
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
