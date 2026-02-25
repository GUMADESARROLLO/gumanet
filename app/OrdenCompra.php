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
}
