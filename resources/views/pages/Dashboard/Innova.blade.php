@extends('layouts.main')


@section('title' , $name)
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('Pages.Dashboard.js_dashboard_innova')
    @include('Pages.Dashboard.css_dasboard')
@endsection

@section('content')

  <div class="">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <img src="{{ url('img/innova.png') }}" width="150" height="70" alt="Innova Logo">
      <div class="d-flex gap-2">
        <input type="date" class="form-control" value="2025-01-16">
        <input type="date" class="form-control" value="2025-06-23">
      </div>
    </div>

    <!-- Summary -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">
            <div class="summary-title">Bultos Fact. Actual</div>
            <div class="summary-value"><span id="bultos_facturacion">0.00</span></div>
            <div class="summary-sub">↑ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">
            <div class="summary-title">Bultos Valor Actual</div>
            <div class="summary-value">C$ <span id="bultos_valor">0.00</span></div>
            <div class="summary-sub">↑ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">
            <div class="summary-title">Bultos 2024</div>
            <div class="summary-value"> <span id="bultos_anterior">0.00</span></div>
            <div class="summary-sub text-danger">↓ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">
            <div class="summary-title">Bultos 2025</div>
            <div class="summary-value"> <span id="bultos_actual">0.00</span></span></div>
            <div class="summary-sub">↑ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tablas -->
    <div class="row g-4">
      <div class="col-md-6">
        <h6>Clientes Fact.</h6>
        <table id="clientesTable" class="display" style="width:100%">
          <thead>
            <tr><th>Nombre</th><th>Monto</th><th>Bls</th><th>Codigo</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="col-md-6">
        <h6>Ventas por Vendedor</h6>
        <table id="vendedoresTable" class="display" style="width:100%">
          <thead>
            <tr><th>Nombre</th><th>Monto</th><th>Bls</th><th>Codigo</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  


@endsection
