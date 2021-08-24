<?php

use App\Http\Controllers\Web\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Web\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Web\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Web\Auth\NewPasswordController;
use App\Http\Controllers\Web\Auth\PasswordResetLinkController;
use App\Http\Controllers\Web\Auth\RegisteredUserController;
use App\Http\Controllers\Web\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')
    ->get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::middleware('guest')
    ->post('/register', [RegisteredUserController::class, 'store']);

Route::middleware('guest')
    ->get('/login', [AuthenticatedSessionController::class, 'show'])
    ->name('login');

Route::middleware('guest')
    ->post('/login', [AuthenticatedSessionController::class, 'login']);

Route::middleware('guest')
    ->get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::middleware('guest')
    ->post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::middleware('guest')
    ->get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::middleware('guest')
    ->post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

Route::middleware('auth')
    ->get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->name('verification.notice');

Route::middleware(['auth', 'signed', 'throttle:6,1'])
    ->get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->name('verification.verify');

Route::middleware(['auth', 'throttle:6,1'])
    ->post('/email/verification-notification', [EmailVerificationNotificationController::class, 'send'])
    ->name('verification.send');

Route::middleware('auth')
    ->get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->name('password.confirm');

Route::middleware('auth')
    ->post('/confirm-password', [ConfirmablePasswordController::class, 'passowrdCheck']);

Route::middleware('auth')
    ->post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
