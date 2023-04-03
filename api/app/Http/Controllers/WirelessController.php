<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;

use App\Models\Router;

class WirelessController extends Controller
{
    public function showSecurityProfiles(Request $request)
    {
        $router = Router::where('id', $request->input("id"))->firstOrFail();
        $securityProfiles = Helper::httpClient('GET', 'interface/wireless/security-profiles', $router);
        return $securityProfiles;
    }

    public function showWirelessNetworks(Request $request)
    {
        $router = Router::where('id', $request->input("id"))->firstOrFail();
        $wirelessNetworks = Helper::httpClient('GET', 'interface/wireless', $router);
        return $wirelessNetworks;
    }
}
