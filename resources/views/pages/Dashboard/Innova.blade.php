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
            
            <div class="summary-value">
              <div class="d-flex justify-content-between align-items-center">
                <span id="bultos_facturacion">9</span>
                <span>
                  <i class="fas fa-box"></i>
                </span>
              </div>
            </div>
            <div class="summary-title">Bultos Fact. Actual</div>
            <div class="summary-sub">↑ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">
            
            <div class="summary-value">
              <div class="d-flex justify-content-between align-items-center">
                <span id="bultos_valor">0.00</span>
                <span>
                  <i class="fas fa-box"></i>
                </span>
              </div>
            </div>
            <div class="summary-title">Bultos Valor Actual</div>
            <div class="summary-sub">↑ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">            
            <div class="summary-value">
              <div class="d-flex justify-content-between align-items-center">
                <span id="bultos_anterior">0.00</span>
                <span>
                  <i class="fas fa-box"></i>
                </span>
              </div>
            </div>
            <div class="summary-title">Bultos 2024</div>
            <div class="summary-sub text-danger">↓ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card summary-card">
          <div class="card-body">            
            <div class="summary-value"> 
              <div class="d-flex justify-content-between align-items-center">
                <span id="bultos_actual">0.00</span>
                <span>
                  <i class="fas fa-box"></i>
                </span>
              </div>
            </div>
            <div class="summary-title">Bultos 2025</div>
            <div class="summary-sub">↑ 0.00 Lorem Ipsum</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tablas -->
    <div class="row g-4">
      
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-innova text-white">            
            <div class="d-flex justify-content-between">
              <h6 class="mb-0">Clientes Facturados</h6>
              <a href="#!" class="text-white mb-0" onClick="OnWay();" >Detalles <i class="fas fa-arrow-alt-circle-right"></i></a>
            </div>
            <p class="text-white mb-0">00/00/0000 al  00/00/0000</p>
          </div>
          <div class="card-body">
            <table id="clientesTable" class="display" style="width:100%"></table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-innova text-white">            
            <div class="d-flex justify-content-between">
              <h6 class="mb-0">Ventas por Vendedor</h6>
              <a href="#!" class="text-white mb-0" onClick="OnWay();" >Detalles <i class="fas fa-arrow-alt-circle-right"></i></a>
            </div>
            <p class="text-white mb-0">00/00/0000 al  00/00/0000</p>
          </div>
          <div class="card-body">
            <table id="vendedoresTable" class="display" style="width:100%"></table>
          </div>
        </div>
      </div>
    </div>
  </div>
  


@endsection
