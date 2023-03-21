@extends('layouts.main')
@section('metodosjs')
@include('jsViews.js_promocionesRuta')
@endsection
@section('content')
<div class="container-fluid"> 
    <div class="card border-0 shadow-sm mt-3 ">
        <div class="col-sm-auto">
            <div class="card-body">					
                <div class="col-md-6">
                    <span id="id_form_role" style="display:none">{{ Session::get('user_role') }}</span>                        
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                        </div>								
                        <input type="text" id="id_txt_buscar" class="form-control" placeholder="Buscar...">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mt-3">			
        <div class="card-body col-sm-12">
            <h5 class="card-title"></h5>
            <div class="row mt-3">
                <div class="col-sm-3">						
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="card-title" id="numero_factura">C$ {{ @number_format($totalMv, 2, '.', ',') }}</h4>
                            <p class="card-text" id="">META VAL.</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">						
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="card-title" id="id_total_Facturado">C$ {{ @number_format($totalVv, 2, '.', ',') }}</h4>
                            <p class="card-text" id="">VENTA VAL.</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">						
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="card-title" id="id_total_moneda_local">{{ @number_format($totalMu, 0, '.', ',') }}</h4>
                            <p class="card-text" id="">META UND.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">						
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="card-title" id="id_total_ton">{{ @number_format($totalVu, 0, '.', ',') }}</h4>
                            <p class="card-text" id="">VENTA UND.</p>
                        </div>
                    </div>
                </div>

                
                                    
            </div>
            <div class="card border-0 shadow-sm mt-3 ">
                <div class="card-body col-sm-12 p-0 mb-2">	
                    <div class="p-0 px-car">
                    <div class="table-responsive flex-between-center scrollbar border border-1 border-300 rounded-2">
                        <table id="table_promociones" class="table table-striped table-bordered table-sm mt-3 fs--1" width="100%">
                        <thead>
                            <tr class="bg-blue text-light">
                            <th>DESCRIPCIÓN</th>
                            <th>PRECIO</th>
                            <th>NUEVA BONIF.</th>
                            <th>VIÑETA</th>
                            <th>VAL. PROM.</th>
                            <th>META VAL.</th>
                            <th>VENTA</th>
                            <th>UND PROM.</th>
                            <th>META UND.</th>
                            <th>VENTA UND.</th>
                            <th>VENTA {{ Carbon\Carbon::createFromFormat('m', @date('m'))->format('F') }}</th>
                            <th>VENTA UND. {{ Carbon\Carbon::createFromFormat('m', @date('m'))->format('F') }}</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($Promociones as $p)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center position-relative mt-2">                                
                                                <div class="flex-1 ms-3">
                                                <a href="#!" id="exp_more" class="exp_more text-dark" idArt="{{ $p['Articulo'] }}"><h6 class="mb-0 fw-semi-bold"><div class="stretched-link text-900">{{ $p['Descripcion'] }}</div></h6></a>
                                                <p class="text-500 fs--2 mb-0">{{ $p['Articulo'] }} </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="pe-4 border-sm-end border-200 mt-2">
                                                <h6 class="mb-0 fw-semi-bold">C${{ @number_format($p['Precio'],2) }} </h6>                                        
                                            </div> 
                                        </td>

                                        <td>
                                            <div class="pe-4 border-sm-end border-200 text-center">
                                                <h6 class="fs--2 text-600 mb-1">{{ $p['NuevaBonificacion'] }}</h6>                                
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">C${{ @number_format($p['ValorVinneta'],2) }}</h6>                    
                                            </div> 
                                        </td>

                                        <td>
                                            <div class="pe-4 border-sm-end border-200 text-center">
                                                <h6 class="fs--2 text-600 mb-1"C$>0</h6>                  
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">C${{ @number_format($p['ValMeta'],2) }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">C${{ @number_format($p['Venta'],2) }} <span class="badge rounded-pill badge-primary">{{ @number_format($p['PromVenta'],2) }}%</span></h6>                    
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200 text-center">
                                                <h6 class="fs--2 text-600 mb-1">0</h6>                    
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200 text-center">
                                                <h6 class="fs--2 text-600 mb-1">{{ $p['MetaUnd'] }}</h6>                    
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200 text-center">
                                                <h6 class="fs--2 text-600 mb-1">{{ $p['VentaUND'] }} <span class="badge rounded-pill badge-primary">{{ @number_format($p['PromVentaUND'],2) }}%</span></h6>                    
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">C${{ @number_format($p['VentaMActual'],2) }}</span></h6>                    
                                            </div> 
                                        </td>
                                        <td>
                                            <div class="pe-4 border-sm-end border-200 text-center">
                                                <h6 class="fs--2 text-600 mb-1">{{ @number_format($p['VentaUNDMActual'],0) }}</h6>                    
                                            </div> 
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>      

</div>
@endsection('content')