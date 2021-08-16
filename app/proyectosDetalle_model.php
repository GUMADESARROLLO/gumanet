<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class proyectosDetalle_model extends Model
{
	protected $table = 'proyectos_rutas';

	protected $fillable = [
		'vendedor','idProyecto'
	];
}
