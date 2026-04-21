<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TravelPostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Rotte degli utenti
Route::controller(UserController::class, function () {
    Route::post('/users', 'store')->name('users.store');
});
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
/* Route::apiResource('/users', UserController::class); */

//Rotte dei post (accessibili a tutti)
Route::get('/posts', [TravelPostController::class, 'index'])->name('posts.index');
Route::get('/posts/{travel_post}', [TravelPostController::class, 'show'])->name('posts.show');

//Rotte dei post (solo per utenti autenticati)
Route::middleware(['auth:sanctum'])->group(function () {
    /* Rotte dei posts */
    Route::post('/posts', [TravelPostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{travel_post}', [TravelPostController::class, 'update'])->name(
        'posts.update',
    );
    Route::delete('/posts/{travel_post}', [TravelPostController::class, 'destroy'])->name(
        'posts.destroy',
    );

    /* Rotte dei likes */
    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');

    /* Rotte dei commenti */
    Route::controller(CommentController::class)->group(function () {
        Route::post('/comments', 'store')->name('comments.store');
        Route::delete('/comments/{comment_model}', 'destroy')->name('comments.destroy');
    });

    //Rotta per la dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

/* Route per l'autenticazione */
Route::prefix('/auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register')->name('auth.register');
        Route::post('/login', 'login')->name('auth.login');
        Route::post('/logout', 'logout')->name('auth.logout');
        Route::get('/me', 'me')->name('auth.me');
    });

/* Rotte per il recupero della password */
Route::controller(PasswordResetController::class)->group(function() {
    Route::post('/forgot-password', 'forgotPassword')->name('password.request');
    Route::post('/reset-password', 'resetPassword')->name('password.reset');
});
