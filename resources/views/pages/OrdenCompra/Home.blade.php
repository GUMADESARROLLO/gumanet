@extends('layouts.ly_reorder')
@section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.OrdenCompra.js_OrdenCompra') 
    @include('pages.OrdenCompra.css_OrdenCompra')
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4 class="h4 text-umk"> ORDEN DE COMPRA </h4>
            <p class="text-muted" > A Continuacion se detalla la informacion de la Orden de Compra & Embarque  </p>
        </div>
        <div class="col-sm-auto ms-auto">
            <div class="table-responsive">
                <table class="table table-sm table-borderless fs--1">
                    <tbody>
                    <tr>
                        <th>ESTADO:</th>
                        <td>{{ $ESTADOS['OC_ESTADO'] }}</td>
                    </tr>  
                    <tr>
                        <th>PRIORIDAD:</th>
                        <td>{{ $ESTADOS['OC_PRIORIDAD'] }}</td>
                    </tr>                
                    
                    
                    </tbody>
                </table>
            </div>
        </div>
        

        <div class="col-sm-12 ">

            <div class="row" > 
                <div class="col-2 text-left">                    
                    <p class=" m-0">ORDEN DE COMPRA:</span></p>
                    <span class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->ORDEN_COMPRA }} </span>
                </div>                          
                <div class="col-6 text-left">                    
                    <p class=" m-0">PROVEEDOR:</span></p>
                    <span class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->PROVEEDOR }} - {{ $OrdenCompra->getProveedor->NOMBRE }}</span>
                </div>
                <div class="col-2 text-left">
                    <p class="text-muted m-0">FECHA ORDEN DE COMPRA:</p>
                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{  date('d/m/Y', strtotime($OrdenCompra->FECHA)) }}</p>
                </div>
                <div class="col-1 text-left">
                    <p class="text-muted m-0"># EMBARQUE: </p>
                    <p class="font-weight-bolder" style="font-size: 1.0rem!important"><a href="#!" class="text-umk" id="MdlEmbarque">{{ $OrdenCompra->getEmbarqueLinea->first()->EMBARQUE }} </a></p>
                </div>
                <div class="col-1 text-right">
                    <p class="text-muted m-0">MONTO $: </p>
                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</p>
                </div>
                
            </div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="navLineas" data-toggle="tab" href="#nav-lineas" role="tab" aria-controls="nav-lineas">LINEAS</a>
                    <a class="nav-item nav-link" id="navMontos" data-toggle="tab" href="#nav-monto" role="tab" aria-controls="nav-monto" >MONTOS</a>
                    <a class="nav-item nav-link" id="navOtros" data-toggle="tab" href="#nav-otros" role="tab" aria-controls="nav-otros" >OTROS</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-lineas" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="tbl_compra_linea" class="table table-bordered mt-3">
                                    <thead class="bg-blue text-light">
                                        <tr>
                                            <th>LINEA</th>
                                            <th>ARTICULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>FECHA REQUERIDA</th>
                                            <th>BODEGA</th>
                                            <th>UNIDAD</th>
                                            <th>CANT. ORDENADA</th>
                                            <th>PRECIO UNIT $</th>
                                            <th>PRECIO UNIT C$</th>
                                            <th>IMPORTE TOTAL $</th>
                                            <th>IMPORTE TOTAL C$</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($OrdenCompra->getLineasOrden as $linea)
                                            <tr>
                                                <td class="text-center">{{ $linea->ORDEN_COMPRA_LINEA }}</td>
                                                <td class="text-center">{{ $linea->ARTICULO }}</td>
                                                <td>{{ strtoupper($linea->DESCRIPCION) }}</td>
                                                <td class="text-center">{{ date('d/m/Y', strtotime($linea->FECHA_REQUERIDA)) }} </td>
                                                <td class="text-center">{{ $linea->BODEGA }}</td>
                                                <td class="text-center">{{ $linea->getArticulo->UNIDAD_VENTA }}</td>
                                                <td class="text-right">{{ number_format($linea->CANTIDAD_ORDENADA, 2) }}</td>
                                                <td class="text-right">{{ number_format($linea->PRECIO_UNITARIO, 2) }}</td>
                                                <td class="text-right">{{ number_format( ($linea->PRECIO_UNITARIO * 36.6243 ), 2) }}</td>
                                                <td class="text-right">{{ number_format($linea->PRECIO_UNITARIO * $linea->CANTIDAD_ORDENADA, 2) }}</td>
                                                <td class="text-right">{{ number_format( ($linea->PRECIO_UNITARIO * 36.6243 * $linea->CANTIDAD_ORDENADA), 2) }}</td>
                                            </tr>
                                        @endforeach                                        
                                    </tbody> 
                                    <tfoot>
                                        <tr>
                                            <th colspan="9" class="text-right">TOTAL:</th>
                                            <th class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</th>
                                            <th class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR * 36.6243, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-monto" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="tbl_compra_monto" class="table table-bordered mt-3">
                                    <thead class="bg-blue text-light">
                                        <tr>
                                            <th>#</th>
                                            <th>RUBRO</th>
                                            <th>MONTO</th>
                                            <th>MONTO LOCAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                       
                                        <tr>
                                            <td>1</td>
                                            <td>TOTAL MERCADERIA</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PORCENTAJE DESCUENTO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->PORC_DESCUENTO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->PORC_DESCUENTO * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>DESCUENTO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DESCUENTO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DESCUENTO * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>IMPUESTO IVA</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_IMPUESTO1, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_IMPUESTO1 * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>IMPUESTO ND</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_IMPUESTO2, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_IMPUESTO2 * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>FLETE</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_FLETE, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_FLETE * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>SEGURO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_SEGURO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_SEGURO * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>DOCUMENTACION</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DOCUMENTACION, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DOCUMENTACION * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>TOTAL</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>ANTICIPO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_ANTICIPO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_ANTICIPO * 36.6243, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>SALDO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR - $OrdenCompra->MONTO_ANTICIPO, 2) }}</td>
                                            <td class="text-right">{{ number_format(($OrdenCompra->TOTAL_A_COMPRAR - $OrdenCompra->MONTO_ANTICIPO) * 36.6243, 2) }}</td>
                                        </tr>
                                    </tbody> 
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-otros" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row" >                                    
                                <div class="col-sm-6 text-center">                    
                                    <p class="text-muted m-0"> CREACION: </p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ substr($OrdenCompra->CreatedBy, 3, 10) }} - {{ date('d/m/Y h:i A', strtotime($OrdenCompra->CreateDate)) }} </p>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <p class="text-muted m-0">CONFIRMACION: </p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->USUARIO_CONFIRMA }} - {{ date('d/m/Y h:i A', strtotime($OrdenCompra->FECHA_HORA_CONFIR)) }}</p>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <p class="text-muted m-0">DIR. EMBARQUE: </p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->DIRECCION_EMBARQUE }}</p>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <p class="text-muted m-0">DIR. CROBRO: </p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->DIRECCION_COBRO }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 


