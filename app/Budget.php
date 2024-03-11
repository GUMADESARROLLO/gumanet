<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Budget extends Model
{


    public static function dtProyect($request) {        
        
        $startDate  = $request->input('f1');
        $endDate    = $request->input('f2');

        // Formatear los resultados según lo esperado por DataTables
        $datosFormateados = [];
        $Prefijo = "";


        $p  = $request->input('pr');

        if ($p === "1") {
            DB::connection("sqlsrv")->select("EXEC PRODUCCION.dbo.gnet_presupuesto_update '$startDate', '$endDate'");
            $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_calc ?, ?', [$startDate, $endDate]);
    
        } elseif ($p === "2") {
            DB::connection("sqlsrv")->select("EXEC PRODUCCION.dbo.gnet_presupuesto_update_71 '$startDate', '$endDate'");
            $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_calc_71 ?, ?', [$startDate, $endDate]);
            $Prefijo = '_71';
        }


        foreach ($resultados as $resultado) {

            $fila = [
                'DETALLE'  => '<a id="exp_more'.$Prefijo.'" class="exp_more'.$Prefijo.'" href="#!"><i class="material-icons expan_more">expand_more</i></a>',
                'ARTICULO' => $resultado->ARTICULO,
                'DESCRIPCION' => strtoupper($resultado->DESCRIPCION),
                'PRESUPUESTO' => $resultado->UND_ANUAL,
                'CS_VALOR' => $resultado->VAL_ANUAL,
                'FECHA' => [], 
                'CANTI_FACT_MES' => $resultado->CANTI_FACT_MES,
                'VALOR_FACT_MES' => $resultado->VALOR_FACT_MES,
                'UND_MES' => $resultado->UND_MES,
                'VAL_MES' => $resultado->VAL_MES,
                'COSTO_PROM' => $resultado->COSTO_PROM,
                'TOTAL_INVENTARIO' => $resultado->TOTAL_INVENTARIO
            ];

    
        $columnas_agregadas = [];
        foreach ($resultado as $columna => $valor) {
            if ($columna !== 'ARTICULO' &&  $columna !== 'DESCRIPCION' && $columna !== 'PRESUPUESTO' && $columna !== 'CS_VALOR' && $columna !== 'TOTAL' && $columna !== 'UND_ANUAL'  && $columna !== 'VAL_ANUAL'  && $columna !== 'UND_MES' && $columna !== 'VAL_MES' && $columna !== 'CANTI_FACT_MES' && $columna !== 'VALOR_FACT_MES'  && $columna !== 'COSTO_PROM'&& $columna !== 'TOTAL_INVENTARIO' ) {
                
                $fila[$columna] = $valor;
                $nombre_columna = strstr($columna, '_', true);

                if (!in_array($nombre_columna, $columnas_agregadas)) {
                    // Agregar el nombre de la columna al array de columnas agregadas
                    $columnas_agregadas[] = $nombre_columna;        
        
                    // Si necesitas agregar el nombre de la columna a un subarray 'FECHA'
                    $fila['FECHA'][] = [
                        'mes' => $nombre_columna,
                    ];
                }
            }
        }
        

        $datosFormateados[] = $fila;
    }
        return $datosFormateados;
    }

    public static function dtArticulo($request) {        
        
        $startDate  = $request->input('f1');
        $endDate    = $request->input('f2');
        $ARTICULO   = $request->input('ARTICULO');
        $Pro        = $request->input('Pro');

        $datosFormateados = [];

     

        if ($Pro === "1") {
            $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_articulos ?, ?, ?', [$startDate, $endDate,$ARTICULO]);
        } else {
            $resultados = DB::connection("sqlsrv")->select('EXEC PRODUCCION.dbo.gnet_presupuesto_articulos_71 ?, ?, ?', [$startDate, $endDate,$ARTICULO]);
        }




        foreach ($resultados as $resultado) {
            $fila = [                
                'ARTICULO' => $resultado->ARTICULO,
                'FECHA' => [], 
                'UND_MES' => $resultado->UND_MES,
            ];

    
        $columnas_agregadas = [];
        foreach ($resultado as $columna => $valor) {
            if ($columna !== 'ARTICULO' && $columna !== 'UND_MES' ) {                
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
