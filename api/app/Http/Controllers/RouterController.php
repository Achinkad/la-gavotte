<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RouterController extends Controller
{
    public function authRouter(Request $request)
    {
        $client = new Client();

        $givenAddress = $request->input('ip');
        $givenUsername = $request->input('username');
        $givenPassword = $request->input('password');

        // Headers
        $headers = [
            'auth' => [$givenUsername, $givenPassword],
            'verify' => false
        ];

        // URL builder
        $url = 'https://' . $givenAddress . '/rest/login';

        // Request build
        $response = $client->request('POST', $url, $headers);

        // JSON decode string
        $data = json_decode($response->getBody()->getContents());

        // Get token
        $token = $data->ret;

        // JSON auth builder
        $routerAutherization = array(
            "username" => $givenUsername,
            "password" => $givenPassword,
            "address" => $givenAddress,
            "token" => $token
        );

        // Save JSON auth in a private storage
        Storage::disk('private')->put('router-auth.json', json_encode($routerAutherization));

        // Response builder
        $responseData = [
            'message' => 'Authentication OK',
            'code' => 200,
            'token' => $token
        ];

        return response()->json($responseData, 200);
    }

    public function showInterfaces(Request $request)
    {
        $clientToken = $request->header('AuthorizationRouter'); // Client token in header request
        $credentials = Helper::routerTokenVerification($clientToken); // Token verification w/credentials return

        $client = new Client();

        // Headers
        $headers = [
            'auth' => [$credentials->username, $credentials->password],
            'verify' => false
        ];

        // URL builder
        $url = 'https://' . $credentials->address . '/rest/interface';

        // Request build
        $response = $client->request('GET', $url, $headers);

        return $response->getBody()->getContents();
    }
}
