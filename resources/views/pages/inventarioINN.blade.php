@extends('layouts.main')
@section('metodosjs')
@include('jsViews.js_inventarioINN')
@endsection
@section('content')
<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <h4 class="h4 mb-4">Inventario Innova</h4>
        </div>
	</div>
    <div class="card border-0 shadow-sm mt-3">			
        <div class="card-body col-sm-12">
            <h5 class="card-title"></h5>
            <div class="card border-0 shadow-sm mt-3 ">
                <div class="card-body col-sm-12 p-0 mb-2">
                    <div class="col-md-12 mb-3" >
                        <span id="id_form_role" style="display:none">{{ Session::get('user_role') }}</span>                        
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                            </div>								
                            <input type="text" id="id_txt_buscar" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>	
                    <div class="p-0 px-car">
                    <div class="table-responsive flex-between-center scrollbar border border-1 border-300 rounded-2">
                        <table id="table_inventario" class="table table-striped table-bordered table-sm mt-3 fs--1" width="100%">
                        <thead>
                            <tr class="bg-blue text-light">
                                <th style="width: 700px;">DESCRIPCIÓN</th>
                                <th style="width: 160px;">CANTIDAD</th>
                                <th style="width: 120px;">UND</th>
                                <th style="width: 160px;">JUMBOS</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($inventario as $iv)
                                    <tr>
                                        <td style="width: 700px;">
                                            <div class="d-flex align-items-center position-relative mt-2">                                
                                                <div class="flex-1 ms-3">
                                                <h6 class="mb-0 fw-semi-bold"><div class="stretched-link text-900">{{ $iv['DESCRIPCION'] }}</div></h6>
                                                <p class="text-500 fs--2 mb-0">{{ $iv['ARTICULO'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                       
                                        <td style="width: 160px; text-align:right;">
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">{{ $iv['CANTIDAD'] }}</h6>
                                            </div> 
                                        </td>

                                        <td style="width: 120px; text-align:center;">
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">{{ $iv['UND'] }}</h6>                    
                                            </div> 
                                        </td>
                                        <td style="width: 160px; text-align:right;">
                                            <div class="pe-4 border-sm-end border-200">
                                                <h6 class="fs--2 text-600 mb-1">{{ $iv['JUMBOS'] }}</h6>                  
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