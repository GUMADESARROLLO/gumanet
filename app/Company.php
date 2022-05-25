<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = []; 

    /*public function users(){
        return $this->belongsToMany(User::class);
    }*/

    public function users(){
    	return $this->belongsToMany('App\User');
    }

}


