<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App;
use Auth;
use App\Models;
use App\Company;
use App\minutasCorp_model;
use DB;
use App\User;
use Redirect;
use Mail;

class minutasCorp_controller extends Controller
{
	public function __construct() {
		$this->middleware(['auth','roles']);//pagina se carga unicamente cuando se este logeado
  	}

	public function index(Request $request) {
		$this->agregarDatosASession();
		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
		$rol = Auth::User()->activeRole();

		switch ($rol) {
			case '1':
				$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
				->where('tbl_minuta_corp.estado', 1)
				->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
				->orderBy('tbl_minuta_corp.fecha', 'desc')
				->paginate(5);
				break;
			case '2':
				$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
				->where('tbl_minuta_corp.estado', 1)
				->where('tbl_minuta_corp.empresa', $company_user)
				->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
				->orderBy('tbl_minuta_corp.fecha', 'desc')
				->paginate(5);
				break;
			case '7':
				$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
				->where('tbl_minuta_corp.estado', 1)
				->where('tbl_minuta_corp.empresa', $company_user)
				->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
				->orderBy('tbl_minuta_corp.fecha', 'desc')
				->paginate(5);
				break;
			case '6':
				$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
				->where('tbl_minuta_corp.estado', 1)
				->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
				->orderBy('tbl_minuta_corp.fecha', 'desc')
				->paginate(5);
				break;
			default:

				break;
		}
		
        $data = [
            'page' => 'Minutas Corporativas',
            'name' =>  'GUMA@NET',
            'blogs' => $blogs,
            'cant' => $blogs->count()
        ];
		
		return view('pages.minuta', $data);
	}

	public function agregarDatosASession() {
		$request = Request();
		$ApplicationVersion = new \git_version();
		$company = Company::where('id',$request->session()->get('company_id'))->first();// obtener nombre de empresa mediante el id de empresa
		$request->session()->put('ApplicationVersion', $ApplicationVersion::get());
		$request->session()->put('companyName', $company->nombre);// agregar nombre de compañia a session[], para obtenert el nombre al cargar otras pagina 
	}

    public function searchBlogs(Request $request) {
		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
		$rol = Auth::User()->activeRole();

		if($request->isMethod('post')) {
			$search 	= $request->input('search');
			$search 	=  '%' . $search . '%';
			
			$date 		= $request->input('date');
			$order 		= ( $date=='desc' )?'desc':'asc';
			
			$dates 		= $request->input('fechas');

			$from = ( $dates==null )?date('Y-m-d h:i:s', strtotime('2020-01-01 00:00:00')):date('Y-m-d 00:00:00', strtotime($dates['fecha1']));
			$to = ( $dates==null )?date('Y-m-d 23:59:59'):date('Y-m-d 23:59:59', strtotime($dates['fecha2']));

			switch ($rol) {
				case '1':
					$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
					->where(function($q) use ($search) {
					$q->where('tbl_minuta_corp.nombre_completo', 'LIKE', $search)->orWhere('tbl_minuta_corp.titulo', 'LIKE', $search)->orWhere('tbl_minuta_corp.contenido_min', 'LIKE', $search)->orWhere('tbl_minuta_corp.autor', 'LIKE', $search);
					})
					->where('tbl_minuta_corp.estado', 1)
					->whereBetween('fecha', [$from, $to])
					->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
					->orderBy('tbl_minuta_corp.fecha', $order)->paginate(5);
					break;
				case '2':
					$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
					->where(function($q) use ($search) {
					$q->where('tbl_minuta_corp.nombre_completo', 'LIKE', $search)->orWhere('tbl_minuta_corp.titulo', 'LIKE', $search)->orWhere('tbl_minuta_corp.contenido_min', 'LIKE', $search)->orWhere('tbl_minuta_corp.autor', 'LIKE', $search);
					})
					->where('tbl_minuta_corp.estado', 1)
					->where('tbl_minuta_corp.empresa', $company_user)
					->whereBetween('fecha', [$from, $to])
					->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
					->orderBy('tbl_minuta_corp.fecha', $order)->paginate(5);
					break;
				case '6':
					$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
					->where(function($q) use ($search) {
					$q->where('tbl_minuta_corp.nombre_completo', 'LIKE', $search)->orWhere('tbl_minuta_corp.titulo', 'LIKE', $search)->orWhere('tbl_minuta_corp.contenido_min', 'LIKE', $search)->orWhere('tbl_minuta_corp.autor', 'LIKE', $search);
					})
					->where('tbl_minuta_corp.estado', 1)
					->whereBetween('fecha', [$from, $to])
					->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
					->orderBy('tbl_minuta_corp.fecha', $order)->paginate(5);
					break;
				default:
					$blogs = minutasCorp_model::select('tbl_minuta_corp.*','companies.nombre')
					->where('tbl_minuta_corp.estado', 1)
					->where('tbl_minuta_corp.empresa', $company_user)
					->join('companies', 'tbl_minuta_corp.empresa', '=', 'companies.id')
					->orderBy('tbl_minuta_corp.tbl_minuta_corp.fecha', 'desc')
					->paginate(5);
					break;
			}
			return view('pages.blogs', compact('blogs'))->render();
		}
    }

