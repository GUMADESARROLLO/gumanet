<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\Role;

class rol extends Controller {
	public function __construct() {
		$this->middleware(['auth','roles']);//pagina se carga unicamente cuando se este logeado
  	}

	public function index() {
		$this->agregarDatosASession();
		$roles = Role::orderBy('id')->get();
		
		$data = array(
			'page' 				=> 'Roles',
			'name' 				=> 'GUMA@NET',
			'hideTransaccion' 	=> ''
		);
		
		return view('pages.Roles.index', compact('data', 'roles'));
	}

    public function agregarDatosASession(){
        $request = Request();
        $ApplicationVersion = new \git_version();
        $company = Company::where('id',$request->session()->get('company_id'))->first();// obtener nombre de empresa mediante el id de empresa
        $request->session()->put('ApplicationVersion', $ApplicationVersion::get());
        $request->session()->put('companyName', $company->nombre);// agregar nombre de compañia a session[], para obtenert el nombre al cargar otras pagina 
    }

    public function crear() {
        $this->agregarDatosASession();

        $data = array(
            'page'              => 'Crear menu',
            'name'              => 'GUMA@NET',
            'hideTransaccion'   => ''
        );
        
        return view('pages.Roles.crear', compact('data'));
    }

    public function guardar(Request $request) {
        $rol = new Role();

        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->save();

        return redirect('rol/crear');
    }
}
