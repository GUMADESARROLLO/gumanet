@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
@include('jsViews.js_vinneta_liq');
@endsection
@section('content')  
<div class="container-fluid">	
		<div class="card border-0 shadow-sm mt-3 ">
			<div class="col-sm-12">
				<div class="card-body">					
					<div class="row ">

						<div class="col-sm-4 mt-4 ">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
								</div>
								<input type="text" id="txtSearch" class="form-control" placeholder="Buscar...">
							</div>
						</div>

						<div class="col-sm-1 mt-4 ">
							<div class="input-group">
								<select class="custom-select" id="dtRutas" name="dtClientes">
								<option value="5" selected>Clientes</option>
								</select>
							</div>
						</div>

						<div class="col-sm-1 mt-4 ">
							<div class="input-group">
								<select class="custom-select" id="dtRutas" name="dtRutas">
								<option value="5" selected>Rutas</option>
								</select>
							</div>
						</div>

						<div class="col-sm-1 mt-4 ">
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
						
						<div class="col-sm-5 border-left">
							<div class="row ">
								<div class="col-sm-5 ">
									<div class="form-group">                
										<label for="f1">Desde:</label>
										<input type="text" class="input-fecha" id="f1">
									</div>
								</div>
								<div class="col-sm-5 ">
									<div class="form-group">                
										<label for="f2">Hasta:</label>
										<input type="text" class="input-fecha" id="f2">
									</div>
								</div>
								<div class="col-sm-2 mt-4 ">
									<a href="#!" class="btn btn-primary float-left" id="BuscarVinneta">Filtrar</a>
								</div>
							</div>
						</div>  
					</div>
				</div>
			</div>
		</div>
		<div class="card border-0 shadow-sm mt-3">	
			<div class="card-body col-sm-12">								
				<table class="table table-striped table-bordered table-sm post_back mt-3" width="100%" id="dtVinneta">
					<thead class="bg-blue text-light"></thead>
				</table>
			</div>
		</div>				
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="mdlAnulacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle">Anulación de recibo</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<span id="id_request">0</span>
			<div class="form-group">
				<label for="message-text" class="col-form-label">Comentario:</label>
				<textarea class="form-control" id="message-text"></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="id_frm_save_anulacion">Guardar</button>
		</div>
		</div>
	</div>
</div>
@endsection

