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
                <div class="d-flex align-items-center gap-2">
                    <span id="badge-pendientes" class="badge badge-pill bg-danger p-2 blink-red" style="display:none">Pendientes 0</span>
                    @if(Auth::user()->role != 7)
                    <button type="button" class="btn btn-outline-light btn-sm" id="btn_abrir_vencidas" title="Facturas vencidas">
                        <i class="fas fa-hourglass-end me-1"></i> <span id="btn_vencidas_factura">Vencidas</span>
                    </button>
                    <button type="button" class="btn btn-outline-light btn-sm" id="btn_abrir_anular" title="Anular factura">
                        <i class="fas fa-ban me-1"></i> <span id="btn_anular_factura">Anular</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        <style>
            .blink-red {
                animation: blink-animation 1s ease-in-out infinite;
            }
            @keyframes blink-animation {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.3; }
            }
        </style>
        @if(Auth::user()->role != 14)
        <div id="selection-toolbar" class="d-none align-items-center justify-content-between px-3 py-2 border-bottom bg-light">
            <span class="small text-muted">
                <span id="selected-count">0</span> factura(s) seleccionada(s)
            </span>
            <button type="button" id="btn-aplicar-masivo" class="btn btn-success btn-sm" disabled>
                <i class="fas fa-check-double me-1"></i>Generar Acciones
            </button>
        </div>
        @endif
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
                        @if(Auth::user()->role != 7)
                        <button type="button" id="btn_revertir_acciones" class="btn btn-danger flex-fill">
                            <i class="fas fa-undo me-2"></i>Revertir
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Clientes -->
    <div class="modal fade" id="ModalClientes" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-innova text-white">
                    <div>
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-users me-2"></i>Clientes con Acciones
                        </h5>
                        <small class="text-white-50">Periodo: <span id="tl_periodo_modal"></span></small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                    </div>
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
                                <th>NOMBRE VENDEDOR</th>
                                <th>CATEGORIA</th>
                                <th class="text-center">ACCIONES FACT.</th>
                                <th>QR</th>
                                <th>REPORTE</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="fw-bold bg-light">
                                <th colspan="6" class="text-end">TOTAL:</th>
                                <th class="text-center" id="total-acciones-clientes">0</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Anular Factura -->
    <div class="modal fade" id="ModalAnularFactura" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-bottom-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-ban me-2"></i>Anular Factura
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="input-factura-anular">
                    <div id="anular-step1">
                       
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="search"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm" placeholder="Buscar factura o cliente..." id="buscar-factura-anular" autocomplete="off">
                        </div>
                        <table id="tbl_facturas_anular" class="table table-hover table-sm" style="width:100%"></table>
                    </div>
                    <div id="anular-step2" style="display:none">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Factura</small>
                                    <strong id="anl-factura"></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Fecha</small>
                                    <strong id="anl-fecha"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 p-2 bg-light rounded">
                            <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Cliente</small>
                            <strong id="anl-cliente"></strong>
                            <small class="text-muted d-block" id="anl-nombre"></small>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Vendedor</small>
                                    <strong id="anl-vendedor"></strong>
                                    <small class="text-muted d-block" id="anl-nombre-vendedor"></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Acciones</small>
                                    <strong id="anl-acciones"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Total Factura</small>
                                    <strong id="anl-total"></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Estado</small>
                                    <span class="badge bg-danger">ANULADA</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Justificación <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="input-justificacion" rows="3" placeholder="Motivo de la anulación..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" id="btn-cancelar-anular">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                            <button type="button" class="btn btn-danger flex-fill" id="btn-confirmar-anular">
                                <i class="fas fa-check me-1"></i>Confirmar
                            </button>
                        </div>
                    </div>
                    <div id="anular-step-error" style="display:none">
                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                            <h6 class="text-danger fw-bold" id="anl-error-msg"></h6>
                        </div>
                        <button type="button" class="btn btn-secondary w-100" id="btn-cerrar-error">
                            <i class="fas fa-arrow-left me-1"></i>Volver al listado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Facturas Vencidas -->
    <div class="modal fade" id="ModalFacturasVencidas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark border-bottom-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-hourglass-end me-2"></i>Facturas Vencidas
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="input-factura-vencida">
                    <div id="vencida-step1">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="search"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm" placeholder="Buscar factura o cliente..." id="buscar-factura-vencida" autocomplete="off">
                        </div>
                        <table id="tbl_facturas_vencidas" class="table table-hover table-sm" style="width:100%"></table>
                    </div>
                    <div id="vencida-step2" style="display:none">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Factura</small>
                                    <strong id="vnc-factura"></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Fecha</small>
                                    <strong id="vnc-fecha"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 p-2 bg-light rounded">
                            <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Cliente</small>
                            <strong id="vnc-cliente"></strong>
                            <small class="text-muted d-block" id="vnc-nombre"></small>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Vendedor</small>
                                    <strong id="vnc-vendedor"></strong>
                                    <small class="text-muted d-block" id="vnc-nombre-vendedor"></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Acciones</small>
                                    <strong id="vnc-acciones"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Total Factura</small>
                                    <strong id="vnc-total"></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Días Vencidos</small>
                                    <strong id="vnc-dvencidos" class="text-danger"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 p-2 bg-light rounded">
                            <small class="text-muted d-block text-uppercase" style="font-size:0.65rem">Estado</small>
                            <span class="badge bg-warning text-dark">VENCIDA</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Justificación <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="input-justificacion-vencida" rows="3" placeholder="Motivo de la reversión..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" id="btn-cancelar-vencida">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </button>
                            <button type="button" class="btn btn-warning flex-fill" id="btn-confirmar-vencida">
                                <i class="fas fa-check me-1"></i>Confirmar
                            </button>
                        </div>
                    </div>
                    <div id="vencida-step-error" style="display:none">
                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                            <h6 class="text-danger fw-bold" id="vnc-error-msg"></h6>
                        </div>
                        <button type="button" class="btn btn-secondary w-100" id="btn-cerrar-error-vencida">
                            <i class="fas fa-arrow-left me-1"></i>Volver al listado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
