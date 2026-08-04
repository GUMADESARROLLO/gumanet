@extends('layouts.main')
@section('title' , "PEDIDOS - SOFTLAND")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.Facturacion.js_grafica')   
    @include('pages.Facturacion.js_facturacion')
    @include('pages.Facturacion.css_tablas_facturacion')
    @include('pages.Budgets.css_presupuesto')
@endsection

@section('content')

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <h4 class="h4 text-umk mb-1">
                        UNIMARK
                    </h4>
                    <p class="text-muted mb-0 small">Reportes de ventas, tomando en cuenta el periodo de <span id="tl_periodo" class="fw-semibold text-umk"></span></p>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                        </div>
                        <input type="text" class="form-control form-control-sm input-fecha" placeholder="Buscar..." id="txt_busqueda_orden_compra" >
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm input-fecha" name="dt_range" />
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-primary-umk btn-sm input-fecha" id="filtrarFechas">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Charts -->
    <div class="row g-4 mb-4">    
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-umk2 text-white">
            <h6 class="mb-0">GRAFICO DE PEDIDOS</h6>
          </div>
          <div class="card-body">
            <div id="chart_pedidos_dia"></div>
          </div>
        </div>
      </div>    
    </div>

    <!-- Tables -->
    <div class="row g-4 mb-4">
      <div class="col-lg-4">
        <div class="panel-fact">
          <div class="panel-header">
            <span>VENDEDORES</span>
            <span class="count" id="count_vendedores"></span>
          </div>
          <div class="table-responsive-wrap">
            <table id="tbl_vendedores" width="100%"></table>
          </div>
          <div class="panel-footer">
            <span id="info_vendedores">Mostrando 0 registros</span>
            <div class="paginacion-custom" id="pag_vendedores"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="panel-fact">
          <div class="panel-header">
            <span>PEDIDOS FACTURADOS</span>
            <span class="count" id="count_pedidos"></span>
          </div>
          <div class="table-responsive-wrap">
            <table id="tbl_pedidos_facturados" width="100%"></table>
          </div>
          <div class="panel-footer">
            <span id="info_pedidos">Mostrando 0 registros</span>
            <div class="paginacion-custom" id="pag_pedidos"></div>
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

    @include('pages.Facturacion.css_modal_pedido')

    <div class="modal fade" id="mdl-detalle-pedido-factura" tabindex="-1" role="dialog" aria-labelledby="mdl-detalle-pedido-factura-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <small id="detalle_tipo">Pedido</small>
                        <span id="mdl-detalle-pedido-factura-title"></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn-close-custom" data-dismiss="modal" aria-label="Cerrar">&times;</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="info-cliente">
                        <div class="label">Nombre comercial</div>
                        <div class="valor" id="detalle_nombre"></div>
                    </div>

                    <div class="info-grid">
                        <div class="info-card">
                            <div class="label">Fecha pedido</div>
                            <div class="valor" id="fecha_pedidio"></div>
                        </div>
                        <div class="info-card">
                            <div class="label">Fecha factura</div>
                            <div class="valor" id="fecha_factura"></div>
                        </div>
                        <div class="info-card">
                            <div class="label">Tiempo pedido a factura</div>
                            <span class="badge-tiempo pill active" id="detalle_tiempo">
                                <i class="bi bi-check-circle-fill"></i> Al dia
                            </span>
                        </div>
                    </div>

                    <div class="buscador-wrap">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="search"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm input-fecha" placeholder="Buscar articulo..." id="txt_busqueda_detalle_pedido">
                        </div>
                    </div>

                    <div class="tabla-wrap">
                        <table id="tbl-detalle-pedido-factura" class="table tabla-articulos" width="100%">
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">TOTAL:</th>
                                    <th class="text-right"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="total-bar">
                            <span class="label">Total</span>
                            <span class="valor" id="detalle_total">C$ 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <span id="detalle_info_pagina">Mostrando 0 registros</span>
                    <div class="d-flex align-items-center">
                        <a href="#" class="text-decoration-none text-muted mr-2" id="btn_detalle_anterior">Anterior</a>
                        <span class="pagina-actual" id="detalle_pagina_actual">1</span>
                        <a href="#" class="text-decoration-none text-muted ml-2" id="btn_detalle_siguiente">Siguiente</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Facturas por Vendedor -->
    <div class="modal fade" id="mdl-facturas-vendedor" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-umk2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                                </div>
                                <input type="text" class="form-control form-control-sm input-fecha" placeholder="Buscar..." id="txt_busqueda_facturas_vendedor" >
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tbl-facturas-vendedor" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>FACTURA</th>
                                    <th>FECHA</th>
                                    <th>COD. CLIENTE</th>
                                    <th>NOMBRE CLIENTE</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">TOTAL:</th>
                                    <th class="text-right"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
