@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
	@include('jsViews.js_proyecciones');
@endsection
@section('content')  
<div class="row" style="margin: 0 auto">
    <div class="card border-0 shadow-sm mt-3" style="width: 100%">
      <div class="card-body">                
        <h5 class="card-title">{{ $page }}</h5>
        <div class="row">
            <div class="col-sm-8">
                 <div class="input-group mb-3">
                    <select class="custom-select" id="cmbUnidad" name="cmbUnidad">
                        <option value="not" selected>Seleccione</option>
                        <option value="c_a">Cruz Azul</option>
                        <option value="m_p">Mercado Privado</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
            	<a href="#!" class="btn btn-primary" id="btnVerPro">Ver</a>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive mt-3 mb-5">
			<table id="dtProyecciones" class="table table-bordered" width="100%">
				<thead class="text-center">
					<tr>
						<th>ARTICULO</th>
						<th>DESCRIPCION</th>
						<th>CATEGORIA</th>
						<th>ORDEN MIN</th>
						<th>EMPAQUE(UD)</th>
						<th>...</th>
					</tr>
				</thead>
				<tbody>

                </tbody>
			</table>
        </div>
    </div>
</div>
<!-- PAGINA TEMPORAL DE DETALLES -->
<div id="page-details" class="p-4 border-left" style="background-color: #f1f5f8">
    <div class="row">
        <div class="col-lg-12">
            <a href="#!" class="active-page-details btn btn-outline-primary btn-sm">Regresar</a>
        </div>
    </div>
    <div class="row mt-3" id="body-modal">

    </div>
</div>
@endsection