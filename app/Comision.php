<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Comision extends Model{
    public static function getData($Mes,$Anno)
    {
        $i=0;

        $RutaArray  = array();

        $Vendedor   = Vendedor::getVendedor();
        
        foreach ($Vendedor as $v){
            
            $Salariobasico = 5000 ;

            $RutaArray[$i]['VENDEDOR']                   = $v->VENDEDOR;
            $RutaArray[$i]['NOMBRE']                     = $v->NOMBRE;
            $RutaArray[$i]['BASICO']                     = $Salariobasico;
            $RutaArray[$i]['DATARESULT']                 = Comision::CalculoCommision($v->VENDEDOR,$Mes,$Anno,$Salariobasico);
            
            $i++;
        }
        
        
        return $RutaArray;
    }

    public static function CalculoCommision($Ruta,$Mes,$Anno,$Salariobasico)
    {

        $data                       = array();
        $RutaArray                  = array();
        $Comision_de_venta          = array();
        $recuperacion_de_credito    = array();
        $recuperacion_de_contado    = array();
        $i=0;

        $cliente_prom=0;
        $cliente_meta=0;
        $cliente_fact=0;
        $cliente_CUMp=0;
        $Cliente_cober=0;
        
        $query      = DB::connection('sqlsrv')->select('EXEC PRODUCCION.dbo.fn_comision_calc_8020 "'.$Mes.'","'.$Anno.'","'.$Ruta.'", "'.'N/D'.'" ');
        $qCobertura = DB::connection('sqlsrv')->select('EXEC PRODUCCION.dbo.fn_comision_calc_BonoCobertura "'.$Ruta.'"');

        if (count($qCobertura )>0) {
            $cliente_prom=number_format($qCobertura[0]->PROMEDIOANUAL,0);
            $cliente_meta=number_format($qCobertura[0]->METAMES,0);
            $cliente_fact=number_format($qCobertura[0]->MESFACTURADO,0);
            $cliente_CUMp=number_format($qCobertura[0]->CUMPLI,2);
            $Cliente_cober= $qCobertura[0]->isCumple;
        }
        
        
        $Array_articulos_cumplen = array_filter($query,function($item){
            if($item->isCumpl=='SI'){
                return $item;
            }
        });


        $Array_articulos_lista80 = array_filter($query,function($item){
            if($item->Lista=='80' ){
                return $item;
            }
        });

        $Array_articulos_lista20 = array_filter($query,function($item){
            if($item->Lista=='20'){
                return $item;
            }
        });


        $Array_countItem80 = array_filter($Array_articulos_cumplen,function($item){
            if($item->Lista=='80' ){
                return $item;
            }
        });
        

        $count_articulos_lista80            = count(array_filter($query,function($item){
                                                    if($item->Lista=='80' && $item->VentaVAL > 0){
                                                        return $item;
                                                    }
                                                })); 
        $sum_venta_articulos_lista80        = array_sum(array_column($Array_articulos_lista80,'VentaVAL'));
        $factor_comision_venta_lista80      = 3;
        
        $count_articulos_lista20            = count(array_filter($Array_articulos_cumplen,function($item){
                                                if($item->Lista=='20'){
                                                    return $item;
                                                }
                                            })); 
        $sum_venta_articulos_lista20        = array_sum(array_column($Array_articulos_lista20,'VentaVAL'));
        $factor_comision_venta_lista20      = Comision::NivelFactorComision($count_articulos_lista20,$sum_venta_articulos_lista20);
    
        $Total_articulos_cumplen            = $count_articulos_lista80  + $count_articulos_lista20; 
        $sum_venta_articulos_Total          = $sum_venta_articulos_lista80 + $sum_venta_articulos_lista20;
        $factor_comision_venta_Total        = $factor_comision_venta_lista80 + $factor_comision_venta_lista20;

        $Comision80 = ($sum_venta_articulos_lista80 * $factor_comision_venta_lista80) / 100;
        $Comision20 = ($sum_venta_articulos_lista20 * $factor_comision_venta_lista20) / 100;

        $ttComision = $Comision80 + $Comision20;

        
        
        $Comision_de_venta = [
            'Lista80' => [
                $count_articulos_lista80,
                number_format($sum_venta_articulos_lista80, 2),
                $factor_comision_venta_lista80,
                number_format($Comision80,2)
            ],
            'Lista20' => [
                $count_articulos_lista20,
                number_format($sum_venta_articulos_lista20, 2),
                $factor_comision_venta_lista20,
                number_format($Comision20,2)
            ],
            'Total' => [
                $Total_articulos_cumplen,
                number_format($sum_venta_articulos_Total, 2),
                $factor_comision_venta_Total,
                number_format($ttComision,2)
            ]
        ];

        $Bono_de_cobertura = Comision::BonoCobertura($Cliente_cober);
        
        $ComisionesMasBonos = ($Bono_de_cobertura);

      
        $Totales_finales = [
            number_format($Bono_de_cobertura,0),
            number_format( ($Bono_de_cobertura + $ttComision) ,2),
            number_format($ComisionesMasBonos,0),
            $cliente_CUMp,
            $cliente_prom,
            $cliente_meta,
            $cliente_fact
        ];

        

        $RutaArray['Comision_de_venta']          = $Comision_de_venta ;
        $RutaArray['Totales_finales']            = $Totales_finales ;
        $RutaArray['Total_Compensacion']         = number_format(($Salariobasico + $Bono_de_cobertura + $ttComision),2);

        
        return $RutaArray;
    }
    public static function BonoCobertura($cump)
    { 

        $valor_pagar = 0;

        if ($cump >= 100) {
            $valor_pagar = 3500;
        } elseif ($cump >= 90 && $cump < 100) {
            $valor_pagar = 3150;
        } elseif ($cump >= 80 && $cump < 90) {
            $valor_pagar = 2800;
        } elseif ($cump < 80) {
            $valor_pagar = 0;
        }

        return $valor_pagar;

    }

    public static function NivelFactorComision($Count,$Valor)
    {
        $factor = Factor::where('min', '<=', $Count)->where('max', '>=', $Count)->first();
        if ($factor) {
            return ($Valor >= $factor->meta) ? $factor->valor : 3 ;
        }

    }
}