<?php

namespace App\Helper;

use GuzzleHttp\Client;
use App\Models\Router;
use GuzzleHttp\Psr7\Response;

class Helper
{
    // Decodes a given base64 enconded string
    public static function authorizationDecode(String $token) : Array
    {
        return explode(":", base64_decode($token));
    }

    // Creates a new HTTP client and sends a request
    public static function httpClient(String $httpAction, String $restPath, Router $router, String $optionalArg = null)
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

    // Decode JSON response from router API
    public static function decodeResponse(Response $response)
    {
        return json_decode($response->getBody()->getContents());
    }
}
