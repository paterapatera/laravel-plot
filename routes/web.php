<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['localEnv'])->group(function () {
    Route::get('/read-log', [Web\ReadLogController::class, 'index']);
    Route::get('/read-log/show', [Web\ReadLogController::class, 'show']);
});
