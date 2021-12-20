<?php

namespace App\Http\Controllers;

use App\DetalleOrden_model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models;
use App\Company;
use Illuminate\Support\Facades\DB;


class DetalleOrdenController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('pages.detalleOrden');
    }

    function getDetalleOrdenes()
    {
        $data = array();
        $i = 0;
        $j = 0;
        $produccion_total = DB::table('producciontest.inn_produccion_total');
        $obj = $produccion_total->get();
        foreach ($obj as $Orden => $key) {
            $data[$i]['numOrden'] = $key->numOrden;
            $orden_produccion = DB::table('producciontest.orden_produccion')->where('numOrden', $key->numOrden)->get();
            foreach ($orden_produccion as $orden_prod => $op) {
                $data[$i]['fechaInicio'] = $op->fechaInicio . ' ' . $op->horaInicio;
                $data[$i]['fechaFinal'] = $op->fechaFinal . ' ' . $op->horaFinal;
                $productos = DB::table('producciontest.productos')->where('idProducto',  $op->producto)->get();
                foreach ($productos as $producto => $p) {
                    $data[$i]['producto'] = $p->nombre;
                    $data[$i]['ver'] = '<a href="#!"  class="btn "  onclick="getMoreDetail(' . "'" . $key->numOrden . "'" . ', ' . "'" . $p->nombre . "'" . ')"><i class="fas fa-eye fa-2x text-primary"></i></a>';
                }
            }
            $co_subTT = DB::table('producciontest.inn_costo_orden_subtotal')
                ->select(DB::raw('SUM(subtotal) as total'))
                ->where('numOrden',  $key->numOrden)->get();

            //$query4 = DB::table('producciontest.inn_costo_orden_subtotal')->where('numOrden',  $op->numOrden)->get();
            if (is_null($co_subTT)) {
                $data[$i]['costo_total'] = 'C$ ' . number_format(0, 2);
            } else {
                foreach ($co_subTT as $costo_subTotal => $cst) {
                    $data[$i]['costo_total'] = 'C$ ' . number_format($cst->total, 2);
                }
            }
            $data[$i]['prod_real'] = number_format($key->prod_real, 2);
            $data[$i]['prod_total'] = number_format($key->merma_total +  $key->prod_real, 2);
            $i++;
        }
        return response()->json($data);
    }

    public function getMateriaPrima($numOrden)
    {
        $data = array();
        $i = 0;
        //  $query2 = DB::table('producciontest.orden_produccion')->where('numOrden', $key->numOrden)->get();

        $mp_directa =  DB::table('producciontest.mp_directa')->select('producciontest.mp_directa.*', 'producciontest.fibras.descripcion', 'producciontest.maquinas.nombre')
            ->join('producciontest.fibras', 'producciontest.mp_directa.idFibra', '=', 'producciontest.fibras.idFibra')
            ->join('producciontest.maquinas', 'producciontest.mp_directa.idMaquina', '=', 'producciontest.maquinas.idMaquina')
            ->where('producciontest.mp_directa.numOrden', $numOrden)
            ->get();

        foreach ($mp_directa as $key => $mp) {
            $data[$i]['fibra'] = $mp->descripcion;
            $data[$i]['maquina'] = $mp->nombre;
            $data[$i]['cantidad'] = $mp->cantidad . " kg";
            $i++;
        }

        return response()->json($data);
    }

    public function getMOD($numOrden)
    {
        $data = array();
        $i = 0;
        $t_pulpeo = DB::table('producciontest.tiempo_pulpeo')->select(DB::raw('COALESCE(SUM(cant_dia),0) as cantDia, COALESCE(SUM(cant_noche),0) as cantNoche,  COALESCE(tiempoPulpeo,0) as tiempoPulpeo'))
            ->where('numOrden', $numOrden)
            ->groupBy('tiempoPulpeo')
            ->get()->first();

        $t_lavado = DB::table('producciontest.tiempo_lavado')->select(DB::raw('COALESCE(SUM(cant_dia),0)as cantDia, COALESCE(SUM(cant_noche),0) as cantNoche, COALESCE(tiempoLavado,0)'))
            ->where('numOrden', $numOrden)
            ->groupBy('tiempoLavado')
            ->get()->first();

        $t_muertos =  DB::table('producciontest.tiempos_muertos')->select(DB::raw('COALESCE(SUM(y1_dia),0) as cantDiaY1, COALESCE(SUM(y2_dia),0) as cantDiaY2, COALESCE(SUM(y1_noche),0) as cantNocheY1, COALESCE(SUM(y2_noche),0)as cantNocheY2'))
            ->where('numOrden', $numOrden)
            ->get()->first();

        $hrsTrabajadas = DB::table('producciontest.orden_produccion')->select('hrsTrabajadas')->where('numOrden', $numOrden)->get()->first();
        $hrsTrabajadas = $hrsTrabajadas->hrsTrabajadas / 2;

        if ($t_pulpeo) {
            $t_pulpeo_dia = ($t_pulpeo->cantDia * $t_pulpeo->tiempoPulpeo) / 60;
            $t_pulpeo_noche = ($t_pulpeo->cantNoche * $t_pulpeo->tiempoPulpeo) / 60;
        } else {
            $t_pulpeo_dia = 0;
            $t_pulpeo_noche = 0;
        }
        if ($t_lavado) {
            $t_lavado_dia = ($t_lavado->cantDia * $t_pulpeo->tiempoPulpeo) / 60;
            $t_lavado_noche = ($t_lavado->cantNoche * $t_pulpeo->tiempoPulpeo) / 60;
        } else {
            $t_lavado_dia = 0;
            $t_lavado_noche = 0;
        }
        if ($t_muertos) {
            $y1_jumboroll_dia = $hrsTrabajadas - ($t_muertos->cantDiaY1 / 60);
            $y1_jumboroll_noche = $hrsTrabajadas - ($t_muertos->cantNocheY1 / 60);
            $y1_jumboroll_total = $y1_jumboroll_dia + $y1_jumboroll_noche;

            $y2_jumboroll_dia = $hrsTrabajadas - ($t_muertos->cantDiaY2 / 60);
            $y2_jumboroll_noche = $hrsTrabajadas - ($t_muertos->cantNocheY2 / 60);
            $y2_jumboroll_total = $y2_jumboroll_dia + $y2_jumboroll_noche;
        } else {
            $y1_jumboroll_dia = 0;
            $y1_jumboroll_noche = 0;
            $y1_jumboroll_total = 0;

            $y2_jumboroll_dia = 0;
            $y2_jumboroll_noche = 0;
            $y2_jumboroll_total = 0;
        }

        $data[0]['actividad'] = 'Pulper 1 - Pasta Reciclada';
        $data[0]['dia']       = number_format($t_pulpeo_dia, 2) . " hrs";
        $data[0]['noche']     = number_format($t_pulpeo_noche, 2)  . " hrs";
        $data[0]['total']     = $t_pulpeo_dia + $t_pulpeo_noche  . " hrs";

        $data[1]['actividad'] = 'Lavadora de Tetrapack';
        $data[1]['dia'] = number_format($t_lavado_dia, 2)  . " hrs";
        $data[1]['noche'] = number_format($t_lavado_noche, 2)  . " hrs";
        $data[1]['total'] = $t_lavado_dia + $t_lavado_noche  . " hrs";

        $data[2]['actividad'] = 'Pulper 2 - Pasta Virgen';
        $data[2]['dia'] = number_format(0, 2)  . " hrs";
        $data[2]['noche'] = number_format(0, 2)  . " hrs";
        $data[2]['total'] = number_format(0, 2) . " hrs";

        $data[3]['actividad'] = 'Pulper 2 - Pasta Virgen';
        $data[3]['dia'] = number_format(0, 2)  . " hrs";
        $data[3]['noche'] = number_format(0, 2)  . " hrs";
        $data[3]['total'] = number_format(0, 2)  . " hrs";

        $data[4]['actividad'] = 'Yankee 1 - Jumbo Roll';
        $data[4]['dia'] = number_format($y1_jumboroll_dia, 2)  . " hrs";
        $data[4]['noche'] = number_format($y1_jumboroll_noche, 2)  . " hrs";
        $data[4]['total'] = number_format($y1_jumboroll_total, 2)  . " hrs";

        $data[5]['actividad'] = 'Yankee 2 - Jumbo Roll';
        $data[5]['dia'] = number_format($y2_jumboroll_dia, 2)  . " hrs";
        $data[5]['noche'] = number_format($y2_jumboroll_noche, 2)  . " hrs";
        $data[5]['total'] = number_format($y2_jumboroll_total, 2)  . " hrs";

        $data[6]['actividad'] = 'Caldera';
        $data[6]['dia'] = number_format($hrsTrabajadas, 2)  . " hrs" ;
        $data[6]['noche'] = number_format($hrsTrabajadas, 2)  . " hrs";
        $data[6]['total'] = number_format($hrsTrabajadas * 2, 2)  . " hrs";

        $data[7]['actividad'] = 'Planta de Tratamiento';
        $data[7]['dia'] = number_format($hrsTrabajadas, 2)  . " hrs";
        $data[7]['noche'] = number_format($hrsTrabajadas, 2)  . " hrs";
        $data[7]['total'] = number_format($hrsTrabajadas * 2, 2)  . " hrs";

        // return $array;
        return response()->json($data);
    }
    public function getSubCostos($numOrden)
    {
        $data = array();
        $i = 0;
        //$query2 = DB::table('producciontest.orden_produccion')->where('numOrden', $key->numOrden)->get();
        $costos_por_orden = DB::table('producciontest.inn_costo_orden_subtotal')->where('numOrden', $numOrden)->get();

        foreach ($costos_por_orden as $key => $costo) {
            $data[$i]['codigo'] = $costo->codigo;
            $data[$i]['descripcion'] = $costo->descripcion;
            $data[$i]['unidad_Medida'] = $costo->unidad_medida;
            $data[$i]['cantidad'] = $costo->cantidad . " kg";
            $data[$i]['costo_Unitario'] = "C$ " . $costo->costo_unitario;
            $data[$i]['costo_Total'] = $costo->subtotal;
            $i++;
        }

        return response()->json($data);
    }

    public function getQuimicos($numOrden)
    {
        $data = array();
        $i = 0;

        $quimico_maquina =   DB::table('producciontest.quimico_maquina')->select('producciontest.quimico_maquina.*', 'producciontest.quimicos.descripcion', 'producciontest.maquinas.nombre')
            ->join('producciontest.quimicos', 'producciontest.quimico_maquina.idQuimico', '=', 'producciontest.quimicos.idQuimico')
            ->join('producciontest.maquinas', 'producciontest.quimico_maquina.idMaquina', '=', 'producciontest.maquinas.idMaquina')
            ->where('producciontest.quimico_maquina.numOrden', $numOrden)
            ->get();

        foreach ($quimico_maquina as $key => $qm) {
            $data[$i]['quimico'] = $qm->descripcion;
            $data[$i]['maquina'] = $qm->nombre;
            $data[$i]['cantidad'] = $qm->cantidad  . " kg";
            $i++;
        }

        return response()->json($data);
    }

    public function getOtrosConsumos($numOrden)
    {
        $data = array();
        $electricidad = DB::table('producciontest.electricidad')->select('inicial', 'final')
            ->where('numOrden', $numOrden)
            ->get()->first();

        $consumo_agua =  DB::table('producciontest.consumo_agua')->select('inicial', 'final')
            ->where('numOrden', $numOrden)
            ->get()->first();

        $consumo_gas = DB::table('producciontest.gas')->select('inicial', 'final')
            ->where('numOrden', $numOrden)
            ->get()->first();

        //Electricidad
        if ($electricidad) {
            $inicialE = ($electricidad->inicial == '') ? 0 : $electricidad->inicial;
            $finalE = ($electricidad->final == '') ? 0 : $electricidad->final;
        } else {
            $inicialE = 0;
            $finalE = 0;
        }
        //Consumo de agua
        if ($consumo_agua) {
            $inicialA = ($consumo_agua->inicial == '') ? 0 : $consumo_agua->inicial;
            $finalA = ($consumo_agua->final == '') ? 0 : $consumo_agua->final;
        } else {
            $inicialA = 0;
            $finalA = 0;
        }
        //Consume de gas
        if ($consumo_gas) {
            $inicialG = ($consumo_gas->inicial == '') ? 0 : $consumo_gas->inicial;
            $finalG = ($consumo_gas->final == '') ? 0 : $consumo_gas->final;
        } else {
            $inicialG = 0;
            $finalG = 0;
        }

        //Electricidad
        if ($finalE > 0) {
            $data[0]['Einicial']          = $inicialE;
            $data[0]['Efinal']           = $finalE;
            $data[0]['EtotalConsumo']    =  number_format(($finalE - $inicialE), 2);
            $data[0]['EtotalCordobas']    = number_format(($finalE - $inicialE) * 560, 2);
        } else {
            $data[0]['Einicial']          = 0;
            $data[0]['Efinal']           = 0;
            $data[0]['EtotalConsumo']    = number_format(0,2);
            $data[0]['EtotalCordobas']  = number_format(0,2);
        }
        //Consumo de Agua
        if ($finalA > 0) {
            $data[0]['Ainicial']          =       $inicialA;
            $data[0]['Afinal']           = $finalA;
            $data[0]['AtotalConsumo']    =  number_format(($finalA - $inicialA), 2);
        } else { 
            $data[0]['Ainicial']          = 0;
            $data[0]['Afinal']           = 0;
            $data[0]['AtotalConsumo']    = number_format(0,2);
            //$data[1]['AtotalCordobas']   = number_format(0,2);
        }
        //Consumo de Gas
        if ($finalG > 0) {
            $data[0]['Ginicial']          = $inicialG;
            $data[0]['Gfinal']            = $finalG;
            $data[0]['GtotalConsumo']     =  number_format(($finalG - $inicialG), 2);
        } else {
            $data[0]['Ginicial']          = 0;
            $data[0]['Gfinal']           = 0;
            $data[0]['GtotalConsumo']    = number_format(0,2);
            $data[0]['GtotalCordobas']   = number_format(0,2);
        }

        //return $data;
        return response()->json($data);
    }
}
