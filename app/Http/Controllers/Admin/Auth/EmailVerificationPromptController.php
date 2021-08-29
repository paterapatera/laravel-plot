<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends AbstractAdminController
{
    /**
     * メールを確認するように促す画面
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        // すでに確認済みの場合はHOMEにリダイレクト
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::ADMIN_HOME)
            : view('admin.auth.verify-email');
    }
}
