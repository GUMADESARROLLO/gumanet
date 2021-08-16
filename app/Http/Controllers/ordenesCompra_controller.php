<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\ordenesCompra_model;

class ordenesCompra_controller extends Controller {
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

        return view('pages.ordenesCompra', $data);
    }

    public function agregarDatosASession() {
        $request = Request();
        $ApplicationVersion = new \git_version();
        $company = Company::where('id',$request->session()->get('company_id'))->first();
        $request->session()->put('ApplicationVersion', $ApplicationVersion::get());
        $request->session()->put('companyName', $company->nombre);
    }

    public function getDataOrdenesCompra(Request $request) {

        $f1 = $request->input('f1');
        $f2 = $request->input('f2');

        

        if (!$f1) {
            return response()->json(false);
        }

        $obj = ordenesCompra_model::getOrdenesCompra($f1, $f2);
        return response()->json($obj);

    }

    public function getDetalleOrdenCompra(Request $request){
        if($request->isMethod('post')) {
           $obj = ordenesCompra_model::getDetalleOrdenCompra($request->input('ordCompra'));
            return response()->json($obj);
        }
    }


}
