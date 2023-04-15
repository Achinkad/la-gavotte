<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Router;

class BackupController extends Controller
{
    public function doBackup(Request $request)
    {
        $router = Router::where('id', $request->router)->firstOrFail();

        // Body Builder
        $body = $request->except('router');

        try {
            $response = Helper::httpClient('POST', 'system/backup/save', $router, $body);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
