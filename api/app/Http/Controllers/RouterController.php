<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Router;
use App\Http\Requests\StoreRouterRequest;
use App\Http\Resources\RouterResource;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

class RouterController extends Controller
{
    public function index(Request $request)
    {
        $routers = Router::all();

        $client = new Client(['verify' => false]);

        foreach ($routers as $router) {
            $completeUrl = 'https://' . $router->ip_address . '/rest/system/identity';

            $headerOptions = [
                'Authorization' => 'Basic ' . $router->authorization,
                'Content-Type' => 'application/json'
            ];

            try {
                $response = $client->request('GET', $completeUrl, [
                    'headers' => $headerOptions,
                    'timeout' => 0.8
                ]);

                $router->disabled = false;
            } catch (ConnectException $e) {
                $router->disabled = true;
            }
        }

        if (isset($request->dashboard)) {
            foreach ($routers as $router) {
                if (!$router->disabled) {
                    $stats = Helper::httpClient('GET', 'system/resource', $router);
                    if ($stats->getStatusCode() != 200) die();
                    $stats = Helper::decodeResponse($stats);
                    $router->freeMemory = $stats->{'free-memory'};
                    $router->freeSpace = $stats->{'free-hdd-space'};
                }
            }
        }

        return $routers;
    }

    public function show(){
        return true;
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
