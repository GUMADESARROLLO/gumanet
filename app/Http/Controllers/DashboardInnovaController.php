<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\DashboardInnova; 

class DashboardInnovaController extends Controller
{
    public function getDataInnova(Request $request)
    {
        $Metricas_Actuales = DashboardInnova::getActuales($request);
        $Metricas_Comparativas = DashboardInnova::getComparativas($request);


        $Metricas = [
            'ACTUAL'        => $Metricas_Actuales,
            'COMPARATIVA'   => $Metricas_Comparativas
        ];
        return response()->json($Metricas);
    }
    
}