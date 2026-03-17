<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioUMK extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_gnet_inventario_umk";
}