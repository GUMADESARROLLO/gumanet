<?php

namespace App;

use App\user;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\exception_for;

class DashboardInnova extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_VtasTotal_Innova";

    public static function TransacionesBultosValor($desde, $hasta)
    {
        return self::query()
            ->selectRaw('SUM(Cantidad) as CANTIDAD, SUM(Venta) as VENTA_SIN_IVA, SUM(Venta) * 1.15 as VENTA_CON_IVA')
            ->whereBetween('FECHA_FACTURA', [$desde, $hasta])
            ->orderByDesc('CANTIDAD')
            ->get()->toArray()[0];
    }
    public static function TransacionesClientes($desde, $hasta)
    {
        return self::query()
            ->selectRaw('CLIENTE,NOMBRE, SUM(Cantidad) AS CANTIDAD, SUM(Venta) AS VENTA_SIN_IVA, SUM(Venta) * 1.15 AS VENTA_CON_IVA')
            ->whereBetween('FECHA_FACTURA', [$desde, $hasta])
            ->groupBy('CLIENTE', 'NOMBRE')
            ->orderByDesc('CANTIDAD')
            ->get();
    }
    public static function TransacionesVendedores($desde, $hasta)
    {
        return self::query()
            ->selectRaw('VENDEDOR,NOMBRE_VENDEDOR, SUM(Cantidad) AS CANTIDAD, SUM(Venta) AS VENTA_SIN_IVA, SUM(Venta) * 1.15 AS VENTA_CON_IVA')
            ->whereBetween('FECHA_FACTURA', [$desde, $hasta])
            ->groupBy('VENDEDOR', 'NOMBRE_VENDEDOR')
            ->orderByDesc('CANTIDAD')
            ->get();
    }
    public static function TransacionesArticulos($desde, $hasta)
    {
        return self::query()
            ->selectRaw('CLASIFICACION_SIMPLE, SUM(Cantidad) AS CANTIDAD, SUM(Venta) AS VENTA_SIN_IVA, SUM(Venta) * 1.15 AS VENTA_CON_IVA')
            ->whereBetween('FECHA_FACTURA', [$desde, $hasta])
            ->groupBy('CLASIFICACION_SIMPLE')
            ->orderByDesc('CANTIDAD');
    }

    public static function BultosComparativa( $nYearAnterior, $nYearActual)
    {
        return self::query()
            ->selectRaw('Anio, SUM(Cantidad) AS CANTIDAD,SUM(Venta) * 1.15 AS VENTA_CON_IVA')
            ->whereBetween('Anio', [$nYearAnterior, $nYearActual])
            ->groupBy('Anio')
            ->get()->toArray();
    }

    public static function BultosComparativaYTD($nYearAnterior, $nYearActual)
    {
        return self::query()
                ->selectRaw('Anio, nMes, SUM(Cantidad) AS CANTIDAD,SUM(Venta) * 1.15 AS VENTA_CON_IVA')
                ->whereBetween('Anio', [$nYearAnterior, $nYearActual])
                ->groupBy('Anio','nMes')
                ->orderBy('Anio', 'nMes')
                ->get()->toArray();
    }


    public static function getActuales($request)
    {
        $Metricas   = [];
        $Clientes   = [];
        $Vendedores = [];
        $SKU_CHART  = [];
        $CLS_CHART  = [];
        
        $desde      = $request->desde;
        $hasta      = $request->hasta;

        //$desde      = '2025-06-01';
        //$hasta      = '2025-06-30';

        //$Today       = date('Y-m-d');

        //DIA ACTUAL
        $Clientes   = DashboardInnova::TransacionesClientes($desde, $hasta);
        $Vendedores = DashboardInnova::TransacionesVendedores($desde, $hasta);

        // RANGO DE FECHA
        $Ventas     = DashboardInnova::TransacionesBultosValor($desde, $hasta);
        $Articulos  = DashboardInnova::TransacionesArticulos($desde, $hasta);

        $SumaVentas = array_sum(array_column($Articulos->get()->toArray(), 'VENTA_CON_IVA'));
        $SumaBultos = array_sum(array_column($Articulos->get()->toArray(), 'CANTIDAD'));

        // PARA QUE NO EXISTAN DIVICION ENTRE ZERO
        $SumaVentas = $SumaVentas ?? 0.002;
        $SumaBultos = $SumaBultos ?? 0.002;


        $ClientesHoy = DashboardInnova::TransacionesClientes($desde, $hasta);


        // ESTAS METRICAS SERAN AFECTADAS POR EL RANGO DE FECHA BUSCADO
        $Metricas = [
            'UpdateAt'                 => $hasta,
            'BULTOS_TOTAL_UND'         => number_format($Ventas['CANTIDAD'], 2),
            'BULTOS_TOTAL_NIO'         => number_format($Ventas['VENTA_CON_IVA'],2),       
        ];


        // ESTO MUESTRA AL DIA ACTUAL
        foreach ($Clientes as $key => $value) {
            $Clientes[$key] = [
                'CODIGO'            => $value->CLIENTE,
                'NOMBRE'            => $value->NOMBRE,
                'BULTOS_TOTAL_UND'  => number_format($value->CANTIDAD, 2),  
                'BULTOS_TOTAL_NIO'  => number_format($value->VENTA_CON_IVA, 2),
            ];
        }
        foreach ($Vendedores as $key => $value) {
            $Vendedores[$key] = [
                'CODIGO'            => $value->VENDEDOR,
                'NOMBRE'            => $value->NOMBRE_VENDEDOR,
                'BULTOS_TOTAL_UND'  => number_format($value->CANTIDAD, 2),  
                'BULTOS_TOTAL_NIO'  => number_format($value->VENTA_CON_IVA, 2),
            ];
        }

        //MUESTRA EL VALOR Y CANTIDADDES DE BULTOS ENTRE EL GANGO DE FECHA
        foreach ($Articulos->get() as $key => $value) {  

            $Peso = ($value->VENTA_CON_IVA / $SumaVentas ) * 100;

            $SKU_CHART[$key] = [
                'SKU'               => '',
                'DESCRIPCION'       => $value->CLASIFICACION_SIMPLE,
                'BULTOS_TOTAL_UND'  => round($value->CANTIDAD, 2), 
                'BULTOS_TOTAL_NIO'  => round($value->VENTA_CON_IVA, 2),
                'PESO'              => round($Peso, 2),
            ];
            
        }

        foreach ($ClientesHoy as $key => $value) {           
            $CLS_CHART[$key] = [
                'CODIGO'           => $value->CLIENTE,
                'NOMBRE'            => $value->NOMBRE,
                'BULTOS_TOTAL_UND'  => number_format($value->CANTIDAD, 2),  
                'BULTOS_TOTAL_NIO'  => number_format($value->VENTA_CON_IVA, 2),
            ];
        }

        $Metricas_Merge = [
            'Metricas' => $Metricas,
            'Clientes' => $Clientes,
            'Vendedores' => $Vendedores,
            'SKU_CHART' => [
                'data' => $SKU_CHART,
                'Totals' => 
                    ['Bultos' => number_format($SumaBultos, 2), 'Valor' => number_format($SumaVentas, 2)]
                
            ],
            'CLS_CHART' => $CLS_CHART,
            'DESDE'     => $desde,
            'HASTA'     => $hasta
        ];

        return $Metricas_Merge;
    }

    public static function getComparativas($request)
    {
        $UND_YTD = [];        
        $DATA_YTD = [];

        $nYearActual    = date('Y');
        $nYearAnterior  = date('Y' , strtotime('-1 year'));



        $Bultos_Comparativa = DashboardInnova::BultosComparativa( $nYearAnterior,$nYearActual );


        $KeyYearActual      = array_search($nYearActual, array_column($Bultos_Comparativa, 'Anio'));
        $KeyYearAnterior    = array_search($nYearAnterior, array_column($Bultos_Comparativa, 'Anio'));

        // SE RECUPERAR EL VALOR EN DINERO YA CON IVA
        $ValYearActual = $Bultos_Comparativa[$KeyYearActual]['VENTA_CON_IVA'] ?? 0;
        $ValYearAnterior = $Bultos_Comparativa[$KeyYearAnterior]['VENTA_CON_IVA'] ?? 0;

        // SE RECUPERAR EL VALOR EN UNIDADES
        $CantYearActual = $Bultos_Comparativa[$KeyYearActual]['CANTIDAD'] ?? 0;
        $CantYearAnterior = $Bultos_Comparativa[$KeyYearAnterior]['CANTIDAD'] ?? 0;




        $UND_YTD = [
            'BULTOS_UND_ANIO_ACTUAL'    => number_format($CantYearActual,2),  
            'BULTOS_UND_ANIO_ANTERIOR'  => number_format($CantYearAnterior,2),
            'BULTOS_VAL_ANIO_ACTUAL'    => number_format($ValYearActual,2),
            'BULTOS_VAL_ANIO_ANTERIOR'  => number_format($ValYearAnterior,2),
        ];

        // AQUI ESTE VALOR VA CAMBIAR EN BASE A SI MIRA BULTO O NIO
        $DATA_YTD = [
            'DATA_ANIO_ACTUAL'      => 0,
            'DATA_ANIO_ANTERIOR'    => 0,
            'DATA_CRECIMIENTO'      => 0,
        ];



        $Metricas_Merge = [
            'UND_YTD' => $UND_YTD,
            'DATA_YTD' => $DATA_YTD
        ];

        return $Metricas_Merge;
    }

    public static function getComparativasYTD($request)
    {
        $nYearActual    = date('Y');
        $nYearAnterior  = date('Y', strtotime('-1 year'));
        $valorAnterior  = 0;
        $valorActual    = 0;
        $bultoAnterior  = 0;
        $bultoActual    = 0; 
        try{

        $BultosMensual = DashboardInnova::BultosComparativaYTD($nYearAnterior, $nYearActual);

        //$SumaVentas = array_sum(array_column($BultosMensual->get()->toArray(), 'VENTA_CON_IVA'));
        //$SumaBultos = array_sum(array_column($BultosMensual->get()->toArray(), 'CANTIDAD'));

        //$SumaVentas = $SumaVentas ?? 0.002;
        //$SumaBultos = $SumaBultos ?? 0.002;

        $comparativaMensual = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $anioAnteriorData = array_filter($BultosMensual, function ($item) use ($mes, $nYearAnterior) {
                return $item['Anio'] == $nYearAnterior && $item['nMes'] == $mes;
            });

            $anioActualData = array_filter($BultosMensual, function ($item) use ($mes, $nYearActual) {
                return $item['Anio'] == $nYearActual && $item['nMes'] == $mes;
            });

            $anioAnteriorData = array_values($anioAnteriorData);
            $anioActualData = array_values($anioActualData);

            $ventaAnterior = isset($anioAnteriorData[0]) ? $anioAnteriorData[0]['VENTA_CON_IVA'] : 0;
            $ventaActual = isset($anioActualData[0]) ? $anioActualData[0]['VENTA_CON_IVA'] : 0;

            $cantAnterior = isset($anioAnteriorData[0]) ? $anioAnteriorData[0]['CANTIDAD'] : 0;
            $cantActual = isset($anioActualData[0]) ? $anioActualData[0]['CANTIDAD'] : 0;

            $valorAnterior = $valorAnterior + $ventaAnterior;
            $valorActual = $valorActual + $ventaActual;
            $bultoAnterior = $bultoAnterior + $cantAnterior;
            $bultoActual = $bultoActual + $cantActual;

            $comparativaMensual[] = [
                'Mes' => $mes,
                'Venta_Anterior' => round($ventaAnterior, 2),
                'Venta_Actual' => round($ventaActual, 2),
                'Cantidad_Anterior' => round($cantAnterior, 2),
                'Cantidad_Actual' => round($cantActual, 2),
            ];
        }

        $crecimientoValor = (($valorActual / $valorAnterior)-1) * 100;
        $crecimientoBulto = (($bultoActual / $bultoAnterior)-1) * 100;

        return [
            'COMPARATIVA_YTD'       => $comparativaMensual,
            'YTD_VALOR_ANTERIOR'    => $valorAnterior,
            'YTD_VALOR_ACTUAL'      => $valorActual,
            'YTD_VALOR_CRECIMIENTO' => $crecimientoValor,
            'YTD_UND_ANTERIOR'      => $bultoAnterior,
            'YTD_UND_ACTUAL'        => $bultoActual,
            'YTD_UND_CRECIMIENTO'   => $crecimientoBulto
        ];
        }catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
        }
    }


    
}