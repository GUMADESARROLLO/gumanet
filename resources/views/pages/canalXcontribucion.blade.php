@extends('layouts.main')
@section('metodosjs')
@include('jsViews.js_contribuciones')
@endsection
@section('content')
<div class="container-fluid"> 
  <div style="padding:20px">
      <div class="d-flex align-items-center">
      <h4 class="h4">CONTRIBUCION POR CANAL</h4>
        <div id="id_Status" class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
      </div>
  </div>
  
  <div class="card border-0 shadow-sm mt-3 ">
        <div class="card-body col-sm-12 p-0 mb-2">
            <div class="row col-md-12 mb-3 mt-3" >
                <span id="id_form_role" style="display:none">{{ Session::get('user_role') }}</span>                        
                <div class="input-group col-md-9">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                    </div>								
                    <input type="text" id="id_txt_buscar" class="form-control" placeholder="Buscar...">
                </div>
                <!--<div class="col-md-3 border-left">
                    <div class="input-group">
                        <select class="custom-select"  id="id_select_mes">
                            
                                <option value="15" >2 SEMANAS</option>
                                <option value="1" >1 MES</option>
                                <option value="3" >3 MESES</option>
                                <option value="6" >6 MESES</option>
                                <option value="12" >12 MESES</option>
                            
                        </select>
                        <div class="btn input-group-text bg-transparent" id="id_btn_new">
                            <span class="fas fa-history fs--1 text-600"></span>
                        </div>
                    </div>
                </div>-->
            </div>	
            <div class="p-0 px-car">
            <div class="flex-between-center responsive mb-3" id="kardex">
                    
                
            </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm ">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="flex-between-center scrollbar border border-1 border-300 rounded-2">
          
            <table id="table_contribucion" class="table nowrap table-bordered" width="100%">
              <thead>
                <tr class="bg-blue text-light">
                  <th style="width: 400px;" colspan="3">SKU</th>
                  <th colspan="6">FARMACIA</th>
                  <th colspan="6">CADENA FARMACIA</th>
                  <th colspan="6">MAYORISTA</th>
                  <th colspan="6">INSTITUCION PRIVADA</th>
                  <th colspan="6">CRUZ AZUL</th>
                  <th colspan="6">INSTITUCION PUBLICA</th>
                </tr>
                <tr>
                    <th class="bg-blue text-light">ARTICULO</th>
                    <th class="bg-blue text-light">DESCRIPCION</th>
                    <th class="bg-blue text-light">FABRICANTE</th>
                    <th class="bg-warning text-black">CANTIDAD</th>
                    <th class="bg-warning text-black">PROMEDIO C$</th>
                    <th class="bg-warning text-black">VENTA C$</th>
                    <th class="bg-warning text-black">COSTO C$</th>
                    <th class="bg-warning text-black">CONTRIBUCION C$</th>
                    <th class="bg-warning text-black">MARGEN %</th>
                    <th>CANTIDAD</th>
                    <th>PROMEDIO C$</th>
                    <th>VENTA C$</th>
                    <th>COSTO C$</th>
                    <th>CONTRIBUCION C$</th>
                    <th>MARGEN %</th>
                    <th>CANTIDAD</th>
                    <th>PROMEDIO C$</th>
                    <th>VENTA C$</th>
                    <th>COSTO C$</th>
                    <th>CONTRIBUCION C$</th>
                    <th>MARGEN %</th>
                    <th>CANTIDAD</th>
                    <th>PROMEDIO C$</th>
                    <th>VENTA C$</th>
                    <th>COSTO C$</th>
                    <th>CONTRIBUCION C$</th>
                    <th>MARGEN %</th>
                    <th>CANTIDAD</th>
                    <th>PROMEDIO C$</th>
                    <th>VENTA C$</th>
                    <th>COSTO C$</th>
                    <th>CONTRIBUCION C$</th>
                    <th>MARGEN %</th>
                    <th>CANTIDAD</th>
                    <th>PROMEDIO C$</th>
                    <th>VENTA C$</th>
                    <th>COSTO C$</th>
                    <th>CONTRIBUCION C$</th>
                    <th>MARGEN %</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>

  





    
              

</div>


@endsection('content')