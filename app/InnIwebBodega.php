<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Existencias por bodega de los articulos de Innova.
 * VIEW de SQL Server (solo lectura), sin llave primaria propia.
 */
class InnIwebBodega extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PRODUCCION.dbo.INN_iweb_bodegas';

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
            ->orderBy('BODEGA')
            ->get(['BODEGA', 'NOMBRE', 'CANT_DISPONIBLE']);
    }
}
