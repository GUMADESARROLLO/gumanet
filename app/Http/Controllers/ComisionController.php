<?php

namespace App\Http\Controllers;

use App\Comision;
use Illuminate\Http\Request;

class ComisionController extends Controller
{
    public function index()
    {  
        return view('pages.Comiciones');
    }

    public function getDataComiciones(Request $request)
    {  
        $Mes   = $request->input('mes');
        $Anno   = $request->input('anno');

        $Comision = Comision::getData($Mes,$Anno);
        //return response()->json($Comision);
        return response()->json($Comision);
    }
}
