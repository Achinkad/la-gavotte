<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Router;
use App\Http\Requests\StoreRouterRequest;
use App\Http\Resources\RouterResource;

class RouterController extends Controller
{
    public function index()
    {
        return RouterResource::collection(Router::all());
    }

    public function store(StoreRouterRequest $request)
    {
        $newRouter = DB::transaction(function () use ($request) {
            $router = new Router;
            $router->fill($request->validated());

            $response = Helper::httpClient('POST', 'login', $router);

            // Check if credentials are correct
            if ($response->getStatusCode() != 200) {
                return response()->json($response->getBody(), $response->getStatusCode());
                die();
            }

            // Get identity of the router
            $identity = Helper::httpClient('GET', 'system/identity', $router);
            if ($response->getStatusCode() != 200) die();
            $router->identity = Helper::decodeResponse($identity)->name;

            // Get MAC address of the router
            $macAddress = Helper::httpClient('GET', 'interface?name=ether1', $router);
            if ($response->getStatusCode() != 200) die();
            $router->mac_address = Helper::decodeResponse($macAddress)[0]->{'mac-address'};

            $router->save();

            $currentUser = auth()->guard('api')->user();
            $currentUser->router()->attach($router->id);

            return $router;
        });

        return new RouterResource($newRouter);
    }
}
