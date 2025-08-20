<?php

namespace App\Http\Controllers;
use App\AnalisisIMS;
use Illuminate\Http\Request;

class AnalisisIMSController extends Controller 
{ 
    public function __construct() {
        $this->middleware('auth');
    }

    public function ViewHome() 
    { 
        $NamePath = 'GUMANET | ANALISIS IMS';
        return view('pages.AnalisisIMS.Home', compact('NamePath'));
    }

    public function getDataAnalisisIMS(Request $request) 
    {

        $data   = AnalisisIMS::getAnalisisIMS($request);
        $Origin = AnalisisIMS::getAnalisisIMSOrigin($request);

        $Dts = [
            'AnalisisIMS'   => $data,
            'Origin'        => $Origin,
        ];
        return response()->json($Dts);
    }


}