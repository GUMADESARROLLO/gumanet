<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InnovaKardex extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_inventario_innova_kardex";

    

    public static function getKardex(Request $request)
    {
        $id = $request->input('ArticuloID');
        $d1 = $request->input('DateStart');
        $d2 = $request->input('DateEnd');
        return InnovaKardex::where('ID_ART ',$id)
                ->orderBy('ID', 'DESC')
                ->whereBetween('FECHA', [$d1, $d2])
                ->get();
    }

    public static function getReporteKardex($ini, $end)
    {
        $d1 = $ini;
        $d2 = $end;

        $json_arrays = array();
        $i = 0 ;

        try{
     
            $dtFechas = InnovaKardex::select('FECHA')
                    ->whereBetween('FECHA', [$d1, $d2])
                    ->groupBy('FECHA')
                    ->get();

            $json_arrays['header_date_count'] = count($dtFechas) ;

            foreach($dtFechas as $f){
                $json_arrays['header_date'][$i] = $f->FECHA;
                $i++;
            }
            $json_arrays['header_date'][$i+1] = 'Ult. Registro.';
            
            $Rows = DB::connection('sqlsrv')->select('SET NOCOUNT ON ;EXEC PRODUCCION.dbo.gnet_calcular_kardex '."'".$d1."'".','."'".$d2."'".",''" );
            foreach($Rows as $r){
                
                $RoleUsr = KardexUsuario::find($r->USUARIO);
                
                $json_arrays['header_date_rows'][$i]['ARTICULO'] = $r->ARTICULO;
                $json_arrays['header_date_rows'][$i]['DESCRIPCION'] = $r->DESCRIPCION;
                $json_arrays['header_date_rows'][$i]['UND'] = $r->UND;
                $json_arrays['header_date_rows'][$i]['USUARIO'] = $RoleUsr->rol->descripcion;
                foreach($json_arrays['header_date'] as $dtFecha => $valor){

                    $rows_in = 'IN01_'.date('Ymd',strtotime($valor));
                    $rows_out = 'OUT02_'.date('Ymd',strtotime($valor));
                    $rows_stock = 'STOCK03_'.date('Ymd',strtotime($valor));
                    
                    if($valor == 'Ult. Registro.'){
                        $rows_in = 'IN_TODAY';
                        $rows_out = 'OUT_TODAY';
                        $rows_stock = 'STOCK_TODAY';
                    }

                    $json_arrays['header_date_rows'][$i][$rows_in] = ($r->$rows_in=='0.0' || $r->$rows_in=='00.00') ? '' : number_format($r->$rows_in,2)  ;
                    $json_arrays['header_date_rows'][$i][$rows_out] = ($r->$rows_out=='0.0' || $r->$rows_out=='00.00') ? '' : number_format($r->$rows_out,2);
                    $json_arrays['header_date_rows'][$i][$rows_stock] =($r->$rows_stock=='0.0' || $r->$rows_stock=='00.00') ? '' : number_format($r->$rows_stock,2) ;
                }
                $i++;
            }
            //DD($json_arrays);
            return $json_arrays;
        }catch(Exception $e){
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            //return $json_arrays;
        }

       
    }

    public static function InitKardex(Request $request){
        try {
            $datos_a_insertar = array();    
            $Articulos = ArticuloInnova::getArticulos();
            InnovaKardex::where('USUARIO', Auth::id())->delete();
            foreach ($Articulos as $key => $val) {
                $datos_a_insertar[$key]['ID_ART']           = $val->ID;
                $datos_a_insertar[$key]['ARTICULO']         = $val->ARTICULO;
                $datos_a_insertar[$key]['DESCRIPCION']      = $val->DESCRIPCION;
                $datos_a_insertar[$key]['ENTRADA']          = 0;
                $datos_a_insertar[$key]['SALIDA']           = 0;
                $datos_a_insertar[$key]['STOCK']            = $val->CANTIDAD;
                $datos_a_insertar[$key]['TIPO_MOVIMIENTO']  = 'In';
                $datos_a_insertar[$key]['FECHA']            = date('Y-m-d');
                $datos_a_insertar[$key]['USUARIO']          = Auth::id();
                $datos_a_insertar[$key]['created_at']       = date('Y-m-d H:i:s');
                
            }
            InnovaKardex::insert($datos_a_insertar); 
            
        } catch (Exception $e) {
            $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
            return response()->json($mensaje);
        }
    }

    public static function getResumenKardex(){
        $json = array();
        $i = 0;

        $result = DB::connection('sqlsrv')->select('SELECT * FROM PRODUCCION.dbo.view_stats_inventario_innova');
     
        foreach($result as $row){
            $json[$i]['Product'] = $row->Product;
            $json[$i]['PT'] = $row->PT;
            $json[$i]['JR'] = $row->JR;
            $json[$i]['MP'] = $row->MP;
            $json[$i]['TE'] = $row->TE;
            $i++;
        }
            $json[$i]['Product'] = 'CHOLIN GIGANTE';
            $json[$i]['PT'] = "0";
            $json[$i]['JR'] = "0";
            $json[$i]['MP'] = "0";
            $json[$i]['TE'] = "0";

        return $json;
    }
}
