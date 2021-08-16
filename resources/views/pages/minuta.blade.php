@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_minutasCorp');
@endsection
@section('content')
<?php setlocale(LC_TIME, "spanish") ?>
<link rel="stylesheet" type="text/css" href="{{ url('css/daterangepicker.min.css') }}">
<div class="container-fluid">	
	<div class="row">
		<div class="col-md-10">
			<h4 class="h4 mb-4">Minutas Corporativas</h4>
		</div>
		<div class="col-md-2">
			<a href="minutaCU" class="btn btn-primary btn-block"><span data-feather="edit-3"></span> Redactar</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="input-group mt-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
				</div>
				<input type="text" id="search" class="form-control" placeholder="Buscar por Titulo, Contenido o Autor" aria-label="Username" aria-describedby="basic-addon1">
			</div>
		</div>
		<div class="col-md-2">
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
	</div>
	<div class="blogs mb-5">
		@if ( $cant==0 )
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<p class="text-center font-weight-bolder">Aun no se han cargado ningun registro</p>
						<center><img src="./images/icon_sinresultados.png" width="100" class="mt-4 mb-4" /></center>
					</div>
				</div>
			</div>
		</div>
		@else
		@foreach( $blogs as $key )
		<div class="card border-light mb-3 shadow-sm bg-white rounded" id="{{$key->idMinuta}}">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10">
						<h5 class="mb-3"><a href="minuta/{{$key->idMinuta}}/ver" style="text-decoration: none">{{ $key->titulo }}</a></h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<p class="card-text">{{ $key->contenido_min }}</p>
					</div>
				</div>		
			</div>
			<div class="card-footer bg-white border-0">
				<div class="row">
					<div class="col-md-10">
						<p class="float-left text-muted font-weight-normal"><span data-feather="user-check"></span> Por {{ $key->nombre_completo }}. <span class="ml-3" data-feather="calendar"></span> {{ strftime('%A, %d de %B %G', strtotime($key->fecha)) }} | {{ $key->nombre }}</p>
					</div>
					@if(Auth::User()->id==$key->idUser)
					<div class="col-md-2">
	            		<a class="nav-link text-secondary float-right p-1" href="#!" onclick="deleteMinuta({{ $key->idMinuta }})"><span data-feather="trash-2"></span></a>
	            		<a class="nav-link text-secondary float-right p-1" href="minuta/{{$key->idMinuta}}/edit"><span data-feather="edit"></span></a>
					</div>
					@endif
				</div>
			</div>
		</div>
		@endforeach
		@endif 

		<div class="row">
			<div class="col-md-12">
				{!! $blogs->render() !!}
			</div>
		</div>
	</div>
</div>

@endsection