<?php

namespace App\Http\Controllers;

use App\Facturacion;
use App\Vendedor;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    public function __construct() 
    {
		$this->middleware(['auth','roles']);
    }

    public function Dashboard()
    {
        
        $facturacion = Facturacion::take(100)->get();

        return view('pages.Facturacion.Dashboard', compact('facturacion'));
    }

    public function getDataFacturacion(Request $request)
    {
        $Facturacion = Facturacion::Filtrar($request);

        $Array_Facturacion = [
            'FACTURACION'        => $Facturacion,
        ];
        return response()->json($Array_Facturacion);
    }

}