<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;

class BridgeController extends Controller
{

    public function showBridges(Request $request){
       
        $bridges=[];
        $helper = new Helper();

        if($request->identifier_bridges=='all'){
            $routers=Router::all();
            
            if($routers!=[] && $request->identifier_bridges!=null){
                
                foreach($routers as $router){
                   
                    $response = Helper::httpClient('GET','interface/bridge',$router);

                    foreach($helper->decodeResponse($response) as $bridge){
                        $bridge->router=$router->ip_address; #talvez seja antes de descodificar
                        array_push($bridges,$bridge);
                    }

                }
                
            }
            
        }
        else{
            $router=Router::where('ip_address',$request->identifier_bridges)->first();

            if($router!=[] && $request->identifier_bridges!=null){
                
                $response = Helper::httpClient('GET','interface/bridge',$router);
                
                foreach($helper->decodeResponse($response) as $bridge){
                    $bridge->router=$router->ip_address; #talvez seja antes de descodificar
                    array_push($bridges,$bridge);
                }
            }
        }

        #VER SE ISTO SE APLICA
        /*if ($interfaces->getStatusCode() != 200) {
            return response()->json($response->getBody(), $response->getStatusCode());
            die();
        }*/

        return $bridges;
    }



    public function showBridgePorts(Request $request){
        $ports=[];
        $helper = new Helper();
        

        if($request->identifier_ports=='all'){
            $routers=Router::all();
          
            if($routers!=[] && $request->identifier_ports!=null){
                
                foreach($routers as $router){
                   
                    $response = Helper::httpClient('GET','interface/bridge/port',$router);

                    foreach($helper->decodeResponse($response) as $port){
                        $port->router=$router->ip_address; #talvez seja antes de descodificar
                        array_push($ports,$port);
                    }

                }
                
            }
            
        }
        else{
            $router=Router::where('ip_address',$request->identifier_ports)->first();

            if($router!=[] && $request->identifier_ports!=null){
                
                $response = Helper::httpClient('GET','interface/bridge/port',$router);
                
                foreach($helper->decodeResponse($response) as $port){
                    $port->router=$router->ip_address; #talvez seja antes de descodificar
                    array_push($ports,$port);
                }
            }
        }

        #VER SE ISTO SE APLICA
        /*if ($interfaces->getStatusCode() != 200) {
            return response()->json($response->getBody(), $response->getStatusCode());
            die();
        }*/

        return $ports;
    }

    public function createBridges(Request $request){

        
    }

}
