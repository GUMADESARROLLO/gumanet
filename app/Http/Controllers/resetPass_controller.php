<?php

namespace App\Http\Controllers;


use App\usuario_model;
use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword_Request;

class resetPass_controller extends Controller
{

	public function __construct()
	 {
	    $this->middleware('auth');//pagina se carga unicamente cuando se este logeado
	 }


    function index(){
        $data = [
            'page' => 'Resetear',
            'name' =>  'GUMA@NET'
        ];
        return view('auth.passwords.reset',$data);
    }

    public function resetPass(ResetPassword_Request $request){
    	$pass = $request->only('password');//muestra Array( [password] => 12345 )
    	 return back()->with('status',usuario_model::resetPass($pass));

        //$request->only(Hash::make('password'));
        //$request->password;//muestra "12345"
    	//$request->input('password');//muestra "12345"

    }

    

}
