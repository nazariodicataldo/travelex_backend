<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\TravelPostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Rotte degli utenti
Route::controller(UserController::class, function () {
    Route::post('/users', 'store')->name('users.store');
    Route::get('/users/{id}', 'show')->name('users.show');
});
Route::apiResource('/users', UserController::class);

//Rotte dei post
Route::apiResource('/posts', TravelPostController::class);

/* Rotte dei likes */
Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');

/* Rotte dei commenti */
Route::controller(CommentController::class)->group(function () {
    Route::post('/comments', 'store')->name('comments.store');
    Route::delete('/comments', 'destroy')->name('comments.destroy');
});

/* Route per l'autenticazione */
Route::prefix('/auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register')->name('auth.register');
        Route::post('/login', 'login')->name('auth.login');
    });
