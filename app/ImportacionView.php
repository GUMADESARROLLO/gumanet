<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;

class ImportacionView extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_gnet_ImportacionCalc";


    public static function getImportacionView(Request $request)
    {
        $COD_MOLECULA       = $request->COD_MOLECULA;
        $CANT_HOMOLO_GROUP  = [];
        $dta_return         = [];
        $nYEar_actual       = date('Y');
        $nYEar_pasado       = $nYEar_actual - 1;

        $IMPORTACIONES      = ImportacionView::WHERE('ARTICULO', $COD_MOLECULA)->get()->toArray();

        // Agrupar y sumar UNIDADES_HOMOLOGADAS por NYEAR
        foreach ($IMPORTACIONES as $item) {
            $year = $item['NYEAR'] ?? null;

            if (!$year) {
                continue;
            }

            if (!isset($CANT_HOMOLO_GROUP[$year])) {
                $CANT_HOMOLO_GROUP[$year] = [
                    'UNIDADES_HOMOLOGADAS' => 0,
                    'FOB_TOTAL' => 0,
                ];
            }

            $CANT_HOMOLO_GROUP[$year]['UNIDADES_HOMOLOGADAS'] += (float)($item['UNIDADES_HOMOLOGADAS'] ?? 0);
            $CANT_HOMOLO_GROUP[$year]['FOB_TOTAL'] += (float)($item['FOB_TOTAL'] ?? 0);
        }

        $nyear_val_actual = (!isset($CANT_HOMOLO_GROUP[$nYEar_actual])) ? 0 : $CANT_HOMOLO_GROUP[$nYEar_actual]['FOB_TOTAL'] ;
        $nyear_val_pasado = (!isset($CANT_HOMOLO_GROUP[$nYEar_pasado])) ? 0 : $CANT_HOMOLO_GROUP[$nYEar_pasado]['FOB_TOTAL'] ;

        $nyear_cant_actual = (!isset($CANT_HOMOLO_GROUP[$nYEar_actual])) ? 0 : $CANT_HOMOLO_GROUP[$nYEar_actual]['UNIDADES_HOMOLOGADAS'] ;
        $nyear_cant_pasado = (!isset($CANT_HOMOLO_GROUP[$nYEar_pasado])) ? 0 : $CANT_HOMOLO_GROUP[$nYEar_pasado]['UNIDADES_HOMOLOGADAS'] ;


        $dta_return = [ 
            'MERCADO'   =>[
                'VALOR_MERCADO' => [
                    $nYEar_actual  => number_format($nyear_val_actual, 2),
                    $nYEar_pasado  => number_format($nyear_val_pasado, 2),
                    'Crec'         => 0.02,
                ],
                'CANT_HOMOLOGADAS' => [
                    $nYEar_actual  => number_format($nyear_cant_actual, 0),
                    $nYEar_pasado  => number_format($nyear_cant_pasado, 0),
                    'Crec'         => 0.12,
                ],
            ],
            'COMPETIDORES' => self::getTopCompetidores($COD_MOLECULA, $nYEar_actual, $nYEar_pasado)
        ];

        return response()->json($dta_return);
    }

    public static function getTopCompetidores($Articulos,$nYear_actual, $nYear_pasado){

        $Competidores = [];

        $Consulta_SQL = 'SELECT
                NOMBRE_IMPORTADOR AS COMPETIDOR,
                NOMBRE_COMERCIAL AS MARCA,
                PAIS_ORIGEN AS ORIGEN,
                SUM(CASE WHEN NYEAR = '.$nYear_pasado.' THEN FOB_TOTAL ELSE 0 END) AS [PASADO_FOB],
                SUM(CASE WHEN NYEAR = '.$nYear_actual.' THEN FOB_TOTAL ELSE 0 END) AS [ACTUAL_FOB],
                SUM(CASE WHEN NYEAR = '.$nYear_pasado.' THEN UNIDADES_HOMOLOGADAS ELSE 0 END) AS [PASADO_CANT],
                SUM(CASE WHEN NYEAR = '.$nYear_actual.' THEN UNIDADES_HOMOLOGADAS ELSE 0 END) AS [ACTUAL_CANT]
            FROM
                PRODUCCION.dbo.view_gnet_ImportacionCalc
            WHERE
                ARTICULO = '.$Articulos.'
            GROUP BY
                NOMBRE_IMPORTADOR,
                NOMBRE_COMERCIAL,
                PAIS_ORIGEN;';

        $Top_Competidores = DB::connection('sqlsrv')->select($Consulta_SQL);

        foreach ($Top_Competidores as $item) {

            // Verificar si los valores son nulos y asignar 0 si es necesario
            $item->ACTUAL_FOB = $item->ACTUAL_FOB ?? 0;
            $item->PASADO_FOB = $item->PASADO_FOB ?? 0;
            $item->ACTUAL_CANT = $item->ACTUAL_CANT ?? 0;
            $item->PASADO_CANT = $item->PASADO_CANT ?? 0;

            $item->FOB_CREC = ($item->PASADO_FOB != 0) ? number_format(($item->ACTUAL_FOB - $item->PASADO_FOB) / $item->PASADO_FOB * 100, 2) . '%' : 0; // FOB_CREC
            $item->CNT_CREC = ($item->PASADO_CANT != 0) ? number_format(($item->ACTUAL_CANT - $item->PASADO_CANT) / $item->PASADO_CANT * 100, 2) . '%' : 0; // CNT_CREC


            $Competidores[] = [
                'COMPETIDOR'    => $item->COMPETIDOR,
                'MARCA'         => $item->MARCA,
                'ORIGEN'        => $item->ORIGEN,
                'ACTUAL_FOB'    => number_format($item->ACTUAL_FOB, 2),
                'PASADO_FOB'    => number_format($item->PASADO_FOB, 2),
                'FOB_CREC'      => $item->FOB_CREC,
                'ACTUAL_CANT'   => number_format($item->ACTUAL_CANT, 0),
                'PASADO_CANT'   => number_format($item->PASADO_CANT, 0),
                'CNT_CREC'      => $item->CNT_CREC,
                
            ];
        }


        return $Competidores;

    }

    
}
