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

            $active_routers=$request->active_routers;
           
                if($active_routers!=null){

                foreach($active_routers as $router_id){
                    $router=Router::where('id',$router_id)->first();

                    try{
                        $response = Helper::httpClient('GET','ip/firewall/filter',$router);

                    } catch (\Exception $e) {
                        return response()->json($e->getMessage(), $e->getCode());
                    }
                    foreach($helper->decodeResponse($response) as $rule){
                        $rule->router=$router->id; 
                        array_push($rules,$rule);
                    }

                }
            }
        }
        else{
            
            $router=Router::where('id',$request->identifier)->firstOrFail();

            if($router!=[] && $request->identifier!=null){
                try{
                    $response = Helper::httpClient('GET','ip/firewall/filter',$router);
                } catch (\Exception $e) {
                    return response()->json($e->getMessage(), $e->getCode());
                }

                foreach($helper->decodeResponse($response) as $rule){
                    $rule->router=$router->id; #talvez seja antes de descodificar
                    array_push($rules,$rule);
                }
            }
        }


        return $rules;
    }

    public function createRules(Request $request){

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


        if($request->identity=='all'){
            $responses=[];
            
            $active_routers=explode(',',$request->active_routers);
            unset($request['identity']);
            unset($request['active_routers']);
            
            foreach($active_routers as $router_id){
                $router=Router::where('id',$router_id)->first();
                
                try{
                    $response = Helper::httpClient('PUT','ip/firewall/filter',$router,$request->all());
                    array_push($responses,$response);
                } catch (\Exception $e) {
                    return response()->json($e->getMessage(), $e->getCode());
                }
                      
                return $responses;
                }
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        unset($request['identity']);
        

        try{
            $response = Helper::httpClient('PUT','ip/firewall/filter',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
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
        try{
            Helper::httpClient('PUT','ip/firewall/filter/'.$rule_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteRules(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','ip/firewall/filter/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

}
