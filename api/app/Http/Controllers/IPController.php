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
}
