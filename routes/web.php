<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    Illuminate\Support\Facades\Log::info('aaa', ['aaaa' => 123]);
    App\Models\User::where('password', '=', 'aaaa')->get();
    return view('welcome');
});
