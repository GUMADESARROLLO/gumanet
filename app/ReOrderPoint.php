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
                "EJECUTADO_UND_YTD"         => $a->EJECUTADO_UND_YTD,
                "DEMANDA_ANUAL_CA_NETA"      => $a->DEMANDA_ANUAL_CA_NETA,
                "DEMANDA_ANUAL_CA_AJUSTADA"  => $a->DEMANDA_ANUAL_CA_AJUSTADA,
                "FACTOR"                    => $a->FACTOR,
                "LIMITE_LOGISTICO_MEDIO"    => $a->LIMITE_LOGISTICO_MEDIO,
                "CLASE"                     => $a->CLASE,
                "VALUACION"                 => $a->VALUACION,
                "CONTRIBUCION"              => number_format($a->CONTRIBUCION,4,".",""),
                "PEDIDO_TRANSITO"           => number_format($a->PEDIDO_TRANSITO,4,".",""),
                "MOQ"                       => number_format($a->MOQ,4,".",""),
                "ESTIMACION_SOBRANTES_UND"  => number_format($a->ESTIMACION_SOBRANTES_UND,4,".",""),
                "REORDER1"                  => number_format($a->REORDER1,4,".",""),
                "REORDER"                   => number_format($a->REORDER,4,".",""),
                "CANTIDAD_ORDENAR"          => number_format($a->CANTIDAD_ORDENAR,4,".","")
            ];
        }

        return $array;
    }
    public static function getDataGrafica($Articulos) {

        $array = array();

        $Sales = ReOrderPoint::WHERE('ARTICULO',$Articulos)->first();
        
        $array["LEADTIME"] = $Sales->LEADTIME;
        $array["DEMANDA_ANUAL_CA_NETA"] = $Sales->DEMANDA_ANUAL_CA_NETA;
        $array["DEMANDA_ANUAL_CA_AJUSTADA"] = $Sales->DEMANDA_ANUAL_CA_AJUSTADA;
        $array["LIMITE_LOGISTICO_MEDIO"] = $Sales->LIMITE_LOGISTICO_MEDIO;
        $array["CONTRIBUCION"] = $Sales->CONTRIBUCION;

        $array["REORDER1"] = number_format($Sales->REORDER1,4,".","");
        $array["REORDER"] = number_format($Sales->REORDER,4,".","");
        $array["CANTIDAD_ORDENAR"] = number_format($Sales->CANTIDAD_ORDENAR,4,".","");
        $array["MOQ"] = $Sales->MOQ;
        $array["PEDIDO_TRANSITO"] = $Sales->PEDIDO_TRANSITO;
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
