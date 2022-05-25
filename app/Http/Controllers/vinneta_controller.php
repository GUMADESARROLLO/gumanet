<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\vinneta_model;

class vinneta_controller extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $this->agregarDatosASession();

        $data = array(
            'page'              => 'Inventario',
            'name'              => 'GUMA@NET',
            'hideTransaccion'   => ''
        );

        return view('pages.vinneta', $data);
    }

    public function agregarDatosASession() {
        $request = Request();
        $ApplicationVersion = new \git_version();
        $company = Company::where('id',$request->session()->get('company_id'))->first();
        $request->session()->put('ApplicationVersion', $ApplicationVersion::get());
        $request->session()->put('companyName', $company->nombre);
    }

    public function getVinnetas(Request $request) {

        $f1 = $request->input('f1');
        $f2 = $request->input('f2');

        

        if (!$f1) {
            return response()->json(false);
        }

        $obj = vinneta_model::getVinnetas($f1, $f2);
        return response()->json($obj);

    }

    public function getHistorialFactura(Request $request) {

        $Factura = $request->input('vFactura');
        if (!$Factura) {
            return response()->json(false);
        }

        $obj = vinneta_model::getHistorialFactura($Factura);
        return response()->json($obj);

    }

    public function getPagadoRuta(Request $request) {

        $f1 = $request->input('f1');
        $f2 = $request->input('f2');

        

        if (!$f1) {
            return response()->json(false);
        }

        $obj = vinneta_model::getPagadoRuta($f1, $f2);
        return response()->json($obj);

    }

    public function getpagado(Request $request) {

        $f1 = $request->input('f1');
        $f2 = $request->input('f2');

        

        if (!$f1) {
            return response()->json(false);
        }

        $obj = vinneta_model::getpagado($f1, $f2);
        return response()->json($obj);

    }

    public function getVinnetasResumen(Request $request) {

        $f1 = $request->input('f1');
        $f2 = $request->input('f2');

        

        if (!$f1) {
            return response()->json(false);
        }

        $obj = vinneta_model::getVinnetasResumen($f1, $f2);
        return response()->json($obj);

    }

    public function getDetalleOrdenCompra(Request $request){
        if($request->isMethod('post')) {
            $obj = vinneta_model::getDetalleOrdenCompra($request->input('ordCompra'));
            return response()->json($obj);
        }
    }


}
