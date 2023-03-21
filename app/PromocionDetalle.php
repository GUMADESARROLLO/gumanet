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
        $anno   = Date('Y');
        $meses = arraY('ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
        $Rows       = PromocionDetalle::where('anno', $anno)->get();
        
        $iPromo     = Promocion::where('id',$Rows[0]->id_promocion)->get();
        $fecha_ini  = $iPromo[0]->original['fecha_ini'];
        $fecha_end  = $iPromo[0]->original['fecha_end'];

        foreach($Rows as $r){

            $Articulos = trim($r->Articulo) ;
            $strQuery   = 'EXEC PRODUCCION.dbo.fn_promocion_venta_item "'.$fecha_ini.'","'.$fecha_end.'","'.$Articulos.'" ';            
            $query      = DB::connection('sqlsrv')->select($strQuery);

            //sql   = 'EXEC PRODUCCION.dbo.fn_promocion_history_item_sale "'.$anno.'","'.$r->Articulo.'" ';            
            //$resp      = DB::connection('sqlsrv')->select($sql);

            $Venta          = 0;
            $PromVenta      = 0;
            $VentaUND       = 0;
            $PromVentaUND   = 0;
            $VentaMesA      = 0;
            $VentaUNDMesA   = 0;
            $j      = 1;

            if (count($query )>0) {
                $Venta              = number_format($query[0]->VAL,2,'.','');
                $VentaUND           = number_format($query[0]->UND,0,'.','');
                $VentaMesA          = number_format($query[0]->VentaMesActual,2,'.','');
                $VentaUNDMesA       = number_format($query[0]->UNDMesActual,0,'.','');

                $AVG_VLR           = number_format($query[0]->AVG_VALOR_LAST_YEAR,2,'.','');
                $AVG_UND           = number_format($query[0]->AVG_UND_LAST_YEAR,0,'.','');
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
            $json[$i]['Promedio_VAL']       = $AVG_VLR;
            $json[$i]['Promedio_UND']       = $AVG_UND;
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

    public static function getPromoMes($articulo){

        $json = array();
        $anno = Date('Y');

        $strQuery   = 'EXEC PRODUCCION.dbo.fn_promocion_history_item_sale "'.$anno.'","'.$articulo.'" ';            
        $query      = DB::connection('sqlsrv')->select($strQuery);

        if(count($query)){
            $json[] = $query;
        }
                
        return $json;
    }    
}
