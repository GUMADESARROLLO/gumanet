<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\proyectosDetalle_model;

class proyectos_model extends Model
{
	protected $table = 'proyectos';
	protected $primaryKey = 'id';

	protected $fillable = [
		'id','name','priori'
	];

    public function proyectosDetalle()
    {
        return $this->hasMany('App\proyectosDetalle_model');
    }
}
