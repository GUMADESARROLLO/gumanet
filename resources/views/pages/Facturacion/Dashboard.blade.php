@extends('layouts.main')
@section('title' , "PEDIDOS - SOFTLAND")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.Facturacion.js_grafica')   
    @include('pages.Facturacion.js_facturacion')
    @include('pages.DashboardINN.css_dasboard')
@endsection

@section('content')

    <!-- Header -->
    <div class="row border">
      <div class="col-md-9">            
        <h4 class="h4 text-innova"> INNOVA INDUSTRIAS S.A.</h4>
        <p class="text-muted mb-4">Reportes de ventas de productos, tomando en cuenta el periodo de <span id="tl_periodo"></span>.</p>
      </div>     
      <div class="col-md-2 ">        
        <div class="form-group">                
          <label for="f1">Fecha Evaluacion</label>
          <input type="text" class="input-fecha" name="dt_range" />
        </div>
      </div>

      <div class="col-md-1 mt-4">
        <div class="btn-group w-100">               
          <button type="button" class="btn btn-primary-umk btn-block float-right" id="filtrarFechas">  Filtrar </button>		
        </div>      
      </div>
     
    </div>

    
    

    <!-- Charts -->
    <div class="row g-4 mb-4">    
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-innova text-white">
            <h6 class="mb-0">GRAFICO DE CLIENTES</h6>
          </div>
          <div class="card-body">
            <div id="chart_pedidos_dia"></div>
          </div>
        </div>
      </div>    
    </div>


   
    </div>


    <div class="modal fade" id="mdl-topsku" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h4 class="modal-title text-umk" id="id-name-articulo">  </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-sm-11">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"> <i class="fas fa-search"></i> </span>
                                </div>
                                <input type="text" id="id_search_importaciones" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                        
                        <div class="col-sm-1">
                            <a id="exp-to-excel" href="#!" class="btn btn-success btn-block text-light float-right button_export_excel"><i class="fas fa-file-excel"></i> </a>
                        </div>      
                    </div>

                    <div class="table-responsive">
                        <table id="tbl_topsku_clientes" class="table table-striped " width="100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
