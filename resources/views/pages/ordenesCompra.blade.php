@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_ordenesCompra');
@endsection
@section('content')  
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<h5 class="card-title">ORDENES DE COMPRA</h5>
				  <div class="row">
				    <div class="col-sm-5 mt-4">
				      <div class="input-group">
				        <div class="input-group-prepend">
				          <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
				        </div>
				        <input type="text" id="buscadorOrden" class="form-control" placeholder="Buscar en Ordenes de Compra">
				      </div>
				    </div>
				    <div class="col-sm-1 mt-4">
				      <div class="input-group">
				        <select class="custom-select" id="dtLength" name="dtLength">
				          <option value="5" selected>5</option>
				          <option value="10">10</option>
				          <option value="20">20</option>
				          <option value="50">50</option>
				          <option value="-1">Todo</option>
				        </select>
				      </div>
				    </div>
				    <div class="col-sm-6 border-left">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">                
										<label for="f1">Buscar desde</label>
										<input type="text" class="input-fecha" id="f1">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">                
										<label for="f2">Hasta</label>
										<input type="text" class="input-fecha" id="f2">
									</div>
								</div>
								<div class="col-sm-4 mt-4">
									<a href="#!" class="btn btn-primary float-left" id="buscarOrdenes">Buscar</a>
								</div>
							</div>
				    </div>      
				  </div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
	    <div class="col-12 mb-4">
	        <div class="table-responsive mt-3">
	            <table class="table table-striped table-bordered table-sm post_back" width="100%" id="dtOrdenesCompra"></table>
	        </div>
	    </div>
	</div>
</div>
@endsection

