<?php

namespace App\Http\Controllers;

use App\usuario_model;
use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


//Modelos
use App\User;
use App\Role;
use App\Company;
use App\rutas_asignadas;

class usuario_controller extends Controller
{

	public function __construct()
	 {
	    $this->middleware('auth');//pagina se carga unicamente cuando se este logeado
	 }


    function index(){
        $users = User::all();
        $roles = Role::all();
        $companies = Company::all();
        $rutasAsig = rutas_asignadas::all();
        $rutas = usuario_model::rutas();
        $data = [
            'page' => 'Usuarios',
            'name' =>  'GUMA@NET'
        ];
        
        return view('pages.usuarios',compact('data','users','roles','companies','rutasAsig','rutas'));
    }



    public function getCompaniesByUserId($User_id){
        $user = User::find($User_id);
           return $user->companies;
    }


    public function getUsersByCompanyId($Company_id){
         $company = Company::find($Company_id);
           return $company->users;
    }
   

    public function editUser(Request $request){


        $company = array_map('intval',explode(',', $request->company_id));

        $user = User::find($request->id);//obtiene los datos especificos con el id ingresado
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->description = $request->description;
            $user->updated_at = new \DateTime();
            $user->save();// actualiza datos obtenidod desde el Request[] de existir id, de lo contrario lo crea, en este caso lo actualiza
         $user->companies()->sync($company);//todos los id que no esten en array $company que esten en la tabla pivote se eliminaran

    }

    public function deleteUser(Request $request){
        $company = array_map('intval',explode(',', $request->company_id));

        print_r($company);
        $user = User::find($request->id);//obtiene los datos especificos con el id ingresado
        $user->delete();//elimina usuario perteneciente al id ingresado anteriormente
        $user->companies()->detach($company);//elimina los registros de usuarios relacionados con la tabla companies de la tabla pivote o intermedia "company_user"
    }

    public function changeUserStatus(Request $request) {
        $statusUser;
        if ($request->estado == "0") {
           $statusUser = 1;
        }else{
            $statusUser = 0;
        }
         $user = User::find($request->id);
         $user->estado = $statusUser;
         $user->save();


    }
    

}
