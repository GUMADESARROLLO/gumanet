<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Budget extends Model
{

    public static function dtProyectClientesFact($request) {        
        
        $startDate  = $request->input('desde');
        $endDate    = $request->input('hasta');
        $clientes   = [];
        $vendedoresHoy = [];
        $vendedores = [];
        $SKU_CHART  = [];
        $CLS_CHART  = [];
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


        $metricas = [
            'CLIENTES'  => $clientes,
            'VENDEDORESHOY' => $vendedoresHoy,
            'VENDEDORES'=> $vendedores,
            'SKU_CHART' => $SKU_CHART,
            'CLS_CHART' => $CLS_CHART,
            'GRUPOS'    => $result,
            'DESDE'     => $startDate,
            'HASTA'     => $endDate
        ];
        return $metricas;
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
