<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Router;

class IPController extends Controller
{
    public function getAddresses(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();

        try {
            $addresses = Helper::httpClient('GET', 'ip/address', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $addresses = Helper::associateRouterToObject($addresses, $router);

        return $addresses;
    }

    public function createAddress(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Body Builder
        $body = $request->except('router');

        try {
            $response = Helper::httpClient('PUT', 'ip/address', $router, $body);
            return $response->getBody()->getContents(); // Return JSON Reponse -> NEW ADDRESS
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editAddress(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Body Builder
        $body = $request->except('id', 'router');

        try {
            $response = Helper::httpClient('PATCH', 'ip/address/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteAddress(Request $request)
    {
        $router = Router::where('id', $request->routerID)->firstOrFail();
        $addressID = $request->route('id');

        try {
            $response = Helper::httpClient('DELETE', 'ip/address/' . $addressID, $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function toogleDisabledAddress(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();
        $addressID = $request->route('id');
        $option = $request->disabled == "true" ? "false" : "true"; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'ip/address/' . $addressID, $router, $body);
    }

    public function getRoutes(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();

        try {
            $routes = Helper::httpClient('GET', 'ip/route', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $routes = Helper::associateRouterToObject($routes, $router);

        return $routes;
    }

    public function createRoute(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['dst-address'] = $request['destination'];

        // Body Builder
        $body = $request->except('router', 'destination');

        try {
            $response = Helper::httpClient('PUT', 'ip/route', $router, $body);
            return $response->getBody()->getContents(); // Return JSON Reponse -> NEW ROUTE
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editRoute(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['dst-address'] = $request['destination'];

        // Body Builder
        $body = $request->except('id', 'router', 'destination');

        try {
            $response = Helper::httpClient('PATCH', 'ip/route/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteRoute(Request $request)
    {
        $router = Router::where('id', $request->routerID)->firstOrFail();
        $routeID = $request->route('id');

        try {
            $response = Helper::httpClient('DELETE', 'ip/route/' . $routeID, $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function toogleDisabledRoute(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();
        $routeID = $request->route('id');
        $option = $request->disabled == "true" ? "false" : "true"; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'ip/route/' . $routeID, $router, $body);
    }

    public function getDHCP(Request $request)
    {
        $router = Router::where('id', $request->id)->firstOrFail();

        try {
            $routes = Helper::httpClient('GET', 'ip/dhcp-server', $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        $routes = Helper::associateRouterToObject($routes, $router);

        return $routes;
    }

    public function createDHCP(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Change the values for the RouterOS API
        $request['lease-time'] = $request['leaseTime'];
        $request['address-pool'] = $request['addressPool'];

        // Body Builder
        $body = $request->except('router', 'leaseTime', 'addressPool');

        try {
            $response = Helper::httpClient('PUT', 'ip/dhcp-server', $router, $body);
            return $response->getBody()->getContents(); // Return JSON Reponse -> NEW DHCP SERVER
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editDHCP(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        if(substr($request['leaseTime'], -1) != 'm') $request['leaseTime'] = $request['leaseTime'] . 'm';

        // Change the values for the RouterOS API
        $request['lease-time'] = $request['leaseTime'];
        $request['address-pool'] = $request['addressPool'];

        // Body Builder
        $body = $request->except('id', 'router', 'leaseTime', 'addressPool');

        try {
            $response = Helper::httpClient('PATCH', 'ip/dhcp-server/' . $request->id, $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteDHCP(Request $request)
    {
        $router = Router::where('id', $request->routerID)->firstOrFail();
        $routeID = $request->route('id');

        try {
            $response = Helper::httpClient('DELETE', 'ip/dhcp-server/' . $routeID, $router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function toogleDisabledDHCP(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();
        $dhcpServerID = $request->route('id');
        $option = $request->disabled == "true" ? "false" : "true"; // Toggle Disabled
        $body = array('disabled' => $option);
        $response = Helper::httpClient('PATCH', 'ip/dhcp-server/' . $dhcpServerID, $router, $body);
    }
}
