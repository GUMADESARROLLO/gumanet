<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClientesNew extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.UMK.CLIENTE";
    //protected $table = "PRODUCCION.dbo.CLIENTE";
}