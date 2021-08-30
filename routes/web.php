<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web;

Route::get('/', function () {
    return view('welcome');
});

// ------------------------

Route::get('/csv', [Web\CsvController::class, 'index'])->name('csv');
Route::get('/csv/download', [Web\CsvController::class, 'download'])->name('csv.download');


// ------------------------

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['localEnv'])->group(function () {
    Route::get('/read-log', [Web\ReadLogController::class, 'index']);
    Route::get('/read-log/show', [Web\ReadLogController::class, 'show']);
});

require __DIR__ . '/auth.php';
