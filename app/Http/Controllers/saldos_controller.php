<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\saldos_model;

class saldos_controller extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$this->agregarDatosASession();

		$data = [
			'name' =>  'GUMA@NET',
			'rutas' => saldos_model::rutas()
		];
		
		return view('pages.saldos', $data);
	}

	public function agregarDatosASession() {
		$request = Request();
		$ApplicationVersion = new \git_version();
		$company = Company::where('id',$request->session()->get('company_id'))->first();
		$request->session()->put('ApplicationVersion', $ApplicationVersion::get());
		$request->session()->put('companyName', $company->nombre);
	}

	public function saldosAll() {
		$obj = saldos_model::saldosAll('');
		return response()->json($obj);
	}

	public function saldosXRuta(Request $request) {
        
        if($request->isMethod('post')) {
            $obj = saldos_model::saldosXRuta($request->input('ruta_'));
            return response()->json($obj);
        }
	}
    
}
