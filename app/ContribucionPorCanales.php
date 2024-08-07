<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContribucionPorCanales extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_canal_contribuciones";

    public static function calcularCanales()
    {
        $currentDate = date('Y-m-d');
        $startOfMonth = date('Y-m-01', strtotime($currentDate));

        $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-12 months', strtotime($startOfMonth)));
        $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate . ' -1 days'));

        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.pr_calc_reorder_factura_linea ?, ?", [$FechaIni, $FechaEnd]);

    }
}