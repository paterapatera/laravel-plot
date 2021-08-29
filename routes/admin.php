<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\Auth\RegisteredAdminController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredAdminController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredAdminController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'show'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'login']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware(['auth:admin', 'verified'])->group(function () {
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'passowrdCheck']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
});

Route::middleware(['auth:admin', 'signed', 'throttle:6,1'])
    ->get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->name('verification.verify');

Route::middleware(['auth:admin', 'throttle:6,1'])
    ->post('/email/verification-notification', [EmailVerificationNotificationController::class, 'send'])
    ->name('verification.send');
