@extends('layouts.main')
@section('title' , "PEDIDOS - SOFTLAND")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.Facturacion.js_grafica')   
    @include('pages.Facturacion.js_facturacion')
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
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-umk2 text-white">
            <h6 class="mb-0">VENDEDORES</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="tbl_vendedores" class="table table-striped" width="100%"></table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-umk2 text-white">
            <h6 class="mb-0">PEDIDOS FACTURADOS</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="tbl_pedidos_facturados" class="table table-striped" width="100%"></table>
            </div>
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

    <style>
    #mdl-detalle-pedido-factura .modal-content {
      border: none;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 24px 60px rgba(0,0,0,.22);
    }
    #mdl-detalle-pedido-factura .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1px;
      background: #C4CDD8;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
    }
    #mdl-detalle-pedido-factura .info-cell {
      background: #fff;
      padding: 14px 16px;
    }
    #mdl-detalle-pedido-factura .info-cell label {
      display: block;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: #5E718A;
      margin-bottom: 4px;
    }
    #mdl-detalle-pedido-factura .info-cell .value {
      font-size: .92rem;
      font-weight: 600;
      color: #14243A;
    }
    #mdl-detalle-pedido-factura .info-cell .value.mono {
      font-family: 'Courier New', monospace;
      font-size: .85rem;
      color: #254D94;
    }
    #mdl-detalle-pedido-factura .pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: .75rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
    }
    #mdl-detalle-pedido-factura .pill.active   { background: #e6f9f2; color: #1DB87A; }
    #mdl-detalle-pedido-factura .pill.inactive { background: #fdecea; color: #E03C3C; }
    #mdl-detalle-pedido-factura .pill.warning  { background: #fff5e6; color: #F5A623; }
    </style>

    <div class="modal fade" id="mdl-detalle-pedido-factura" tabindex="-1" role="dialog" aria-labelledby="mdl-detalle-pedido-factura-title" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-umk2">
                    <h4 class="modal-title text-white" id="mdl-detalle-pedido-factura-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-grid">
                        <div class="info-cell full">
                            <label>Nombre comercial</label>
                            <div class="value" id="detalle_nombre"></div>
                        </div>
                        <div class="info-cell">
                            <label>FECHA PEDIDO</label>
                            <div class="value" id="fecha_pedidio"></div>
                        </div>
                        <div class="info-cell">
                            <label>FECHA FACTURA</label>
                            <div class="value" id="fecha_factura"></div>
                        </div>
                        <div class="info-cell full">
                            <label>TIEMPO PEDIDO A FACTURA</label>
                            <div class="mt-1">
                                <span class="pill active" id="detalle_tiempo">
                                    <i class="bi bi-check-circle-fill"></i> Al dia
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                                </div>
                                <input type="text" class="form-control form-control-sm input-fecha" placeholder="Buscar..." id="txt_busqueda_detalle_pedido" >
                            </div>
                        </div>
                    </div>
                   
                    <div class="table-responsive">
                        <table id="tbl-detalle-pedido-factura" class="table table-striped" width="100%">
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">TOTAL:</th>
                                    <th class="text-right"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Facturas por Vendedor -->
    <div class="modal fade" id="mdl-facturas-vendedor" tabindex="-1" role="dialog" aria-labelledby="mdl-facturas-vendedor-title" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-umk2">
                    <h4 class="modal-title text-white" id="mdl-facturas-vendedor-title"></h4>
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
