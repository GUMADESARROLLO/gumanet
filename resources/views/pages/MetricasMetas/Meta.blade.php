@extends('layouts.ly_reorder')
@section('metodosjs')
@include('pages.MetricasMetas.js_metas')
@endsection
@section('content')
<style>
  span.btn-change-color {
    background-color: #28a745;
  }
</style>
<div class="container-fluid"> 
  <div class="row">
    <div class="col-sm-6">
      <p class="font-italic text-muted pt-0 mt-0">Metas para el mes de: <span id="tl_periodo"> - </span></p>	
    </div>
  </div>
  <div class="row">
    <div class="col">		      
      <div class="input-group"> 
        <input type="text" id="id_txt_buscar" class="form-control" aria-describedby="basic-addon1" placeholder="Buscar...">
          <div class="input-group-prepend">
            <span class="btn-change-color text-white input-group-text" id="BtnClick"><i data-feather="refresh-cw"></i></span>
          </div>
      </div>
    </div>
    <div class="col-sm-1">
      <div class="input-group">
        <select class="custom-select" id="InputCanales" name="InputCanales">
          <option value="5" selected>5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="100">100</option>
          <option value="-1">Todo</option>
        </select>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="row ">
        <div class="col mt-1">
          <div class="form-group">  
            <input type="text" class="input-fecha" id="f1">
          </div>
        </div>
        <div class="col mt-1 ">
          <div class="form-group">  
            <input type="text" class="input-fecha" id="f2">
          </div>
        </div>
        
      </div>
    </div>
      
  </div>

    <div class="card border-0 shadow-sm ">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="table-responsive flex-between-center scrollbar border border-1 border-300 rounded-2">
          
            <table id="table_metas" class="table table-bordered table-sm" width="100%">
             <thead>
                <tr>
                    <th>Descripcion</th>
                    <th>Facturado</th>
                    <th>Mes</th>
                    <th>Año</th>
                    <th>Estado</th>
                </tr>
             </thead>
            </table>
          </div>
        </div>
      </div>
    </div>    

</div>


@endsection('content')