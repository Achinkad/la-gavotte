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
use App\Http\Controllers\IPController;
use App\Http\Controllers\VPNController;
use App\Http\Controllers\BackupController;

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

/* --- [API Routes] -> Routing (BGP) --- */
Route::get('routing/bgp', [RoutingController::class, 'showBGP']);
Route::put('routing/bgp', [RoutingController::class, 'createBGP']);
Route::put('routing/bgp/{id}', [RoutingController::class, 'editBGP']);
Route::delete('routing/bgp/{id}', [RoutingController::class, 'deleteBGP']);

/* --- [API Routes] -> Routing (OSPF) --- */
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

/* --- [API Routes] -> Routing (RIP) --- */
Route::get('routing/rip/instance', [RoutingController::class, 'showRIPInstance']);
Route::put('routing/rip/instance', [RoutingController::class, 'createRIPInstance']);
Route::put('routing/rip/instance/{id}', [RoutingController::class, 'editRIPInstance']);
Route::delete('routing/rip/instance/{id}', [RoutingController::class, 'deleteRIPInstance']);
Route::get('routing/rip/template', [RoutingController::class, 'showRIPTemplate']);
Route::put('routing/rip/template', [RoutingController::class, 'createRIPTemplate']);
Route::put('routing/rip/template/{id}', [RoutingController::class, 'editRIPTemplate']);
Route::delete('routing/rip/template/{id}', [RoutingController::class, 'deleteRIPTemplate']);

/* --- [API Routes] -> Firewall --- */
Route::get('firewalls', [FirewallController::class, 'showRules']);
Route::put('firewalls', [FirewallController::class, 'createRules']);
Route::put('firewalls/{id}', [FirewallController::class, 'editRules']);
Route::delete('firewalls/{id}', [FirewallController::class, 'deleteRules']);

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

/* --- [API Routes] -> VPN (SERVER) --- */
Route::get('vpn/server', [VPNController::class, 'getServerVPN']);
Route::post('vpn/server/create', [VPNController::class, 'createServerVPN']);
Route::patch('vpn/server/edit/{id}', [VPNController::class, 'editServerVPN']);
Route::delete('vpn/server/delete/{id}', [VPNController::class, 'deleteServerVPN']);
Route::patch('vpn/server/active/{id}', [VPNController::class, 'toogleDisabledServerVPN']);

/* --- [API Routes] -> VPN (CLIENT) --- */
Route::get('vpn/client', [VPNController::class, 'getClientVPN']);
Route::post('vpn/client/create', [VPNController::class, 'createClientVPN']);
Route::patch('vpn/client/edit/{id}', [VPNController::class, 'editClientVPN']);
Route::delete('vpn/client/delete/{id}', [VPNController::class, 'deleteClientVPN']);
Route::patch('vpn/client/active/{id}', [VPNController::class, 'toogleDisabledClientVPN']);

/* --- [API Routes] -> BACKUP --- */
Route::post('backup', [BackupController::class, 'doBackup']);

/* --- [API Routes] -> Auth --- */
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
