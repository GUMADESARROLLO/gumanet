<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticulosTransito extends Model
{
    
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_articulos_transito";
    protected $primaryKey = 'Articulo';
    protected $keyType    = 'string';

    protected $fillable = [
        'Articulo',
        'Descripcion',
        'fecha_estimada',
        'fecha_pedido',
        'documento',
        'cantidad',
        'mercado',
        'mific',
        'observaciones',
        'Nuevo'
    ];
}
