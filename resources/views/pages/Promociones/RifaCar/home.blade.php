@extends('layouts.main')
@section('title' , "PROMOCIONES - RIFA DE CARRO")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.Promociones.RifaCar.js_Promociones')
    @include('pages.Promociones.RifaCar.css_tickets')
    @include('pages.OrdenCompra.css_Ordenes')
@endsection

@section('content')

    <!-- Header -->
    <div class="row border">
        <div class="col-md-7">            
            <h4 class="h4 text-innova"> FACTURAS EMITIDAS</h4>
            <p class="text-muted mb-4">Facturas de clientes, del periodo de <span id="tl_periodo"></span>.</p>
        </div>
        <div class="col-md-2 ">        
            <div class="form-group">                
                <label for="f1">BUSQUEDA</label>
                <input type="text" class="form-control" placeholder="Ej: 0000XXXX" id="txt_busqueda_orden_compra">
            </div>
        </div>
        <div class="col-md-2 ">        
            <div class="form-group">                
                <label for="f1">FECHA DE EVALUACION</label>
                <input type="text" class="input-fecha" name="dt_range" />
            </div>
        </div>

        <div class="col-md-1 mt-4">
            <div class="btn-group w-100">               
                <button type="button" class="btn btn-primary-umk btn-block float-right" id="filtrarFechas">  Filtrar </button>		
            </div>      
        </div>
    </div>


    <div class="row g-3 mb-4">
      <div class="col-md-3 border-end">
        <div class="card summary-card">
          <div class="card-body">
            
            <div class="summary-value">
              <div class="d-flex justify-content-between align-items-center">
                <span id="TOTAL_CLIENTES"> 0.00 </span>
                <span>
                  <i class="fas fa-exclamation-circle"></i>
                </span>
              </div>
            </div>
            <div class="summary-title" style="color: #890fa1">CLIENTES FACT.</div>
          </div>
        </div>
      </div>
      <div class="col-md-3 border-end">
        <div class="card summary-card">
          <div class="card-body">
            
            <div class="summary-value">
              <div class="d-flex justify-content-between align-items-center">
                <span id="TOTAL_FACTURAS">0.00</span>
                <span>
                  <i class="fas fa-boxes"></i>
                </span>
              </div>
            </div>
            <div class="summary-title" style="color: #890fa1">FACT. EMITIDAS</div>
          </div>
        </div>
      </div>
      <div class="col-md-3 border-end">
        <div class="card summary-card">
          <div class="card-body">            
            <div class="summary-value">
              <div class="d-flex justify-content-between align-items-center">
                <span id="TOTAL_ACCIONES">0.00</span>
                <span>
                  <i class="fa fa-exclamation-circle"></i>
                </span>
              </div>
            </div>
            <div class="summary-title" style="color: #890fa1">ACCIONES FACT.</div>
          </div>
        </div>
      </div>
      <div class="col-md-3 border-end">
        <div class="card summary-card">
          <div class="card-body">            
            <div class="summary-value"> 
              <div class="d-flex justify-content-between align-items-center">
                <span id="ULTIMA_ACCION">0.00</span>
                <span>
                  <i class="fa fa-exclamation-circle"></i>
                </span>
              </div>
            </div>
            <div class="summary-title" style="color: #890fa1">ULTIMA ACCION ASIGNADA</div>
          </div>
        </div>
      </div>
    </div>




    <!-- Tablas -->
    <div class="row g-4 mb-4">      
        <div class="col-md-12">
            <div class="card">
            <div class="card-header bg-innova text-white">            
                <div class="d-flex justify-content-between">
                    <h6 class="mb-0">FACTURAS</h6>
                    </div>
                    <p class="text-white mb-0" >Lista encontrado de facturas generadas</p>
                </div>
            <div class="card-body">
                <table id="tbl_ordenes_compras" class="display" style="width:100%">
                    <tfoot>
                        <tr>
                        <th colspan="6" >
                            <div class="row">
                            <div class="col-md-10 text-right">
                                <span class="item-right">TOTAL:</span>
                            </div>
                            <div class="col-md-2 text-right">  
                                <span id="total_ordenes">C$. 0.00</span><br>
                            </div>
                            </div>
                        </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>



    <!-- Modal -->
<div class="modal fade" id="ModalAcciones" tabindex="-1" aria-labelledby="modalApartadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">

            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <h3 class="modal-title fw-bold w-100 text-center" id="modalApartadoLabel">
                    Acciones participantes
                </h3>

                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 pb-4">

                <!-- Lista de números -->
                <div class="border rounded-3 p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Factura: <span id="lbl_factura"></span> </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-center align-items-center text-center" id="tbl_factura_acciones"></div>
                </div>

                <!-- Información -->
                <div class="alert border-0 rounded-3 d-flex align-items-start mb-4" style="background:#eaf3ff;">
                    <div class="me-3">
                        <div class="rounded-circle border border-primary d-flex align-items-center justify-content-center"  style="width:40px;height:40px;">
                            <i class="fas fa-info text-primary"></i>
                        </div>
                    </div>

                    <div>
                        <div class="fw-bold text-primary">
                            <span id="lbl_nombre_cliente"></span>
                        </div>

                        <small class="text-secondary">
                            <span id="lbl_codigo_cliente"></span>
                        </small>
                    </div>
                </div>

                <!-- Botón -->
                <button 
                    type="button"
                    id="btn_imprimir_acciones"
                    target="_blank"
                    class="btn btn-success w-100 py-2 rounded-3 fw-semibold">
                    <i class="fas fa-print me-2"></i>
                    Imprimir
                </button>

            </div>
        </div>
    </div>
</div>


    <div class="modal fade" id="" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >ACCIONES</h5>
                </div>
                <div class="modal-body" style="background-color: #f1f5f8;">


                    




                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>

@endsection
