<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Budget;


class BudgetController extends Controller {
    public function __construct() {
		$this->middleware(['auth','roles']);
    }
    public function Budget89(){
      
      $data = array(
        'page'              => 'Inventario',
        'name'              => 'GUMA@NET',
        'hideTransaccion'   => ''
      );
      return view('pages.Budgets.Budget89',$data);
        
    }
    public function Budget71(){
      
      $data = array(
        'page'              => 'Inventario',
        'name'              => 'GUMA@NET',
        'hideTransaccion'   => ''
      );
      return view('pages.Budgets.Presupuesto',$data);
        
    }
    public function dtProyect(Request $request) {

        $result = Budget::dtProyectClientesFact($request);
        
        return response()->json($result);
      
      
    }

    public function getFacturasClientesUmk(Request $request) {

        $result = Budget::getFacturasClientesUmk($request);
        
        return response()->json($result);
      
      
    }

    public function dtArticulo(Request $request) {
      $obj = Budget::dtArticulo($request);
      return response()->json($obj);
    }
}