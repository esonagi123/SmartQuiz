<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\QuizCore;
use App\Http\Controllers\Account\Account;
use App\Http\Controllers\Account\Login;
use App\Http\Controllers\MainController;

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
    return view('main.index');
});

Route::get('main', [MainController::class, 'index'])->name('main.index');

Route::get('quiz', [QuizCore::class, 'index'])->name('quiz.index');
Route::get('quiz/create', [QuizCore::class, 'create'])->name('quiz.create');
Route::post('quiz/store', [QuizCore::class, 'store'])->name('quiz.store');

Route::get('quiz/{testID}/create', [QuizCore::class, 'createQuestion'])->name('quiz.createQuestion');
Route::post('quiz/storeQuestion', [QuizCore::class, 'ajax_QuestionStore'])->name('ajax.QuestionStore');
Route::post('quiz/updateQuestion', [QuizCore::class, 'ajax_QuestionUpdate'])->name('ajax.QuestionUpdate');
Route::post('quiz/storeChoice', [QuizCore::class, 'ajax_ChoiceStore'])->name('ajax.ChoiceStore');
Route::delete('quiz/destroyChoice', [QuizCore::class, 'ajax_ChoiceDestroy'])->name('ajax.ChoiceDestroy');


Route::get('register', [Account::class, 'index'])->name('register');
Route::post('register/join', [Account::class, 'store'])->name('register.join');
Route::get('login', [Login::class, 'index'])->name('login');
Route::post('login/check', [Login::class, 'check'])->name('login.check');


Route::get('register', [Account::class, 'index'])->name('register');

Route::get('/quiz', function () {
    return view('quiz.index');
});