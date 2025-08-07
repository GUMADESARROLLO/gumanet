<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ReOrderPoint;
use App\ReOrderPointR3;
use App\ReOrderPointBase;
use App\ContribucionPorCanales;
use Illuminate\Support\Facades\Session;


class ReOrderPointController extends Controller
{
    public function ReOrderPoint()
    {  
        $data = array(
            'page'		=> 'Reorder Point',
            'name'		=> 'GUMA@NET',
            'hideTransaccion' => ''
        );
        return view('pages.ReOrderPoint.Home', $data);
    }

    public function getData() {
		$obj = ReOrderPoint::getArticulo();
		return response()->json($obj);
    }
    public function getDataGrafica($Articulos,$Canal) {
        $obj = ReOrderPoint::getDataGrafica($Articulos,$Canal);
        return response()->json($obj);
    }
    public function CalcReorder() {
        
        $isSesion = Session::isStarted();
        
        if ($isSesion) {
            $obj = ReOrderPoint::CalcReorder();
            return response()->json([
                'Titulo' => 'Reorder Point.',
                'Mensaje' => 'Calculos completados' 
            ],200);
        }else{
            return response()->json(['error' => 'La Sesion ha expirado.'],404);
        }
    }
    public function ExportToExcel() {
        $obj = ReOrderPoint::ExportToExcel();
        return $obj;
    }
    public function ExportToExcelCanales() {
        $obj = ContribucionPorCanales::ExportToExcel();
        return $obj;
    }

    public function ReorderPointView() {
    
        return view('pages.ReOrderPoint.ReleaseR3',);
    }

    public function getReorderPoint(Request $request) {
        $ReOrder = ReOrderPointR3::getReorderPoint($request);
        $Records = ReOrderPointBase::getRecords($request);

        $Data= [
            'ReOrder' => $ReOrder,
            'Records' => $Records,
        ];
        return response()->json($Data);
    }
    public function getCalcular(Request $request) {
        $ReOrder = ReOrderPointR3::Calcular();
        return response()->json([
            'Titulo' => 'Reorder Point.',
            'Mensaje' => 'Calculos completados' 
        ],200);
    }



    
}
