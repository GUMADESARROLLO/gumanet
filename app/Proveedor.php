<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.PROVEEDOR";
}
