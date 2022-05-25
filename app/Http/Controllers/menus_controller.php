<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\Role;
use App\menu;

class menus_controller extends Controller {

	public function __construct() {
		$this->middleware(['auth','roles']);//pagina se carga unicamente cuando se este logeado
  	}

	public function index() {
		$this->agregarDatosASession();
		$roles = Role::orderBy('id')->pluck('nombre', 'id')->toArray();
        $menus = menu::getMenus();
        $menusRoles = menu::with('roles')->get()->pluck('roles', 'id')->toArray();

		$data = array(
			'page' 				=> 'Menus',
			'name' 				=> 'GUMA@NET',
			'hideTransaccion' 	=> ''
		);
		
		return view('pages.Menu.menu', compact('data', 'roles', 'menus', 'menusRoles'));
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
        
        return view('pages.Menu.crear', compact('data'));
    }

    public function guardar(Request $request) {
        if ($request->ajax()) {
            $menus = new menu();

            if ($request->input('estado')==1) {                
                $menus->find($request->input('menu_id'))->roles()->attach($request->input('rol_id'));
            }else {
                $menus->find($request->input('menu_id'))->roles()->detach($request->input('rol_id'));
            }
        } else {
            abort(404);
        }
    }

    public function guardarNuevoMenu(Request $request) {
        $menu = new menu();
        $menu->nombre = $request->nombre;
        $menu->url = $request->url;
        $menu->icono = $request->icono;
        $menu->orden = 0;
        $menu->menu_id = 0;
        $menu->save();

        return redirect('menu/crear');
    }
}
