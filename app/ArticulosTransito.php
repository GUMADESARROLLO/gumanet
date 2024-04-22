<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ArticulosTransito extends Model
{
    
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_articulos_transito_dev";
    protected $primaryKey = 'Articulo';
    protected $keyType    = 'string';

    protected $fillable = [
        'Articulo',
        'Descripcion',
        'fecha_estimada',
        'fecha_pedido',
        'documento',
        'cantidad',
        'mercado',
        'mific',
        'observaciones',
        'Nuevo',
        'Precio_mific'
    ];


    public static function SaveTransitoExcel(Request $request) 
    {
        if ($request->ajax()) {
            try {
                $datos_a_insertar = array();    
                ArticulosTransito::truncate();
                foreach ($request->input('datos') as $k => $v) 
                {
                    $v['CANTIDAD'] = str_replace(',', '', $v['CANTIDAD']);
                    $Articulo = ($v['ARTICULO'] == 'N/D') ? mt_rand(10000000, 99999999).'-N' : $v['ARTICULO'] ;

                    $datos_a_insertar[$k] = [
                        'Articulo'		    => $Articulo,
                        'Descripcion'		=> strtoupper($v['DESCRIPC']),
                        'cantidad'		    => number_format((float)$v['CANTIDAD'], 2,'.',''),
                        'fecha_pedido'		=> $v['dtPedido'],
                        'fecha_estimada'	=> $v['dtEstimada'],
                        'mercado'		    => $v['Mercado'],
                        'mific'			    => $v['Mific']	,
                        'documento'		    => $v['Documento'],
                        'observaciones'		=> $v['Comment'],
                        'Nuevo'		        => 'N',
                        'Precio_mific'      =>$v['Pre_MIFIC'],
                    ];
                }
                $response = ArticulosTransito::insert($datos_a_insertar); 
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}
