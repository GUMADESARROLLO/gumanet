@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
@include('jsViews.js_inteligenciaMercado');
@endsection
@section('content')
<?php setlocale(LC_TIME, "spanish") ?>
<link rel="stylesheet" type="text/css" href="{{ url('css/daterangepicker.min.css') }}">
<div class="container-fluid">	
	<div class="row">
		<div class="col-md-12">
			<h4 class="h4 mb-4">Inteligencia de Mercado</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="input-group mt-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
				</div>
				<input type="text" id="search" class="form-control" placeholder="Buscar por Titulo, Contenido, Autor o por Ruta Asignada" aria-label="Username" aria-describedby="basic-addon1">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="orderByDate" class="text-muted m-0">Ordenar por</label>
				<select class="form-control form-control-sm" id="orderByDate">
					<option value="desc">Recientes</option>
					<option value="asc">Mas antiguos</option>
				</select>
			</div>
		</div>
		<div class="col-sm-2 mt-3">
			<button id="dom-id" class="btn btn-light btn-block text-primary fa-1x"><i class="fas fa-calendar-day"></i> Filtro por Fechas</button>
		</div>
		<div class="col-sm-2 mt-3">
			<a id="exp-to-excel" href="#!" class="btn btn-light btn-block text-success" onclick="descargarArchivo()"><i class="fas fa-file-excel"></i> Exportar</a>
		</div>
	</div>
	<form id="fmrDescargarComent" method="post" action="dowloadComents"> @csrf </form>
	<div class="comentarios">
		@foreach( $comentarios as $key )

		<div class="card border-light mb-3 shadow-sm bg-white rounded">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10">
						<h5 class="card-title font-weight-bold text-primary">{{ $key->Titulo }}</h5>
						<p class="card-text">{{ $key->Contenido }}</p>					
					</div>
					<div class="col-md-2 ">
					@if ($key->Imagen!='' || $key->Imagen!=NULL)
						<img src="{{Storage::Disk('s3')->temporaryUrl('news/'.$key->Imagen, now()->addMinutes(5))}}" width="100" class="img-fluid rounded float-right" style="cursor: pointer" />
					@endif						
					</div>
				</div>
			</div>
			<div class="card-footer bg-white border-0">
				<div class="row">
					<div class="col-9">
						<p class="float-left font-weight-bold mr-4"><img src="./images/user.svg" class="img01" /> {{ $key->Nombre }}</p>
						<p class="float-left font-weight-bold mr-4">
						<img src="./images/clock.svg" class="img01" />
									{{ strftime('%a %d de %b %G', strtotime($key->Fecha)) }}. {{ date('h:i a', strtotime($key->Fecha)) }}
						</p>
						<p class="float-left font-weight-bold mr-4"><img src="./images/globe.svg" class="img01" /> {{ $key->Autor }}</p>
					</div>
					<div class="col-1 text-center">
					@if($key->Read == 0)
						<div class="alert-success font-weight-bold mr-1" role="alert" style="border-radius: 30px;">Nuevo!</div>
					@endif  
						
					</div class="col-2">
					<button class="btn btn-outline-primary btn-sm btnVerChat"
						data-id="{{ $key->id }}"
						data-titulo="{{ $key->Titulo }}"
						data-contenido="{{ $key->Contenido }}"
						data-nombre="{{ $key->Nombre }}"
						data-autor="{{ $key->Autor }}"
						data-fecha="{{ date('d/m/Y h:i a', strtotime($key->Fecha)) }}">
					Ver / Responder
				</button>

				</div>
			</div>
		</div>
		@endforeach
		<div class="row">
			<div class="col-md-12  text-center">
				{!! $comentarios->render() !!}
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalChat" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detalle del Comentario</h5>
        <button class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        {{-- Comentario principal --}}
        <div class="direct-chat-msg" id="comentario_principal"></div>

        <hr>

        {{-- Respuestas cargadas aquí --}}
        <div id="contenedor_respuestas" style="max-height: 260px; overflow-y:auto;"></div>

        <hr>

        {{-- Formulario respuesta --}}
        <form id="formRespuesta" method="POST" action="{{ route('comentarios') }}">
          @csrf
          <input type="hidden" name="comentario_id" id="comentario_id">
          <div class="input-group">
            <input type="text" name="respuesta" id="respuesta" class="form-control" placeholder="Escriba una respuesta..." required>
            <div class="input-group-append">
              <button class="btn btn-primary">Enviar</button>
            </div>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>


<script src="{{ url('js/jquery.daterangepicker.min.js') }}"></script>
@endsection