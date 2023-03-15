<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromocionDetalle extends Model
{
    protected $table = "gumadesk.view_resumen_promocion";
    public static function getDetalles()
    {  
        $i      = 0;
        $json   = array();
        $Rows       = PromocionDetalle::where('anno', '2023')->get();
        
        $iPromo     = Promocion::where('id',$Rows[0]->id_promocion)->get();
        $fecha_ini  = $iPromo[0]->original['fecha_ini'];
        $fecha_end  = $iPromo[0]->original['fecha_end'];

        foreach($Rows as $r){


            $strQuery   = 'EXEC PRODUCCION.dbo.fn_promocion_venta_item "'.$fecha_ini.'","'.$fecha_end.'","'.$r->Articulo.'" ';            
            $query      = DB::connection('sqlsrv')->select($strQuery);

           

            $strQuery2   = 'EXEC PRODUCCION.dbo.fn_promocion_venta_item "'.PromocionDetalle::data_first_month_day().'","'.PromocionDetalle::data_last_month_day(date('m')+1).'","'.$r->Articulo.'" ';            
            $query2      = DB::connection('sqlsrv')->select($strQuery2);

            $Venta          = 0;
            $PromVenta      = 0;
            $VentaUND       = 0;
            $PromVentaUND   = 0;

            if (count($query )>0) {
                $Venta          = number_format($query[0]->VAL,2,'.','');
                $VentaUND       = number_format($query[0]->UND,0,'.','');
            }

            if (count($query2 )>0) {
                $VentaMesA          = number_format($query2[0]->VAL,2,'.','');
                $VentaUNDMesA       = number_format($query2[0]->UND,0,'.','');
            }

            $PromVenta      = ( $Venta !=0 ) ? ( $Venta / $r->ValMeta  ) * 100 : 0;
            $PromVentaUND   = ( $VentaUND !=0 ) ? ( $VentaUND / $r->MetaUnd  ) * 100 : 0;


            $json[$i]['id_promocion']       = $r->id_promocion;
            $json[$i]['Articulo']           = $r->Articulo;
            $json[$i]['Descripcion']        = $r->Descripcion;
            $json[$i]['Precio']             = $r->precio;
            $json[$i]['NuevaBonificacion']  = $r->NuevaBonificacion;
            $json[$i]['ValorVinneta']       = $r->ValorVinneta;
            $json[$i]['ValMeta']            = $r->ValMeta;
            $json[$i]['MetaUnd']            = $r->MetaUnd;
            //$json[$i]['Promedio_VAL']       = $r->Promedio_VAL;
            //$json[$i]['Promedio_UND']       = $r->Promedio_UND;
            $json[$i]['Venta']              = $Venta;
            $json[$i]['PromVenta']          = $PromVenta;
            $json[$i]['VentaMActual']       = $VentaMesA;
            $json[$i]['VentaUNDMActual']    = $VentaUNDMesA;
            $json[$i]['VentaUND']           = $VentaUND;
            $json[$i]['PromVentaUND']       = $PromVentaUND;
            $i++;

        }
        return  $json;
    }

    public static function data_last_month_day($month) { 
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month, 0, $year));
   
        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    }
   
    /** Actual month first day **/
    public static function data_first_month_day() {
        $month = date('m');
        $year = date('Y');
        return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    }

    public static function getPromoMes($articulo){

        $json = array();
        
        for($i = 1; $i <= 12; $i++){
            $fecha_ini = Date('Y')."/".$i."/"."01";
            $fecha_end = PromocionDetalle::data_last_month_day($i);
            if($i <= (date('m')+1)){
                $strQuery   = 'EXEC PRODUCCION.dbo.fn_promocion_venta_item "'.$fecha_ini.'","'.$fecha_end.'","'.$articulo.'" ';            
                $query      = DB::connection('sqlsrv')->select($strQuery);

                $Venta = $VentaUND = 0;
                if (count($query )>0) {
                    $Venta          = number_format($query[0]->VAL,2,'.',',');
                    $VentaUND       = number_format($query[0]->UND,0,'.',',');
                }

                $json[$i]['venta'] = $Venta;
                $json[$i]['unidad'] = $VentaUND;

            }else{
                $json[$i]['venta'] = 0.00;
                $json[$i]['unidad'] = 0;
            }
            
            
        }
        return $json;
    }    
}
