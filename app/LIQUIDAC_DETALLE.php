<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LIQUIDAC_DETALLE extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.LIQUIDAC_DETALLE";
}