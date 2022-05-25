<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class minutasCorp_model extends Model
{
    protected $table = 'tbl_minuta_corp';

    protected $fillable = [
		'titulo','contenido_min','contenido_max','idUser','autor','nombre_completo','rol','fecha','archivos','empresa','estado'
	];
}
