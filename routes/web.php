<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Http\Controllers\Core\QuizCore;

use App\Http\Controllers\Account\Account;
use App\Http\Controllers\Account\Login;
use App\Http\Controllers\Account\Mypage;

use App\Http\Controllers\MainController;



Route::get('/', function () {
    return redirect('/quiz');
});


Route::middleware(['app'])->group(function () {

    Route::prefix('quiz')->group(function () {

        Route::get('/', [QuizCore::class, 'index'])->name('quiz.index');
        
        Route::middleware(['quiz'])->group(function () {
            
            Route::get('/create', [QuizCore::class, 'create'])->name('quiz.create');
            Route::post('/store', [QuizCore::class, 'store'])->name('quiz.store');

            Route::get('/{testID}/create', [QuizCore::class, 'createQuestion'])->name('quiz.createQuestion');
            Route::post('/storeQuestion', [QuizCore::class, 'ajax_QuestionStore'])->name('ajax.QuestionStore');
            Route::patch('/updateQuestion', [QuizCore::class, 'ajax_QuestionUpdate'])->name('ajax.QuestionUpdate');
            Route::patch('/updateQuiz', [QuizCore::class, 'ajax_QuizUpdate'])->name('ajax.QuizUpdate');
            Route::post('/updateGubun', [QuizCore::class, 'ajax_GubunUpdate'])->name('ajax.QuestionUpdate');
            Route::post('/storeChoice', [QuizCore::class, 'ajax_ChoiceStore'])->name('ajax.ChoiceStore');

            Route::delete('/destroyChoice', [QuizCore::class, 'ajax_ChoiceDestroy'])->name('ajax.ChoiceDestroy');
            Route::delete('/destroyQuestion', [QuizCore::class, 'ajax_QuestionDestroy'])->name('ajax.QuestionDestroy');
            Route::delete('/reset', [QuizCore::class, 'ajax_reset'])->name('ajax.QuestionReset');

            Route::get('/{testID}/edit', [QuizCore::class, 'editQuestion'])->name('quiz.editQuestion');

            Route::get('/solve/{testID}/type{type}', [QuizCore::class, 'solve'])->name('quiz.solve');
            Route::post('/result/{testID}', [QuizCore::class, 'result'])->name('quiz.result');


            
        });
    });

    Route::get('/mypage', [Mypage::class, 'index'])->name('mypage');
});

Route::get('register', [Account::class, 'index'])->name('register');
Route::post('register/join', [Account::class, 'store'])->name('register.join');
Route::get('login', [Login::class, 'index'])->name('login');
Route::post('login/check', [Login::class, 'check'])->name('login.check');
Route::get('logout', [Login::class, 'logout'])->name('logout');
