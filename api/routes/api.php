<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BridgeController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\FirewallController;
use App\Http\Controllers\WirelessController;
use App\Http\Controllers\InterfaceController;

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

/* --- [API Routes] -> Firewall --- */
Route::get('firewalls', [FirewallController::class, 'showRules']);
Route::put('firewalls', [FirewallController::class, 'createRules']);
Route::put('firewalls/{id}', [FirewallController::class, 'editRules']);
Route::delete('firewalls/{id}', [FirewallController::class, 'deleteRules']);

/* --- [API Routes] -> Routing --- */
Route::get('routing/bgp', [RoutingController::class, 'showBGP']);
Route::put('routing/bgp', [RoutingController::class, 'createBGP']);
Route::put('routing/bgp/{id}', [RoutingController::class, 'editBGP']);
Route::delete('routing/bgp/{id}', [RoutingController::class, 'deleteBGP']);

Route::get('routing/ospf/instance', [RoutingController::class, 'showOSPFInstance']);
Route::put('routing/ospf/instance', [RoutingController::class, 'createOSPFInstance']);
Route::put('routing/ospf/instance/{id}', [RoutingController::class, 'editOSPFInstance']);
Route::delete('routing/ospf/instance/{id}', [RoutingController::class, 'deleteOSPFInstance']);

Route::get('routing/ospf/area', [RoutingController::class, 'showOSPFArea']);
Route::put('routing/ospf/area', [RoutingController::class, 'createOSPFArea']);
Route::put('routing/ospf/area/{id}', [RoutingController::class, 'editOSPFArea']);
Route::delete('routing/ospf/area/{id}', [RoutingController::class, 'deleteOSPFArea']);

Route::get('routing/ospf/template', [RoutingController::class, 'showOSPFTemplate']);
Route::put('routing/ospf/template', [RoutingController::class, 'createOSPFTemplate']);
Route::put('routing/ospf/template/{id}', [RoutingController::class, 'editOSPFTemplate']);
Route::delete('routing/ospf/template/{id}', [RoutingController::class, 'deleteOSPFTemplate']);

Route::get('routing/rip/instance', [RoutingController::class, 'showRIPInstance']);
Route::put('routing/rip/instance', [RoutingController::class, 'createRIPInstance']);
Route::put('routing/rip/instance/{id}', [RoutingController::class, 'editRIPInstance']);
Route::delete('routing/rip/instance/{id}', [RoutingController::class, 'deleteRIPInstance']);

Route::get('routing/rip/template', [RoutingController::class, 'showRIPTemplate']);
Route::put('routing/rip/template', [RoutingController::class, 'createRIPTemplate']);
Route::put('routing/rip/template/{id}', [RoutingController::class, 'editRIPTemplate']);
Route::delete('routing/rip/template/{id}', [RoutingController::class, 'deleteRIPTemplate']);


/* --- [API Routes] -> Wireless --- */
Route::get('wireless/security-profiles', [WirelessController::class, 'showSecurityProfiles']);
Route::get('wireless', [WirelessController::class, 'showWirelessNetworks']);
Route::patch('wireless/{id}', [WirelessController::class, 'toogleDisabledWirelessNetwork']);

/* --- [API Routes] -> Auth --- */
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
