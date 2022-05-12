<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\inteligenciaMercado_model;
use App\Company;
use Response;


//use Illuminate\Support\Facades\Http;


class importaciones_controller extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
    }
 
     public function exist_notify(Request $request){
        $client = new Client;
        $data = array();
        $count_IM = 0 ; $count_expo = 0;
        //Get Notificaciones  de expor taciones;
        $notificaciones = $client->get('http://127.0.0.1/exportaciones/api/notificaciones');
        $data_Exp = $notificaciones->getBody()->getContents();
        
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;	
        $count_IM = inteligenciaMercado_model::where('Read', '=', 0)->where('empresa', $company_user)->count();

        foreach(json_decode($data_Exp) as $resp){
            if($resp->leido == 0){
                $count_expo++;
            }
        }
        
        $total = $count_expo +  $count_IM;
 
        if($total > 0 ){
            return response()->json($total);
        }else{
            return response()->json(false);
        }
         
     }


}
