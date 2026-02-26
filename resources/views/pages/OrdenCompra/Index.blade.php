@extends('layouts.main')
@section('title' , "COMPRAS")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.OrdenCompra.js_Ordenes')
    @include('pages.OrdenCompra.css_Ordenes')
@endsection

@section('content')

    <!-- Header -->
    <div class="row border">
      <div class="col-md-7">            
        <h4 class="h4 text-innova"> ORDENES DE COMPRAS</h4>
        <p class="text-muted mb-4">Ordenes de compra, tomando en cuenta el periodo de <span id="tl_periodo"></span>.</p>
      </div>
      <div class="col-md-2 ">        
        <div class="form-group">                
          <label for="f1">Excluir Clientes</label>
          <input type="text" class="form-control" placeholder="Ej: Algo va aqui">
        </div>
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
      <!-- <div class="col-md-1 mt-4">
        <div class="btn-group w-100">               
          <button type="button" class="btn btn-success btn-block float-right" id="export_excel">Exportar </button>		
        </div>      
      </div> -->
    </div>




    <!-- Tablas -->
    <div class="row g-4 mb-4">      
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-innova text-white">            
            <div class="d-flex justify-content-between">
              <h6 class="mb-0">ORDENES DE COMPRAS</h6>
            </div>
            <p class="text-white mb-0" >Lista encontrado de ordenes de compras</p>
          </div>
          <div class="card-body">
            <table id="tbl_ordenes_compras" class="display" style="width:100%">
              <tfoot>
                <tr>
                  <th colspan="3" >
                    <div class="row">
                      <div class="col-md-6">
                        <span class="item-left">Total:</span>
                      </div>
                      <div class="col-md-6 text-right">  
                        <span id="total_sku_valor">C$. 0.00</span><br>
                        <span id="total_sku_bultos">0.00 Bls</span>
                      </div>
                    </div>
                  </th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      
    </div>







@endsection
