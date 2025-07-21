<?php

namespace App\Http\Controllers;
use App\ImportacionMuestra;
use Illuminate\Http\Request;


class ImportacionController extends Controller
{
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
        $ImportacionMuestra = ImportacionMuestra::All();
        return response()->json($ArticuloImportacion);
    }
}