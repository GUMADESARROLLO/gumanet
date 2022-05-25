@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
@include('jsViews.js_vinneta');
@endsection
@section('content')  
<div class="container-fluid">	
		<div class="card border-0 shadow-sm mt-3 ">
			<div class="col-sm-12">
				<div class="card-body">					
					<div class="row ">
						<div class="col-sm-6 mt-4 ">
						<span id="id_form_role" style="display:none">{{ Session::get('user_role') }}</span>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
								</div>								
								<input type="text" id="txtSearch" class="form-control" placeholder="Buscar...">
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
									<a href="#!" class="btn btn-primary float-left" id="BuscarVinneta">
										<i class="material-icons text-white mt-1"  style="font-size: 20px">filter_list_alt</i>
									</a>
								</div>
							</div>
						</div>  
					</div>
				</div>
			</div>
		</div>
		<div class="card border-0 shadow-sm mt-3">			
			<div class="card-body col-sm-12">
				<h5 class="card-title">Por Facturas.</h5>
				<div class="row mt-3">
					<div class="col-sm-2">						
						<div class="card text-center">
							<div class="card-body">
								<h3 class="card-title" id="numero_factura">0.00</h3>
								<p class="card-text" id="">Facturas con Viñetas.</p>
							</div>
						</div>
					</div>

					<div class="col-sm-2">						
						<div class="card text-center">
							<div class="card-body">
								<h3 class="card-title" id="id_total_Facturado">C$ 0.00</h3>
								<p class="card-text" id="">Monto Total Facturado.</p>
							</div>
						</div>
					</div>

					<div class="col-sm-2">
						<div class="card text-center">
							<div class="card-body">
								<h3 class="card-title" id="MontoVinneta">C$ 0.00</h3>
								<p class="card-text">Total en Viñetas.</p>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-2">
						<div class="card text-center">
							<div class="card-body">
								<h3 class="card-title" id="id_roi"> 0.00</h3>
								<p class="card-text">ROI</p>
							</div>
						</div>
					</div>	

					<div class="col-sm-4">
						<div class="card text-center">
							<div class="card-body">
								<h3 class="card-title" id="MontoPagado">C$ 0.00</h3>
								<p class="card-text">Total Pagado en Viñetas</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">						
					<table class="table table-striped table-bordered table-sm post_back mt-3" width="100%" id="dtVinneta">
						<thead class="bg-blue text-light"></thead>
					</table>
				</div>	
			</div>
		</div>

		<div class="card border-0 shadow-sm mt-3">
			<div class="col-sm-12">				
				<div class="card-body">					
					<div class="row ">
						<div class="col-sm-7">						
							<h5 class="card-title">Viñetas Desalojadas</h5>
							<table class="table table-striped table-bordered table-sm post_back" width="100%" id="dtResumenVinneta" >
							<thead class="bg-blue text-light"></thead>
							<tfoot>
								<tr>
									<th colspan="11" style="text-align:right">Total:</th>
								</tr>
							</tfoot>
							</table>
						</div>
						
						<div class="col-sm-5">
							<h5 class="card-title text-right">Valor de Viñetas Pagadas.</h5>
							<table class="table table-striped table-bordered table-sm post_back" width="100%" id="dtRutas" >
							<thead class="bg-blue text-light"></thead>
							<tfoot>
								<tr>
									<th colspan="3" style="text-align:right">Total:</th>
								</tr>
							</tfoot>
							</table>
						</div>  
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="mdlAnulacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		<div class="modal-header">

			<div style="display:none">
				<span id="id_Factura">0</span><br>
				<span id="id_Linea">0</span><br>
				<span id="id_Cliente">0</span><br>
				<span id="id_ValorUnd">0</span>
			</div>
			
			<h5 class="modal-title" id="exampleModalLongTitle">Nº 
				<span id="id_Vinneta">0</span>
			</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label for="exampleFormControlInput1">Cantidad.</label>
				<input type="number" class="form-control" id="id_Cantidad" placeholder="0">
			</div>
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

<!-- Modal -->
<div class="modal fade " id="mdlHistory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document" >
		<div class="modal-content">
		<div class="modal-header">			
			<h5 class="modal-title" id="exampleModalLongTitle">Historial <span id="id_Factura_history">0</span></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" id="id_contenido_history" style="background-color: #f1f5f8;">
		</div>
		
		</div>
	</div>
</div>

@endsection

