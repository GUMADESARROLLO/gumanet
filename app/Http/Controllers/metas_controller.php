<?php
namespace App\Http\Controllers;
use App;
use App\metas_model;
use App\Models;
use App\User;
use App\Gn_couta_x_producto;
use App\Tmp_meta_exl;
use App\meta_recuperacion_exl;
use App\Company;
use DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

class metas_controller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');//pagina se carga unicamente cuando se este logeado
        ini_set('memory_limit', '3048M');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    function index(){
        $this->agregarDatosASession();
        $users = User::all();
        $data = [
            'page' => 'Metas',
            'name' => 'GUMA@NET'
        ];
        
        return view('pages.metas',compact('data','users'));
    }

    public function agregarDatosASession(){
        $request = Request();
        $ApplicationVersion = new \git_version();
        $company = Company::where('id',$request->session()->get('company_id'))->first();// obtener nombre de empresa mediante el id de empresa
        $request->session()->put('ApplicationVersion', $ApplicationVersion::get());
        $request->session()->put('companyName', $company->nombre);// agregar nombre de compañia a session[], para obtenert el nombre al cargar otras pagina 
    }

    public function exportMetaFromExlVenta(Request $request){
        
        $file_directory = "tmp_excel/";

        if(!empty($_FILES["addExlFileMetas"])){
            
            $mes = $request->input('mes');
            $anno = $request->input('anno');


            $file_array = explode(".", $_FILES["addExlFileMetas"]["name"]);
            $new_file_name = "tmp_excel.". $file_array[1];
            move_uploaded_file($_FILES["addExlFileMetas"]["tmp_name"], $file_directory . $new_file_name);
            if($file_array[1]=="xlsx" || $file_array[1]=="xls"){
                

                $file_type  = PHPExcel_IOFactory::identify("tmp_excel/".$new_file_name);
                $objReader  = PHPExcel_IOFactory::createReader($file_type);
                $objPHPExcel = $objReader->load($file_directory . $new_file_name);
                //$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

                try{
                    $i = 2;//contador
                    $param=0;
                
                    $nomRuta = $objPHPExcel->getActiveSheet()->getCell('A1')->getCalculatedValue();
                    $nomVende = $objPHPExcel->getActiveSheet()->getCell('B1')->getCalculatedValue();
                    $metaRecu = $objPHPExcel->getActiveSheet()->getCell('C1')->getCalculatedValue();

                    if($nomRuta == '' || $nomVende == '' || $metaRecu == ''){

                    }else{
                        $jsonArray = array();
                        $nomRuta = $objPHPExcel->getActiveSheet()->getCell('A1')->getCalculatedValue();
                        $nomVende = $objPHPExcel->getActiveSheet()->getCell('B1')->getCalculatedValue();
                        $meta = $objPHPExcel->getActiveSheet()->getCell('C1')->getCalculatedValue();
                        $limite = $objPHPExcel->getActiveSheet()->getCell('D1')->getCalculatedValue();

                        if(strlen($nomRuta) != NULL & strlen($nomVende) != NULL & strlen($meta) != NULL & strlen($limite) == NULL)
                        {
                        
                            while ($param==0) {

                                $jsonArray[$i-2]['ruta'] =$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
                                $jsonArray[$i-2]['vendedor'] =$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
                                $jsonArray[$i-2]['meta'] =$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
                                /*
                                meta_recuperacion_exl::insert(array('fechaMeta' => $anno.'/'.$mes.'/01','FHGrabacion'=> new\DateTime(),
                                    'ruta' => $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue(),
                                    'vendedor' => $this->addZeros($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue()),
                                    'meta' => $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue()));
                                    */          

                                $i++;
                                if($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue()==NULL){
                                    $param=1;
                                } 
                            }
                        }
                        return $jsonArray;
                    }
                    

                }catch(Exception $e){
                    echo "excepción: ".$e;
                }

            }else{
                echo "Archivo invalido";
            }

        }else{
            echo "no paso";
        }
    }

    public function addDataRecuToDB(Request $request) {
        $data = array();
        $data = $request->all();

        if (isset($data['datas'])) {


        if (count($data['datas']) > 0) {         
            $company_id = Company::where('id',$request->session()->get('company_id'))->first()->id;
            foreach($data['datas'] as $key) {
                meta_recuperacion_exl::insert(array('idCompanny' => $company_id,'fechaMeta' => $key['anno'].'/'.$key['mes'].'/01','ruta' => $key['ruta'],'vendedor' => $key['vendedor'],'meta' => $key['meta'],'FHGrabacion' => new\DateTime()));
            }

            return 1;
        }else{
            return 0;
        }

        }else{
        return 0;
        }
    }

    public function exportMetaFromExl(Request $request) {
        $obj = json_decode($request->input('data'), true);

        try{
            foreach (array_chunk($obj,1000) as $t) {
                Tmp_meta_exl::insert($t);
            }
        }catch(Exception $e) {
            echo "excepción: ".$e;
        }
    }

    public function getTmpExlData() {          
        //return DataTables::of(Tmp_meta_exl::latest()->get())->make(true);
        $tempTable = Tmp_meta_exl::query();
        return DataTables::of($tempTable)->make(true);      
    }

    public function getHistorialMeta(Request $request){
        $idPeriodo = '';
            
        if($request->isMethod('post')){
            $mes = $request->input('mes');
            $anno = $request->input('anno');
            $metaData = array();            

            $fecha =  date('Y-m-d', strtotime($anno.'-'.$mes.'-01'));
            $company_id = Company::where('id',$request->session()->get('company_id'))->first()->id;
            $idPeriodo = DB::connection('sqlsrv')->table('metacuota_GumaNet')->where('Fecha',$fecha)->where('IdCompany',$company_id)->get(['IdPeriodo']);


            if($idPeriodo->isNotEmpty()){
                
                $metaData = Gn_couta_x_producto::where('IdPeriodo',$idPeriodo[0]->IdPeriodo)->get();

            }else{
                $metaData['data']['CodVendedor'] = 'No Hay Datos';
                $metaData['data']['CodProducto'] = 'No Hay Datos';
                $metaData['data']['NombreProducto'] = 'No Hay Datos';
                $metaData['data']['Meta'] = 'No Hay Datos';
                $metaData['data']['val'] = 'No Hay Datos';
            }
                
            

            
            return DataTables::of($metaData)->make(true);
            
        }
    }


    public function getHistoriaMetaRecu(Request $request){
        if($request->isMethod('post')){
            $mes = $request->input('mes');
            $anno = $request->input('anno');
            $metaDataVenta = array();            

            $fecha =  date('Y-m-d', strtotime($anno.'-'.$mes.'-01'));
            $company_id = Company::where('id',$request->session()->get('company_id'))->first()->id;

            $metaDataVenta = meta_recuperacion_exl::where('idCompanny',$company_id)->where('fechaMeta',$fecha)->get();

            return DataTables::of($metaDataVenta)->make(true);
        }
    }

    public function existeFechaMetaVenta(Request $request){
        if($request->isMethod('post')){
            $mes = $request->input('mes');
            $anno = $request->input('anno');
            $fecha =  date('Y-m-d', strtotime($anno.'-'.$mes.'-01'));
            $company_id = Company::where('id',$request->session()->get('company_id'))->first()->id;
            $res = meta_recuperacion_exl::where('fechaMeta', $fecha)->where('idCompanny', $company_id)->get();
            if (empty($res[0])){
                return 0;
            }else{
                return 1;
            }
        }
    }


    public function existeFechaMeta(Request $request){
        if($request->isMethod('post')){
            $mes = $request->input('mes');
            $anno = $request->input('anno');
            $fecha =  date('Y-m-d', strtotime($anno.'-'.$mes.'-01'));
            $company_id = Company::where('id',$request->session()->get('company_id'))->first()->id;
            $res = DB::connection('sqlsrv')->table('metacuota_GumaNet')->where('Fecha', $fecha)->where('idCompany', $company_id)->get();
            if (empty($res[0])){
                return 0;
            }else{
                return 1;
            }
        }
    }


    public function truncate_tmp_exl_tbl() {
        Tmp_meta_exl::truncate();
    }


    public function add_data_meta(Request $request){
        $metaXProducto = array();
        $data = json_decode(json_encode(Tmp_meta_exl::all()), True); //class stdObjet to array

        
        if(empty($data)){
            return 0;
        }else{
            $fecha = date('Y-m-d', strtotime(substr($data[0]['fechaMeta'],0,10))); //devuelve los primeros 10 digitos de la cadena
            $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
            
            $dHab = $data[0]['dHab'];
            
            //VALIDA SI EXISTE LA META SEGUN LA FECHA
            $ex = $this->validateDate($fecha, $company_user, $request);

            if ($ex==false) {
                $fechaDesc = date('d-m-Y', strtotime(substr($data[0]['fechaMeta'],0,10))); //devuelve los primeros 10 digitos de la cadena
                $this->cambiarEstadoMeta();

                ///////AGREGA ENCABEZADO DE META
                $idPeriodo = DB::connection('sqlsrv')->table('metacuota_GumaNet')->insertGetId(['Tipo'=>'CUOTA', 'Descripcion' => 'COUTA-'.$fechaDesc, 'Estado' => 1,'Fecha' => $fecha,'IdCompany' => $company_user,'dias_facturados' => $dHab]);
            }else {
                $idPeriodo = $ex;
                DB::connection('sqlsrv')->table('gn_cuota_x_productos')->where('IdPeriodo', $idPeriodo)->delete();
                
            }

            //$this->addDataFromTmpToDataMeta($data,$idPeriodo);
            $metaXPXPrroducto = $this->calcAddUnidadMeta();
            $this->addDataFromTmpToCoutaXProd($metaXPXPrroducto, $idPeriodo);
            return 1;
        }
    }

    public function validateDate($fecha, $company_id, $request) {
        $res = DB::connection('sqlsrv')->table('metacuota_GumaNet')->where('Fecha', $fecha)->where('idCompany', $company_id)->get();
            
        if (empty($res[0])){
            return false;
        }else{
            return DB::connection('sqlsrv')->table('metacuota_GumaNet')->where('Fecha', $fecha)->where('idCompany', $company_id)->first()->IdPeriodo;
        }
    }

    private function calcAddUnidadMeta(){
        $coleccion = Tmp_meta_exl::select('fechaMeta', 'ruta', 'articulo', 'descripcion',\DB::raw('sum(valor) as valor, sum(unidad) as unidad'))->groupBy('ruta','articulo','descripcion','fechaMeta')->get();
        return $coleccion;
    }

    private function addDataFromTmpToCoutaXProd($metaXProducto, $idPeriodo){//Agregar datos Calculados
        foreach ($metaXProducto as $key){
            Gn_couta_x_producto::insert(['CodVendedor' => $key['ruta'],'CodProducto' => $key['articulo'],'NombreProducto' => $key['descripcion'], 'FHGrabacion' => new\DateTime(),'Meta' => $key['unidad'], 'IdPeriodo' => $idPeriodo, 'val' => $key['valor']]);
        }
        
    }

    /*private function addDataFromTmpToCoutaXProd($data, $idPeriodo){
        
        //////AGREGAR DATOS PARA CALCULAR METAS///////
        /foreach ($data as $key) {
            $fecha = date('Y-m-d', strtotime($key['created_at']));
            Metadata::insert(['fechaMeta'=> $key['fechaMeta'], 'ruta'=> $key['ruta'], 'codigo'=> $key['codigo'], 'cliente'=> $key['cliente'], 'articulo'=> $key['articulo'], 'descripcion'=> $key['descripcion'], 'valor'=> $key['valor'], 'unidad'=> $key['unidad'], 'created_at'=> $key['created_at'], 'IdPeriodo' => $idPeriodo]);
        }
        
    }*/

    private function cambiarEstadoMeta(){
        DB::connection('sqlsrv')->table('metacuota_GumaNet')->where('Estado',1)->update(['Estado' => 0]);
    }

    private function addZeros($code){
        $res='';
        switch (strlen($code)) {
            case 4:
                $res = '0'.$code;
                break;
            case 3:
                $res = '00'.$code;
                break;
            case 2:
                $res = '000'.$code;
                break;
            case 1:
                $res = '0000'.$code;
                break;
            
            default:
                $res = $code;
                break;
        }

        return $res;
        
    }
}