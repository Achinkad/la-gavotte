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

                        try {
                            $response = Helper::httpClient('GET','interface',$router);
                        } catch (\Exception $e) {
                            return response()->json($e->getMessage(), $e->getCode());
                        }

                    }
                    else{
                        try {
                            $response = Helper::httpClient('GET','interface?type='.$request->type,$router);
                        } catch (\Exception $e) {
                            return response()->json($e->getMessage(), $e->getCode());
                        }

                    }

                    foreach($helper->decodeResponse($response) as $interface){
                        $interface->router=$router->id; #talvez seja antes de descodificar
                        array_push($interfaces,$interface);
                    }



                }

            }

        }
        else{
            $router=Router::where('id',$request->identifier)->firstOrFail();
            if($router!=[] && $request->type!=null && $request->identifier!=null){
                if($request->type=='all'){
                    try{
                        $response = Helper::httpClient('GET','interface',$router);
                    } catch (\Exception $e) {
                        return response()->json($e->getMessage(), $e->getCode());
                    }
                    
                }
                else{
                    try{
                        $response = Helper::httpClient('GET','interface?type='.$request->type,$router);
                    } catch (\Exception $e) {
                        return response()->json($e->getMessage(), $e->getCode());
                    }
                }

                foreach($helper->decodeResponse($response) as $interface){
                    $interface->router=$router->id; #talvez seja antes de descodificar
                    array_push($interfaces,$interface);
               }
            }
        }


        return $interfaces;
    }
}
