<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Softland.umk.ORDEN_COMPRA";

    public function getProveedor()
    {
        return $this->hasOne(Proveedor::class, 'PROVEEDOR', 'PROVEEDOR');
    }

    public function getLineasOrden()
    {
        return $this->hasMany(OrdenCompraLinea::class, 'ORDEN_COMPRA', 'ORDEN_COMPRA');
    }

    public function getEmbarqueLinea()
    {
        return $this->hasMany(EmbarqueLinea::class, 'ORDEN_COMPRA', 'ORDEN_COMPRA');
    }

    public static function getData($request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $ttOrdene = 0;


        //SUSTITUIR ESTO POR UNA FUNCION QUE TRADUZCA LOS ESTADOS DE LA ORDEN DE COMPRA 
        $LISTA_ESTADOS = [
            'R' => 'RECIBIDO',
            'M' => 'MEDIA',
            'A' => 'PLANEADA',      
            'O' => 'CANCELADO',
            'E' => 'TRANSITO',
        ];

        $Obj =  self::whereBetween('FECHA', [$request->desde, $request->hasta])->get();

        $array_ordenes = array();
        $Ordenes = array();

        foreach ($Obj as $key => $a) 
        {  
            $OC_ESTADO = $a->ESTADO;
            $OC_PRIORIDAD = $a->PRIORIDAD;

            $OC_ESTADO = $LISTA_ESTADOS[$OC_ESTADO] ?? "N/D";
            $OC_PRIORIDAD = $LISTA_ESTADOS[$OC_PRIORIDAD] ?? "N/D";

            $OR_EMBARQUE = $a->getEmbarqueLinea->first()->EMBARQUE ?? 'NO';
            $OR_LIQUIDAC = $a->getEmbarqueLinea->first()->getInfoEmbarque->LIQUIDAC_COMPRA ?? 'NO';



            $Ordenes[$key] = [
                "ORDEN_COMPRA"          => $a->ORDEN_COMPRA,
                "ORD_EMBARQ"            => $OR_EMBARQUE,
                "ORD_LIQUID"            => $OR_LIQUIDAC,
                "FECHA"                 => date('Y-m-d', strtotime($a->FECHA)),
                "TOTAL_A_COMPRAR"       => $a->TOTAL_A_COMPRAR,
                "ESTADO"                => $OC_ESTADO,
                "PRIORIDAD"             => $OC_PRIORIDAD,
                "PROVEEDOR"             => $a->PROVEEDOR,
                "NOMBRE_PROVEEDOR"      => $a->getProveedor->NOMBRE,
                "FECHA_COTIZACION"      => (is_null($a->FECHA_COTIZACION)) ? ' - ' : date('Y-m-d', strtotime($a->FECHA_COTIZACION)),
                "FECHA_OFRECIDA"        => (is_null($a->FECHA_OFRECIDA)) ? ' - ' : date('Y-m-d', strtotime($a->FECHA_OFRECIDA)),
                "FECHA_REQ_EMBARQUE"    => (is_null($a->FECHA_REQ_EMBARQUE)) ? ' - ' : date('Y-m-d', strtotime($a->FECHA_REQ_EMBARQUE)),
                "FECHA_REQUERIDA"       => (is_null($a->FECHA_REQUERIDA)) ? ' - ' : date('Y-m-d', strtotime($a->FECHA_REQUERIDA)),
            ];

            $ttOrdene = $ttOrdene + $a->TOTAL_A_COMPRAR;
            
        }
        $array_ordenes = [
            "ORDEN_COMPRA" => $Ordenes,
            "TOTAL_ORDENES" => $ttOrdene
        ];
        return $array_ordenes;
    }
}
