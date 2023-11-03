@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_reorder_point');
@endsection
@section('content')
<div class="container-fluid">
  <div class="row mb-5">
    <div class="col-md-10">
      <h4 class="h4">Reorder Point</h4>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-sm-11">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
        </div>
        <input type="text" id="InputDtShowSearchFilterArt" class="form-control" aria-describedby="basic-addon1">
      </div>
    </div>
    <div class="col-sm-1">
      <div class="input-group mb-3">
        <select class="custom-select" id="InputDtShowColumnsArtic" name="InputDtShowColumnsArtic">
          <option value="5" selected>5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="100">100</option>
          <option value="-1">Todo</option>
        </select>
      </div>
    </div>
    <div class="col-sm-2 p-0 m-0" style="display:none">
      <a id="exp-to-excel" href="#!" onclick="descargarArchivo('inventario')" class="btn btn-light btn-block text-success float-right"><i class="fas fa-file-excel"></i> Exportar</a>
    </div>      
  </div>
  <div class="row">
      <div class="col-12">
        <div class="table-responsive mt-3 mb-2">
              <table class="table table-bordered" width="100%" id="dtInvCompleto"></table>
          </div>
      </div>
  </div>
  <!--MODAL: DETALLE DE ARTICULO-->
<div class="modal fade modal-fullscreen" data-backdrop="static" data-keyboard="false" id="mdDetalleArt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header d-block">
      <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="navBodega" data-toggle="tab" href="#nav-bod" role="tab" aria-controls="nav-bod" aria-selected="true">COMPORTAMIENTO</a>
            <a class="nav-item nav-link" id="navPrecios" data-toggle="tab" href="#nav-prec" role="tab" aria-controls="nav-prec" aria-selected="false">Precios</a>
          </div>
      </div>
      <div class="modal-body">

        <div class="tab-content" id="nav-tabContent">

          <div class="tab-pane fade show active" id="nav-bod" role="tabpanel" aria-labelledby="navBodega">
            <div class="row">
                <div class="col-sm-12">
                  <div class="container-vms" id="grafVtsDiario" style="width: 100%; margin: 0 auto"></div>
                </div>
            </div>
          </div>
          
          <div class="tab-pane fade" id="nav-prec" role="tabpanel" aria-labelledby="navPrecios">
            <div class="row">
              <div class="col-sm-12">
                
              </div>
            </div>
          </div>
        </div>
        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>
@endsection