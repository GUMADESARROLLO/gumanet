@extends('layouts.main')

@section('title', 'Historial del Articulo')
@section('name_user', 'Administrador')
@section('metodosjs')
  @include('jsViews.js_historico_articulos')
@endsection

@section('content')

<link rel="stylesheet" href="{{ url('css/historico_articulos.css') }}">

<div class="container-fluid ha-body" style="min-height: calc(100vh - 120px);">

  <!-- PRODUCT SUMMARY -->
  <div class="ha-card overflow-hidden mb-4">
    <div class="row no-gutters">
      <div class="col-md-3 ha-product-photo-area d-flex align-items-center justify-content-center p-4">
        <img src="" id="ha-product-img" class="img-fluid" style="max-width:130px;display:none" alt="Producto" />
        <svg viewBox="0 0 160 160" class="img-fluid" id="ha-product-svg" style="max-width:130px" aria-label="Producto">
          <ellipse cx="80" cy="140" rx="34" ry="6" fill="#13221D" opacity="0.08"/>
          <rect x="52" y="46" width="56" height="86" rx="10" fill="#FFFFFF" stroke="#DBDFD6" stroke-width="2"/>
          <rect x="52" y="46" width="56" height="86" rx="10" fill="none" stroke="{{ $Style['Color'] }}" stroke-width="1" opacity="0.2"/>
          <rect x="58" y="70" width="44" height="54" rx="3" fill="#DDEAE8"/>
          <text x="80" y="90" font-family="monospace" font-size="7" fill="{{ $Style['Color'] }}" text-anchor="middle" font-weight="600">MED</text>
          <text x="80" y="99" font-family="monospace" font-size="6" fill="{{ $Style['Color'] }}" text-anchor="middle">RX</text>
          <line x1="63" y1="106" x2="97" y2="106" stroke="{{ $Style['Color'] }}" stroke-width="1" opacity="0.4"/>
          <text x="80" y="115" font-family="monospace" font-size="5.5" fill="#4B5A54" text-anchor="middle">RX ONLY</text>
          <rect x="46" y="32" width="68" height="18" rx="4" fill="{{ $Style['Color'] }}"/>
          <rect x="50" y="24" width="60" height="12" rx="3" fill="#5C8B5A"/>
          <circle cx="80" cy="30" r="2" fill="#FFFFFF" opacity="0.6"/>
        </svg>
      </div>
      <div class="col-md-9 p-4 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
          <div>
            <small class="text-uppercase d-block mb-1" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)" id="ha-sku-label">SKU-{{ $data['articulo'] }} · Medicamento</small>
            <h1 class="h3 mb-1 font-weight-bold" id="ha-descripcion">Cargando...</h1>
            <small style="color:var(--ha-ink-soft)" id="ha-presentacion">---</small>
          </div>
          <span class="ha-badge" style="font-size:11px; padding:3px 10px; border:1px solid var(--ha-compra); color:var(--ha-compra); border-radius:12px;" id="ha-total-lotes">-- LOTES</span>
        </div>

        <div class="row mt-4 pt-3" style="border-top:1px solid var(--ha-hairline)">
          <div class="col-6 col-sm-3 mb-2">
            <p class="mb-0 font-weight-bold" style="font-size:22px; color:var(--ha-compra)" id="ha-stock">--</p>
            <small style="color:var(--ha-ink-soft);font-size:12px">Disponible para Facturar</small>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <p class="mb-0 font-weight-bold" style="font-size:22px; color:var(--ha-venta)" id="ha-ultimo-costo">--</p>
            <small style="color:var(--ha-ink-soft);font-size:12px">Último Costo</small>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <p class="mb-0 font-weight-bold" style="font-size:22px; color:var(--ha-consumo)" id="ha-costo-promedio">--</p>
            <small style="color:var(--ha-ink-soft);font-size:12px">Costo Promedio</small>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <p class="mb-0 font-weight-bold" style="font-size:22px; color:var(--ha-liquidacion)" id="ha-lotes-liquidados">--</p>
            <small style="color:var(--ha-ink-soft);font-size:12px">Agotados</small>
          </div>
          <div class="col-12 mt-2 pt-2 border-top" style="border-color:var(--ha-hairline)!important">
            <small style="color:var(--ha-ink-soft);font-size:11px;text-transform:uppercase;letter-spacing:1px">Bonificado</small>
            <div id="ha-bonificado" class="mt-1" style="font-size:13px;color:var(--ha-ink)">--</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN GRID: tabs -->
  <div class="row">
    <div class="col-12 mb-4">
      <ul class="nav nav-tabs mb-2" id="ha-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="ha-tab-lotes" data-toggle="tab" href="#ha-pane-lotes" role="tab">Lotes <small class="text-muted" id="ha-lotes-count">(0)</small></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="ha-tab-precios" data-toggle="tab" href="#ha-pane-precios" role="tab">Precios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="ha-tab-estadistica" data-toggle="tab" href="#ha-pane-estadistica" role="tab">Estadística</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="ha-pane-lotes" role="tabpanel">
          <div class="row">
            <div class="col-lg-4 mb-3">
              <div id="batch-list"></div>
            </div>
            <div class="col-lg-8">
              <div class="ha-card">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-start flex-wrap" style="border-color:var(--ha-hairline)!important">
                  <div>
                    <small class="text-uppercase d-block" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)">Bitácora completa</small>
                    <h3 id="ledger-title" class="h4 mb-0 mt-1 font-weight-bold">&mdash;</h3>
                  </div>
                  <div id="ledger-badge"></div>
                </div>
                <div id="ledger-meta" class="p-3 row" style="font-size:14px"></div>
                <div class="px-3 py-2 w-100 d-flex flex-wrap align-items-center" style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--ha-ink-soft);gap:6px 16px">
                  <span class="ha-legend-item active d-inline-flex align-items-center" style="gap:5px" data-tipo=""><span class="ha-legend-dot" style="background:var(--ha-ink-soft)"></span>Todos</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Compra"><span class="ha-legend-dot" style="background:var(--ha-compra)"></span>Compra</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Venta"><span class="ha-legend-dot" style="background:var(--ha-venta)"></span>Venta</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Traspaso"><span class="ha-legend-dot" style="background:var(--ha-traspaso)"></span>Traspaso</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Consumo"><span class="ha-legend-dot" style="background:var(--ha-consumo)"></span>Consumo</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Físico"><span class="ha-legend-dot" style="background:var(--ha-recepcion)"></span>Físico</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Costo"><span class="ha-legend-dot" style="background:var(--ha-compra)"></span>Costo</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Aprobación"><span class="ha-legend-dot" style="background:var(--ha-traspaso)"></span>Aprobación</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Ensamble"><span class="ha-legend-dot" style="background:var(--ha-recepcion)"></span>Ensamble</span>
                  <span class="ha-legend-item d-inline-flex align-items-center" style="gap:5px" data-tipo="Reservación"><span class="ha-legend-dot" style="background:var(--ha-recepcion)"></span>Reservación</span>
                </div>
                <div id="ledger-body" class="p-3"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="ha-pane-precios" role="tabpanel">
          <div class="ha-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color:var(--ha-hairline)!important">
              <div>
                <small class="text-uppercase d-block" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)">Precios</small>
                <h3 class="h6 mb-0 mt-1 font-weight-bold">Por canal de venta</h3>
              </div>
              <span style="font-size:11px;color:var(--ha-ink-soft)" id="ha-precios-count"></span>
            </div>
            <div id="ha-precios" class="p-3">
              <div class="text-muted text-center py-3">--</div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="ha-pane-estadistica" role="tabpanel">
          <!-- Daterange picker global -->
          <div class="d-flex justify-content-end mb-3">
            <div class="d-flex align-items-center" style="gap:6px">
              <small style="color:var(--ha-ink-soft);font-size:11px;text-transform:uppercase;letter-spacing:0.5px">Rango:</small>
              <input type="text" class="form-control form-control-sm input-fecha" id="ha-fecha-global" style="width:220px;font-size:12px" placeholder="Seleccionar rango">
            </div>
          </div>

          <!-- Row 1: Barras agrupadas Mes/Año -->
          <div class="ha-card mb-3">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap" style="border-color:var(--ha-hairline)!important">
              <div>
                <small class="text-uppercase d-block" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)">Estadística</small>
                <h3 class="h6 mb-0 mt-1 font-weight-bold">Ventas agrupadas por Mes/Año</h3>
              </div>
            </div>
            <div class="p-3">
              <div id="ha-chart-barras" style="height:280px"></div>
            </div>
          </div>

          <!-- Row 2: Top 12 Clientes + Indicadores + Comportamiento -->
          <div class="row">
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="ha-card h-100">
                <div class="p-3 border-bottom" style="border-color:var(--ha-hairline)!important">
                  <small class="text-uppercase d-block" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)">Clientes</small>
                  <h3 class="h6 mb-0 mt-1 font-weight-bold">Top 12 — Mayor compra (C$)</h3>
                </div>
                <div class="p-3" id="ha-top-clientes">--</div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="ha-card h-100">
                <div class="p-3 border-bottom" style="border-color:var(--ha-hairline)!important">
                  <small class="text-uppercase d-block" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)">Indicadores</small>
                  <h3 class="h6 mb-0 mt-1 font-weight-bold">Resumen del artículo</h3>
                </div>
                <div class="p-3" id="ha-indicadores">--</div>
              </div>
            </div>
            <div class="col-md-12 col-lg-4 mb-3">
              <div class="ha-card h-100">
                <div class="p-3 border-bottom" style="border-color:var(--ha-hairline)!important">
                  <div>
                    <small class="text-uppercase d-block" style="font-size:11px;letter-spacing:2px;color:var(--ha-ink-soft)">Comportamiento</small>
                    <h3 class="h6 mb-0 mt-1 font-weight-bold">Tendencia mensual</h3>
                  </div>
                </div>
                <div class="p-3">
                  <div id="ha-chart-linea" style="height:250px"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="pt-3 mt-3 border-top d-flex justify-content-between flex-wrap" style="border-color:var(--ha-hairline)!important;font-size:11px;color:var(--ha-ink-soft)">
    <span>Cada movimiento queda registrado de forma permanente · No editable</span>
    <span>Última sincronización: hoy, 09:41</span>
  </div>

</div>
@endsection
