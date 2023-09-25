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
Route::get('quiz/create', [QuizCore::class, 'create'])->name('quiz.create');
Route::post('quiz/store', [QuizCore::class, 'store'])->name('quiz.store');

Route::get('quiz/{testID}/create', [QuizCore::class, 'createQuestion'])->name('quiz.createQuestion');
Route::post('quiz/storeQuestion', [QuizCore::class, 'ajax_QuestionStore'])->name('ajax.QuestionStore');
Route::post('quiz/storeChoice', [QuizCore::class, 'ajax_ChoiceStore'])->name('ajax.ChoiceStore');
Route::delete('quiz/destroyChoice', [QuizCore::class, 'ajax_ChoiceDestroy'])->name('ajax.ChoiceDestroy');







Route::get('/login', function () {
    return view('account.login');
});
Route::get('/register', function () {
    return view('account.register');
});
Route::get('/forgot-pw', function () {
    return view('account.forgot-pw');
});
Route::get('/quiz', function () {
    return view('quiz.index');
});