<?php

namespace App\Http\Controllers\Auth;
use Auth;
use App\usuario_model;
use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use App\Company;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    
    //protected $redirectTo = '/Dashboard';
    public function redirectTo() {
        
        // User role
        $role = Auth::User()->activeRole(); 
    
        // Check user role
        switch ($role) {
            case '1':
                    return '/Dashboard';
                break;
            case '2':
                    return '/Dashboard';
                break;
            case '3':
                    return '/Metas';
                break; 
            case '4':
                    return '/Recuperacion';
                break;
            case '5':
                    return '/Inventario';
                break; 
            default:
                    return '/login'; 
                break;
        }
}

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm()//para no afectar al metodo showLoginForm del trait AuthenticatesUsers, el metodo debe de sobre escribirse en el controlador
    {
        $ApplicationVersion = new \git_version();
        $data = [
            'name' =>  'GUMA@NET',
            'version' =>  $ApplicationVersion::get()
        ];
        $companies =  Company::all();//$this->getCompanies();//obtiene los registros de la tabla companies
        //dd($companies);//dump down, se ejecuta y se muestran los dato y detiene el proceso
        return view('auth.login',$data,compact('companies'));// envia variable al MOD
    }


    public function login(Request $request){
        
        $this->validateLogin($request);//valida los campos del formulario del login


        if ($this->hasTooManyLoginAttempts($request)) {//si se ha hecho arios intentos se bloquea por 1 minuto

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        $user = $request->email;//obtener el email del campo email
        $idComapny = $request->campoEmpresa;//obtener valor del input del campo empresa
        $queryResult = DB::table('users')->where('email',$user)->pluck('id');// consulta para obtener el id del usuario a logearse de exiatir en email
       

        if (!$queryResult->isEmpty()) {//si queryResult no esta vacio existe el usuario
       
            if(!$this->verifEmpresa($queryResult,$idComapny)->isEmpty()) {//verifica si id usuario e id empresa existen en la tabla intermedia relacional 'company_user'
                
                if ($this->attemptLogin($request)) {
                    $request->session()->put('user_email', $user);
                    $request->session()->put('company_id', $idComapny);
                    return $this->sendLoginResponse($request);
                }
                
                
            }


        }

       

        return $this->sendFailedLoginResponse($request);
    }

  

    private function verifEmpresa($user_id, $company_id){
        
        $data = DB::table('company_user')->where('user_id',$user_id)->where('company_id',$company_id)->get();
        return $data;

    }


    protected function credentials(Request $request)
    {
       $request['estado'] = 0;
       return $request->only($this->username(), 'password', 'estado');
    }

    public function getCompanies(){
        
        return usuario_model::getCompanies();

    }
    
        
}
