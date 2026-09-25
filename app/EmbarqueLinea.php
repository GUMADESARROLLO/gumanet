<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbarqueLinea extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.DBO.view_gnet_embarque_linea";

    public function getInfoEmbarque()
    {
        return $this->belongsTo(Embarque::class, 'EMBARQUE', 'EMBARQUE');
    }
    
   
}
