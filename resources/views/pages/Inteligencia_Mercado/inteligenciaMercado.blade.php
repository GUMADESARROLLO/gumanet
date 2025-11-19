@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
@include('jsViews.js_inteligenciaMercado');
@include('pages.Inteligencia_Mercado.css_imteligenciaMercado');
@endsection
@section('content')
<?php setlocale(LC_TIME, "spanish") ?>
<div class="container-fluid">	
	<div class="row">
		<div class="col-md-12">
			<h4 class="h4 mb-4">Inteligencia de Mercado</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-8">
			<div class="input-group mt-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
				</div>
				<input type="text" id="search" class="form-control" placeholder="Buscar por Titulo, Contenido, Autor o por Ruta Asignada" aria-label="Username" aria-describedby="basic-addon1">
			</div>
		</div>
		

		<div class="col-2">
			<div class="form-group mt-3">          
				<input type="text" class="input-fecha" name="dt_range" />
			</div>     
		</div>
		
		<div class="col-2 mt-3">
			<a id="exp-to-excel" href="#!" class="btn btn-light btn-block text-success" onclick="descargarArchivo()"><i class="fas fa-file-excel"></i> Exportar</a>
		</div>
	</div>
	<form id="fmrDescargarComent" method="post" action="dowloadComents"> @csrf </form>
	<div class="comentarios">
		 @include('pages.Inteligencia_Mercado.cards_Comentarios', ['comentarios' => $comentarios])
	</div>


<div class="modal fade" id="modalChat" tabindex="-1">
  <div class="modal-dialog modal-xl" style="max-width:80% !important;">
    <div class="modal-content" style="height:97vh;">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">COMENTARIO</h5>
        <button class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        {{-- Comentario principal --}}
        <div class="direct-chat-msg" id="comentario_principal"></div>

        <hr>

        {{-- Respuestas cargadas aquí --}}
        <div id="contenedor_respuestas" style="max-height: 600px; overflow-y:auto;"></div>

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


@endsection