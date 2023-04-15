<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Router;

class VPNController extends Controller
{
    //
    //
    // --> VPN SERVERS CONFIGURATIONS
    //
    //

    public function getServerVPN(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();

        try {
            $serversVPN = Helper::httpClient('GET', 'interface/wireguard', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $serversVPN = Helper::associateRouterToObject($serversVPN, $router);

        return $serversVPN;
    }

    public function createServerVPN(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['listen-port'] = $request['port'];

        // Body Builder
        $body = $request->except('router', 'port');

        try {
            $response = Helper::httpClient('PUT', 'interface/wireguard', $router, $body);
            return $response->getBody()->getContents(); // Return JSON Reponse -> NEW VPN SERVER
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editServerVPN(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['listen-port'] = $request['port'];

        // Body Builder
        $body = $request->except('router', 'id', 'port');

        try {
            $response = Helper::httpClient('PATCH', 'interface/wireguard/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteServerVPN(Request $request)
    {
        $router = Router::where('id', $request->routerID)->firstOrFail();
        $vpnServerID = $request->route('id');

        try {
            $response = Helper::httpClient('DELETE', 'interface/wireguard/' . $vpnServerID, $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function toogleDisabledServerVPN(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();
        $idVPN = $request->route('id');
        $option = $request->disabled == "true" ? "false" : "true"; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'interface/wireguard/' . $idVPN,  $router, $body);
    }

    //
    //
    // --> VPN CLIENTS CONFIGURATIONS
    //
    //

    public function getClientVPN(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();

        try {
            $serversVPN = Helper::httpClient('GET', 'interface/wireguard/peers', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $serversVPN = Helper::associateRouterToObject($serversVPN, $router);

        return $serversVPN;
    }

    public function createClientVPN(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['listen-port'] = $request['port'];

        // Body Builder
        $body = $request->except('router', 'port');

        try {
            $response = Helper::httpClient('PUT', 'interface/wireguard/peers', $router, $body);
            return $response->getBody()->getContents(); // Return JSON Reponse -> NEW VPN CLIENT
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editClientVPN(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['listen-port'] = $request['port'];

        // Body Builder
        $body = $request->except('router', 'id', 'port');

        try {
            $response = Helper::httpClient('PATCH', 'interface/wireguard/peers/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteClientVPN(Request $request)
    {
        $router = Router::where('id', $request->routerID)->firstOrFail();
        $vpnClientID = $request->route('id');

        try {
            $response = Helper::httpClient('DELETE', 'interface/wireguard/peers/' . $vpnClientID, $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function toogleDisabledClientVPN(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();
        $idVPN = $request->route('id');
        $option = $request->disabled == "true" ? "false" : "true"; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'interface/wireguard/peers/' . $idVPN,  $router, $body);
    }
}
