<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Router;

class VPNController extends Controller
{
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
}
