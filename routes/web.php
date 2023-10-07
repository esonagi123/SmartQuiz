<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\QuizCore;
use App\Http\Controllers\Account\Account;
use App\Http\Controllers\Account\Login;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return redirect('/quiz');
});

// Route::get('main', [MainController::class, 'index'])->name('main.index');
Route::prefix('quiz')->group(function () {

    Route::get('/', [QuizCore::class, 'index'])->name('quiz.index');

    Route::get('/create', [QuizCore::class, 'create'])->name('quiz.create');
    Route::post('/store', [QuizCore::class, 'store'])->name('quiz.store');

    Route::get('/{testID}/create', [QuizCore::class, 'createQuestion'])->name('quiz.createQuestion');
    Route::post('/storeQuestion', [QuizCore::class, 'ajax_QuestionStore'])->name('ajax.QuestionStore');
    Route::patch('/updateQuestion', [QuizCore::class, 'ajax_QuestionUpdate'])->name('ajax.QuestionUpdate');
    Route::post('/storeChoice', [QuizCore::class, 'ajax_ChoiceStore'])->name('ajax.ChoiceStore');

    Route::delete('/destroyChoice', [QuizCore::class, 'ajax_ChoiceDestroy'])->name('ajax.ChoiceDestroy');
    Route::delete('/destroyQuestion', [QuizCore::class, 'ajax_QuestionDestroy'])->name('ajax.QuestionDestroy');
    Route::delete('/reset', [QuizCore::class, 'ajax_reset'])->name('ajax.QuestionReset');

    Route::get('/{testID}/edit', [QuizCore::class, 'editQuestion'])->name('quiz.editQuestion');

});

Route::get('register', [Account::class, 'index'])->name('register');
Route::post('register/join', [Account::class, 'store'])->name('register.join');
Route::get('login', [Login::class, 'index'])->name('login');
Route::post('login/check', [Login::class, 'check'])->name('login.check');
Route::get('logout', [Login::class, 'logout'])->name('logout');