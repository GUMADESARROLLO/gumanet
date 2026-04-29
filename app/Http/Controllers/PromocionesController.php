<?php

namespace App\Http\Controllers;

use App\Facturas;
use App\Vendedor;
use Illuminate\Http\Request;
use PDF;

class PromocionesController extends Controller
{
    public function __construct() 
    {
		$this->middleware(['auth','roles']);
    }

    public function RifaCar()
    {
        
        $Facturas = Facturas::take(100)->get();

        return view('pages.Promociones.RifaCar.home', compact('Facturas'));
    }

    public function getFactPromocion(Request $request)
    {
        $Facturacion = Facturas::Filtrar($request);

        $Array_Facturacion = [
            'FACTURACION'        => $Facturacion,
        ];
        return response()->json($Array_Facturacion);
    }

    public function getFactAcciones(Request $request)
    {
        $Acciones = Facturas::Acciones($request);

        $Array_Acciones = [
            'ACCIONES'        => $Acciones,
        ];
        return response()->json($Array_Acciones);
    }

    public function AsignarAcciones(Request $request)
    {
        $InfoFactura = Facturas::AsignarAcciones($request);
        return response()->json($InfoFactura);
    }

    public function ImprimirAcciones(Request $request)
    {
        $Acciones = Facturas::ImprimirAcciones($request);
        $InfoFactura = Facturas::getInfoFactura($request->Factura);
        
        //$Pdf = PDF::loadView('pages.Promociones.RifaCar.Imprimir', compact('Acciones', 'InfoFactura'));
        //return $Pdf->download('Acciones.pdf');
        return view('pages.Promociones.RifaCar.Imprimir', compact('Acciones', 'InfoFactura'));
        
    }

}