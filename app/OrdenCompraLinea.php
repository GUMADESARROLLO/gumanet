<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraLinea extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.ORDEN_COMPRA_LINEA";

    public function getArticulo()
    {
        return $this->hasOne(Articulo::class, 'ARTICULO', 'ARTICULO');
    }
}
