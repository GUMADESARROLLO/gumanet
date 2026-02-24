<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UltimaOrdenCompras extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_gnet_ultimaOrdenCompras";


    public static function UltimaCompra($Articulo)
    {  
        return self::select('ORDEN_COMPRA')->where("ARTICULO",$Articulo)->get()->toArray();
    }
}
