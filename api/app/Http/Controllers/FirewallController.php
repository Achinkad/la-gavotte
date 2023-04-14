<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;
use Mockery\Undefined;

class FirewallController extends Controller
{

    public function showRules(Request $request){
       
        $rules=[];
        $helper = new Helper();

        if($request->identifier=='all'){
            $routers=Router::all();
            
            if($routers!=[] && $request->identifier!=null){
                
                foreach($routers as $router){
                   
                    $response = Helper::httpClient('GET','ip/firewall/filter',$router);

                    foreach($helper->decodeResponse($response) as $rule){
                        $rule->router=$router->id; #talvez seja antes de descodificar
                        array_push($rules,$rule);
                    }

                }
                
            }
            
        }
        else{
            
            $router=Router::where('id',$request->identifier)->firstOrFail();

            if($router!=[] && $request->identifier!=null){
                
                $response = Helper::httpClient('GET','ip/firewall/filter',$router);
                
                foreach($helper->decodeResponse($response) as $rule){
                    $rule->router=$router->id; #talvez seja antes de descodificar
                    array_push($rules,$rule);
                }
            }
        }

        #VER SE ISTO SE APLICA
        /*if ($interfaces->getStatusCode() != 200) {
            return response()->json($response->getBody(), $response->getStatusCode());
            die();
        }*/

        return $rules;
    }

    public function createRules(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

    
        $request['src-address']=$request['src_address'];    
        $request['dst-address']=$request['dst_address'];
        $request['src-port']=$request['src_port'];
        $request['dst-port']=$request['dst_port'];
        unset($request['src_address']);
        unset($request['dst_address']);
        unset($request['src_port']);
        unset($request['dst_port']);
        unset($request['identity']);

        
        if($request['src-address']==null){
            unset($request['src-address']);
        }
        if($request['dst-address']==null){
            unset($request['dst-address']);
        }
        if($request['src-port']==null){
            unset($request['src-port']);
        }
        if($request['dst-port']==null){
            unset($request['dst-port']);
        }


        $response = Helper::httpClient('PUT','ip/firewall/filter',$router,$request->all());
        return $response;
    }

    public function editRules(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $rule_id=$request->rule_identity;
        $request['src-address']=$request['src_address'];    
        $request['dst-address']=$request['dst_address'];
        $request['src-port']=$request['src_port'];
        $request['dst-port']=$request['dst_port'];
        unset($request['src_address']);
        unset($request['dst_address']);
        unset($request['src_port']);
        unset($request['dst_port']);
     
        if($request['src-address']==null){
            unset($request['src-address']);
        }
        if($request['dst-address']==null){
            unset($request['dst-address']);
        }
        if($request['src-port']==null){
            unset($request['src-port']);
        }
        if($request['dst-port']==null){
            unset($request['dst-port']);
        }

        unset($request['router_identity']);
        unset($request['rule_identity']);
        
        Helper::httpClient('PUT','ip/firewall/filter/'.$rule_id,$router,$request->all());
    }

    public function deleteRules(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        Helper::httpClient('DELETE','ip/firewall/filter/'.$request->id,$router);
      
    }

}
