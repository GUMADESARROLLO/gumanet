<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;

class Mific extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_precio_mific_dev";


    public static function getData($request)
    {
        $Obj =  self::get();

        $UNIDAD_NEGOCIO = [
            'ND'  => 'N/D',
            'GP'  => 'GUMAPHARMA',
            'UMK' => 'UNIMARK S.A.',
            'INN' => 'INNOVA INDUSTRIA S.A.',
        ];

        $Mific = [];

        foreach ($Obj as $key => $a) 
        {  
            $UND = $UNIDAD_NEGOCIO[$a->UNIDAD_NEGOCIO] ?? "N/D";

            $Mific[$key] = [
                "ID_MIFIC"              => $a->ID_MIFIC,
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

    public static function UpdateMific(Request $request) 
    {
        $UpdateMific = [
            'ARTICULO'              => $request->input('sku_umk'),
            'MIFIC_FARMACIA'        => $request->input('precio_farmacia'),
            'MIFIC_PUBLICO'         => $request->input('precio_publico'),
            'UNIDAD_NEGOCIO'        => $request->input('unidad_negocio'),
            'REGISTRO_SANITARIO'    => $request->input('registro_sanitario'),
            'NOMBRE_COMERCIAL'      => $request->input('nombre_comercial'),
            'NOMBRE_GENERICO'       => $request->input('nombre_generico'),
            'CONCENTRACION'         => $request->input('concentracion'),
            'PRESENTACION'          => $request->input('presentacion'),
            'CANTIDAD'              => $request->input('cantidad'),
            'LABORATORIO'           => $request->input('laboratorio'),
            'UpdateAt'              => date('Y-m-d H:i:s'),
            'UpdateBy'              => Auth::user()->id,
        ];



        $Response = self::where('ID_MIFIC', $request->input('id_row'))->update($UpdateMific);

        return $Response;
    }

    public static function SaveMific(Request $request) 
    {
        $SaveMific = [
            'ARTICULO'              => $request->input('sku_umk'),
            'MIFIC_FARMACIA'        => $request->input('precio_farmacia'),
            'MIFIC_PUBLICO'         => $request->input('precio_publico'),
            'UNIDAD_NEGOCIO'        => $request->input('unidad_negocio'),
            'REGISTRO_SANITARIO'    => $request->input('registro_sanitario'),
            'NOMBRE_COMERCIAL'      => $request->input('nombre_comercial'),
            'NOMBRE_GENERICO'       => $request->input('nombre_generico'),
            'CONCENTRACION'         => $request->input('concentracion'),
            'PRESENTACION'          => $request->input('presentacion'),
            'CANTIDAD'              => $request->input('cantidad'),
            'LABORATORIO'           => $request->input('laboratorio'),
            'CreatedAt'             => date('Y-m-d H:i:s'),
            'CreatedBy'             => Auth::user()->id,
        ];

        $Response = self::insert($SaveMific);

        return $Response;
    }

    public static function DeleteMific(Request $request) 
    {
        $DeleteMific = self::where('ID_MIFIC', $request->input('id_row'))->delete();
        return $DeleteMific;
    }


    
}