<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;

class ContribucionPorCanales extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_canal_contribuciones";
}