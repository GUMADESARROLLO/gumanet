<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogTransacInventarioUmk extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.LOG_TRANSAC_INVENTARIO_UMK";
}
