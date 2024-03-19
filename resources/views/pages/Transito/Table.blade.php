@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_InventarioTransito');
@endsection
@section('content')
<div class="container-fluid">
  <div class="row mb-5">
    <div class="col-md-10">
      <h4 class="h4">Inventario Transito</h4>
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
<div class="modal fade bd-example-modal-xl" data-backdrop="static" data-keyboard="false" id="mdDetalleArt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header d-block">
        <h5 class="modal-title text-center" id="tArticulo"></h5>
      </div>
      <div class="modal-body">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="navBodega" data-toggle="tab" href="#nav-bod" role="tab" aria-controls="nav-bod" aria-selected="true">Informacion</a>
        </nav>
        <div class="tab-content" id="nav-tabContent">

          <div class="tab-pane fade show active" id="nav-bod" role="tabpanel" aria-labelledby="navBodega">
            <div class="row">
              <div class="col-sm-12" >
                
                <div class="card" style="border-top: none">
                  <div class="card-body">
                    <div class="col-sm-12 mt-3">
                        <div class="row" >
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fci">FECHA ESTIMADA:</label>
                                <input type="text" class="input-fecha" id="">
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            </div>

                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fcf">FECHA PEDIDO:</label>
                                <input type="text" class="input-fecha" id="">
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            </div>
                        
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">DOCUMENTO:</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            </div>

                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">CANTIDAD:</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            </div>
                        
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">MERCADO:</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option>N/D</option>
                                    <option>PRIVADOR</option>
                                    <option>INSTITUCION</option>
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            </div>

                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">MIFIC:</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option>N/D</option>
                                    <option>SI</option>
                                    <option>NO</option>
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="validationTextarea">OBSERVACIONES: </label>
                                <textarea class="form-control" id="validationTextarea" placeholder="Required example textarea" required></textarea>
                                <small id="emailHelp" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer.</small>
                            </div>
                            

                        </div>
                    </div>

                    </div>
                </div>
              </div>
            </div>
          </div>
          


        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-success btn-sm" id="btnSaveTransito">Guardar</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>
@endsection