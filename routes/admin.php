<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web;

// ログイン
Route::get('admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);

// 認証後にアクセスできるルート
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    // ログアウト
    Route::get('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout']);

    Route::get('dashboard', function () {
        return '未実装';
    });
});
