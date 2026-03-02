<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Budget extends Model
{

    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_gmv_master_articulos";

    public static function GRUPO9010($ini, $end, $grupo){
        return self::query()
        ->selectRaw('
            T2.Ruta,
            T3.NOMBRE AS VENDEDOR,
            T2.ARTICULO,
            T2.DESCRIPCION,
            T2.[Cod. Cliente] AS CODIGO,
            T2.[Nombre del Cliente] AS CLIENTE,
            SUM(T2.CANTIDAD) AS CANTIDAD,
            SUM(T2.VENTA_NETA) AS VENTA,
            T1.GRUPOS
        ')
        ->join('Softland.dbo.VtasTotal_UMK as T2', 'T1.ARTICULO', '=', 'T2.ARTICULO')
        ->join('PRODUCCION.dbo.UMK_VENDEDOR as T3', 'T2.Ruta', '=', 'T3.VENDEDOR')
        ->whereBetween('T2.Dia', [$ini, $end])
        ->whereRaw('( ? = \'TODO\' OR T1.GRUPOS = ? )', [$grupo, $grupo])
        ->groupBy(
            'T2.Ruta',
            'T3.NOMBRE',
            'T2.ARTICULO',
            'T2.DESCRIPCION',
            'T2.[Cod. Cliente]',
            'T2.[Nombre del Cliente]',
            'T1.GRUPOS'
        )
        ->get();
    }
    

    public static function dtProyectClientesFact($request) {        
        
        $startDate  = $request->input('desde');
        $endDate    = $request->input('hasta');
        $clientes   = [];
        $vendedoresHoy = [];
        $vendedores = [];
        $SKU_CHART  = [];
        $CLS_CHART  = [];
        $factEsencial = 0;
        $factExpansion = 0;
        $factTotal  = 0;
        $grupo      = $request->input('grupo');
        
        $resultadosHoy = DB::connection('sqlsrv')->select("SELECT * FROM PRODUCCION.dbo.fn_proyecto_90_10(?, ?, ?) ORDER BY VENTA DESC",[$endDate, $endDate, $grupo]);
        
        $clientesTmp = [];

        foreach ($resultadosHoy as $row) {

            $codigo = $row->CODIGO;

            if (!isset($clientesTmp[$codigo])) {
                $clientesTmp[$codigo] = [
                    'CODIGO'   => $codigo,
                    'NOMBRE'   => $row->CLIENTE,
                    'CANTIDAD' => 0,
                    'VENTA'    => 0,
                ];
            }

            $clientesTmp[$codigo]['CANTIDAD'] += $row->CANTIDAD;
            $clientesTmp[$codigo]['VENTA']    += $row->VENTA;
        }

        foreach ($clientesTmp as $item) {
            $clientes[] = [
                'CODIGO'   => $item['CODIGO'],
                'NOMBRE'   => $item['NOMBRE'],
                'CANTIDAD' => round($item['CANTIDAD'], 2),
                'VENTA'    => round($item['VENTA'], 2),
            ];
        }

        $vendedorTemp = [];
        foreach ($resultadosHoy as $row) {

            $key = $row->Ruta . '|' . $row->VENDEDOR;

            if (!isset($vendedorTemp[$key])) {
                $vendedorTemp[$key] = [
                    'CODIGO'   => $row->Ruta,
                    'NOMBRE'   => $row->VENDEDOR,
                    'CANTIDAD' => 0,
                    'VENTA'    => 0,
                ];
            }

            $vendedorTemp[$key]['CANTIDAD'] += $row->CANTIDAD;
            $vendedorTemp[$key]['VENTA']    += $row->VENTA;
        }

        foreach ($vendedorTemp as $item) {
            $vendedoresHoy[] = [
                'CODIGO'   => $item['CODIGO'],
                'NOMBRE'   => $item['NOMBRE'],
                'CANTIDAD' => round($item['CANTIDAD'], 2),
                'VENTA'    => round($item['VENTA'], 2),
            ];
        }


        $resultados = DB::connection('sqlsrv')->select("SELECT * FROM PRODUCCION.dbo.fn_proyecto_90_10(?, ?, ?) ORDER BY VENTA DESC",[$startDate, $endDate, $grupo]);
        $vendedorTemp = [];
        foreach ($resultados as $row) {

            $key = $row->Ruta . '|' . $row->VENDEDOR;

            if (!isset($vendedorTemp[$key])) {
                $vendedorTemp[$key] = [
                    'CODIGO'   => $row->Ruta,
                    'NOMBRE'   => $row->VENDEDOR,
                    'CANTIDAD' => 0,
                    'VENTA'    => 0,
                ];
            }

            $vendedorTemp[$key]['CANTIDAD'] += $row->CANTIDAD;
            $vendedorTemp[$key]['VENTA']    += $row->VENTA;
        }

        foreach ($vendedorTemp as $item) {
            $vendedores[] = [
                'CODIGO'   => $item['CODIGO'],
                'NOMBRE'   => $item['NOMBRE'],
                'CANTIDAD' => round($item['CANTIDAD'], 2),
                'VENTA'    => round($item['VENTA'], 2),
            ];
        }

        $skuTemp = [];

        foreach ($resultados as $row) {

            $sku = $row->ARTICULO;

            if (!isset($skuTemp[$sku])) {
                $skuTemp[$sku] = [
                    'SKU'         => $sku,
                    'DESCRIPCION' => $row->DESCRIPCION,
                    'CANTIDAD'    => 0,
                    'VENTA'       => 0,
                    'PESO'        => 0,
                ];
            }

            $skuTemp[$sku]['CANTIDAD'] += $row->CANTIDAD;
            $skuTemp[$sku]['VENTA']    += $row->VENTA;
            $skuTemp[$sku]['PESO']     += $row->PESO;
        }

        foreach ($skuTemp as $item) {
            $SKU_CHART[] = [
                'SKU'         => $item['SKU'],
                'DESCRIPCION' => $item['DESCRIPCION'],
                'CANTIDAD'    => round($item['CANTIDAD'], 2),
                'VENTA'       => round($item['VENTA'], 2),
                'PESO'        => round($item['PESO'], 2),
            ];
        }

        $clientesTmp = [];

        foreach ($resultados as $row) {

            $codigo = $row->CODIGO;

            if (!isset($clientesTmp[$codigo])) {
                $clientesTmp[$codigo] = [
                    'CODIGO'   => $codigo,
                    'NOMBRE'   => $row->CLIENTE,
                    'CANTIDAD' => 0,
                    'VENTA'    => 0,
                ];
            }

            $clientesTmp[$codigo]['CANTIDAD'] += $row->CANTIDAD;
            $clientesTmp[$codigo]['VENTA']    += $row->VENTA;
        }

        
        foreach ($clientesTmp as $item) {
            $CLS_CHART[] = [
                'CODIGO'   => $item['CODIGO'],
                'NOMBRE'   => $item['NOMBRE'],
                'CANTIDAD' => number_format($item['CANTIDAD'], 2),
                'VENTA'    => number_format($item['VENTA'], 2),
            ];
        }

        $result = DB::connection('sqlsrv')->select("SELECT T1.ARTICULO, T2.DESCRIPCION, T1.GRUPOS FROM PRODUCCION.dbo.tbl_gmv_master_articulos T1 JOIN PRODUCCION.dbo.iweb_articulos T2 ON T1.ARTICULO = T2.ARTICULO WHERE T1.VENDEDOR = 'F05' GROUP BY T1.ARTICULO, T2.DESCRIPCION, T1.GRUPOS");

        $esenacial = array_filter($resultados, fn($row) => $row->GRUPOS === 'A');
        $expansion = array_filter($resultados, fn($row) => $row->GRUPOS === 'B');

        $factEsencial = array_sum(array_column($esenacial, 'VENTA'));
        $factExpansion = array_sum(array_column($expansion, 'VENTA'));
        $factTotal = array_sum(array_column($resultados,'VENTA'));

        $metricas = [
            'CLIENTES'  => $clientes,
            'VENDEDORESHOY' => $vendedoresHoy,
            'VENDEDORES'=> $vendedores,
            'SKU_CHART' => $SKU_CHART,
            'CLS_CHART' => $CLS_CHART,
            'GRUPOS'    => $result,
            'DESDE'     => $startDate,
            'HASTA'     => $endDate,
            'FACTESEN'  => number_format($factEsencial,2),
            'FACTEXPA'  => number_format($factExpansion,2),
            'FACTTOTA'  => number_format($factTotal,2)
        ];
        return $metricas;
    }

    public static function getFacturasClientesUmk($request){
        $ini    = $request->desde;
        $end    = $request->hasta;   
        $CLI    = $request->CLIENTE;   

        //$respuesta = Budget::GRUPO9010('2026-02-01','2026-02-26','04856');
        
        $result = DB::connection('sqlsrv')->select(
                "
                SELECT
                    FACTURA,
                    Dia,
                    SUM(CANTIDAD)   AS CANTIDAD,
                    SUM(VENTA_NETA) AS VENTA
                FROM Softland.dbo.VtasTotal_UMK
                WHERE [Cod. Cliente] = ?
                AND Dia BETWEEN ? AND ?
                GROUP BY
                    FACTURA,
                    Dia
                ",
                [$CLI, $ini, $end]
            );
        return $result;
    }

    public static function dtArticulo($request) {        
        
        $startDate  = $request->input('f1');
        $endDate    = $request->input('f2');
        $ARTICULO   = $request->input('ARTICULO');
        $Pro        = $request->input('Pro');
        $tipo       = $request->input('tipo');

        $datosFormateados = [];

     

        if ($Pro === "1") {
            if($tipo == "1"){
                $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_articulos ?, ?, ?', [$startDate, $endDate,$ARTICULO]);
            } else {
                $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_articulos_monto ?, ?, ?', [$startDate, $endDate,$ARTICULO]);
            }
        } else {
            if($tipo === "1"){
                $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_articulos_71 ?, ?, ?', [$startDate, $endDate,$ARTICULO]);
            }else{
                $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_articulos_monto_71 ?, ?, ?', [$startDate, $endDate,$ARTICULO]);
            }
        }




        foreach ($resultados as $resultado) {
            $fila = [                
                'ARTICULO' => $resultado->ARTICULO,
                'FECHA' => [], 
                'UND_MES' => $resultado->META,
            ];

    
        $columnas_agregadas = [];
        foreach ($resultado as $columna => $valor) {
            if ($columna !== 'ARTICULO' && $columna !== 'META' ) {                
                $fila[$columna] = $valor;
                $fila['FECHA'][] = [
                    'mes' => $columna,
                ];
              
            }
        }
        

        $datosFormateados[] = $fila;
    }
        return $datosFormateados;
    }



    
}
