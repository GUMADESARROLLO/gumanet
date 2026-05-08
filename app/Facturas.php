<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Facturas extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.FACTURA";

    public static function Filtrar($request)
    {
        $desde = $request->desde . ' 00:00:00';
        $hasta = $request->hasta . ' 23:59:59';

        $SinAcciones = 0;

        $FacturasConAcciones = DB::connection('sqlsrv')->select("SELECT FACTURA FROM PRODUCCION.dbo.LOG_ACCIONES_RIFA ");

        $query = "
            SELECT
                T0.CLIENTE,
                T1.NOMBRE,
                T0.FACTURA,
                T0.TOTAL_FACTURA,
                FLOOR(T0.TOTAL_FACTURA / 1000) AS ACCIONES,
                T0.FECHA,
                T0.VENDEDOR,
                T0.NIVEL_PRECIO
            FROM
                Softland.umk.FACTURA T0
            INNER JOIN Softland.umk.CLIENTE T1 ON T0.CLIENTE = T1.CLIENTE
            WHERE
                T0.ANULADA = 'N'
                AND T0.VENDEDOR NOT IN ('F01','F12')
                AND T0.TOTAL_FACTURA > 1000
                AND T0.CLIENTE NOT IN ( SELECT CLIENTE FROM PRODUCCION.dbo.tbl_cadena_de_farmacia)
                AND T0.FECHA BETWEEN ? AND ?
        ";

        $rows = DB::connection('sqlsrv')->select($query, [$desde, $hasta]);

        $Arry = [];
        
        foreach ($rows as $item) {
            $TieneAccion = array_search($item->FACTURA, array_column($FacturasConAcciones, 'FACTURA'));
            $isAccion = ($TieneAccion !== false) ? 'S' : 'N';

            if ($isAccion == 'N') {
                $SinAcciones++;
            }

            $Arry[] = [
                "FACTURA"           => $item->FACTURA,
                "FECHA"             => date('Y-m-d H:i:s', strtotime($item->FECHA)),
                "CLIENTE"           => $item->CLIENTE,
                "NOMBRE"            => $item->NOMBRE,                
                "TOTAL_FACTURA"     => $item->TOTAL_FACTURA,
                "VENDEDOR"          => $item->VENDEDOR,
                "ACCIONES"          => $item->ACCIONES,
                "IsAccion"          => $isAccion
            ];
        }

        $clientesUnicos = array_unique(array_column($Arry, 'CLIENTE'));

        $UltmAccion = DB::connection('sqlsrv')->select("SELECT NUMERO FROM PRODUCCION.dbo.NUMEROS_RIFA WHERE USADO = 1 ORDER BY NUMERO DESC")[0]->NUMERO ?? 'N/A';

        return $Arry = [
            "DATA" => $Arry,
            'TOTAL_FACTURADO'       => array_sum(array_column($Arry, 'TOTAL_FACTURA')),
            "TOTAL_FACTURAS"        => count($Arry),
            "TOTAL_ACCIONES"        => array_sum(array_column($Arry, 'ACCIONES')),
            "TOTAL_CLIENTES"        => count($clientesUnicos),
            "ULTIMA_ACCION"         => $UltmAccion,
            "TOTAL_SIN_ACCIONES"    => $SinAcciones,
        ];
    }
    public static function Acciones($request)
    {
        $Factura = $request->Factura;
    
        $query = "SELECT T0.NUMERO, T0.USADO, T0.CLIENTE, T0.FACTURA, T0.FECHA_ASIGNACION, T0.PREMIADO, T0.FECHA_PREMIO FROM PRODUCCION.dbo.NUMEROS_RIFA T0 WHERE T0.FACTURA = ? ORDER BY T0.FECHA_ASIGNACION DESC";

        $rows = DB::connection('sqlsrv')->select($query, [$Factura]);

        $Arry = [];

        foreach ($rows as $item) {
            $Arry[] = [
                "ACCION"            => $item->NUMERO,
                "FECHA"             => date('Y-m-d', strtotime($item->FECHA_ASIGNACION)),
                "CLIENTE"           => $item->CLIENTE,
                "FACTURA"           => $item->FACTURA,      
            ];
        }

        return $Arry;
    }


    public static function AsignarAcciones($request)
    {
        $Factura = $request->Factura;

        $InfoFactura = Facturas::where('FACTURA', $Factura)->first();

        if (!$InfoFactura) {
            throw new \Exception("Factura no encontrada");
        }

        $CLIENTE        = $InfoFactura->CLIENTE;
        $TOTAL_FACTURA  = $InfoFactura->TOTAL_FACTURA;

        $query = "
            EXEC PRODUCCION.dbo.SP_INSERTAR_ACCIONES_CONTINUO 
            @CLIENTE = ?, 
            @FACTURA = ?, 
            @TOTAL_FACTURA = ?
        ";

        DB::connection('sqlsrv')->statement($query, [
            $CLIENTE,
            $Factura,
            $TOTAL_FACTURA
        ]);

        return [
            'status' => true,
            'message' => 'Acciones asignadas correctamente'
        ];
    }
    public static function ImprimirAcciones($request)
    {
        $Factura = $request->Factura;
        //$Factura = '00293154';

        $InfoFactura = FacturasAcciones::where('FACTURA', $Factura)->get();

        return $InfoFactura;
    }

    public static function getInfoFactura($Factura)
    {
        $query = "
            SELECT
                T0.CLIENTE,
                T1.NOMBRE,
                T0.FACTURA,
                T0.TOTAL_FACTURA,
                FLOOR(T0.TOTAL_FACTURA / 1000) AS ACCIONES,
                T0.FECHA,
                T0.VENDEDOR,
                T0.NIVEL_PRECIO
            FROM
                Softland.umk.FACTURA T0
            INNER JOIN Softland.umk.CLIENTE T1 
                ON T0.CLIENTE = T1.CLIENTE
            WHERE
                T0.ANULADA = 'N'
                AND T0.VENDEDOR NOT IN ('F02','F12')
                AND T0.TOTAL_FACTURA > 1000
                AND T0.CLIENTE NOT IN (
                    SELECT CLIENTE FROM PRODUCCION.dbo.tbl_cadena_de_farmacia
                )
                AND T0.FACTURA = ?
        ";

        $Factura = DB::connection('sqlsrv')->select($query, [$Factura]);

        return $Factura ? $Factura[0] : null;
    }

}
