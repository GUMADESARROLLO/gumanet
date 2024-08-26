@extends('layouts.ly_reorder')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_reorder_point');
@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="{{ url('css/colors-reorder-point.css') }}">
<div class="container-fluid">
  <p class="font-italic text-muted pt-0 mt-0">Actualizado hasta <span id="id_UpdateAt"> - </span></p>	
   
  <div class="row">
    <div class="col-sm-10">		
      
      <div class="input-group"> 
        <input type="text" id="txt_search" class="form-control" aria-describedby="basic-addon1" placeholder="Buscar...">
          <div class="input-group-prepend">
            <span class="btn-change-color text-white input-group-text" id="BtnClick"><i data-feather="refresh-cw"></i></span>
          </div>
      </div>
    </div>
    <div class="col-sm-1">
      <div class="input-group mb-3">
        <select class="custom-select" id="select_rows" name="InputDtShowColumnsArtic">
          <option value="5" selected>5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="100">100</option>
          <option value="-1">Todo</option>
        </select>
      </div>
    </div>

    <div class="col-sm-1 p-0 m-0" >
      <a id="exp-to-excel" href="#!" class="btn btn-light btn-block text-success"><i class="fas fa-file-excel"></i> Exportar</a>
    </div>      
  </div>

  <div class="row">
  

      <div class="col-12">
        <div class="table-responsive mt-3 mb-2">
            <table class="table nowrap table-bordered table-sm" id="dt_articulos" width="100%" >
              <thead class="bg-blue text-light">
                <tr>
                  <th class="col-yellow">
                    <span 
                      data-toggle="tooltip"  
                      data-placement="top" 
                      title="Codigo de Articulos en el sistema">ARTICULO
                    </span>
                  </th>
                  <th class="col-blue-ca-1">
                    <span 
                      data-toggle="tooltip"  
                      data-placement="top" 
                      title="Descripcion del Articulos , dentro del sistema">DESCRIPCIÓN
                    </span>
                  </th>
                  <th class="col-green">
                    <span 
                      data-toggle="tooltip"  
                      data-placement="top" 
                      title="Tiempo desde que sale el pedido">LEADTIME
                    </span>
                  </th>
                  <th class="col-green">
                    <span 
                      data-toggle="tooltip"  
                      data-placement="top" 
                      title=" - ">FACTOR STOCK SEGURIDAD
                    </span>
                  </th>
                  <th class="col-red-strong">
                    <span 
                      data-toggle="tooltip"  
                      data-placement="top" 
                      title=" - ">ROTACION PREVISTA EXISTENCIAS POR VENCER
                    </span>
                  </th>
                  <th class="col-red-strong">
                    <span 
                      data-toggle="tooltip"  
                      data-placement="top" 
                      title="Total de Existencia en Bodega 002">TOTAL UMK
                    </span>
                  </th>
                  <th class="col-red-light">
                    <span data-toggle="tooltip"  
                      data-placement="top" 
                      title="Total de Existencia en Bodega 001">TOTAL GP
                    </span>
                  </th>
                  <th class="col-red-light"><span data-toggle="tooltip"  data-placement="top" title="Disponibilidad total de Inventario para facturar">TOTAL DISPONIBLE</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title="Existencia de Articulos menor a 7 meses">EXIST. < 7 Meses</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title="Existencia Igual o mayor a 7 meses + ON-HAND">EXIST. >= 7 Meses</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title="Fecha de Lote que esta mas proximo a vencer">LOTE MAS PROX. A VENCER</span></th>
                  <th class="col-blue-light"><span data-toggle="tooltip"  data-placement="top" title="Cantidad de Lote mas proximo a vencer">EXIST. EN LOTE MAS PROX. POR VENCERSE</span></th>
                  <th class="col-red-light"><span data-toggle="tooltip"  data-placement="top" title="Fecha de ult. entrada de lote">ULT. FECHA ENTRADA LOTE</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title="Cantidad de Ult. entrada de lote">ULT. CANT. INGRESADA</span></th>
                  <th class="col-green-strong"><span data-toggle="tooltip"  data-placement="top" title="Promedio de unidad desplazadas en 12 meses">PROM. UND. 12m</span></th>
                  <th class="col-yellow"><span data-toggle="tooltip"  data-placement="top" title="Cantidad Pedida del Articulo">PEDIDO</span></th>
                  <th class="col-blue-light"><span data-toggle="tooltip"  data-placement="top" title="Cantidad en estado de Transito">TRANSITO</span></th>
                  <th class="col-green-strong"><span data-toggle="tooltip"  data-placement="top" title="Ventas realizadas en el periodo de 12m">VENTAS EJEC. 12m</span></th>
                  <th class="col-yellow-strong"><span data-toggle="tooltip"  data-placement="top" title="Contribucion Bruta aportada en 12 meses">CONTRIBUCION BRUTA. 12m C$.</span></th>
                  <th class="col-green"><span data-toggle="tooltip"  data-placement="top" title="Cantidad de Lote >= 7 meses + ON-HAND">ROTACION CORTA</span></th>
                  <th class="col-green"><span data-toggle="tooltip"  data-placement="top" title="Cantidad de Lote >= 7 meses + ON-HAND + TRANSITO ">ROTACION MEDIA</span></th>
                  <th class="col-red-light"><span data-toggle="tooltip"  data-placement="top" title="Cantidad de Lote >= 7 meses + ON-HAND + PEDIDO + TRANSITO">ROTACION LARGA</span></th>
                  <th class="col-red-light"><span data-toggle="tooltip"  data-placement="top" title="Cantidad minima solicitada en los ultimos 2 años">MOQ</span></th>
                  <th class="col-red-light"><span data-toggle="tooltip"  data-placement="top" title=" - ">REORDER</span></th>
                  <th class="col-red-light"><span data-toggle="tooltip"  data-placement="top" title=" - ">CANTIDAD A ORDENAR</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title=" - ">RAZON REORDER/MOQ</span></th>
                  <th class="col-blue-ca-2"><span data-toggle="tooltip"  data-placement="top" title=" - ">COST PROM. C$</span></th>
                  <th class="col-green-strong"><span data-toggle="tooltip"  data-placement="top" title=" - ">COST PROM. USD</span></th>
                  <th class="col-yellow-mostasa"><span data-toggle="tooltip"  data-placement="top" title=" - ">ULT. COST. USD</span></th>
                  <th class="col-green-strong"><span data-toggle="tooltip"  data-placement="top" title=" - ">DEM. ANUAL CA NETA</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title=" - ">DEM. ANUAL CA AJUSTADA</span></th>
                  <th class="col-green-strong"><span data-toggle="tooltip"  data-placement="top" title=" - ">FACTOR</span></th>
                  <th class="col-green-strong"><span data-toggle="tooltip"  data-placement="top" title=" - ">LIMITE LOGISTICO MEDIO</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title=" - ">CLASE</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title=" - ">VALUACION</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title=" - ">REORDER1</span></th>
                  <th class="col-blue-ca-1"><span data-toggle="tooltip"  data-placement="top" title=" - ">ESTIMACION SOBRANTES UND</span></th>
                </tr>
              </thead>
            </table>
          </div>
      </div>
  </div>
  <!--MODAL: DETALLE DE ARTICULO-->
  <div class="modal fade " data-backdrop="static" data-keyboard="false" id="mdDetalleArt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        
        
        <div class="card">  
          <div class="card-header bg-primary text-white">  
              <h4 class="card-title text-uppercase" id="id_titulo_modal_all_items" > - </h4>  
          </div>
          <div class="card-body">  
            <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist" style="display:none">
                <a class="nav-item nav-link active" id="navBodega" data-toggle="tab" href="#nav-bod" role="tab" aria-controls="nav-bod" aria-selected="true">TAB 01</a>
                <a class="nav-item nav-link" id="navPrecios" data-toggle="tab" href="#nav-prec" role="tab" aria-controls="nav-prec" aria-selected="false">TAB02</a>
              </div>
            </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-bod" role="tabpanel" aria-labelledby="navBodega">
              <div class="row">
                  <div class="col-sm-12 mt-3">
                    
                    <div class="col-12" >
                      <div class="row">
                        <div class="col-12 col-sm-6 col-md-2">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_leadtime"> 0.00 </h4>
                              <p class="text-800 fs--1 mb-0">LEADTIME</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_demanda_neta"> 0.00 </h4>
                              <p class="text-800 fs--1 mb-0">DEMANDA ANUAL CRUZ AZUL NETA</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_demanda_ajustada"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">DEMANDA ANUAL CRUZ AZUL AJUSTADA</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_limite_logistico_medio"> 0.00 </h4>
                              <p class="text-800 fs--1 mb-0">LIMITE LOGISTICO MEDIO</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_ventas"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">VENTAS EJECUTADAS 12m</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_contribucion"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">CONTRIBUCION EJECUTADA 12m</p>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-2 mt-3">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_R_corta"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">ROTACION CORTA</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 mt-3">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_R_media"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">ROTACION MEDIA</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 mt-3">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_R_larga"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">ROTACION LARGA</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 mt-3">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_costo"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">COSTO PROMEDIO USD</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 mt-3">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_ultimo_costo"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">ULTIMO COSTO USD</p>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-2 mt-3">
                          <div class="d-flex align-items-center">
                            <div class="ms-3">
                              <h4 class="mb-0" id="id_promedio_mensual"> 0.00</h4>
                              <p class="text-800 fs--1 mb-0">PROMEDIO MENSUAL</p>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                      <hr class="bg-200 mb-6 mt-3">
                    </div>

                    <form id="needs-validation" novalidate>  
                      
                      <div class="row" >  
                          <div class="col-12 col-sm-6 col-md-2">  
                              <label for="firstName">CLASE</label>  
                              <input type="text" class="form-control" id="id_clase" placeholder="0.00" required>  
                          </div> 
                          <div class="col-12 col-sm-6 col-md-2">  
                              <label for="lastName">TRANSITO</label>  
                              <input type="text" class="form-control" id="id_transito" placeholder="0.00" required>  
                          </div>
                          <div class="col-12 col-sm-6 col-md-4">  
                              <label for="lastName">PEDIDO</label>  
                              <input type="text" class="form-control" id="id_pedido" placeholder="0.00" required>  
                          </div>
                          <div class="col-12 col-sm-6 col-md-4">  
                              <label for="firstName">MOQ</label>  
                              <input type="text" class="form-control" id="id_moq" placeholder="0.00" required>  
                          </div>  

                          <div class="col-12 col-sm-6 col-md-4 mt-3">  
                              <label for="lastName">REORDER1</label>  
                              <input type="text" class="form-control" id="id_reorder1" placeholder="0.00" required>  
                          </div>  
                          <div class="col-12 col-sm-6 col-md-4 mt-3">  
                              <label for="lastName">REORDENAR</label>  
                              <input type="text" class="form-control" id="id_reordenar" placeholder="0.00" required>  
                          </div>  
                          <div class="col-12 col-sm-6 col-md-4 mt-3">  
                              <label for="lastName">CANTIDAD ORDENAR</label>  
                              <input type="text" class="form-control" id="id_cant_ordenar" placeholder="0.00" required>  
                          </div>                                
                      </div>
                      <div class="row mt-3">  
                          <div class="col-sm-12 col-md-12 col-xs-12">
                              <div class="container-vms" id="grafVtsDiario" style="width: 100%; margin: 0 auto"></div>
                          </div>  
                      </div>  
                      
                    </form> 
                  </div>
              </div>
            </div>
          </div>
            


          </div> 
          <div class="modal-footer">		
            <button class="btn btn-danger rounded-0" data-dismiss="modal" type="submit">Cerrar</button>  
		      </div> 
        </div>  
      </div>
    </div>
  </div>
</div>
@endsection