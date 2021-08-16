@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_inventarioTotalizado');
@endsection
@section('content')  
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h4 class="h4 mb-4">Inventario Totalizado</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="input-group mt-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
				</div>
				<input type="text" id="InputDtShowSearchFilterInvTotal" class="form-control" placeholder="Buscar en Inventario" aria-label="Username" aria-describedby="basic-addon1">
			</div>
		</div>
		<div class="col-md-3 mt-3">
			<a id="exp-to-excel" href="#!" class="btn btn-light btn-block text-success float-left"><i class="fas fa-file-excel"></i> Exportar</a>
		</div>
		<div class="col-md-1">
			<div class="form-group">
				<label for="orderByDate" class="text-muted m-0">Ver</label>
				<select class="form-control form-control-sm" id="InputDtShowColumnsInvTotal" name="InputDtShowColumnsInvTotal">
					<option value="10" selected>10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="-1">Todo</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
	    <div class="col-12 mb-4">
	        <div class="table-responsive mt-3">
	            <table class="table table-bordered table-sm" width="100%" id="dtInventarioTotal"></table>
	        </div>
	    </div>
	</div>
</div>
@endsection