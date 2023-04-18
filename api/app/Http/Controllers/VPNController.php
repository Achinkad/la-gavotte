<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request['public-key'] = $request['publicKey'];
        $request['allowed-address'] = $request['allowedAddresses'];
        $request['endpoint-address'] = $request['endpointAddress'];
        $request['endpoint-port'] = $request['endpointPort'];

        // Body Builder
        $body = $request->except('router', 'publicKey', 'allowedAddresses', 'endpointAddress', 'endpointPort');

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
        $request['public-key'] = $request['publicKey'];
        $request['allowed-address'] = $request['allowedAddresses'];
        $request['endpoint-address'] = $request['endpointAddress'];
        $request['endpoint-port'] = $request['endpointPort'];

        // Body Builder
        $body = $request->except('router', 'id', 'publicKey', 'allowedAddresses', 'endpointAddress', 'endpointPort');

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

    public function downloadConfigFile(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        try {
            $response = Helper::httpClient('GET', 'interface/wireguard?name=' . $request->vpn['interface'], $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $vpnServerPublicKey = Helper::decodeResponse($response)[0]->{'public-key'};

        $content = '[Interface]
PrivateKey = <YOUR_PRIVATE_KEY>
Address = '. $request->vpn["allowed-address"] .'

[Peer]
PublicKey = '. $vpnServerPublicKey .'
Endpoint = '. $request->vpn["endpoint-address"] .':'. $request->vpn["endpoint-port"] .'
AllowedIPs = 0.0.0.0/0
PersistentKeepalive = '. substr($request->vpn["persistent-keepalive"], 0, -1) .'';

        Storage::disk('public')->put('wg0.conf', $content);
        return Storage::download('public/wg0.conf');
    }
}
