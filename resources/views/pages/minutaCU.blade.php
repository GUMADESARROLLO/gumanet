@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_minutaCorpCU');
@endsection
@section('content')
<div class="container-fluid">	
	<div class="row">
		<div class="col-md-8">
			<h4 class="h4 mb-4">Nueva Minuta Corporativa</h4>
		</div>
		<div class="col-md-2">
			<a href="#!" id="cancelMinuta" class="btn btn-danger btn-block float-left"><span data-feather="slash"></span> Cancelar</a>			
		</div>
		<div class="col-md-2">
			<a href="#!" class="btn btn-success btn-block" id="guardarMinuta"><span data-feather="save"></span> Guardar</a>
		</div>
	</div>
	<form method="post" name="fmrMinuta" id="fmrMinuta" action="{{url('/saveMinuta')}}">
		@csrf
		<div class="row mt-5">
			<div class="col-md-12 mb-3">
				<input type="text" class="form-control" id="tituloMinuta" name="tituloMinuta" placeholder="Inserte un titulo">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<textarea required class="editor" name="content_max" id="content_max"></textarea>
			</div>
		</div>
	</form>
</div>
@endsection