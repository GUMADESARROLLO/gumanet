<?php

namespace App\Http\Controllers;

use App\InnovaKardex;
use App\InnovaModel;
use Illuminate\Http\Request;

class InnovaController extends Controller
{
    public function inventarioInnova(){
        $inventario = InnovaModel::getAll();
        return view('pages.inventarioINN', compact('inventario'));
    }

    public function getKerdex(Request $request){
        $ini = $request->ini;
        $end = $request->end;
       
        $kardex = InnovaKardex::getReporteKardex($ini, $end);
        return response()->json($kardex);
    }

    public function getResumenKardex(){
        $resumen = InnovaKardex::getResumenKardex();
        return response()->json($resumen);
    }
}
