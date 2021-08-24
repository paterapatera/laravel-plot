<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * パスワード忘れた場合の画面
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('web.auth.forgot-password');
    }

    /**
     * パスワードリセット処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // パスワードリセットリンクを送信
        $status = Password::sendResetLink(
            $request->only('email')
        );
        // パスワードがリセットメールが送信された場合は、メッセージを付けて前画面にリダイレクト
        // エラーが発生した場合は、入力情報を保持したままエラーメッセージをつけて前画面にリダイレクト
        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
