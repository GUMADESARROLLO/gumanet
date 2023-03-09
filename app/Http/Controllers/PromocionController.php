<?php

namespace App\Http\Controllers;

use App\Promocion;
use App\PromocionDetalle;
use App\Vendedor;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function getPromocion()
    {  
        $Mes   = date('n');
        $Anno   = date('Y');

        $totalMv = $totalVv = $totalMu = $totalVu = 0;
        $Promociones   = PromocionDetalle::getDetalles();

        foreach($Promociones as $p){
            $totalMv += $p['ValMeta'];
            $totalVv += $p['Venta'];
            $totalMu += $p['MetaUnd'];
            $totalVu += $p['VentaUND'];
        }
        
        return view('pages.promocionesRuta',compact('Promociones','totalMv', 'totalVv', 'totalMu', 'totalVu'));
    }
    
}
