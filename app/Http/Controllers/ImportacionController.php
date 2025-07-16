<?php

namespace App\Http\Controllers;
use App\ArticuloImportacion;
use Illuminate\Http\Request;


class ImportacionController extends Controller
{
    public function Home()
    {
        $ArticuloImportacion     = ArticuloImportacion::All();
        $data = [
            'name' =>  'GUMA@NET',
            'page' => 'Importacion'
        ];
        return view('pages.Importacion.Home', compact('data', 'ArticuloImportacion'));
    }
    public function getImportacion(Request $request)
    {
        $ArticuloImportacion = ArticuloImportacion::All();
        return response()->json($ArticuloImportacion);
    }
}