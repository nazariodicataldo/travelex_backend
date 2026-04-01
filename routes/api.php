<?php

use App\Http\Controllers\TravelPostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Rotte degli utenti
Route::apiResource('users', UserController::class);

//Rotte dei post
Route::apiResource('travel_posts', TravelPostController::class);
