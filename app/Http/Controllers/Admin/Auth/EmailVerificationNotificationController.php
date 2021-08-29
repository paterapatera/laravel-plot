<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends AbstractAdminController
{
    /**
     * 登録確認メール送信
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // すでに確認済みの場合
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
