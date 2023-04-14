<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BridgeController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\WirelessController;
use App\Http\Controllers\InterfaceController;
use App\Http\Controllers\IPController;

/* --- [API Routes] -> Users --- */
Route::resource('users', UserController::class);
Route::get('user', [UserController::class, 'showUserLoggedIn'])->middleware('auth:api');

/* --- [API Routes] -> Router --- */
Route::get('routers/interfaces', [InterfaceController::class, 'showInterfaces']);
Route::resource('routers', RouterController::class);

/* --- [API Routes] -> Bridge --- */
Route::get('bridges/ports', [BridgeController::class, 'showBridgePorts']);
Route::get('bridges', [BridgeController::class, 'showBridges']);
Route::put('bridges/ports', [BridgeController::class, 'createBridgePorts']);
Route::put('bridges', [BridgeController::class, 'createBridges']);
Route::delete('bridges/{id}', [BridgeController::class, 'deleteBridges']);
Route::delete('bridges/ports/{id}', [BridgeController::class, 'deleteBridgePorts']);
Route::patch('bridges/{id}', [BridgeController::class, 'editBridges']);
Route::patch('bridges/ports/{id}', [BridgeController::class, 'editBridgePorts']);

/* --- [API Routes] -> Security Profiles --- */
Route::post('wireless/create/security-profile', [WirelessController::class, 'createSecurityProfile']);
Route::put('wireless/edit/security-profile/{id}', [WirelessController::class, 'editSecurityProfile']);
Route::delete('wireless/delete/security-profile/{id}', [WirelessController::class, 'deleteSecurityProfile']);
Route::get('wireless/security-profiles', [WirelessController::class, 'showSecurityProfiles']);

/* --- [API Routes] -> Wireless --- */
Route::get('wireless', [WirelessController::class, 'showWirelessNetworks']);
Route::patch('wireless/active/{id}', [WirelessController::class, 'toogleDisabledWirelessNetwork']);
Route::patch('wireless/{id}', [WirelessController::class, 'editWirelessNetwork']);

/* --- [API Routes] -> IP (Address) --- */
Route::get('ip/address', [IPController::class, 'getAddresses']);
Route::post('ip/address/create', [IPController::class, 'createAddress']);
Route::delete('ip/address/delete/{id}', [IPController::class, 'deleteAddress']);
Route::patch('ip/address/edit/{id}', [IPController::class, 'editAddress']);
Route::patch('ip/address/active/{id}', [IPController::class, 'toogleDisabledAddress']);

/* --- [API Routes] -> IP (Routes) --- */
Route::get('ip/routes', [IPController::class, 'getRoutes']);
Route::post('ip/route/create', [IPController::class, 'createRoute']);
Route::patch('ip/route/edit/{id}', [IPController::class, 'editRoute']);
Route::patch('ip/route/active/{id}', [IPController::class, 'toogleDisabledRoute']);
Route::delete('ip/route/delete/{id}', [IPController::class, 'deleteRoute']);

/* --- [API Routes] -> IP (DHCP) --- */
Route::get('ip/dhcp-server', [IPController::class, 'getDHCP']);
Route::post('ip/dhcp-server/create', [IPController::class, 'createDHCP']);
Route::patch('ip/dhcp-server/edit/{id}', [IPController::class, 'editDHCP']);
Route::patch('ip/dhcp-server/active/{id}', [IPController::class, 'toogleDisabledDHCP']);
Route::delete('ip/dhcp-server/delete/{id}', [IPController::class, 'deleteDHCP']);

/* --- [API Routes] -> IP (DNS) --- */
Route::get('ip/dns', [IPController::class, 'getDNS']);
Route::patch('ip/dns/edit', [IPController::class, 'editDNS']);
Route::patch('ip/dns/active', [IPController::class, 'toogleDisabledDNS']);

/* --- [API Routes] -> Auth --- */
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
