<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetricasMetas extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.dbo.VtasTotal_UMK";

    public static function DetalleFactura($anio)
    {
        return self::query()
            ->selectRaw('ARTICULO,DESCRIPCION,SUM(CANTIDAD) AS CANTIDAD,nMes,Año')
            ->where('Año', $anio)
            ->groupBy('ARTICULO','DESCRIPCION','nMes','Año')
            ->get();
    }

    public static function MesFacturado($anio)
    {
        return self::query()
            ->selectRaw('SUM(CANTIDAD) AS CANTIDAD,Mes,Año')
            ->where('Año', $anio)
            ->groupBy('Mes','Año')
            ->get();
    }

    public static function getDatageneral($request){
        $Meses = [];

        $anio = $request->anio;

        $Meses     = MetricasMetas::MesFacturado($anio);

        foreach ($Meses as $key => $value) {
            $Meses[$key] = [
                'DESCRIPCION'            => 'Detalle de metas '.$value->Mes,
                'FACTURADO'         => number_format($value->CANTIDAD,2),
                'Mes'  => $value->Mes,
                'Año'  => $value->Año,
                'Estado'            => 'En espera'
            ];
        }

        return $Meses;
    }
}