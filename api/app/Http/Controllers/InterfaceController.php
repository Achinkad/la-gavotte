<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;

class InterfaceController extends Controller
{
    public function showInterfaces(Request $request)
    {
        $type = $request->type;
        

        if($type=='all'){
            $routers=Router::all();
            if($routers!=[]){

                foreach($routers as $router){
                    $response = Helper::httpClient($router,'GET','interface');
                }
                
            }
            
        }
        else{
            #$router=Router::where('ip_address');
            /*if($router=[]){
                $response = Helper::httpClient($router,'GET','interface');
            }*/
        }

        if ($response->getStatusCode() != 200) {
            return response()->json($response->getBody(), $response->getStatusCode());
            die();
        }

        return $response;
    }
}
