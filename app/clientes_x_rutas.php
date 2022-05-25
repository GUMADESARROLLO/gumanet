<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientes_x_rutas extends Model
{
	protected $table = "clientes_x_rutas";
	protected $fillable = ['id', 'ruta', 'cantidad'];
}
