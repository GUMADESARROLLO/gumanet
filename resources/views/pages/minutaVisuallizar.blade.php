@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_minutasVisualizar');
@endsection
@section('content')
<?php setlocale(LC_TIME, "spanish") ?>
@if($action=='ver')
<div class="container-fluid">	
	<div class="row">
		<div class="col-md-12">
			<div class="card text-white border-light shadow-sm bg-white rounded">
				<img src="{{ url('images/blog.png') }}">
				<div class="card-img-overlay mt-5">
					@foreach( $blog as $key )
					<h1>{{ $key->titulo }}</h1>
					<p class="card-text">{{ strftime('%A, %d de %B %G', strtotime($key->fecha)) }} - Por {{ $key->nombre_completo }}</p>
					@endforeach
				</div>
			</div>
			@foreach( $blog as $key )
			<div class="card border-light shadow-sm bg-white rounded mb-5">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							{!! $key->contenido_max !!}
						</div>
					</div>		
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
@elseIf($action=='edit')
<div class="container-fluid">	
	<div class="row">
		<div class="col-md-8">
			<h4 class="h4 mb-4">Nueva Minuta Corporativa</h4>
		</div>
		<div class="col-md-2">
			<a href="#!" id="cancelMinuta" class="btn btn-danger btn-block float-left"><span data-feather="slash"></span> Cancelar</a>			
		</div>
		<div class="col-md-2">
			<a href="#!" class="btn btn-success btn-block" id="updateMinuta"><span data-feather="save"></span> Actualizar</a>
		</div>
	</div>
	@foreach( $blog as $key )
	
	<form method="post" name="fmrMinuta" id="fmrMinuta" action="{{url('/updateMinuta')}}">
		@csrf
		<div class="row mt-5">
			<div class="col-md-12 mb-3">
				<input type="text" class="form-control" value="{{$key->titulo}}" id="tituloMinuta" name="tituloMinuta" placeholder="Inserte un titulo">
				<input type="hidden" name="idMinuta" id="idMinuta" value="{{$key->idMinuta}}">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<textarea required class="editor" name="content_max" id="content_max">
					{!! $key->contenido_max !!}
				</textarea>
			</div>
		</div>
	</form>
	@endforeach

</div>
@endif

@endsection