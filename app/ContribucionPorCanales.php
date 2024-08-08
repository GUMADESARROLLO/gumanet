<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContribucionPorCanales extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_canal_contribuciones";

    public static function calcularCanales($fechaIni, $fechaEnd)
    {
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.pr_calcular_canal_contribucion ?, ?", [$fechaIni, $fechaEnd]);
    }

    public static function periodoFechas(){
        $currentDate = date('Y-m-d');
        $startOfMonth = date('Y-m-01', strtotime($currentDate));

        $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-12 months', strtotime($startOfMonth)));
        $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate . ' -1 days'));

        $result = DB::connection('sqlsrv')->select("SELECT MIN(fecha) AS primera_fecha, MAX(fecha) AS ultima_fecha 
                                                    FROM PRODUCCION.dbo.tbl_canales_contribucion");
        return [
            'primera_fecha' => $result[0]->primera_fecha,
            'ultima_fecha' => $result[0]->ultima_fecha,
            'fechaIni' => $FechaIni,
            'fechaEnd' => $FechaEnd,
        ];
    }
}