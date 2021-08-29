<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends AbstractAdminController
{
    /**
     * メールのリンクをクリックした時、確認済みにする処理
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        // すでに確認済みの場合
        if ($request->user()->hasVerifiedEmail()) {
            return redirect(RouteServiceProvider::ADMIN_HOME);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect(RouteServiceProvider::ADMIN_HOME)->with('status', 'メールアドレスの確認が完了しました');
    }
}
