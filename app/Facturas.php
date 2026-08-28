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

        $query = " SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura T0 WHERE T0.FECHA BETWEEN ? AND ? ";
        
        $qFactPendientesAnular = self::getFacturasAnuladasPendientes();
        $qFactVencidas = self::getFacturasVencidas();

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
            "PORCENTAJE_DISPONIBLE" => $porcentajeDisponible,
            "FACT_ANULADAS_PEND"    => count($qFactPendientesAnular),
            "FACT_VENCIDAS_PEND"    => count($qFactVencidas)
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

        $accionesExistentes = DB::connection('sqlsrv')->select(
            "SELECT COUNT(*) AS total
            FROM PRODUCCION.dbo.NUMEROS_RIFA
            WHERE FACTURA = ?",
            [$Factura]
        );

        $accionesRevertidas = (int) $accionesExistentes[0]->total;

        if ($accionesRevertidas > 0) {
            DB::connection('sqlsrv')->statement(
                "EXEC PRODUCCION.dbo.SP_REVERSAR_ACCIONES_RIFA @FACTURA = ?",
                [$Factura]
            );
        }

        DB::connection('sqlsrv')->insert(
            "INSERT INTO PRODUCCION.dbo.LOG_REVERSIONES_RIFA (FACTURA, CLIENTE, TOTAL_FACTURA, ACCIONES, JUSTIFICACION, USUARIO_REVERSO) VALUES (?, ?, ?, ?, ?, ?)",
            [$Factura, $cliente, $totalFactura, $accionesRevertidas, $Justificacion, Auth::user()->id ?? 1]
        );

        return [
            'status' => true,
            'message' => $accionesRevertidas > 0
                ? 'Acciones revertidas correctamente'
                : 'Factura anulada sin acciones que revertir'
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
        $query = " SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura T0 WHERE T0.FACTURA = ? ";

        $Factura = DB::connection('sqlsrv')->select($query, [$Factura]);

        return $Factura ? $Factura[0] : null;
    }

    public static function VerificarFacturaAnulada($Factura)
    {
        $revertida = DB::connection('sqlsrv')->selectOne(
            "SELECT FACTURA FROM PRODUCCION.dbo.LOG_REVERSIONES_RIFA WHERE FACTURA = ?",
            [$Factura]
        );

        if ($revertida) {
            return [
                'status' => false,
                'message' => 'Esta factura ya fue revertida anteriormente'
            ];
        }

        $factura = DB::connection('sqlsrv')->selectOne(
            "SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura_anuladas WHERE FACTURA = ?",
            [$Factura]
        );

        if (!$factura) {
            return [
                'status' => false,
                'message' => 'Factura no encontrada en el sistema'
            ];
        }

        if ($factura->ANULADA !== 'S') {
            return [
                'status' => false,
                'message' => 'Esta factura no está anulada'
            ];
        }

        $acciones = DB::connection('sqlsrv')->select(
            "SELECT * FROM PRODUCCION.dbo.NUMEROS_RIFA WHERE FACTURA = ?",
            [$Factura]
        );

        $accionesAsignadas = count($acciones);

        return [
            'status' => true,
            'data' => [
                'FACTURA'           => $factura->FACTURA,
                'CLIENTE'           => $factura->CLIENTE,
                'NOMBRE'            => $factura->NOMBRE,
                'TOTAL_FACTURA'     => $factura->TOTAL_FACTURA,
                'ACCIONES'          => $factura->ACCIONES,
                'ACCIONES_ASIGNADAS'=> $accionesAsignadas,
                'FECHA'             => $factura->FECHA,
                'VENDEDOR'          => $factura->VENDEDOR,
                'NOMBRE_VENDEDOR'   => $factura->NOMBRE_VENDEDOR,
                'ANULADA'           => $factura->ANULADA
            ]
        ];
    }

    public static function VerificarFacturaVencida($Factura)
    {
        $revertida = DB::connection('sqlsrv')->selectOne(
            "SELECT FACTURA FROM PRODUCCION.dbo.LOG_REVERSIONES_RIFA WHERE FACTURA = ?",
            [$Factura]
        );

        if ($revertida) {
            return [
                'status' => false,
                'message' => 'Esta factura ya fue revertida anteriormente'
            ];
        }

        $factura = DB::connection('sqlsrv')->selectOne(
            "SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura_vencidas WHERE FACTURA = ?",
            [$Factura]
        );

        if (!$factura) {
            return [
                'status' => false,
                'message' => 'Factura no encontrada en el sistema'
            ];
        }

        $acciones = DB::connection('sqlsrv')->select(
            "SELECT * FROM PRODUCCION.dbo.NUMEROS_RIFA WHERE FACTURA = ?",
            [$Factura]
        );

        $accionesAsignadas = count($acciones);

        return [
            'status' => true,
            'data' => [
                'FACTURA'           => $factura->FACTURA,
                'CLIENTE'           => $factura->CLIENTE,
                'NOMBRE'            => $factura->NOMBRE,
                'TOTAL_FACTURA'     => $factura->TOTAL_FACTURA,
                'ACCIONES'          => $factura->ACCIONES,
                'ACCIONES_ASIGNADAS'=> $accionesAsignadas,
                'FECHA'             => $factura->FECHA,
                'VENDEDOR'          => $factura->VENDEDOR,
                'NOMBRE_VENDEDOR'   => $factura->NOMBRE_VENDEDOR,
                'DVENCIDOS'         => $factura->DVencidos
            ]
        ];
    }

    public static function getFacturasAnuladasPendientes()
    {
        $query = " SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura_anuladas T0 ORDER BY T0.FECHA DESC ";

        $rows = DB::connection('sqlsrv')->select($query);

        $Arry = [];

        foreach ($rows as $item) {
            $Arry[] = [
                'FACTURA'       => $item->FACTURA,
                'NOMBRE'        => $item->NOMBRE,
                'TOTAL_FACTURA' => $item->TOTAL_FACTURA,
                'ACCIONES'      => $item->ACCIONES,
                'FECHA'         => date('Y-m-d H:i:s', strtotime($item->FECHA))
            ];
        }

        return $Arry;
    }

    public static function getFacturasVencidas()
    {
        $query = " SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_masterFactura_vencidas T0 ORDER BY T0.FECHA DESC ";

        $rows = DB::connection('sqlsrv')->select($query);

        $Arry = [];

        foreach ($rows as $item) {
            $Arry[] = [
                'FACTURA'       => $item->FACTURA,
                'NOMBRE'        => $item->NOMBRE,
                'TOTAL_FACTURA' => $item->TOTAL_FACTURA,
                'ACCIONES'      => $item->ACCIONES,
                'FECHA'         => date('Y-m-d H:i:s', strtotime($item->FECHA)),
                'DVENCIDOS'     => $item->DVencidos
            ];
        }

        return $Arry;
    }

}
