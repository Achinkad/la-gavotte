<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;

class RoutingController extends Controller
{

    //BGP//////////////////////////

    public function showBGP(Request $request){
       
        $bgp_connections=[];
              
        $router=Router::where('id',$request->identifier)->firstOrFail();
 
        try{
            $bgp_connections = Helper::httpClient('GET','routing/bgp/connection',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
                
        return $bgp_connections;
    }

    public function createBGP(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        $request['router-id']=$request['router_id'];    
        $request['remote.address']=$request['remote_address'];
        $request['local.address']=$request['local_address'];
        $request['remote.as']=$request['remote_as'];
        $request['remote.port']=$request['remote_port'];
        $request['local.port']=$request['local_port'];
        $request['local.role']=$request['local_role'];
        unset($request['router_id']);
        unset($request['remote_address']);
        unset($request['local_address']);
        unset($request['remote_as']);
        unset($request['remote_port']);
        unset($request['local_port']);
        unset($request['local_role']);
        unset($request['identity']);

        if($request['router-id']==null){
            unset($request['router-id']);
        }
        if($request['remote.address']==null){
            unset($request['remote.address']);
        }
        if($request['local.address']==null){
            unset($request['local.address']);
        }
        if($request['remote.as']==null){
            unset($request['remote.as']);
        }
        if($request['remote.port']==null){
            unset($request['remote.port']);
        }
        if($request['local.port']==null){
            unset($request['local.port']);
        }
        if($request['local.role']==null){
            unset($request['local.role']);
        }

        try{
            $response = Helper::httpClient('PUT','routing/bgp/connection',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function editBGP(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $bgp_id=$request->bgp_identity;
        
        $request['router-id']=$request['router_id'];    
        $request['remote.address']=$request['remote_address'];
        $request['local.address']=$request['local_address'];
        $request['remote.as']=$request['remote_as'];
        $request['remote.port']=$request['remote_port'];
        $request['local.port']=$request['local_port'];
        $request['local.role']=$request['local_role'];
        unset($request['router_id']);
        unset($request['remote_address']);
        unset($request['local_address']);
        unset($request['remote_as']);
        unset($request['remote_port']);
        unset($request['local_port']);
        unset($request['local_role']);
        unset($request['identity']);

        if($request['router-id']==null){
            unset($request['router-id']);
        }
        if($request['remote.address']==null){
            unset($request['remote.address']);
        }
        if($request['local.address']==null){
            unset($request['local.address']);
        }
        if($request['remote.as']==null){
            unset($request['remote.as']);
        }
        if($request['remote.port']==null){
            unset($request['remote.port']);
        }
        if($request['local.port']==null){
            unset($request['local.port']);
        }
        if($request['local.role']==null){
            unset($request['local.role']);
        }


        unset($request['router_identity']);
        unset($request['bgp_identity']);
        try{
            Helper::httpClient('PUT','routing/bgp/connection/'.$bgp_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteBGP(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','routing/bgp/connection/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }


    //OSPF//////////////////////////

    public function showOSPFInstance(Request $request){
       
        $ospf_instances=[];
                 
        $router=Router::where('id',$request->identifier)->firstOrFail();
   
        try{
            $ospf_instances = Helper::httpClient('GET','routing/ospf/instance',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $ospf_instances;
    }

    public function createOSPFInstance(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        $request['router-id']=$request['router_id'];    

        unset($request['router_id']);
        unset($request['identity']);

        
        if($request['redistribute']==null){
            unset($request['redistribute']);
        }

        try{
            $response = Helper::httpClient('PUT','routing/ospf/instance',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function editOSPFInstance(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $ospf_id=$request->ospf_identity;
        $request['router-id']=$request['router_id'];    
        unset($request['router_id']);
        
        
        if($request['redistribute']==null){
            unset($request['redistribute']);
        }


        unset($request['router_identity']);
        unset($request['ospf_identity']);
        
        try{
            Helper::httpClient('PUT','routing/ospf/instance/'.$ospf_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteOSPFInstance(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','routing/ospf/instance/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function showOSPFArea(Request $request){
       
        $ospf_areas=[];
 
        $router=Router::where('id',$request->identifier)->firstOrFail();

        try{
            $ospf_areas = Helper::httpClient('GET','routing/ospf/area',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
                
        return $ospf_areas;
    }

    public function createOSPFArea(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        $request['area-id']=$request['area_id'];    

        unset($request['area_id']);
        unset($request['identity']);

        try{
            $response = Helper::httpClient('PUT','routing/ospf/area',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function editOSPFArea(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $ospf_id=$request->ospf_identity;

        $request['area-id']=$request['area_id'];    
        unset($request['area_id']);


        unset($request['router_identity']);
        unset($request['ospf_identity']);
        
        try{
            Helper::httpClient('PUT','routing/ospf/area/'.$ospf_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteOSPFArea(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','routing/ospf/area/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
      
    }


    public function showOSPFTemplate(Request $request){
       
        $ospf_templates=[];
               
        $router=Router::where('id',$request->identifier)->firstOrFail();
 
        try{
            $ospf_templates = Helper::httpClient('GET','routing/ospf/interface-template',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
                
        return $ospf_templates;
    }

    public function createOSPFTemplate(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        
        unset($request['identity']);

        try{
            $response = Helper::httpClient('PUT','routing/ospf/interface-template',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function editOSPFTemplate(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $ospf_id=$request->ospf_identity;

        unset($request['router_identity']);
        unset($request['ospf_identity']);
        
        try{
            Helper::httpClient('PUT','routing/ospf/interface-template/'.$ospf_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function deleteOSPFTemplate(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','routing/ospf/interface-template/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }

    //RIP//////////////////////////

    public function showRIPInstance(Request $request){
       
        $rip_instances=[];
                
        $router=Router::where('id',$request->identifier)->firstOrFail();
       
        try{
            $rip_instances = Helper::httpClient('GET','routing/rip/instance',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $rip_instances;
    }

    public function createRIPInstance(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        
        unset($request['identity']);

        try{
            $response = Helper::httpClient('PUT','routing/rip/instance',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function editRIPInstance(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $rip_id=$request->rip_identity;

        unset($request['router_identity']);
        unset($request['rip_identity']);
        
        try{
            Helper::httpClient('PUT','routing/rip/instance/'.$rip_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }


    public function deleteRIPInstance(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','routing/rip/instance/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
      
    }

    public function showRIPTemplate(Request $request){
       
        $rip_templates=[];
               
        $router=Router::where('id',$request->identifier)->firstOrFail();
     
        try{
            $rip_templates = Helper::httpClient('GET','routing/rip/interface-template',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }       
        
        return $rip_templates;
    }

    public function createRIPTemplate(Request $request){

        if($request->identity=='-'){
          return "false";
        }

        $router=Router::where('id',$request['identity'])->firstOrFail();

        
        unset($request['identity']);

        try{
            $response = Helper::httpClient('PUT','routing/rip/interface-template',$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    public function editRIPTemplate(Request $request) 
    {
       
        $router=Router::where('id',$request->router_identity)->firstOrFail();

        $rip_id=$request->rip_identity;

        unset($request['router_identity']);
        unset($request['rip_identity']);
        
        try{
            Helper::httpClient('PUT','routing/rip/interface-template/'.$rip_id,$router,$request->all());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }


    public function deleteRIPTemplate(Request $request) 
    {

        $router=Router::where('id',$request->identity)->firstOrFail();

        try{
            Helper::httpClient('DELETE','routing/rip/interface-template/'.$request->id,$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
      
    }
}
