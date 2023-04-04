<?php

namespace App\Helper;

use App\Models\Router;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

class Helper
{
    // Decodes a given base64 enconded string
    public static function authorizationDecode(String $token) : Array
    {
        return explode(":", base64_decode($token));
    }

    // Creates a new HTTP client and sends a request
    public static function httpClient(String $httpRequestMethod, String $local, Router $router, Array $bodyContent = null)
    {
        $client = new Client(['verify' => false]);

        $completeUrl = 'https://' . $router->ip_address . '/rest/' . $local;

        $headerOptions = [
            'Authorization' => 'Basic ' . $router->authorization,
            'Content-Type' => 'application/json'
        ];

        try {
            if ($bodyContent) {
                $response = $client->request($httpRequestMethod, $completeUrl, [
                    'headers' => $headerOptions,
                    'json' => $bodyContent
                ]);
            } else {
                $response = $client->request($httpRequestMethod, $completeUrl, [
                    'headers' => $headerOptions
                ]);
            }

            return $response;
        } catch (RequestException $error) {
            return $error;
        }
    }

    // Decode JSON response from router API
    public static function decodeResponse(Response $response)
    {
        return json_decode($response->getBody()->getContents());
    }
}
