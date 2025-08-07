<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Cell;
use App\Logs_calcs;

use Session;

class ReOrderPointR3 extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_base_reorder_v3_calc";

    /**
     * Executes three stored procedures to calculate reorder points for articles.
     *
     * @return void
     */
    public static function Calcular()
    {
        $currentDate = date('Y-m-d');
        //$startOfMonth = date('Y-m-01', strtotime($currentDate));

        // $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-11 months', strtotime($startOfMonth)));
        // $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate . ' -1 days'));

        $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-12 months', strtotime($currentDate)));
        $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate ));

        // Ejecutar el primer procedimiento almacenado
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.sp_base_reorder_v3 ?, ?", [$FechaIni, $FechaEnd]);
        
        // Insertar en el modelo Logs_calcs
        // Logs_calcs::create([
        //     'Modulo'        => 'ReOrderPoint',
        //     'ini'           => $FechaIni,
        //     'end'           => $FechaEnd,
        //     'Observacion'   => 'Calculo de Reorder Point con Dia Actual: ' . $FechaEnd
        // ]);
        

    }
    public static function getReorderPoint($request) 
    {        
        $DataReturn = [];
        $DataReorderPoint = ReOrderPointR3::Where('SEGMENTO', 'FARMACIAS')->get();

        foreach ($DataReorderPoint as $key => $value) {
            $DataReturn[] = [
                'ARTICULO'                  => $value->ARTICULO,
                'DESCRIPCION'               => strtoupper($value->DESCRIPCION),
                'LABORATORIO'               => strtoupper($value->LABORATORIO),
                'PROM_NORMAL'               => $value->PROM_NORMAL,
                'PROM_3M'                   => $value->PROM_3M,
                'PROM_ANUAL'                => $value->PROM_ANUAL,
                'INVENTARIO'                => $value->INVENTARIO,
                'ONHAND'                    => $value->ONHAND,
                'PROCENT_ANUAL'             => number_format(($value->PROCENT_ANUAL * 100 ), 2, '.', ''),
                'NECESITDAD_COMPRA_ANUAL'   => $value->NECESITDAD_COMPRA_ANUAL,
                'FACT_CA_YEAR_ACTUAL'       => $value->FACT_CA_YEAR_ACTUAL,
                'POTENCIAL_CA'              => $value->POTENCIAL_CA,
                'PEDIDO_TOTAL'              => $value->PEDIDO_TOTAL,
                'MOQ'                       => $value->MOQ,
                'ULTM_COST_USD'             => $value->ULTM_COST_USD,
            ];
        }
        return $DataReturn;
    }

    
}
