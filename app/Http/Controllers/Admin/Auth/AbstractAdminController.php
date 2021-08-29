<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

abstract class AbstractAdminController extends Controller
{
    /** @var \Illuminate\Contracts\Auth\PasswordBroker */
    protected $password;
    /** @var \Illuminate\Contracts\Auth\StatefulGuard */
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard('admin');
        $this->password = Password::broker('admins');
    }
}
