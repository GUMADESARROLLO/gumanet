<?php

namespace App\Http\Controllers;
use App\ImportacionMuestra;
use App\ImportacionView;
use Illuminate\Http\Request;


class ImportacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Home()
    {
        $ImportacionMuestra     = ImportacionMuestra::All();
        $data = [
            'name' =>  'GUMA@NET',
            'page' => 'Importacion'
        ];
        return view('pages.Importacion.Home', compact('data', 'ImportacionMuestra'));
    }
    public function getImportacion(Request $request)
    {
        $getImportacionView = ImportacionView::getImportacionView($request);
        return response()->json($getImportacionView);
    }
}