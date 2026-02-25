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
            <h5 class="h5 text-umk"> {{ $OrdenCompra->ORDEN_COMPRA }}</h5>
            <p>Proveedor:</br>{{ $OrdenCompra->PROVEEDOR }} - {{ $OrdenCompra->getProveedor->NOMBRE }}</p>
            <p >Embarque:</br>{{ $OrdenCompra->DIRECCION_EMBARQUE }}</p>
            
        </div>
        <div class="col-sm-auto ms-auto">
            <div class="table-responsive">
            <table class="table table-sm table-borderless fs--1">
                <tbody>
                <tr>
                    <th>Estado:</th>
                    <td>{{ $OrdenCompra->ESTADO }}</td>
                </tr>
                <tr>
                    <th class="text-sm-end">Fecha:</th>
                    <td>{{  date('d/m/Y', strtotime($OrdenCompra->FECHA)) }} </td>
                </tr>           
                <tr>
                    <th>Prioridad:</th>
                    <td>{{ $OrdenCompra->PRIORIDAD }}</td>
                </tr>                
                <tr>
                    <th>Creación:</th>
                    <td>{{ substr($OrdenCompra->CreatedBy, 3, 10) }} - {{ date('d/m/Y h:i A', strtotime($OrdenCompra->CreateDate)) }}</td>
                </tr>
                <tr>
                    <th>Confirmación :</th>
                    <td>{{ $OrdenCompra->USUARIO_CONFIRMA }} - {{ date('d/m/Y h:i A', strtotime($OrdenCompra->FECHA_HORA_CONFIR)) }}</td>
                </tr>
                <tr class="alert-success fw-bold">
                    <th>Monto $:</th>
                    <td>{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
        

        <div class="col-sm-12 ">
             <nav>
                <div class="nav nav-tabs mt-3" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="navLineas" data-toggle="tab" href="#nav-lineas" role="tab" aria-controls="nav-lineas">LINEAS</a>
                    <a class="nav-item nav-link" id="navMontos" data-toggle="tab" href="#nav-monto" role="tab" aria-controls="nav-monto" >MONTOS</a>            
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
                                                <td class="text-right">{{ number_format($linea->CANTIDAD_ORDENADA, 4) }}</td>
                                                <td class="text-right">{{ number_format($linea->PRECIO_UNITARIO, 4) }}</td>
                                                <td class="text-right">{{ number_format( ($linea->PRECIO_UNITARIO * 36.6243 ), 4) }}</td>
                                                <td class="text-right">{{ number_format($linea->PRECIO_UNITARIO * $linea->CANTIDAD_ORDENADA, 4) }}</td>
                                                <td class="text-right">{{ number_format( ($linea->PRECIO_UNITARIO * 36.6243 * $linea->CANTIDAD_ORDENADA), 6) }}</td>
                                            </tr>
                                        @endforeach                                        
                                    </tbody> 
                                    <tfoot>
                                        <tr>
                                            <th colspan="9" class="text-right">TOTAL:</th>
                                            <th class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</th>
                                            <th class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR * 36.6243, 6) }}</th>
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
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PORCENTAJE DESCUENTO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->PORC_DESCUENTO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->PORC_DESCUENTO * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>DESCUENTO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DESCUENTO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DESCUENTO * 36.6243, 6) }}</td>
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
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_IMPUESTO2 * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>FLETE</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_FLETE, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_FLETE * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>SEGURO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_SEGURO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_SEGURO * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>DOCUMENTACION</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DOCUMENTACION, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_DOCUMENTACION * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>TOTAL</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>ANTICIPO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_ANTICIPO, 2) }}</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->MONTO_ANTICIPO * 36.6243, 6) }}</td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>SALDO</td>
                                            <td class="text-right">{{ number_format($OrdenCompra->TOTAL_A_COMPRAR - $OrdenCompra->MONTO_ANTICIPO, 2) }}</td>
                                            <td class="text-right">{{ number_format(($OrdenCompra->TOTAL_A_COMPRAR - $OrdenCompra->MONTO_ANTICIPO) * 36.6243, 6) }}</td>
                                        </tr>
                                    </tbody> 
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 


<div class="modal fade" id="mdlIMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title text-umk" id="exampleModalLongTitle">Base de registros </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-sm-11">
                        
                    </div>
                    
                    <div class="col-sm-1">
                        
                    </div>      
                </div>

                <div class="table-responsive">
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection