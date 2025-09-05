<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClientesOld extends Model
{
    protected $connection = 'sqlsrv_old';
    public $timestamps = false;
    protected $table = "Softland.UMK.CLIENTE";
}