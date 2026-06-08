<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

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
        $ConAcciones = 0;

        $FacturasConAcciones = DB::connection('sqlsrv')->select("SELECT FACTURA FROM PRODUCCION.dbo.LOG_ACCIONES_RIFA ");

        $query = " SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura WHERE FECHA BETWEEN ? AND ? ";

        $rows = DB::connection('sqlsrv')->select($query, [$desde, $hasta]);

        $Arry = [];
        
        foreach ($rows as $item) {
            $TieneAccion = array_search($item->FACTURA, array_column($FacturasConAcciones, 'FACTURA'));
            $isAccion = ($TieneAccion !== false) ? 'S' : 'N';

            if ($isAccion == 'N') {
                $SinAcciones++;
            }

            if ($isAccion == 'S') {
                $ConAcciones = $ConAcciones + $item->ACCIONES;
            }

            $Arry[] = [
                "FACTURA"           => $item->FACTURA,
                "FECHA"             => date('Y-m-d H:i:s', strtotime($item->FECHA)),
                "CLIENTE"           => $item->CLIENTE,
                "NOMBRE"            => $item->NOMBRE,                
                "TOTAL_FACTURA"     => $item->TOTAL_FACTURA,
                "VENDEDOR"          => $item->VENDEDOR . ' - ' . $item->NOMBRE_VENDEDOR,
                "ACCIONES"          => $item->ACCIONES,
                "IsAccion"          => $isAccion
            ];
        }

        $clientesUnicos = array_unique(array_column($Arry, 'CLIENTE'));

        $ultima = DB::connection('sqlsrv')->selectOne("SELECT NUMERO FROM PRODUCCION.dbo.NUMEROS_RIFA WHERE USADO = 1 ORDER BY ID_ASIGNACION DESC");
        $ttAccionesUsados = DB::connection('sqlsrv')->selectOne("SELECT COUNT(*) AS TOTAL FROM PRODUCCION.dbo.NUMEROS_RIFA WHERE USADO = 1");
        $UltmAccion = $ultima ? (int) $ultima->NUMERO : 0;
        $ttAcciones = 60000;

        $porcentajeDisponible = $UltmAccion ? round((($ttAcciones - $ttAccionesUsados->TOTAL) / $ttAcciones) * 100, 1) : 100;

        return $Arry = [
            "DATA" => $Arry,
            'TOTAL_FACTURADO'       => array_sum(array_column($Arry, 'TOTAL_FACTURA')),
            "TOTAL_FACTURAS"        => count($Arry),
            "TOTAL_ACCIONES"        => array_sum(array_column($Arry, 'ACCIONES')),
            "TOTAL_CLIENTES"        => count($clientesUnicos),
            "ULTIMA_ACCION"         => $UltmAccion,
            "TOTAL_SIN_ACCIONES"    => $SinAcciones,
            "TOTAL_ACCIONES_ASIG"   => $ConAcciones,
            "PORCENTAJE_DISPONIBLE" => $porcentajeDisponible
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
        $VENDEDOR       = $InfoFactura->VENDEDOR;

        $query = "
            EXEC PRODUCCION.dbo.SP_INSERTAR_ACCIONES_CONTINUO 
            @CLIENTE = ?, 
            @FACTURA = ?, 
            @TOTAL_FACTURA = ?,
            @VENDEDOR = ?
        ";

        DB::connection('sqlsrv')->statement($query, [
            $CLIENTE,
            $Factura,
            $TOTAL_FACTURA,
            $VENDEDOR
        ]);

        return [
            'status' => true,
            'message' => 'Acciones asignadas correctamente'
        ];
    }

    public static function RevertirAcciones($request)
    {
        $Factura = $request->Factura;
        $Justificacion = $request->Motivo;

        $info = DB::connection('sqlsrv')->selectOne(
            "SELECT CLIENTE, TOTAL_FACTURA FROM Softland.umk.FACTURA WHERE FACTURA = ?",
            [$Factura]
        );

        $cliente = $info->CLIENTE ?? null;
        $totalFactura = $info->TOTAL_FACTURA ?? 0;
        $acciones = floor($totalFactura / 1000);

        DB::connection('sqlsrv')->statement(
            "EXEC PRODUCCION.dbo.SP_REVERSAR_ACCIONES_RIFA @FACTURA = ?",
            [$Factura]
        );

        DB::connection('sqlsrv')->insert(
            "INSERT INTO PRODUCCION.dbo.LOG_REVERSIONES_RIFA (FACTURA, CLIENTE, TOTAL_FACTURA, ACCIONES, JUSTIFICACION, USUARIO_REVERSO) VALUES (?, ?, ?, ?, ?, ?)",
            [$Factura, $cliente, $totalFactura, $acciones, $Justificacion, Auth::user()->id ?? 1]
        );

        return [
            'status' => true,
            'message' => 'Acciones revertidas correctamente'
        ];
    }

    public static function ImprimirAcciones($request)
    {
        $Factura = $request->Factura;

        $InfoFactura = FacturasAcciones::where('FACTURA', $Factura)->get();
        

        return $InfoFactura;
    }

    public static function getInfoFactura($Factura)
    {
        $query = " SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura WHERE FT0.FACTURA = ? ";

        $Factura = DB::connection('sqlsrv')->select($query, [$Factura]);

        return $Factura ? $Factura[0] : null;
    }

}
