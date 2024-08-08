@extends('layouts.main')
@section('metodosjs')
@include('jsViews.js_contribuciones')
@endsection
@section('content')
<div class="container-fluid"> 
  <!--<div style="padding:20px">
      <div class="d-flex align-items-center">
        <div id="id_Status" class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
      </div>
  </div>-->
  <div>
    <h5 id="tl_periodo"></h5>
  </div>
  <div class="card border-0 shadow-sm mt-3 ">
        <div class="card-body col-md-12 p-0 mb-2">
            <div class="row col-md-12 mb-3 mt-3" >
                <div class="input-group col-md-6 mt-4">
                    <input type="text" id="id_txt_buscar" class="form-control" aria-describedby="basic-addon1" placeholder="Buscar...">
                </div>
                <!--<div class="col-md-5 border-left">
                  <div class="row ">
                    <div class="col-sm-5">
                      <div class="form-group">                
                        <label for="f1">Desde:</label>
                        <input type="text" class="input-fecha" id="f1">
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div class="form-group">                
                        <label for="f2">Hasta:</label>
                        <input type="date" class="input-fecha" id="f2">
                      </div>
                    </div>
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="BtnClick" ><i data-feather="refresh-cw"></i></span>
                    </div>                    
                  </div>
                </div>-->
                
                <div class="col-md-1 mt-4">
                  <div class="input-group">
                    <select class="custom-select" id="InputCanales" name="InputCanales">
                      <option value="10" selected>10</option>
                      <option value="20">20</option>
                      <option value="100">100</option>
                      <option value="-1">Todo</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5 border-left">
                  <div class="row ">
                    <div class="col-sm-5 ">
                      <div class="form-group">                
                        <label for="f1">Desde:</label>
                        <input type="date" class="input-fecha" id="f1">
                      </div>
                    </div>
                    <div class="col-sm-5 ">
                      <div class="form-group">                
                        <label for="f2">Hasta:</label>
                        <input type="date" class="input-fecha" id="f2">
                      </div>
                    </div>
                    <div class="col-sm-2 input-group-prepend mt-4 mb-3">
                    <span class="input-group-text" id="BtnClick" ><i data-feather="refresh-cw"></i></span>
                  </div>
							</div>
						</div> 
            </div>	
        </div>
    </div>
    <div class="card border-0 shadow-sm ">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="table-responsive flex-between-center scrollbar border border-1 border-300 rounded-2">
          
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
                  <th colspan="6">TOTAL</th>
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
                    <th style="background-color:peru">CANTIDAD</th>
                    <th style="background-color:peru">PROMEDIO C$</th>
                    <th style="background-color:peru">VENTA C$</th>
                    <th style="background-color:peru">COSTO C$</th>
                    <th style="background-color:peru">CONTRIBUCION C$</th>
                    <th style="background-color:peru">MARGEN %</th>
                    <th style="background-color:burlywood">CANTIDAD</th>
                    <th style="background-color:burlywood">PROMEDIO C$</th>
                    <th style="background-color:burlywood">VENTA C$</th>
                    <th style="background-color:burlywood">COSTO C$</th>
                    <th style="background-color:burlywood">CONTRIBUCION C$</th>
                    <th style="background-color:burlywood">MARGEN %</th>
                    <th style="background-color:limegreen">CANTIDAD</th>
                    <th style="background-color:limegreen">PROMEDIO C$</th>
                    <th style="background-color:limegreen">VENTA C$</th>
                    <th style="background-color:limegreen">COSTO C$</th>
                    <th style="background-color:limegreen">CONTRIBUCION C$</th>
                    <th style="background-color:limegreen">MARGEN %</th>
                    <th style="background-color:cornflowerblue">CANTIDAD</th>
                    <th style="background-color:cornflowerblue">PROMEDIO C$</th>
                    <th style="background-color:cornflowerblue">VENTA C$</th>
                    <th style="background-color:cornflowerblue">COSTO C$</th>
                    <th style="background-color:cornflowerblue">CONTRIBUCION C$</th>
                    <th style="background-color:cornflowerblue">MARGEN %</th>
                    <th style="background-color:limegreen">CANTIDAD</th>
                    <th style="background-color:limegreen">PROMEDIO C$</th>
                    <th style="background-color:limegreen">VENTA C$</th>
                    <th style="background-color:limegreen">COSTO C$</th>
                    <th style="background-color:limegreen">CONTRIBUCION C$</th>
                    <th style="background-color:limegreen">MARGEN %</th>
                    <th style="background-color:burlywood">CANTIDAD</th>
                    <th style="background-color:burlywood">PROMEDIO C$</th>
                    <th style="background-color:burlywood">VENTA C$</th>
                    <th style="background-color:burlywood">COSTO C$</th>
                    <th style="background-color:burlywood">CONTRIBUCION C$</th>
                    <th style="background-color:burlywood">MARGEN %</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>

  





    
              

</div>


@endsection('content')