<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;

use App\Models\Router;

class WirelessController extends Controller
{
    public function showWirelessNetworks(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();
        $wirelessNetworks = Helper::httpClient('GET', 'interface/wireless', $router);
        return $wirelessNetworks;
    }

    public function showSecurityProfiles(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();
        $securityProfiles = Helper::httpClient('GET', 'interface/wireless/security-profiles', $router);
        return $securityProfiles;
    }

    public function toogleDisabledWirelessNetwork(Request $request)
    {
        $router = Router::where('id', $request->body['router'])->firstOrFail();
        $wirelessNetworkId = $request->route('id');
        $option = $request->input('disabled') ? false : true; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'interface/wireless/' . $wirelessNetworkId, $router, $body);
    }
}
