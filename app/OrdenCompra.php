<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.ORDEN_COMPRA";

    public function getProveedor()
    {
        return $this->hasOne(Proveedor::class, 'PROVEEDOR', 'PROVEEDOR');
    }

    public function getLineasOrden()
    {
        return $this->hasMany(OrdenCompraLinea::class, 'ORDEN_COMPRA', 'ORDEN_COMPRA');
    }

    public function getEmbarqueLinea()
    {
        return $this->hasMany(EmbarqueLinea::class, 'ORDEN_COMPRA', 'ORDEN_COMPRA');
    }

    public static function getData($request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';

        $Obj =  self::whereBetween('FECHA', [$request->desde, $request->hasta])->get();

        $array_ordenes = array();

        foreach ($Obj as $key => $a) 
        {  
            $array_ordenes[$key] = [
                "ORDEN_COMPRA"  => $a->ORDEN_COMPRA,
                "FECHA"         => $a->FECHA,
                "TOTAL_A_COMPRAR"     => $a->TOTAL_A_COMPRAR,
            ];
            
        }
        return $array_ordenes;
    }
}
