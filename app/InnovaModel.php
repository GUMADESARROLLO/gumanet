<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnovaModel extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_inventario_innova";
}
