<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Precios por nivel de los articulos de Innova.
 * VIEW de SQL Server (solo lectura), sin llave primaria propia.
 */
class InnIwebPrecio extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PRODUCCION.dbo.INN_iweb_precio';

    protected $primaryKey = 'ARTICULO';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param  string $articulo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPorArticulo($articulo)
    {
        return self::where('ARTICULO', $articulo)
            ->orderBy('NIVEL_PRECIO')
            ->get(['NIVEL_PRECIO', 'PRECIO', 'VERSION']);
    }
}
