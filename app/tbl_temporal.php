<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_temporal extends Model
{
    protected $table = 'tbl_temporal';
    protected $fillable = ['articulo','descripcion','cantidad','venta','VstMesActual','VstAnnoActual'];
}
