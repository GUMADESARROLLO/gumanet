<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrdenCompra;
use App\LIQUIDAC_COMPRA;

class OrdenCompraController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }
    public function OrdenCompraDetalle($OrdenCompraId) {

        $LIQUIDACION_COMPRA = null;
        $OrdenCompra = OrdenCompra::where('ORDEN_COMPRA', $OrdenCompraId)->get()->first();

        $OC_ESTADO = $OrdenCompra->ESTADO;
        $OC_PRIORIDAD = $OrdenCompra->PRIORIDAD;
        $EM_ESTADO = $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->ESTADO ?? null;
        $EM_LIQUIDADO = $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->LIQUIDADO ?? null;
        $LQ_COMPRA = $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->LIQUIDAC_COMPRA ?? null;



        
        if($LQ_COMPRA) {
            $LIQUIDACION_COMPRA = LIQUIDAC_COMPRA::where('LIQUIDAC_COMPRA', $LQ_COMPRA)->get()[0];
        }
        
        $LISTA_ESTADOS = [
            'R' => 'RECIBIDO',
            'M' => 'MEDIA',
            'A' => 'PLANEADA', 
            'O' => 'CANCELADO',  
            'E' => 'TRANSITO',          
        ];
        $LISTA_ESTADOS_LIQ = [
            'S' => 'LIQUIDADO',
            'N' => 'NO LIQUIDADO',            
        ];


        $OC_ESTADO = $LISTA_ESTADOS[$OC_ESTADO] ?? "N/D";
        $OC_PRIORIDAD = $LISTA_ESTADOS[$OC_PRIORIDAD] ?? "N/D";
        $EM_ESTADO = $LISTA_ESTADOS[$EM_ESTADO] ?? "N/D";
        $EM_LIQUIDADO = $LISTA_ESTADOS_LIQ[$EM_LIQUIDADO] ?? "N/D";

        $ESTADOS = [
            'OC_ESTADO'     => $OC_ESTADO,
            'OC_PRIORIDAD'  => $OC_PRIORIDAD,
            'EM_ESTADO'     => $EM_ESTADO,
            'EM_LIQUIDADO'  => $EM_LIQUIDADO,
            'LQ_COMPRA'     => $LQ_COMPRA,
        ];


        return view('Pages.OrdenCompra.Home', compact('OrdenCompra', 'ESTADOS', 'LIQUIDACION_COMPRA'));
    }

    public function OrdenesCompra() {
        return view('Pages.OrdenCompra.Index');
    }

    public function getDataOrdenesCompra(Request $request)
    {
        $Metricas_Actuales = OrdenCompra::getData($request);


        $Metricas = $Metricas_Actuales;
        
        return response()->json($Metricas);
    }
}