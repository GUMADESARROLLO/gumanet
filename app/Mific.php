<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mific extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_precio_mific_dev";


    public static function getData($request)
    {
        $Obj =  self::get();

        $UNIDAD_NEGOCIO = [
            'GP'  => 'GUMAPHARMA',
            'UMK' => 'UNIMARK S.A.',
            'INN' => 'INNOVA INDUSTRIA S.A.',
        ];

        $Mific = [];

        foreach ($Obj as $key => $a) 
        {  
            $UND = $UNIDAD_NEGOCIO[$a->UNIDAD_NEGOCIO] ?? "N/D";

            $Mific[$key] = [
                "SKU_UMK"               => $a->ARTICULO ?? "N/D",
                "REGISTRO_SANITARIO"    => $a->REGISTRO_SANITARIO ?? "N/D",
                "NOMBRE_COMERCIAL"      => $a->NOMBRE_COMERCIAL ?? "N/D",
                "NOMBRE_GENERICO"       => $a->NOMBRE_GENERICO ?? "N/D",
                "CONCENTRACION"         => $a->CONCENTRACION ?? "N/D",
                "PRESENTACION"          => $a->PRESENTACION ?? "N/D",
                "CANTIDAD"              => $a->CANTIDAD ?? "N/D",
                "LABORATORIO"           => $a->LABORATORIO ?? "N/D",
                "PRECIO_FARMACIA"       => number_format($a->MIFIC_FARMACIA, 2, '.', ','),
                "PRECIO_PUBLICO"        => number_format($a->MIFIC_PUBLICO, 2, '.', ','),
                "UNIDAD_NEGOCIO"        => $UND,
                "ACCIONES"              => '<button class="btn btn-sm btn-primary" title="Editar"  onclick="editMific( '.$a->ID_MIFIC.' )"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger" title="Eliminar" onclick="removeMific( '.$a->ID_MIFIC.' )" id="btn-remove-mific"><i class="fas fa-trash-alt"></i></button>',
            ];
            
        }

        return $Mific;
    }


    
}