<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mific;

class MificController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }
    public function Mific() {
        return view('pages.Mific.mific');
    }
    public function getDataMific(Request $request)
    {
        $Mific_Actuales = Mific::getData($request);


        $Metricas = $Mific_Actuales;
        
        return response()->json($Metricas);
    }

    public function getDetallesMific(Request $request)
    {
        $ID_MIFIC = $request->input('Mific');
        $DetallesMific = Mific::where('ID_MIFIC', $ID_MIFIC)->first();

        return response()->json($DetallesMific);
    }
}

    