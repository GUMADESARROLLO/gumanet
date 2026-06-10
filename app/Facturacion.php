<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Facturacion extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_master_pedidos_umk_v2";

    public static function Filtrar($request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;

        $Arry = [];

        $query = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.count_pedidos ?, ?', [$desde, $hasta]);

        foreach ($query as $item) {
            $Arry[] = [
                'DIA' => 'Dia '.$item->DIA,
                'FACTURAS' => $item->FACTURADOS,
                'PEDIDOS' => $item->PEDIDOS
            ];
        }


        return $Arry;
    }

    public static function getVendedores($request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;

        $query = DB::connection("sqlsrv")->select("
            SELECT
                T0.VENDEDOR AS RUTA,
                ISNULL(V.NOMBRE, T0.VENDEDOR) AS NOMBRE,
                COUNT(*) AS CANTIDAD_PEDIDOS
            FROM PRODUCCION.dbo.view_master_pedidos_umk_v2 T0
            LEFT JOIN PRODUCCION.dbo.vtVS2_Vendedores V ON T0.VENDEDOR = V.VENDEDOR
            WHERE T0.FECHA_PEDIDO BETWEEN ? AND ?
            GROUP BY T0.VENDEDOR, V.NOMBRE
            ORDER BY CANTIDAD_PEDIDOS DESC
        ", [$desde, $hasta]);

        return $query;
    }

    public static function getPedidosFacturados($request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;

        $query = DB::connection("sqlsrv")->select("
            SELECT
                PEDIDO,
                FECHA_PEDIDO AS FECHAC_PEDIDO,
                SUM(TOTAL_LINEA) AS TOTAL_A_FACTURAR
            FROM PRODUCCION.dbo.view_master_pedidos_umk_v2
            WHERE FECHA_PEDIDO BETWEEN ? AND ?
            GROUP BY PEDIDO, FECHA_PEDIDO
            ORDER BY PEDIDO
        ", [$desde, $hasta]);

        return $query;
    }

    public static function getDetallePedidoFactura($pedido)
    {
        $query = DB::connection("sqlsrv")->select("
            SELECT
                F.CLIENTE,
                F.NOMBRE_CLIENTE,
                F.PEDIDO,
                F.FACTURA,
                F.TOTAL_FACTURA,
                DATEDIFF(MINUTE, P.CreateDate, F.CreateDate) AS TIEMPO_MINUTOS
            FROM Softland.umk.FACTURA F
            INNER JOIN Softland.umk.PEDIDO P ON F.PEDIDO = P.PEDIDO
            WHERE F.PEDIDO = ?
        ", [$pedido]);

        return $query;
    }
}
