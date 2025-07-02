<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;
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
            ->selectRaw('ARTICULO,DESCRIPCION, SUM(Cantidad) AS CANTIDAD, SUM(Venta) AS VENTA_SIN_IVA, SUM(Venta) * 1.15 AS VENTA_CON_IVA')
            ->whereBetween('FECHA_FACTURA', [$desde, $hasta])
            ->groupBy('ARTICULO', 'DESCRIPCION')
            ->orderByDesc('CANTIDAD')
            ->get();
    }

    public static function BultosComparativa( $nYearAnterior, $nYearActual)
    {
        return self::query()
            ->selectRaw('Anio,SUM(Cantidad) AS CANTIDAD,SUM(Venta) * 1.15 AS VENTA_CON_IVA')
            ->whereBetween('Anio', [$nYearAnterior, $nYearActual])
            ->groupBy('Anio')
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
        foreach ($Articulos as $key => $value) {        
            $SKU_CHART[$key] = [
                'SKU'               => $value->ARTICULO,
                'BULTOS_TOTAL_UND'  => number_format($value->CANTIDAD, 2), 
                'BULTOS_TOTAL_NIO'  => number_format($value->VENTA_CON_IVA, 2),
            ];
        }

        foreach ($ClientesHoy as $key => $value) {           
            $CLS_CHART[$key] = [
                'CLIENTE'           => $value->CLIENTE,
                'NOMBRE'            => $value->NOMBRE,
                'BULTOS_TOTAL_UND'  => number_format($value->CANTIDAD, 2),  
                'BULTOS_TOTAL_NIO'  => number_format($value->VENTA_CON_IVA, 2),
            ];
        }

        $Metricas_Merge = [
            'Metricas' => $Metricas,
            'Clientes' => $Clientes,
            'Vendedores' => $Vendedores,
            'SKU_CHART' => $SKU_CHART,
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


    
}