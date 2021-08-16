<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rutas extends Model
{
	protected $table = "rutas";
	protected $fillable = ['id', 'vendedor', 'nombre', 'emp', 'zona', 'estado'];
}
