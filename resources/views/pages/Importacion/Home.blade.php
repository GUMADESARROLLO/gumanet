@extends('layouts.main')
@section('title' , $data['name'])
@section('name_user' , 'Administrador')
@section('metodosjs')
@include('Pages.Importacion.js_importacion');
@include('Pages.Importacion.css_importacion');

@endsection
@section('content')
<div class="container-fluid">


    
    <div class="row " >
        <div class="col-md-12">            
            <h4 class="h4 text-umk">REPORTE DE IMPORTACIONES</h4>
            <p class="text-muted mb-4">En esta sección podrás visualizar los reportes de importaciones de productos, así como los competidores y sus marcas.</p>
        </div>
        <div class="col-md-10" >
            <div class="form-group">
                <label for="Id_Molecula" class="col-form-label-sm text-muted mb-0">MOLECULAS</label>
                <select class="selectpicker form-control form-control-sm" id="Id_Molecula" data-show-subtext="true" data-live-search="true">                    
                    @foreach($ImportacionMuestra as $key)
                    <option value="{{ $key['ARTICULO'] }}">{{ $key['DESCRIPCION'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>	

        <div class="col-md-2">
            <button id="IdFilterMolecula" class="btn btn-primary-umk btn-block float-right mt-4 ">FILTRAR</button>		
        </div>

        <div class="col-sm-6 mt-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white bg-umk border-0">
                    <div class="d-flex justify-content-between bg-umk">
                        <h6 class="font-weight-bold mb-0">COMERCIO NICARAGUA $</h6>
                        <span class="rounded-pill badge-ranking "><i class="fas fa-star"></i> 5 / 10 Ranking</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                         <div class="col-sm-12 col-md-5 col-lg-3 text-center">                    
                            <p class="text-muted m-0" > - </p>
                            <p class="font-weight-bolder text-style" style="">MERCADO</p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-3 text-center">                    
                            <p class="text-muted m-0" id="text-anio-pasado-valor"> -  </p>
                            <p class="font-weight-bolder text-style" id="val-anio-pasado-valor" >$ 0.00 </p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-4 text-center">
                            <p class="text-muted m-0" id="text-anio-actual-valor"> - </p>
                            <p class="font-weight-bolder text-style" id="val-anio-actual-valor">$ 0.00 </p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-2 text-center">
                            <p class="text-muted m-0">%.</p>
                            <p class="font-weight-bolder text-style" id="dif-porcen-valor" > - </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5 col-lg-3 text-center">      
                            <p class="font-weight-bolder text-style" >UNIMARK S,A.</p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-3 text-center">
                            <p class="font-weight-bolder text-style" >$ 1,000,000.00 </p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-4 text-center">
                            <p class="font-weight-bolder text-style" >$ 1,000,000.00</p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-2 text-center">
                            <p class="font-weight-bolder text-style" > 35 %</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 mt-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white bg-umk border-0">
                    <div class="d-flex justify-content-between ">
                        <h6 class="mb-0 font-weight-bold">COMERCIO NICARAGUA UNIDADES HOMOLOGADAS</h6>
                        <span class="badge-ranking mb-0"><i class="fas fa-star"></i> 5 / 10 Ranking</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" >
                        <div class="col-sm-12 col-md-5 col-lg-5 text-center">                    
                            <p class="text-muted m-0" id="text-anio-pasado-unidades"> - </p>
                            <p class="font-weight-bolder text-style" id="val-anio-pasado-unidades" > 0.00 </p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-5 text-center">
                            <p class="text-muted m-0" id="text-anio-actual-unidades">-</p>
                            <p class="font-weight-bolder text-style" id="val-anio-actual-unidades" > 0.00 </p>
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2 text-center">
                            <p class="text-muted m-0">%.</p>
                            <p class="font-weight-bolder text-style" id="dif-porcen-unidades" > - </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5 col-lg-5 text-center">
                            <p class="font-weight-bolder text-style" >1,000,000.00 </p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-5 text-center">
                            <p class="font-weight-bolder text-style" >1,000,000.00</p>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-2 text-center">
                            <p class="font-weight-bolder text-style" > 35 %</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
			<h4 class="h4 text-umk mt-3">TOP 5 COMPETIDORES</h4>
            <p class="text-muted">A continuación se presentan los 5 competidores que más importaciones han realizado en el año actual y el proyectado para el próximo año.</p>
		</div>

        <div class="col-sm-12">
            <table id="tbl_competidores" class="table table-striped" width="100%">
                <thead>
                    <tr class="text-center bg-umk text-white">
                        <th>COMPETIDOR</th>
                        <th>MARCA</th>
                        <th>2024.</th>
                        <th>2025.</th>
                        <th>%</th>
                        <th>ORIGEN</th>
                        <th>2024.</th>
                        <th>2025.</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody></tbody>
                </tbody>
            </table>
        </div>
		
    </div>


</div> 
@endsection