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

            $strQuery   = 'EXEC PRODUCCION.dbo.fn_promocion_item_venta "'.$fecha_ini.'","'.$fecha_end.'","'.$r->Articulo.'" ';            
            $query      = DB::connection('sqlsrv')->select($strQuery);

            $sql   = 'EXEC PRODUCCION.dbo.fn_promocion_history_item_sale "'.$anno.'","'.$r->Articulo.'" ';            
            $resp      = DB::connection('sqlsrv')->select($sql);


            $Venta          = 0;
            $PromVenta      = 0;
            $VentaUND       = 0;
            $PromVentaUND   = 0;
            $VentaMesA      = 0;
            $VentaUNDMesA   = 0;
            $j      = 1;

            foreach($meses as $mes){
                if($j == Date('n')){
                    $VentaMesA = $resp[1]->$mes;
                    $VentaUNDMesA = $resp[0]->$mes;
                }
                $j++;
            }

            foreach($query as $item){
                $Venta          += number_format($item->VAL,2,'.','');
                $VentaUND       += number_format($item->UND,0,'.','');
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
