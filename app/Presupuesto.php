<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Presupuesto extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_presupuesto_umk";

    public static function getEjecucionPresupuesto(){
        $ventas = "";
        $total = 0;
        $json = array();
        
        $ventas = Presupuesto::selectRaw('CLASE_PRODUCTO, CANAL2, SUM(PRECIO_TOTAL) as total_precio, SUM(CONTRIBUCION) as contribucion')
            ->groupBy('CLASE_PRODUCTO', 'CANAL2')
            ->get();
        
        $nombreMes = Presupuesto::NameMonth();

        foreach($ventas as $v){
            $total += $v->total_precio;
            $json[$v->CLASE_PRODUCTO]['VENTA'] = floatval($v->total_precio); 
            $json[$v->CLASE_PRODUCTO]['CONTRIBUCION'] = floatval($v->contribucion); 
        }        
        $json['PRIMARIOS UMK']['PRESUPUESTO'] = 12887968; 
        $json['SECUNDARIOS']['PRESUPUESTO'] = 2785449; 
        $json['NUEVOS']['PRESUPUESTO'] = 0;
        $json['ONCO']['PRESUPUESTO'] = 101900; 
        $json['GPHARMA']['PRESUPUESTO'] = 1229265;
        $json['CRUZ AZUL']['PRESUPUESTO'] = 3020491; 
        $json['LICITACIONES']['PRESUPUESTO'] = 9333333;

        $json['VENTAS_PRIVADO']['EJECUTADO'] = $json['PRIMARIOS UMK']['VENTA'] + $json['SECUNDARIOS']['VENTA'] + $json['NUEVOS']['VENTA'];
        $json['VENTAS_PRIVADO']['PRESUPUESTO'] = $json['PRIMARIOS UMK']['PRESUPUESTO'] + $json['SECUNDARIOS']['PRESUPUESTO'] + $json['NUEVOS']['PRESUPUESTO'];
        
        $json['VENTAS_PROYECTOS']['EJECUTADO'] = $json['ONCO']['VENTA'] + $json['GPHARMA']['VENTA'];
        $json['VENTAS_PROYECTOS']['PRESUPUESTO'] = $json['ONCO']['PRESUPUESTO'] + $json['GPHARMA']['PRESUPUESTO'];
        
        $json['VENTAS_INSTITUCIONES']['EJECUTADO'] = $json['CRUZ AZUL']['VENTA'] + $json['LICITACIONES']['VENTA'];
        $json['VENTAS_INSTITUCIONES']['PRESUPUESTO'] = $json['CRUZ AZUL']['PRESUPUESTO'] + $json['LICITACIONES']['PRESUPUESTO'];

        $json['VENTAS_BRUTAS']['EJECUTADO'] = $total;

        $json['ANIO'] = Presupuesto::selectRaw('YEAR(FECHA_FACTURA) as anio')->distinct()->pluck('anio')->first();
        $json['MES'] = $nombreMes;
        
        //dd($json);
        return $json;

    }

    public static function getPresupuestoAnual($anio){
        $update = DB::connection('sqlsrv')->select('EXEC PRODUCCION.dbo.pr_calcular_canal_contribucion ? ?', [$anio]);
        return $update;

    }

    public static function actualizarEjecucionPresupuesto($mes, $ano){
        $update = DB::connection('sqlsrv')->select('EXEC PRODUCCION.dbo.pr_calcular_canal_contribucion ? ?', [$mes, $ano]);
        return $update;
    }

    public static function NameMonth()
    {
        $date = new \DateTime(Presupuesto::distinct()->pluck('FECHA_FACTURA')->first());

        $month = $date->format('M');

        $month = str_replace(
            ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ['ENERO', 'FEBRERO', 'MAYO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'],
            $month
        );

        return $month; // Ejemplo: "Ene23"
    }

}