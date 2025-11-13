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
		 @include('pages.Inteligencia_Mercado.cards_Comentarios', ['comentarios' => $comentarios])
	</div>


<div class="modal fade" id="modalChat" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">COMENTARIO</h5>
        <button class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        {{-- Comentario principal --}}
        <div class="direct-chat-msg" id="comentario_principal"></div>

        <hr>

        {{-- Respuestas cargadas aquí --}}
        <div id="contenedor_respuestas" style="max-height: 460px; overflow-y:auto;"></div>

        <hr>

        {{-- Formulario respuesta --}}
        <form id="formRespuesta" method="POST" action="{{ route('comentarios') }}">
          @csrf
          <input type="hidden" name="comentario_id" id="comentario_id">
          <div class="input-group">
            <textarea name="respuesta" id="respuesta" class="form-control" placeholder="Escriba una respuesta..." required></textarea>
            <div class="input-group-append">
              <button class="btn btn-primary"><i class='fas fa-paper-plane'></i></span></button>
            </div>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>


<script src="{{ url('js/jquery.daterangepicker.min.js') }}"></script>
@endsection