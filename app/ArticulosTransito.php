<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticulosTransito extends Model
{
    
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_articulos_transito";
    protected $primaryKey = 'Articulos';

    protected $fillable = [
        'Articulos',
        'fecha_estimada',
        'fecha_pedido',
        'documento',
        'cantidad',
        'mercado',
        'mific',
        'observaciones',
    ];
}
