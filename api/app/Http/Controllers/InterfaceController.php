<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Router;
use Illuminate\Http\Request;

class InterfaceController extends Controller
{
    public function showInterfaces(Request $request)
    {
              
        $router=Router::where('id',$request->identifier)->firstOrFail();
           
       if($request->type=='all'){
        try{
            $interfaces = Helper::httpClient('GET','interface',$router);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
       }else{
            try{   
                $interfaces = Helper::httpClient('GET','interface?type='.$request->type,$router);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), $e->getCode());
            }
        }                             

        return $interfaces;
        
    }
}
