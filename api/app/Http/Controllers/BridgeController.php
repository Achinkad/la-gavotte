<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;


class BridgeController extends Controller
{
    public function deleteBridges(Request $request) 
    {
        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','interface/bridge/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }

    public function deleteBridgePorts(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();
        try{
            Helper::httpClient('DELETE','interface/bridge/port/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editBridges(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $bridge_id=$request->bridge_identity;
        $request['protocol-mode']=$request['protocol'];
        unset($request['protocol']);
        unset($request['router_identity']);
        unset($request['bridge_identity']);

        try{
            Helper::httpClient('PATCH','interface/bridge/'.$bridge_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function editBridgePorts(Request $request) 
    {
        
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $port_id=$request->port_identity;

        unset($request['router_identity']);
        unset($request['port_identity']);

        try{
            Helper::httpClient('PATCH','interface/bridge/port/'.$port_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showBridges(Request $request){
       
        $bridges=[];
        $helper = new Helper();
               
        $router=Router::where('id',$request->identifier_bridges)->firstOrFail();    
                
        try{
            $response = Helper::httpClient('GET','interface/bridge',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        foreach($helper->decodeResponse($response) as $bridge){
            $bridge->router=$router->id; #talvez seja antes de descodificar
            array_push($bridges,$bridge);
        }

        return $bridges;
    }


    public function showBridgePorts(Request $request){
        $ports=[];  
        $helper = new Helper();     

        $router=Router::where('id',$request->identifier_ports)->firstOrFail();
        
        try{
            $response = Helper::httpClient('GET','interface/bridge/port',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        foreach($helper->decodeResponse($response) as $port){
            $port->router=$router->id; #talvez seja antes de descodificar
            array_push($ports,$port);
        }
      
        return $ports;
    }

    public function createBridges(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();
        $request['protocol-mode']=$request['protocol'];
        unset($request['protocol']);
        unset($request['identity']);
        if($request['name']=="null"){
            unset($request['name']);
        }
       
        try{
            $response = Helper::httpClient('PUT','interface/bridge',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function createBridgePorts(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        unset($request['identity']);
        if($request['interface']=="null"){
            unset($request['interface']);
        }
        if($request['bridge']=="null"){
            unset($request['bridge']);
        }
       
        try{
            $response = Helper::httpClient('PUT','interface/bridge/port',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
        
        return $response;

    }

}
