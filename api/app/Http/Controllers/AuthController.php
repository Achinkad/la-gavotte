<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    protected $passport_server_url, $passport_client_id, $passport_client_key;

    public function __construct()
    {
        $this->passport_server_url = env('PASSPORT_SERVER_URL');
        $this->passport_client_id = env('PASSPORT_CLIENT_ID');
        $this->passport_client_key = env('PASSPORT_CLIENT_KEY');
    }

    private function passportAuthenticationData($username, $password) {
        return [
            'grant_type' => 'password',
            'client_id' => $this->passport_client_id,
            'client_secret' => $this->passport_client_key,
            'username' => $username,
            'password' => $password,
            'scope' => ''
        ];
    }

    public function login(Request $request)
    {
        try {
            request()->request->add($this->passportAuthenticationData($request->username, $request->password));
            $request = Request::create($this->passport_server_url . '/oauth/token', 'POST');
            $response = Route::dispatch($request);
            $errorCode = $response->getStatusCode();
            $auth_server_response = json_decode((string) $response->content(), true);
            return response()->json($auth_server_response, $errorCode);
        }
        catch (\Exception $e) {
            return response()->json('Authentication has failed!', 401);
        }
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
