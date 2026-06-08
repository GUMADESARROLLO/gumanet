@extends('layouts.main')
@section('title' , "PROMOCIONES - RIFA DE CARRO")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.Promociones.RifaCar.js_Promociones')
    @include('pages.Promociones.RifaCar.css_tickets')
    @include('pages.OrdenCompra.css_Ordenes')
@endsection

@section('content')

    
    @if(Auth::user()->email !== 'promociones@gmail.com')
    <div class="mb-2">
        <a href="{{ route('MetricasRifa') }}" target="_blank" class="text-decoration-none small fw-semibold text-umk">
            <i class="fas fa-chart-bar me-1"></i>Metricas
        </a>
    </div>
    @endif

    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <h4 class="h4 text-umk mb-1">
                        FACTURAS EMITIDAS
                    </h4>
                    <p class="text-muted mb-0 small">Periodo: <span id="tl_periodo" class="fw-semibold text-umk"></span></p>
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


    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 card-clientes" role="button" style="cursor:pointer">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light-primary p-3 me-3">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase fw-semibold">Clientes Fact.</div>
                        <div class="h4 mb-0 fw-bold" id="TOTAL_CLIENTES">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light-success p-3 me-3">
                        <i class="fas fa-file-invoice text-success"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase fw-semibold">Total Fact. Emitidas</div>
                        <div class="h4 mb-0 fw-bold" id="TOTAL_FACTURAS">0</div>
                        <small class="text-muted" id="TOTAL_SIN_ACCIONES"></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light-info p-3 me-3">
                        <i class="fas fa-ticket-alt text-info"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase fw-semibold">Acciones Fact.</div>
                        <div class="h4 mb-0 fw-bold" id="TOTAL_ACCIONES">0</div>
                        <small class="text-muted" id="acciones-asignadas"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light-warning p-3 me-3">
                        <i class="fas fa-hashtag text-warning"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase fw-semibold">Última Acción</div>
                        <div class="h4 mb-0 fw-bold" id="ULTIMA_ACCION">0</div>
                        <small class="text-muted" id="porcentaje-disponible"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Tabla de Facturas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-innova text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">FACTURAS</h6>
                    <small class="text-white-50">Lista de facturas generadas</small>
                </div>
            </div>
        </div>
        <div id="selection-toolbar" class="d-none align-items-center justify-content-between px-3 py-2 border-bottom bg-light">
            <span class="small text-muted">
                <span id="selected-count">0</span> factura(s) seleccionada(s)
            </span>
            <button type="button" id="btn-aplicar-masivo" class="btn btn-success btn-sm" disabled>
                <i class="fas fa-check-double me-1"></i>Generar Acciones
            </button>
        </div>
        <div class="card-body p-0">
                <table id="tbl_ordenes_compras" class="table" style="width:100%">
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <th colspan="7" class="text-end">TOTAL:</th>
                        <th class="text-end" id="total_ordenes">C$. 0.00</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>



    <!-- Modal Acciones -->
    <div class="modal fade" id="ModalAcciones" tabindex="-1" aria-labelledby="modalApartadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-innova text-white border-bottom-0">
                    <h5 class="modal-title fw-bold" id="modalApartadoLabel">
                        <i class="fas fa-ticket-alt me-2"></i>Acciones Participantes
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-semibold">Factura</small>
                        <div class="fw-bold fs-5" id="lbl_factura"></div>
                    </div>

                    

                    <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold" id="lbl_nombre_cliente"></div>
                            <small class="text-muted" id="lbl_codigo_cliente"></small>
                        </div>
                    </div>

                    <div class="border rounded-3 p-3 mb-3 min-vh-10" id="tbl_factura_acciones"></div>

                    <div class="d-flex gap-2">
                        <button type="button" id="btn_imprimir_acciones" class="btn btn-success flex-fill">
                            <i class="fas fa-print me-2"></i>Imprimir
                        </button>
                        <button type="button" id="btn_revertir_acciones" class="btn btn-danger flex-fill">
                            <i class="fas fa-undo me-2"></i>Revertir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Clientes -->
    <div class="modal fade" id="ModalClientes" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-innova text-white">
                    <div>
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-users me-2"></i>Clientes con Acciones
                        </h5>
                        <small class="text-white-50">Periodo: <span id="tl_periodo_modal"></span></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                    

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm input-fecha" placeholder="Buscar..." id="buscar-cliente" >
                        </div>
                        
                    </div>
                    <table id="tbl_clientes" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CLIENTE</th>
                                <th>NOMBRE</th>
                                <th>VENDEDOR</th>
                                <th>NOMBRE</th>
                                <th>CATEGORIA</th>
                                <th class="text-center">ACCIONES FACT.</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="fw-bold bg-light">
                                <th colspan="6" class="text-end">TOTAL:</th>
                                <th class="text-center" id="total-acciones-clientes">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
