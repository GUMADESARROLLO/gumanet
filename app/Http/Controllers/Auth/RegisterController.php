<?php

namespace App\Http\Controllers\Auth;
use App\usuario_model;
use App\Models;
use App\User;
use App\Company;
use App\Role;
use App\rutas_asignadas;
use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   public function __construct()
    {
        //$this->middleware('guest'); //Pagina se carga solo si usuario No esta Logeado

        $this->middleware('auth');//pagina se carga unicamente cuando se este logeado
    }

    use RegistersUsers;

   
     public function showRegistrationForm()
    {
         $data = [
            'page' => 'Usuarios',
            'name' =>  'GUMA@NET'
        ];
        $companies = $this->getCompanies();
        $roles = $this->getRoles();
        $rutas = usuario_model::rutas();

        return view('auth.register',compact('companies','roles','rutas'));
    }

    public function getCompanies(){   
        return Company::all();
    }

    public function getRoles(){   
        return Role::all();
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/Usuario';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'min:2'],
            'role' => ['required', 'string','not_in:0'],
            'company' => ['required', 'string','not_in:0','not_in:""','not_in:null'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'description' => ['required', 'string', 'min:5'],
            'password' => ['required', 'string', 'min:5', 'confirmed']/*,
            'image' => ['image'],*/
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */


    protected function create(array $data)
    {


        $company = array_map('intval',explode(',', $data['company_values']));
        $rutas = explode(',', $data['rutas_values']);

        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'role' => $data['role'],
            'email' => $data['email'],
            'description' => $data['description'],
            'password' => Hash::make($data['password'])/*,
            'image' => $data['image'],*/
        ]);
            
        $user->companies()->attach($company,['created_at' => new \DateTime(),'updated_at' => new \DateTime()]);
        
        foreach ($rutas as $key => $value) {
                rutas_asignadas::create([
                'user_id' => $user->id,
                'ruta_id' => $value
            ]);
        }

        return $user;
    }   
}
