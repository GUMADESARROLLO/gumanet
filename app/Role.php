<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'roles';
    protected $fillable = ['id', 'nombre', 'descripcion'];
    
}