<div class="modal fade" id="ModalEmbarque" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-xl modal-dialog-centered " >
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title text-umk" id="exampleModalLongTitle">
                    <strong></strong>{{ $OrdenCompra->getEmbarqueLinea->first()->EMBARQUE }} - {{ $ESTADOS['EM_ESTADO'] }} - {{ $ESTADOS['EM_LIQUIDADO'] }} </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                

                <div class="table-responsive">
                    
                    <div class="row">
                        <div class="col">

                            <div class="row" > 

                                <div class="col-sm-3 text-left">                    
                                    <p class="text-muted m-0"> FECHA DE REQUERIDA: </p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ date('d/m/Y', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_REQUERIDA)) }}</p>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <p class="text-muted m-0">FECHA DE EMBARQUE: </p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ date('d/m/Y', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_EMBARQUE)) }}</p>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <p class="text-muted m-0">FECHA DE APLICACION:</p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ date('d/m/Y', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_HORA_APLICAC)) }}</p>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <p class="text-muted m-0">FECHA DE LIQUIDACION:</p>
                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ date('d/m/Y', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_HORA_LIQUIDA)) }}</p>
                                </div>
                            </div>             
                        </div>

                        <div class="col-sm-auto ms-auto">
                            <div class="table-responsive">
                                
                            </div>
                        </div>
                    </div>
                    

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="navLineasEmbarque" data-toggle="tab" href="#nav-lineas-embarque" role="tab" aria-controls="nav-lineas-embarque">LINEAS</a>
                            <a class="nav-item nav-link" id="navCostosEmbarque" data-toggle="tab" href="#nav-costos-embarque" role="tab" aria-controls="nav-costos-embarque" >COSTOS</a>
                            <a class="nav-item nav-link" id="navAuditoria" data-toggle="tab" href="#nav-auditoria" role="tab" aria-controls="nav-auditoria" aria-selected="false">AUDITORIA</a>
                            <a class="nav-item nav-link" id="navOtros" data-toggle="tab" href="#nav-Otros-embarque" role="tab" aria-controls="nav-Otros-embarque" aria-selected="false">OTROS</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-lineas-embarque" role="tabpanel">
                            <table id="tbl_embarque" class="table table-bordered">
                                <thead class="bg-blue text-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ARTICULO</th>
                                        <th>DESCRIPCION</th>
                                        <th>BODEGA</th>
                                        <th>LOTE</th>
                                        <th>EMBARCADA</th>
                                        <th>RECIBIDA</th>
                                        <th>RECHAZADA</th>
                                        <th>PREC. UNIT $</th>
                                        <th>COST. UNIT C$</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach($OrdenCompra->getEmbarqueLinea as $e) 
                                    <tr>
                                        <td class="text-center">{{ $e->EMBARQUE_LINEA }}</td>
                                        <td class="text-center">{{ $e->ARTICULO }}</td>
                                        <td> {{ strtoupper($e->DESCRIPCION ?? '') }}</td>
                                        <td class="text-center">{{ $e->BODEGA }}</td>
                                        <td class="text-center">{{ $e->LOTE }}</td>
                                        <td class="text-right">{{ number_format($e->CANTIDAD_EMBARCADA, 2) }}</td>
                                        <td class="text-right">{{ number_format($e->CANTIDAD_RECIBIDA, 2) }}</td>
                                        <td class="text-right">{{ number_format($e->CANTIDAD_RECHAZADA, 2) }}</td>
                                        <td class="text-right">{{ number_format($e->PRECIO_UNITARIO, 2) }}</td>
                                        <td class="text-right">{{ number_format($e->COST_UN_REAL_LOCAL, 2) }}</td>
                                    </tr>    
                                    @endforeach                                 
                                </tbody> 
                                <tfoot>
                                    <tr>
                                        <th colspan="9" class="text-right">TOTAL:</th>
                                        <th class="text-right">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_REAL_LOCAL'), 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-costos-embarque" role="tabpanel">
                            <div class="row mt-3" >
                                <div class="col-sm-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header text-white bg-secondary border-0">
                                            <p class="text-white m-0 p-0 text-center">COSTO FISCAL C$</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" >                                    
                                                <div class="col-sm-4 text-center">                    
                                                    <p class="text-muted m-0"> FISCAL </p>
                                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_FISC_LOCAL'), 4) }}</p>
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <p class="text-muted m-0">ESTIMADO</p>
                                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_ESTI_LOCAL'), 4) }}</p>
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <p class="text-muted m-0">REAL</p>
                                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_REAL_LOCAL'), 4) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header text-white bg-secondary border-0">
                                            <p class="text-white m-0 p-0 text-center">COSTO FISCAL $</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" >                                    
                                                <div class="col-sm-4 text-center">                    
                                                    <p class="text-muted m-0"> FISCAL </p>
                                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_FISC_DOLAR'), 2) }}</p>
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <p class="text-muted m-0">ESTIMADO</p>
                                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_ESTI_DOLAR'), 2) }}</p>
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <p class="text-muted m-0">REAL</p>
                                                    <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ number_format($OrdenCompra->getEmbarqueLinea->sum('COST_UN_REAL_DOLAR'), 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="tab-pane fade" id="nav-auditoria" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row" >                                    
                                        <div class="col-sm-4 text-center">                    
                                            <p class="text-muted m-0"> CREADO: </p>
                                            <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->USUARIO_CREADO }} - {{  Date('d/m/Y h:i A', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_HORA_CREADO)) }} </p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <p class="text-muted m-0">APLICADO: </p>
                                            <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->USUARIO_APLICADO }} - {{  Date('d/m/Y h:i A', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_HORA_APLICAC)) }} </p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <p class="text-muted m-0">LIQUIDADO:</p>
                                            <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->USUARIO_LIQUIDACIO }} - {{  Date('d/m/Y h:i A', strtotime($OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->FECHA_HORA_LIQUIDA)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-Otros-embarque" role="tabpanel">

                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row" >                                    
                                        <div class="col-sm-4 text-center">                    
                                            <p class="text-muted m-0"> NOTAS: </p>
                                            <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->NOTAS }} </p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <p class="text-muted m-0">REFERENCIA: </p>
                                            <p class="font-weight-bolder" style="font-size: 1.0rem!important">{{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->REFERENCIA }}</p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <p class="text-muted m-0">PROVEEDOR:</p>
                                            <p class="font-weight-bolder" style="font-size: 1.0rem!important"> {{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->PROVEEDOR  }} - {{ $OrdenCompra->getEmbarqueLinea->first()->getInfoEmbarque->getProveedor->NOMBRE  }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection