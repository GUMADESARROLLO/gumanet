<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\solicitudes_model;
use App\reportes_model;
use Illuminate\Support\Facades\DB;
use GPDF;
use PDF;

class vinetaliq_controller extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function resumenpdf(Request $request) {

        $aRutas         = reportes_model::rutas();
        $resumen        = json_decode(vinetaliq_controller::getSolicitudes($request)->content(), true);        
        $Ruta           = $request->input('RU');
        $found_key      = array_search($Ruta, array_column($aRutas, 'VENDEDOR'));
        $Ruta_Name      = $aRutas[$found_key]['NOMBRE'];
        $Fecha_generado = date('d/m/Y H:i:s');
        $FondoInicial   = $request->input('Fondo');
        $Nota           = $request->input('nota');
        
        $data = [
            'Ejecutivo'   =>  $Ruta_Name,
            'Fecha'       =>  $Fecha_generado,
            'Ruta'        =>  $Ruta,
            'Fondo'       =>  $FondoInicial,
            'Fondo'       =>  $FondoInicial,
            'Nota'        =>  $Nota
        ];

        //return view('pages.resumen', compact('data','resumen'));

        $pdf = PDF::loadView('pages.resumen', compact('data','resumen'));
        return $pdf->download('Resumen.pdf');
        
    }
    public function index() {
        $this->agregarDatosASession();

        $clientes   = reportes_model::clientes();
        $rutas      = reportes_model::rutas();

        $data = [
            'name' =>  'GUMA@NET',
            'page' => 'Ventas'
            

        ];
        
        return view('pages.vinneta_liq', compact('data', 'clientes','rutas'));
    }

    public function agregarDatosASession() {
        $request = Request();
        $ApplicationVersion = new \git_version();
        $company = Company::where('id',$request->session()->get('company_id'))->first();
        $request->session()->put('ApplicationVersion', $ApplicationVersion::get());
        $request->session()->put('companyName', $company->nombre);
    }

    public static function getSolicitudes(Request $request) {
        

        $from   = $request->input('f1').' 00:00:00';
        $to     = $request->input('f2').' 23:59:59';
        
        $Ruta   = $request->input('RU');
        $Clie   = $request->input('CL');
        $Stat   = $request->input('St');

        $i=0;

        if (!$from) {
            return response()->json(false);
        }

        $data = array();

        $query=DB::table('tbl_order_vineta')->whereBetween('created_at', [$from, $to])->whereNotIn('status', array(3));

        if($Ruta != '') {
            $query->where('ruta', '=',  $Ruta);
        }
        
        if($Clie != '') {
            $query->where('cod_cliente', 'like', '%'.$Clie.'%');
        }

        if($Stat != '') {
            $query->whereIn('status', array($Stat));
        }

        $obj = $query->get();




        foreach ($obj as $qR => $key) {

            

            $arrDetalles = array();


            $data[$i]["DETALLE"]        = '<a id="exp_more" class="exp_more" href="#!"><i class="material-icons expan_more">expand_more</i></a>';
            $data[$i]['ID']             = $key->id;
            $data[$i]['VENDEDOR']       = $key->ruta;
            $data[$i]['CLIENTE']        = substr(TRIM($key->cod_cliente),0,-1);;
            $data[$i]['NOMBRE_CLIENTE'] = $key->name_cliente;
            $data[$i]['FECHA']          = date('d/m/Y', strtotime($key->created_at));       
            $data[$i]['TOTAL']          = $key->order_total;
            
            $data[$i]['RECIBO']         = $key->recibo;
            $data[$i]['BENEFIC']        = $key->address;
            $data[$i]['COMMENT']        = $key->comment;
            $data[$i]['COMMENT_ANUL']   = $key->comment_anul;


            $OrdenList  = $key->order_list;
            $Lineas     = explode("],", $OrdenList);
            $cLineas    = count($Lineas) - 1;
            

            for ($l=0; $l < $cLineas ; $l++){
                
                $Lineas_detalles     = explode(";", $Lineas[$l]);

                $arrDetalles[$l]['FACTURA']     = str_replace('[', '', $Lineas_detalles[0]);
                $arrDetalles[$l]['VOUCHER']     = $Lineas_detalles[1];
                $arrDetalles[$l]['CANTIDAD']    = $Lineas_detalles[2];
                $arrDetalles[$l]['VALOR_UNIT']  = $Lineas_detalles[3];
                $arrDetalles[$l]['TOTAL_UNIT']  = $Lineas_detalles[4];
                $arrDetalles[$l]['LINEA']       = $Lineas_detalles[5];

                
            }

            $data[$i]['DETALLES']       = $arrDetalles;


            if ($key->status==0) {
                $data[$i]["BOTONES"]        = '<button type="button" class="btn btn-outline-success"  onClick="Liquidar('.$key->id.')">Procesar</button>
                <button type="button" class="btn btn-outline-danger"  onClick="open_modal_anulacion('.$key->id.')">Anular</button>';
            } else if($key->status==1) {
                $data[$i]["BOTONES"]        = '<div class="alert alert-success" role="alert">
                                                    Procesada.
                                                </div>';
            }else if($key->status==2){
                $data[$i]["BOTONES"]        = '<div class="alert alert-danger" role="alert">
                                                    Anulada
                                                </div>';
            } else if($key->status==3){
                $data[$i]["BOTONES"]        = '<div class="alert alert-danger" role="alert">
                                                    Elim. Ruta
                                                </div>';
            }

            $i++;
        }
        
        
        return response()->json($data);

    }
    public static function UpdateStatus($ID,$Status,$Message){
		
        $request = Request();

        $vUpdate = array(
            'status'        =>  $Status,
            'comment_anul'  =>  $Message
        );
        
        solicitudes_model::where('id', $ID)->update($vUpdate);
        
        $Row = solicitudes_model::where('id',$ID)->first();

        if ($Status==1) {
            vinetaliq_controller::SendNotifications("Solicitud Aceptada","Recibo Nº ".$Row->recibo." ",$Row->player_id);
        } else if($Status==2) {
            vinetaliq_controller::SendNotifications("Solicitud Anulada","Recibo Nº ".$Row->recibo." ",$Row->player_id);
        }
        
        

	}

    public static function SendNotifications($Titulo,$Contenido,$userId){

        $onesignal_app_id       = "d97b7af8-b696-418f-875e-8547e9fe7581"; 
        $onesignal_rest_api_key = "ZjIwZTM4MjYtN2Q1OS00YzM4LTljMmMtODY5NTQ3NjA2MmE5";

        define("ONESIGNAL_APP_ID", $onesignal_app_id);
        define("ONESIGNAL_REST_KEY", $onesignal_rest_api_key);

        $content = array(
            "en" => $Contenido
        );
    
        $data_noti = array(
            'app_id' => ONESIGNAL_APP_ID,
            'include_player_ids' => array($userId),
            'data' => array("foo" => "bar", "cat_id"=> "1010101010"),
            'headings'=> array("en" => $Titulo),
            'contents' => $content
        );

        $data_noti = json_encode($data_noti);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_noti);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
    }
    public function cancelarliq(Request $request){
        if($request->isMethod('post')) {
            $id = $request->input('id');
            $me = $request->input('me');
            vinetaliq_controller::UpdateStatus($id,2,$me);
        }
    }

    public function pushliq(Request $request){
        $sql_server = new \sql_server();
        $Sql = "";
        if($request->isMethod('post')) {
            $id = $request->input('id');
            $obj = solicitudes_model::where('id',$id)->get();
            foreach ($obj as $key) {

                $lVENDEDOR       = $key['ruta'];
                $lRECIBO         = $key['recibo'];
                $lCLIENTE        = substr(TRIM($key['cod_cliente']),0,-1);
                $lFECHA          = date('Y-m-d H:i:s');        


                $OrdenList  = $key['order_list'];
                $Lineas     = explode("],", $OrdenList);
                $cLineas    = count($Lineas) - 1;
                

                for ($l=0; $l < $cLineas ; $l++){
                    
                    $Lineas_detalles     = explode(";", $Lineas[$l]);

                    $lFACTURA     = str_replace('[', '', $Lineas_detalles[0]);
                    $lVOUCHER     = $Lineas_detalles[1];
                    $lCANTIDAD    = (int)$Lineas_detalles[2];
                    $lVALOR_UNIT  = (int)$Lineas_detalles[3];
                    $lLINEA       = (int)$Lineas_detalles[5];   

                    $Sql = "INSERT INTO [DESARROLLO].[dbo].[tbl_vineta_liquidadas] ([FACTURA], [VOUCHER], [LINEA], [CANTIDAD], [CLIENTE], [RUTA], [FECHA], [COD_RECIBO], [VALOR_UND]) 
                    VALUES ('".$lFACTURA."', '".$lVOUCHER."', '$lLINEA', '$lCANTIDAD', '".$lCLIENTE."', '".$lVENDEDOR."', '$lFECHA', '".$lRECIBO."', '$lVALOR_UNIT') ";
                    $sql_server->fetchArray($Sql, SQLSRV_FETCH_ASSOC);
                }
            }
            vinetaliq_controller::UpdateStatus($id,1,"");
        }
        
    }

    public function getVinnetasResumen(Request $request) {

        $f1 = $request->input('f1');
        $f2 = $request->input('f2');

        

        if (!$f1) {
            return response()->json(false);
        }

        $obj = vinneta_model::getVinnetasResumen($f1, $f2);

        return response()->json($obj);

    }

    public function getDetalleOrdenCompra(Request $request){
        if($request->isMethod('post')) {
            $obj = vinneta_model::getDetalleOrdenCompra($request->input('ordCompra'));
            return response()->json($obj);
        }
    }


}
