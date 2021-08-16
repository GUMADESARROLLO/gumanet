<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;//encripta el texto dado
use Illuminate\Support\Facades\Auth;//muestra datos de usuario autenticado
use DB;// clase que hace que se usen las bases de datos como objetos

class usuario_model extends Model
{
    public static function getUsuario() {
    	$data;
    	$i = 0;
        $usuario =  DB::table('users')->get();
        foreach ($usuario as $key) {
        	if ($key->estado == 0){
        		$data[$i]["estado"] = "Activo";
			}else{
				$data[$i]["estado"] = "Inactivo";
	        }
    		$data[$i]["name"] = $key->name." ".$key->surname;
    		$data[$i]["email"] = $key->email;
    		$data[$i]["role"] = $key->role;
    		$data[$i]["company"] = $key->company;
    		$data[$i]["description"] = $key->description;
    		$data[$i]["created_at"] = $key->created_at;
    		$i++;
    	}
        
        return $data;
    }


    public static function resetPass($newPass){
        DB::table('users')->where('email',Auth::User()->email)->update(['password' => Hash::make($newPass['password'])]);
        return 'La contraseña ha sido reseteada con exito!';
    }

    public static function getCompanies(){
        return DB::table('companies')->get();
    }

    public static function getRoles(){
        return DB::table('roles')->get();
    }

    public static function rutas() {
         $sql_server = new \sql_server();

        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

        switch ($company_user) {
            case '1':
                $sql_exec = " SELECT * FROM UMK_VENDEDORES_ACTIVO ";
                break;
            case '2':
                $sql_exec = " SELECT * FROM GP_VENDEDORES_ACTIVOS ";
                break;
            case '3':
                return false;
                break;
            case '4':
                $sql_exec = " SELECT * FROM INV_VENDEDORES_ACTIVOS ";
                break;
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

         $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

        if( count($query)>0 ){
            return $query;
        }

        $sql_server->close();
        return false;
    }    
}
