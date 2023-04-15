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

        try {
            $wirelessNetworks = Helper::httpClient('GET', 'interface/wireless', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $wirelessNetworks = Helper::associateRouterToObject($wirelessNetworks, $router);

        return $wirelessNetworks;
    }

    public function toogleDisabledWirelessNetwork(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();
        $wirelessNetworkId = $request->route('id');
        $option = $request->disabled == "true" ? "false" : "true"; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'interface/wireless/' . $wirelessNetworkId, $router, $body);
    }

    public function editWirelessNetwork(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['mac-address'] = $request['macAddress'];
        $request['channel-width'] = $request['channelWidth'];
        $request['security-profile'] = $request['securityProfile'];
        $request['wds-mode'] = $request['wdsMode'];
        $request['wds-default-bridge'] = $request['wdsDefaultBridge'];

        // Body Builder
        $body = $request->except('router', 'id', 'macAddress', 'channelWidth', 'securityProfile', 'wdsMode', 'wdsDefaultBridge');

        try {
            $response = Helper::httpClient('PATCH', 'interface/wireless/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showSecurityProfiles(Request $request)
    {

        $router = Router::where('id', $request->id)->firstOrFail();

        try {
            $securityProfiles = Helper::httpClient('GET', 'interface/wireless/security-profiles', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $securityProfiles;
    }

    public function createSecurityProfile(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['authentication-types'] = $request['authenticationTypes'];
        $request['wpa2-pre-shared-key'] = $request['wpa2PreSharedKey'];
        $request['wpa-pre-shared-key'] = $request['wpaPreSharedKey'];

        // Body Builder
        $body = $request->except('router', 'authenticationTypes', 'wpa2PreSharedKey', 'wpaPreSharedKey');

        try {
            $response = Helper::httpClient('PUT', 'interface/wireless/security-profiles', $router, $body);
            return $response->getBody()->getContents(); // Return JSON Reponse -> NEW SECURITY PROFILE
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editSecurityProfile(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['authentication-types'] = $request['authenticationTypes'];
        $request['wpa2-pre-shared-key'] = $request['wpa2PreSharedKey'];
        $request['wpa-pre-shared-key'] = $request['wpaPreSharedKey'];

        // Body Builder
        $body = $request->except('router', 'id', 'authenticationTypes', 'wpa2PreSharedKey', 'wpaPreSharedKey');

        try {
            $response = Helper::httpClient('PATCH', 'interface/wireless/security-profiles/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteSecurityProfile(Request $request)
    {
        $router = Router::where('id', $request->routerID)->firstOrFail();
        $securityProfileID = $request->route('id');

        try {
            $response = Helper::httpClient('DELETE', 'interface/wireless/security-profiles/' . $securityProfileID, $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
