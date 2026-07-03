@extends('layouts.main')
@section('title' , "CLIENTES")
@section('name_user' , 'Administrador')
@section('metodosjs')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('pages.Clientes.js_Clientes')
    @include('pages.Clientes.css_clientes')
    @include('pages.Promociones.RifaCar.css_tickets')
    @include('pages.OrdenCompra.css_Ordenes')
@endsection

@section('content')

<!-- Tabla de Clientes -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-innova text-white">
        <div>
            <h6 class="mb-0 fw-bold">CLIENTES</h6>
            <small class="text-white-50">Lista de clientes registrados</small>
        </div>
    </div>
    <div class="px-3 py-2 border-bottom bg-light">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white"><i data-feather="search"></i></span>
            </div>
            <input type="text" class="form-control form-control-sm" placeholder="Buscar..." id="txtSearch">
        </div>
    </div>
    <div class="card-body p-0">
        <table id="dtClientes" class="table" style="width:100%">
            <tfoot>
                <tr class="fw-bold bg-light">
                    <th colspan="10" class="text-end">TOTAL:</th>
                    <th class="text-end" id="total_clientes_footer">0</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Modal Cliente -->
<div class="modal fade" id="ModalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header-custom">
                <div class="client-badge">
                    <span class="dot" id="detalle_dot"></span>
                    <span id="detalle_estado_pill">CLIENTE ACTIVO</span>
                </div>
                <h5><i class="bi bi-shop me-2"></i><span id="modal_cliente_nombre"></span></h5>
                <span class="sub">Codigo: <span id="modal_cliente_codigo"></span> &nbsp;·&nbsp; Vendedor: <span id="detalle_vendedor_header"></span></span>
                <button class="btn-close-custom" data-dismiss="modal" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- TABS -->
            <div class="nav-tabs-custom" role="tablist">
                <a class="tab-link active" data-target="#tab-general-pane" role="tab">General</a>
                <a class="tab-link" data-target="#tab-facturacion-pane" role="tab">Facturacion</a>

            </div>

            <!-- BODY -->
            <div class="modal-body-custom">
                <div class="tab-content">
                    <!-- GENERAL -->
                    <div class="tab-pane fade show active" id="tab-general-pane">
                        <div class="info-grid">
                            <div class="info-cell full">
                                <label>Direccion</label>
                                <div class="value" id="detalle_direccion"></div>
                            </div>
                            <div class="info-cell">
                                <label>Telefono 1</label>
                                <div class="value" id="detalle_telefono1"></div>
                            </div>
                            <div class="info-cell">
                                <label>Telefono 2</label>
                                <div class="value" id="detalle_telefono2"></div>
                            </div>
                            <div class="info-cell">
                                <label>Condicion Pago</label>
                                <div class="value" id="detalle_condicion_pago"></div>
                            </div>
                            <div class="info-cell">
                                <label>Fecha Ingreso</label>
                                <div class="value" id="detalle_fecha_ingreso"></div>
                            </div>
                            <div class="info-cell">
                                <label>Nivel Precio</label>
                                <div class="value" id="detalle_nivel_precio"></div>
                            </div>
                            <div class="info-cell">
                                <label>Moroso</label>
                                <div class="value"><span class="pill" id="detalle_moroso">-</span></div>
                            </div>
                        </div>

                        <div class="status-strip">
                            <div class="status-card">
                                <div class="s-label">
                                    <i class="bi bi-credit-card" style="color:var(--brand-mid)"></i>
                                    Limite de credito
                                </div>
                                <div class="s-value blue" id="detalle_limite">C$ 0.00</div>
                            </div>
                            <div class="status-card">
                                <div class="s-label">
                                    <i class="bi bi-receipt" style="color:var(--warning)"></i>
                                    Saldo actual
                                </div>
                                <div class="s-value" id="detalle_saldo">C$ 0.00</div>
                            </div>
                            <div class="status-card">
                                <div class="s-label">
                                    <i class="bi bi-graph-up-arrow" style="color:var(--success)"></i>
                                    Disponible
                                </div>
                                <div class="s-value green" id="detalle_disponible">C$ 0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- FACTURACION -->
                    <div class="tab-pane fade" id="tab-facturacion-pane">
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white input-fecha"><i data-feather="search"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm input-fecha" placeholder="Buscar..." id="txtSearchFactura">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control form-control-sm input-fecha" name="dt_range_fact" placeholder="Rango de fechas..." />
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary-umk btn-sm btn-block input-fecha" id="filtrarFacturacion">
                                    <i class="bi bi-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body d-flex align-items-center py-2">
                                        <div class="rounded-circle bg-light-primary p-2 me-2">
                                            <i class="bi bi-file-text text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted text-uppercase fw-semibold">Facturas</div>
                                            <div class="h5 mb-0 fw-bold" id="ind_facturas">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body d-flex align-items-center py-2">
                                        <div class="rounded-circle bg-light-success p-2 me-2">
                                            <i class="bi bi-cash text-success"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted text-uppercase fw-semibold">Pagos</div>
                                            <div class="h5 mb-0 fw-bold" id="ind_pagos">C$ 0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body d-flex align-items-center py-2">
                                        <div class="rounded-circle bg-light-warning p-2 me-2">
                                            <i class="bi bi-credit-card-2-back text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted text-uppercase fw-semibold">Saldo Pendiente</div>
                                            <div class="h5 mb-0 fw-bold" id="ind_saldo">C$ 0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-sm table-bordered table-striped" id="dtFacturacion" style="width:100%">
                            <thead class="bg-secondary text-light">
                                <tr>
                                    <th>FACTURA</th>
                                    <th>FECHA</th>
                                    <th>VENDEDOR</th>
                                    <th>NOMBRE VENDEDOR</th>
                                    <th>MONTO FACTURA</th>
									<th>PAGOS</th>
									<th>MONTO PENDIENTE</th>
                                    <th style="width:100px">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Seleccione un rango de fechas y presione Filtrar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer-custom">
                <button class="btn-outline-brand" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

@endsection
