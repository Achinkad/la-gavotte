<?php

namespace App\Helper;

use GuzzleHttp\Client;
use App\Models\Router;

class Helper
{
    // Decodes a given base64 enconded string
    public static function authorizationDecode(String $token) : Array
    {
        return explode(":", base64_decode($token));
    }

    // Creates a new HTTP client and sends a request
    public static function httpClient(Router $router, String $httpAction, String $restPath, String $optionalArg = null)
    {
        $client = new Client();

        $credentials = Helper::authorizationDecode($router->authorization);

        $headers = [
            'auth' => [$credentials[0], $credentials[1]],
            'verify' => false
        ];

        $url = 'https://' . $router->ip_address . '/rest/' . $restPath;

        $response = $client->request($httpAction, $url, $headers);

        return $response;
    }
}
