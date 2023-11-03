<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReOrderPoint extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_gnet_reorder_point";

    public static function getArticulo() 
    {
        $array = [];
        $Articulos = ReOrderPoint::all();
        foreach ($Articulos as $key => $a) {

            $array[$key]['ARTICULO']       = $a->ARTICULO;
            $array[$key]['DESCRIPCION']    = strtoupper($a->DESCRIPCION);
            $array[$key]['CANTIDAD']       = number_format(1000.00,2);
        }

        return $array;
    }
    public static function getDataGrafica($mes, $anio) {

        $array_merge = array();


        return dashboard_model::get_Ventas_diarias($mes, $anio, 1, 1,0);
        $sql_server->close();
    }
}
