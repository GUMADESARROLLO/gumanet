<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\ClientesModel;

class ClientesController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function agregarDatosASession() {
        $request = Request();
        $ApplicationVersion = new \git_version();
        $company = Company::where('id',$request->session()->get('company_id'))->first();
        $request->session()->put('ApplicationVersion', $ApplicationVersion::get());
        $request->session()->put('companyName', $company->nombre);
    }

    public function index() {
        $this->agregarDatosASession();

        $data = array(
            'page'              => 'Clientes',
            'name'              => 'GUMA@NET',
            'hideTransaccion'   => ''
        );

        return view('pages.Clientes.clientes', $data);
    }

    public function getClientes(Request $request) {
    
        $obj = ClientesModel::getClientes($request);
        return response()->json($obj);
    }
}
