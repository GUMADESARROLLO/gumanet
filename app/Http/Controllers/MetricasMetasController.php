<?php

namespace App\Http\Controllers;

use App\Models;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MetricasMetas;

class MetricasMetasController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('pages.MetricasMetas.Meta');
    }

    public function getMetricasMetas(Request $request)
    {
        $general = MetricasMetas::getDatageneral($request);


        $Metricas = [
            'General'        => $general
        ];
        return response()->json($Metricas);
    }
}