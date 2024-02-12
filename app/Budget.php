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

        $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.GetProyects8971 ?, ?', [$startDate, $endDate]);

        // Formatear los resultados según lo esperado por DataTables
        $datosFormateados = [];
        foreach ($resultados as $resultado) {
            $fila = [
                'ARTICULO' => $resultado->ARTICULO,
                'DESCRIPCION' => $resultado->ARTICULO,
                'PRESUPUESTO' => 0,
                'CS_VALOR' => 0,
                'FECHA' => [], // Aquí debes colocar los datos de los meses
                'TOTAL' => 0,
                'CANT_LIQUIDADA' => 0
            ];

       

        foreach ($resultado as $columna => $valor) {
            if ($columna !== 'ARTICULO' && $columna !== 'DESCRIPCION' && $columna !== 'PRESUPUESTO' && $columna !== 'CS_VALOR' && $columna !== 'TOTAL' && $columna !== 'CANT_LIQUIDADA') {
                $fila[$columna] = $valor;
            }
        }
         // Recorre los datos de los meses y añádelos a la fila
         foreach ($resultado as $columna => $valor) {
            if ($columna !== 'ARTICULO' && $columna !== 'DESCRIPCION' && $columna !== 'PRESUPUESTO' && $columna !== 'CS_VALOR' && $columna !== 'TOTAL' && $columna !== 'CANT_LIQUIDADA') {
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
