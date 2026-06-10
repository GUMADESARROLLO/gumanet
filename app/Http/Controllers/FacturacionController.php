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
        $Vendedores = Facturacion::getVendedores($request);
        $PedidosFacturados = Facturacion::getPedidosFacturados($request);
        
        $Array_Facturacion = [
            'FACTURACION'        => $Facturacion,
            'VENDEDORES'        => $Vendedores,
            'PEDIDOS_FACTURADOS' => $PedidosFacturados,
        ];
        return response()->json($Array_Facturacion);
    }

    public function getDetallePedidoFactura(Request $request)
    {
        $pedido = $request->pedido;
        $detalle = Facturacion::getDetallePedidoFactura($pedido);
        return response()->json($detalle);
    }

}