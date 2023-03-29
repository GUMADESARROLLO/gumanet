<?php

namespace App\Http\Controllers;

use App\InnovaModel;
use Illuminate\Http\Request;

class InnovaController extends Controller
{
    public function inventarioInnova(){
        $inventario = InnovaModel::getAll();
        return view('pages.inventarioINN', compact('inventario'));
    }
}
