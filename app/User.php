<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Role;
use App\Company;
use App\rutas_asignadas;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'surname','role','company','description','password','image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public $timestamps = true;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->belongsToMany('App\Company');
    }

    public function rutasAsignadas()
    {
        return $this->belongsToMany('App\rutas_asignadas');
        //return $this->belongsToMany(rutas_asignadas::class);
    }

   public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    

    public function activeRole() {//retorna el role del usuario actual
        return  $this->role;
    }

    public function activeCompany() {//retorna la compania
        return  $this->company;
    }

    public function gitVersion() {
        $ApplicationVersion = new \git_version();

        return $ApplicationVersion::get();
    }

}
