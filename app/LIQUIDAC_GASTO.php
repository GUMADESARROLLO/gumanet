<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LIQUIDAC_GASTO extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.LIQUIDAC_GASTO";
}