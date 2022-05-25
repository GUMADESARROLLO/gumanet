<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rutas_asignadas extends Model {

    protected $table = 'rutas_asignadas';

    protected $fillable = [
		'user_id','ruta_id'
	];

}
