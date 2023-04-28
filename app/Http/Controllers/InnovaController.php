<?php

namespace App\Http\Controllers;

use App\InnovaKardex;
use App\InnovaModel;
use Illuminate\Http\Request;

class InnovaController extends Controller
{
    public function inventarioInnova(){
        $inventario = InnovaModel::get();
        return view('pages.inventarioINN', compact('inventario'));
    }

    public function getKerdex(Request $request){
        $ini = $request->ini;
        $end = $request->end;
       
        $kardex = InnovaKardex::getReporteKardex($request);
        return response()->json($kardex);
    }
}
