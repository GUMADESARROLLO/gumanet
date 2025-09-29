<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class IwebBodegas extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.iweb_bodegas";
}