<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReOrderPoint extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_gnet_reorder_lvl3";

    public static function getArticulo() 
    {
        $array = [];
        $Articulos = ReOrderPoint::WHERE('VALUACION','!=',"0")->get();
        foreach ($Articulos as $key => $a) {
            $array[$key] = [
                "ARTICULO"                  => '<a href="#!" onclick="getDetalleArticulo('."'".$a->ARTICULO."'".', '."'".strtoupper($a->DESCRIPCION)."'".')" >'.$a->ARTICULO.'</a>',
                "DESCRIPCION"               => strtoupper($a->DESCRIPCION),
                "VENCE_MENOS_IGUAL_12"      => number_format($a->VENCE_MENOS_IGUAL_12,2),
                "VENCE_MAS_IGUAL_7"         => number_format($a->VENCE_MAS_IGUAL_7,2),
                "LOTE_MAS_PROX_VENCER"      => date("d-m-Y", strtotime($a->LOTE_MAS_PROX_VENCER)),
                "EXIT_LOTE_PROX_VENCER"     => number_format($a->EXIT_LOTE_PROX_VENCER,2),
                "LEADTIME"                  => $a->LEADTIME,
                "EJECUTADO_UND_YTD"         => number_format($a->EJECUTADO_UND_YTD,2),
                "DEMANDA_ANUAL_CA_NETA"      => number_format($a->DEMANDA_ANUAL_CA_NETA,2),
                "DEMANDA_ANUAL_CA_AJUSTADA"  => number_format($a->DEMANDA_ANUAL_CA_AJUSTADA,2),
                "FACTOR"                    => number_format($a->FACTOR,2),
                "LIMITE_LOGISTICO_MEDIO"    => number_format($a->LIMITE_LOGISTICO_MEDIO,2),
                "CLASE"                     => $a->CLASE,
                "VALUACION"                 => $a->VALUACION,
                "CONTRIBUCION"              => number_format($a->CONTRIBUCION,2),
                "PEDIDO_TRANSITO"           => number_format($a->PEDIDO_TRANSITO,2),
                "MOQ"                       => number_format($a->MOQ,2),
                "ESTIMACION_SOBRANTES_UND"  => number_format($a->ESTIMACION_SOBRANTES_UND,2),
                "REORDER1"                  => number_format($a->REORDER1,2),
                "REORDER"                   => number_format($a->REORDER,2),
                "CANTIDAD_ORDENAR"          => number_format($a->CANTIDAD_ORDENAR,2)
            ];
        }

        return $array;
    }
    public static function getDataGrafica($Articulos) {

        $array = array();

        $Sales = ReOrderPoint::WHERE('ARTICULO',$Articulos)->first();
        
        $array["LEADTIME"] = number_format($Sales->LEADTIME,2);
        $array["DEMANDA_ANUAL_CA_NETA"] = number_format($Sales->DEMANDA_ANUAL_CA_NETA,2);
        $array["DEMANDA_ANUAL_CA_AJUSTADA"] = number_format($Sales->DEMANDA_ANUAL_CA_AJUSTADA,2);
        $array["LIMITE_LOGISTICO_MEDIO"] = number_format($Sales->LIMITE_LOGISTICO_MEDIO,2);
        $array["CONTRIBUCION"] = number_format($Sales->CONTRIBUCION,2);

        $array["REORDER1"] = number_format($Sales->REORDER1,2);
        $array["REORDER"] = number_format($Sales->REORDER,2);
        $array["CANTIDAD_ORDENAR"] = number_format($Sales->CANTIDAD_ORDENAR,2);
        $array["MOQ"] = number_format($Sales->MOQ, 2);
        $array["PEDIDO_TRANSITO"] = number_format($Sales->PEDIDO_TRANSITO, 2);
        $array["CLASE"] = $Sales->CLASE;
        
        for ($i=1; $i <= 12; $i++) { 
            $array["VENTAS"][$i] = [
                "Mes"                 => "Mes".$i,
                "data" =>  (isset($Sales) && !empty($Sales->$i)) ? (float) number_format($Sales->$i,2,".","") : 0 
            ];
        }

        return $array;
    }
}
