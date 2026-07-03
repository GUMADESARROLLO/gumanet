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
                COUNT(DISTINCT T0.PEDIDO) AS CANTIDAD_PEDIDOS
            FROM PRODUCCION.dbo.view_master_pedidos_umk_v2 T0
            LEFT JOIN PRODUCCION.dbo.vtVS2_Vendedores V ON T0.VENDEDOR = V.VENDEDOR
            WHERE T0.FECHA_PEDIDO BETWEEN ? AND ?
            AND T0.VENDEDOR != 'F12'
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
                F.PEDIDO,
                F.FACTURA,
                F.CLIENTE AS COD_CLIENTE,
                F.NOMBRE_CLIENTE,
                P.CreateDate AS FECHAC_PEDIDO,
                F.CreateDate AS FECHA_FACTURA,
                DATEDIFF(MINUTE, P.CreateDate, F.CreateDate) AS TIEMPO_MINUTOS,
                F.TOTAL_FACTURA
            FROM Softland.umk.FACTURA F
            INNER JOIN Softland.umk.PEDIDO P ON F.PEDIDO = P.PEDIDO
            WHERE P.FECHA_PEDIDO BETWEEN ? AND ?
            AND F.VENDEDOR != 'F12'
            ORDER BY F.PEDIDO, F.FACTURA
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
                P.CreateDate AS FECHAC_PEDIDO,
                F.CreateDate AS FECHA_FACTURA,
                F.TOTAL_FACTURA,
                DATEDIFF(MINUTE, P.CreateDate, F.CreateDate) AS TIEMPO_MINUTOS
            FROM Softland.umk.FACTURA F
            INNER JOIN Softland.umk.PEDIDO P ON F.PEDIDO = P.PEDIDO
            WHERE F.PEDIDO = ?
            AND F.VENDEDOR != 'F12'
        ", [$pedido]);

        return $query;
    }

    public static function getDetallePedidoProductos($pedido)
    {
        $query = DB::connection("sqlsrv")->select("
            SELECT
                PL.ARTICULO,
                UPPER(ISNULL(A.DESCRIPCION, '')) AS DESCRIPCION,
                PL.CANTIDAD_PEDIDA AS CANTIDAD,
                PL.PRECIO_UNITARIO,
                (PL.CANTIDAD_PEDIDA * PL.PRECIO_UNITARIO) AS PRECIO_TOTAL
            FROM Softland.umk.PEDIDO_LINEA PL
            LEFT JOIN Softland.umk.ARTICULO A ON PL.ARTICULO = A.ARTICULO
            WHERE PL.PEDIDO = ?
            ORDER BY PL.ARTICULO
        ", [$pedido]);

        return $query;
    }

    public static function getDetalleFacturaProductos($factura)
    {
        $query = DB::connection("sqlsrv")->select("
            SELECT
                FL.ARTICULO,
                UPPER(ISNULL(A.DESCRIPCION, '')) AS DESCRIPCION,
                FL.CANTIDAD,
                FL.PRECIO_UNITARIO,
                FL.PRECIO_TOTAL
            FROM Softland.umk.FACTURA_LINEA FL
            LEFT JOIN Softland.umk.ARTICULO A ON FL.ARTICULO = A.ARTICULO
            WHERE FL.FACTURA = ?
            ORDER BY FL.ARTICULO
        ", [$factura]);

        return $query;
    }

    public static function getRucCliente($cliente)
    {
        $query = DB::connection("sqlsrv")->select("
            SELECT RUC
            FROM PRODUCCION.dbo.GMV_Clientes
            WHERE CLIENTE = ?
        ", [$cliente]);

        if (count($query) > 0) {
            return $query[0]->RUC ?? '';
        }
        return '';
    }

    public static function getFacturasByVendedor($request, $vendedor)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;

        $query = DB::connection("sqlsrv")->select("
            SELECT
                F.FACTURA,
                F.FECHA,
                F.CLIENTE AS COD_CLIENTE,
                F.NOMBRE_CLIENTE,
                F.TOTAL_FACTURA
            FROM Softland.umk.FACTURA F
            WHERE F.VENDEDOR = ?
            AND CAST(F.FECHA AS DATE) BETWEEN ? AND ?
            AND F.VENDEDOR != 'F12'
            ORDER BY F.FECHA DESC, F.FACTURA DESC
        ", [$vendedor, $desde, $hasta]);

        return $query;
    }
}
