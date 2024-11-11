<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Presupuestoumk extends Model
{

    public static function getEjecucionPresupuesto($mes, $ano){
        $ventas = "";
        $i = 0;
        $primario = $secundario = $onco = $gpharma = $nuevo = 0;
        $json = array();
        
        if($mes == 'ACUMULADA'){
            $ventas = DB::connection('sqlsrv')->select('EXEC PRODUCCION.dbo.pr_presupuesto_umk ?, ?', [$mes,$ano]);
        }else{
            $ventas = DB::connection('sqlsrv')->select('EXEC PRODUCCION.dbo.pr_presupuesto_umk ?, ?', [$mes,$ano]);
        }

        foreach($ventas as $v){
            if($v->CLASE_PRODUCTO == 'PRIMARIOS UMK'){
                $primario += $v->PRECIO_TOTAL;
            }
            if($v->CLASE_PRODUCTO == 'SECUNDARIOS'){
                $secundario += $v->PRECIO_TOTAL;
            }
            if($v->CLASE_PRODUCTO == 'GPHARMA'){
                $gpharma += $v->PRECIO_TOTAL;
            }
            if($v->CLASE_PRODUCTO == 'ONCO'){
                $onco += $v->PRECIO_TOTAL;
            }
            if($v->CLASE_PRODUCTO == 'NUEVOS'){
                $nuevo += $v->PRECIO_TOTAL;
            }
        }

        $totalPrivado = $primario + $secundario + $nuevo;
        $totalPrivadoPresup = intval('12887968') + intval('2785449');

        $json['VentaBruta']['Ejecutado'] = number_format($totalPrivado,2,'.',',');
        $json['VentaBruta']['Presupuesto'] = number_format($totalPrivadoPresup,2,'.',',');
        $json['VentaBruta']['DifAbsoluta'] = $totalPrivado - $totalPrivadoPresup;
        $json['VentaBruta']['DifRelativa'] = number_format((($totalPrivado - $totalPrivadoPresup)/$totalPrivado) * 100,2);
        
        $json['TotalPrivado']['Ejecutado'] = number_format($totalPrivado,2,'.',',');
        $json['TotalPrivado']['Presupuesto'] = number_format($totalPrivadoPresup,2,'.',',');
        $json['TotalPrivado']['DifAbsoluta'] = $totalPrivado - $totalPrivadoPresup;
        $json['TotalPrivado']['DifRelativa']  = number_format((($totalPrivado - $totalPrivadoPresup)/$totalPrivado) * 100,2);

        $json['Primario']['Ejecutado'] = number_format($primario,2,'.',',');
        $json['Primario']['PorcientoEje'] = number_format($primario/$totalPrivado,2,'.',',');
        $json['Primario']['Presupuesto'] = number_format('12887968',2,'.',',');

        $json['TotalPrivado']['Secundario'] = number_format($secundario,2,'.',',');
        $json['TotalPrivado']['Nuevo'] = number_format($nuevo,2,'.',',');
        $json['TotalPrivado']['Total']  = number_format($totalPrivado,2,'.',',');

        
        //dd($json['VentaBruta']['Ejecutado']);
        return $json;

    }
}