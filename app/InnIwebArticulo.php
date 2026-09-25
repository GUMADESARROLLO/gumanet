<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Articulos de Innova expuestos por la vista PRODUCCION.dbo.INN_GMV_mstr_articulos.
 * Es una VIEW de SQL Server (solo lectura), por eso no hay timestamps ni escrituras.
 */
class InnIwebArticulo extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PRODUCCION.dbo.INN_GMV_mstr_articulos';

    protected $primaryKey = 'ARTICULO';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    /** Prefijo que identifica los articulos de Innova dentro de la vista. */
    const PREFIJO = '6in%';

    public function scopeInnova($query)
    {
        return $query->where('ARTICULO', 'like', self::PREFIJO);
    }

    /**
     * Listado para la tabla de inventario Innova.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getArticulos()
    {
        // 'total' es la existencia en unidad de almacén; se expone con un nombre
        // legible para la tabla. Coincide 1:1 con INN_GMV_mstr_articulos.EXISTENCIA.
        return self::innova()
            ->orderBy('ARTICULO')
            ->get(['ARTICULO', 'DESCRIPCION', 'EXISTENCIA']);
    }

    /**
     * Ficha minima del articulo: alimenta el titulo del modal de detalle y
     * sirve de validacion de existencia para responder 404.
     *
     * @param  string $articulo
     * @return static|null
     */
    public static function getFicha($articulo)
    {
        return self::innova()
            ->where('ARTICULO', $articulo)
            ->first(['ARTICULO', 'DESCRIPCION']);
    }
}
