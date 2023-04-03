<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Router;
use App\Http\Requests\StoreRouterRequest;

class RouterController extends Controller
{

    
    public function store(StoreRouterRequest $request)
    {
        DB::transaction(function () use ($request) {
            $router = new Router;
            $router->fill($request->validated());

            $response = Helper::httpClient($router);

            if ($response->getStatusCode() != 200) {
                return response()->json($response->getBody(), $response->getStatusCode());
                die();
            }

            $router->save();

            $currentUser = auth()->guard('api')->user();
            $currentUser->router()->attach($router->id);
        });

        return response()->json("OK", 200);
    }

    public function showInterfaces(Request $request)
    {
        $client = new Client();

        // Headers
        $headers = [
            'Authorization' => [$credentials->username, $credentials->password],
            'verify' => false
        ];

        // URL builder
        $url = 'https://' . $credentials->address . '/rest/interface';

        // Request build
        $response = $client->request('GET', $url, $headers);

        return $response->getBody()->getContents();
    }
}
