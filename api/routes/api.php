<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


/* --- [API Routes] -> Users --- */
Route::resource('users', UserController::class);
Route::get('user', [UserController::class, 'showUserLoggedIn'])->middleware('auth:api');

/* --- [API Routes] -> Auth --- */
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
