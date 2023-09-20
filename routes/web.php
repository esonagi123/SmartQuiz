<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\QuizCore;

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
    return view('base');
});

Route::get('quiz', [QuizCore::class, 'index'])->name('quiz.index');
Route::get('/login', function () {
    return view('account/login');
});

Route::get('/register', function () {
    return view('account/register');
});

Route::get('/forgot-pw', function () {
    return view('account/forgot-pw');
});

Route::get('/quiz', function () {
    return view('/quiz/index');
});