<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LIQUIDAC_COMPRA extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.LIQUIDAC_COMPRA";

    public function LiquidacionLinea()
    {
        return $this->hasMany(LIQUIDAC_DETALLE::class, 'LIQUIDAC_COMPRA', 'LIQUIDAC_COMPRA');
    }
    public function LiquidacionGasto()
    {
        return $this->hasMany(LIQUIDAC_GASTO::class, 'LIQUIDAC_COMPRA', 'LIQUIDAC_COMPRA');
    }
}