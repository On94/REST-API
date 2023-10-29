<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('sign-up',[AuthenticationController::class,'signUp']);
Route::post('sign-in',[AuthenticationController::class,'signIn']);
Route::post('forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::put('reset-password', [AuthenticationController::class, 'resetPassword']);
Route::get('reset-password-link/{token}', [AuthenticationController::class, 'resetPasswordLink'])->name('reset.password.link');

Route::group(['middleware' => ['auth:api']], function () {

    Route::prefix('articles')->group(function () {
        Route::post('comment',[ArticleController::class,'comment']);
        Route::post('like',[ArticleController::class,'like']);

    });

    Route::patch('change-email',[AuthenticationController::class,'changeEmail']);
    Route::put('verify-new-email',[AuthenticationController::class,'verifyNewEmail']);

});

Route::post('article-store',[ArticleController::class,'store']);
Route::get('articles',[ArticleController::class,'all']);
