<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Budget extends Model
{
    public static function dtProyect($request) {        
        
        $startDate = $request->input('f1');
        $endDate = $request->input('f2');

        $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_GetProyects8971 ?, ?', [$startDate, $endDate]);

        // Formatear los resultados según lo esperado por DataTables
        $datosFormateados = [];

        foreach ($resultados as $resultado) {
            $fila = [
                'ARTICULO' => $resultado->ARTICULO,
                'DESCRIPCION' => $resultado->DESCRIPCION,
                'PRESUPUESTO' => $resultado->UND_ANUAL,
                'CS_VALOR' => $resultado->VAL_ANUAL,
                'FECHA' => [], 
                'PREC_PROM' => 0,
                'CONTRIBUCION' => 0,
                'UND_MES' => $resultado->UND_MES,
                'VAL_MES' => $resultado->VAL_MES
            ];

       

        foreach ($resultado as $columna => $valor) {
            if ($columna !== 'ARTICULO' && $columna !== 'DESCRIPCION' && $columna !== 'PRESUPUESTO' && $columna !== 'CS_VALOR' && $columna !== 'TOTAL' && $columna !== 'UND_ANUAL'  && $columna !== 'VAL_ANUAL'  && $columna !== 'UND_MES' && $columna !== 'VAL_MES') {
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
