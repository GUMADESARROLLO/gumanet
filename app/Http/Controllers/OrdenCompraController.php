<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrdenCompra;

class OrdenCompraController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }
    public function Home($OrdenCompraId) {
        $OrdenCompra = OrdenCompra::where('ORDEN_COMPRA', $OrdenCompraId)->get()->first();
        return view('Pages.OrdenCompra.Home', compact('OrdenCompra'));
    }
}