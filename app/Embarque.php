<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embarque extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.EMBARQUE";

    public function getProveedor()
    {
        return $this->belongsTo(Proveedor::class, 'PROVEEDOR', 'PROVEEDOR');
    }
}
