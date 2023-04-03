<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InterfaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\WirelessController;

/* --- [API Routes] -> Users --- */
Route::resource('users', UserController::class);
Route::get('user', [UserController::class, 'showUserLoggedIn'])->middleware('auth:api');

/* --- [API Routes] -> Router --- */
Route::resource('routers', RouterController::class);

/* --- [API Routes] -> Wireless --- */
Route::put('wireless/security-profiles', [WirelessController::class, 'createSecurityProfile']);

/* --- [API Routes] -> Auth --- */
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
