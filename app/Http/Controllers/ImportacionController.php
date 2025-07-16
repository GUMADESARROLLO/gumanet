<?php

namespace App\Http\Controllers;
use App\ArticuloImportacion;


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
}