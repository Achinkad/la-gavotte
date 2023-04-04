<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;

class InterfaceController extends Controller
{
    public function showInterfaces(Request $request)
    {
        $interfaces=[];
        $helper = new Helper();

        if($request->identifier=='all'){
            $routers=Router::all();
            
            if($routers!=[] && $request->type!=null && $request->identifier!=null){
                
                foreach($routers as $router){
                    if($request->type=='all'){
                        $response = Helper::httpClient('GET','interface',$router);
                    }
                    else{
                        $response = Helper::httpClient('GET','interface?type='.$request->type,$router);
                    }
                  
                   foreach($helper->decodeResponse($response) as $interface){
                        $interface->router=$router->ip_address; #talvez seja antes de descodificar
                        array_push($interfaces,$interface);
                   }

                }
                
            }
            
        }
        else{
            $router=Router::where('ip_address',$request->identifier)->first();
            if($router!=[] && $request->type!=null && $request->identifier!=null){
                if($request->type=='all'){
                    $response = Helper::httpClient('GET','interface',$router);
                }
                else{
                    $response = Helper::httpClient('GET','interface?type='.$request->type,$router);
                }
                foreach($helper->decodeResponse($response) as $interface){
                    $interface->router=$router->ip_address; #talvez seja antes de descodificar   
                    array_push($interfaces,$interface);
               }
            }
        }

        #VER SE ISTO SE APLICA
        /*if ($interfaces->getStatusCode() != 200) {
            return response()->json($response->getBody(), $response->getStatusCode());
            die();
        }*/

        return $interfaces;
    }
}
