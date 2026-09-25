@extends('layouts.main')
@section('title' , $data['name'])
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('pages.InventarioInn.js_inventario')
@endsection
@section('content')

<div class="container-fluid">

  <div class="row mb-4">
    <div class="col-12">
      <img src="{{ asset($Style['Logo']) }}" alt="Inventario Innova" width="{{ $Style['With'] }}">
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      <div class="row mb-3">
        <div class="col-12">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-white" id="addon_buscar">
                <i data-feather="search"></i>
              </span>
            </div>
            <input type="text" id="id_buscar_articulo" class="form-control"
                   placeholder="Buscar por artículo o descripción..."
                   aria-describedby="addon_buscar" autocomplete="off" disabled>
          </div>
        </div>
      </div>

      {{-- estados de carga / error, controlados desde js_inventario --}}
      <div id="id_cargando" class="text-center py-5">
        <div class="spinner-border" role="status" style="color: {{ $Style['Color'] }};">
          <span class="sr-only">Cargando...</span>
        </div>
        <div class="text-muted mt-2">Cargando artículos...</div>
      </div>

      <div id="id_error" class="alert alert-warning border" role="alert" style="display:none;"></div>

      <div id="id_contenedor_tabla" style="display:none;">
        <table id="tbl_articulos_inn" class="table table-striped table-bordered table-sm" style="width:100%">
          <thead>
            <tr class="text-white" style="background-color: {{ $Style['Color'] }};">
              {{-- los anchos los fija columns.width en js_inventario, no el markup --}}
              <th>ARTÍCULO</th>
              <th>DESCRIPCIÓN</th>
              <th class="text-right">EXISTENCIA</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

    </div>
  </div>

  {{-- Detalle del articulo: ficha + tabs de bodegas y precios.
       Se alimenta desde js_inventario al hacer click en una fila. --}}
  <div class="modal fade" id="modal_articulo_inn" tabindex="-1" role="dialog"
       aria-labelledby="modal_articulo_inn_label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">

        <div class="modal-header text-white" style="background-color: {{ $Style['Color'] }};">
          <div>
            <h5 class="modal-title mb-0" id="modal_articulo_inn_label">&mdash;</h5>
            <small id="md_codigo" class="d-block" style="opacity:.75;"></small>
          </div>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <div id="md_cargando" class="text-center py-4">
            <div class="spinner-border" role="status" style="color: {{ $Style['Color'] }};">
              <span class="sr-only">Cargando...</span>
            </div>
            <div class="text-muted mt-2">Cargando detalle...</div>
          </div>

          <div id="md_error" class="alert alert-warning border mb-0" role="alert" style="display:none;"></div>

          <div id="md_contenido" style="display:none;">

            <ul class="nav nav-tabs" id="md_tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tab_bodegas_link" data-toggle="tab" href="#tab_bodegas"
                   role="tab" aria-controls="tab_bodegas" aria-selected="true">
                  Bodegas
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="tab_precios_link" data-toggle="tab" href="#tab_precios"
                   role="tab" aria-controls="tab_precios" aria-selected="false">
                  Precios
                </a>
              </li>
            </ul>

            <div class="tab-content border border-top-0 p-3">

              <div class="tab-pane fade show active" id="tab_bodegas" role="tabpanel"
                   aria-labelledby="tab_bodegas_link">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-white" id="addon_buscar_bodegas">
                      <i data-feather="search"></i>
                    </span>
                  </div>
                  <input type="text" id="md_buscar_bodegas" class="form-control"
                         placeholder="Buscar por bodega o nombre..."
                         aria-describedby="addon_buscar_bodegas" autocomplete="off">
                </div>
                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                  <table class="table table-sm table-striped table-bordered mb-0">
                    <thead>
                      <tr class="text-white" style="background-color: {{ $Style['Color'] }};">
                        <th style="width: 90px;">BODEGA</th>
                        <th>NOMBRE</th>
                        <th class="text-right" style="width: 150px;">DISPONIBLE</th>
                      </tr>
                    </thead>
                    <tbody id="md_tbody_bodegas"></tbody>
                    <tfoot>
                      <tr class="font-weight-bold">
                        <td colspan="2" class="text-right">TOTAL</td>
                        <td class="text-right" id="md_total_bodegas">0.00</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab_precios" role="tabpanel"
                   aria-labelledby="tab_precios_link">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-white" id="addon_buscar_precios">
                      <i data-feather="search"></i>
                    </span>
                  </div>
                  <input type="text" id="md_buscar_precios" class="form-control"
                         placeholder="Buscar por nivel de precio..."
                         aria-describedby="addon_buscar_precios" autocomplete="off">
                </div>
                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                  <table class="table table-sm table-striped table-bordered mb-0">
                    <thead>
                      <tr class="text-white" style="background-color: {{ $Style['Color'] }};">
                        <th>NIVEL DE PRECIO</th>
                        <th class="text-right" style="width: 150px;">PRECIO</th>
                        <th class="text-center" style="width: 100px;">VERSIÓN</th>
                      </tr>
                    </thead>
                    <tbody id="md_tbody_precios"></tbody>
                  </table>
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