    public function createUpdateMinuta() {
		$this->agregarDatosASession();

        $data = [
            'page' => 'Crear Minuta',
            'name' =>  'GUMA@NET'
        ];
		
		return view('pages.minutaCU', $data);
    }

    public function getDataMinuta($idMinuta, $action) {
		$this->agregarDatosASession();
		$blog = minutasCorp_model::where('idMinuta', $idMinuta)->get();

        $data = [
            'page' => 'Crear Minuta',
            'name' =>  'GUMA@NET',
            'blog' => $blog,
            'action' => ( $action )
        ];
		
		return view('pages.minutaVisuallizar', $data);
    }

    public function guardarMinuta(Request $request) {
    	if($request->isMethod('post')) {
    		$titulo = ($request->input('tituloMinuta')=='')?'ND':$request->input('tituloMinuta');
    		$contenido_min = ($request->input('content_max2')=='')?'ND':$request->input('content_max2');
    		$contenido_min = substr( $contenido_min, 0, 240);
    		$contenido_max = ($request->input('content_max')=='')?'ND':$request->input('content_max');
    		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

	        $users = User::where('email', $request->session()->get('user_email'))->first();
	       	
	        $user = minutasCorp_model::create([
	            'titulo' => $titulo,
	            'contenido_min' => ((strlen($contenido_max)<240)?$contenido_min:$contenido_min.' [...]'),
	            'contenido_max' => $contenido_max,
	            'idUser' => $users->id,
	            'autor' => $users->email,
	            'nombre_completo' => $users->name.' '.$users->surname,
	            'rol' => $users->role,
	            'fecha' => date('Y-m-d h:i:s'),
	            'archivos' => 'N/D',
	            'empresa' => $company_user,
	            'estado' => 1
	        ]);
    	}

    	$data = minutasCorp_model::latest('idMinuta')->first();

    	$name = $users->name.' '.$users->surname;
    	$date = date('d/m/Y h:i:s');
		$to_name = 'Bismark Escobar';
		$to_email = 'analista.guma@gmail.com';
		
		$data = array(
			'name' => $name,
			'title' => $titulo,
			'date' => $date,
			'url' => 'http://186.1.15.167:8448/gumanet/public/minuta/'.$data['idMinuta'].'/ver'
		);

		/*Mail::send('pages.mail', $data, function($message) use ($to_name, $to_email) {
			$message->to($to_email, $to_name)->subject('Nueva Minuta Corporativa');
			$message->from('developer.guma@gmail.com','Email de Notificación');
		});*/

		return Redirect::to('MinutasCorporativas');
    }

    public function deleteMinuta($idMinuta) {
		minutasCorp_model::where('idMinuta', $idMinuta)
		->update([
			'estado' => 0
		]);

		return (response()->json(true));
    }

    public function actulizarMinutaCorp(Request $request) {
    	if($request->isMethod('post')) {
    		$idMinuta = $request->input('idMinuta');
    		$titulo = ($request->input('tituloMinuta')=='')?'ND':$request->input('tituloMinuta');
    		$contenido_min = ($request->input('content_max2')=='')?'ND':$request->input('content_max2');
    		$contenido_min = substr( $contenido_min, 0, 240);
    		$contenido_max = ($request->input('content_max')=='')?'ND':$request->input('content_max');
    		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

	       	
			minutasCorp_model::where('idMinuta', $idMinuta)
				->update([
				'titulo' => $titulo,
				'contenido_min' => ((strlen($contenido_max)<240)?$contenido_min:$contenido_min.' [...]'),
				'contenido_max' => $contenido_max,
				'fecha' => date('Y-m-d h:i:s')
				]);
    	}		
		return Redirect::to('MinutasCorporativas');
    }
}
