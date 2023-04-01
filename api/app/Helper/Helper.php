<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function routerTokenVerification(String $givenToken)
    {
        $authValues = null;

        if (Storage::disk('private')->exists('router-auth.json')) {
            $authValues = Storage::disk('private')->get('router-auth.json');
            $authValues = json_decode($authValues);
        }

        return $authValues->token === $givenToken ? $authValues : abort(403);
    }
}
