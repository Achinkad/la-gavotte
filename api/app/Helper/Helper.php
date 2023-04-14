<?php

namespace App\Helper;

use App\Models\Router;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;




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
                    'json' => $bodyContent,
                    'timeout' => 3
                ]);
            } else {
                $response = $client->request($httpRequestMethod, $completeUrl, [
                    'headers' => $headerOptions,
                    'timeout' => 3
                ]);
            }

            return $response;
        } catch (ConnectException $e) {
            throw new \Exception("Request timeout. Please verify the router connection.", 504); // Gateway Timeout
        } catch (RequestException $e) {
            throw new \Exception("Request malformed. Please verify your request.", 400); // Bad Request
        } catch (ServerException $e) {
            throw new \Exception("Server error.", 500); // Server Error
        }
    }

    // Decode JSON response from router API
    public static function decodeResponse(Response $response)
    {
        return json_decode($response->getBody()->getContents());
    }

    // Associate a router to an array of objects -> For the views
    public static function associateRouterToObject($object, $router)
    {
        $arrayObject = [];

        foreach(Helper::decodeResponse($object) as $o) {
            $o->routerAddress = $router->ip_address;
            $o->routerId = $router->id;
            array_push($arrayObject, $o);
        }

        return $arrayObject;
    }
}
