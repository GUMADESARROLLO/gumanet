<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_master_pedidos";

    public static function Filtrar($request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;

        $Arry = [];

        $query = self::selectRaw('nDAY, 
                SUM(CANTIDAD_PEDIDA) AS CANTIDAD,
                COUNT(DISTINCT PEDIDO) AS FACTURAS,
                SUM(TOTAL_LINEA) AS TOTAL')->whereBetween('FECHA_PEDIDO', [$desde, $hasta])
            ->groupBy('nDAY')
            ->orderBy('nDAY');

        foreach ($query->get() as $item) {
            $Arry[] = [
                'DIA' => 'Dia '.$item->nDAY,
                'CANTIDAD' => $item->CANTIDAD,
                'FACTURAS' => $item->FACTURAS,
                'TOTAL_LINEA' => $item->TOTAL
            ];
        }


        return $Arry;
    }
}
