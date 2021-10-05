@extends('layouts.main')
@section('title' , $data['name'])
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_reportes');
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h4 class="h4 mb-4">REPORTE DE VENTAS</h4>
		</div>
	</div>
	<div class="row" style="display: none;">
		
		<div class="col-md-6">
			<div class="form-group">
				<label for="cmbArticulo" class="col-form-label-sm text-muted mb-0">Articulos</label>
				<select class="selectpicker form-control form-control-sm" id="cmbArticulo" data-show-subtext="true" data-live-search="true">
					<option value="" selected>ARTICULOS - TODOS</option>
					@foreach($articulos as $key)
					<option value="{{$key['ARTICULO']}}">[{{ $key['ARTICULO']}}] - {{ $key['DESCRIPCION']}}</option>
					@endforeach
				</select>
			</div>
		</div>
		@if( Session::get('company_id')==4 )
		<div class="col-md-6">
			<div class="form-group">
				<label for="cmbRutas" class="col-form-label-sm text-muted mb-0">Rutas</label>
				<select class="selectpicker form-control form-control-sm" id="cmbRutas" data-show-subtext="true" data-live-search="true">
					<option value="">RUTAS - TODOS</option>
					@foreach($rutas as $key)
						<option>{{ $key['VENDEDOR'] }}</option>
					@endforeach
				</select>
			</div>
		</div>
		@else
		<div class="col-md-2">
		  <div class="form-group">
		    <label for="cmbClase" class="col-form-label-sm text-muted mb-0">Clase terapeutica</label>
			<select class="selectpicker form-control form-control-sm" id="cmbClase" data-show-subtext="true" data-live-search="true">
				<option value="">CLASE TERAPEUTICA - TODOS</option>
				@foreach($clases as $key)
					@if($key['clase'] != '')
						<option value="{{ $key['clase'] }}">{{ strtoupper($key['clase']) }}</option>							
					@endif				
				@endforeach
			</select>
		  </div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="cmbRutas" class="col-form-label-sm text-muted mb-0">Rutas</label>
				<select class="selectpicker form-control form-control-sm" id="cmbRutas" data-show-subtext="true" data-live-search="true">
					<option value="">RUTAS - TODOS</option>
					@foreach($rutas as $key)
						<option>{{ $key['VENDEDOR'] }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="cmbCliente" class="col-form-label-sm text-muted mb-0">Clientes</label>
				<select class="selectpicker form-control form-control-sm" id="cmbCliente" data-show-subtext="true" data-live-search="true">					
					<option selected value="">CLIENTES - TODOS</option>
					@foreach($clientes as $key)
						
						<option value="{{$key['CLIENTE']}}">{{ $key['NOMBRE'] }}</option>
					@endforeach
				</select>
			</div>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-md-6" >
			<div class="form-group">
				<label for="cmbLabs" class="col-form-label-sm text-muted mb-0">Laboratorio</label>
				<select class="selectpicker form-control form-control-sm" id="cmbLabs" data-show-subtext="true" data-live-search="true">
				<option value="">LABORATORIOS - TODOS</option>
					@foreach($Labs as $key)
					<option>{{ $key['DESCRIPCION'] }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="cmbMes" class="col-form-label-sm text-muted mb-0">Mes</label>
				<select class="form-control form-control-sm float-right d-block" id="cmbMes">
					<option value="all">Todos</option>
					<?php
						setlocale(LC_ALL, 'es_ES');
						$mes = date("m");

						for ($i= 1; $i <= 12 ; $i++) {
							$dateObj   = DateTime::createFromFormat('!m', $i);
							$monthName = strftime('%B', $dateObj->getTimestamp());

							if ($i==$mes) {
								echo'<option selected value="'.$i.'">'.$monthName.'</option>';
							}else {
								echo'<option value="'.$i.'">'.$monthName.'</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="cmbAnio" class="col-form-label-sm text-muted mb-0">Año</label>
				<select class="form-control form-control-sm" id="cmbAnio">
				<?php
					$year = date("Y");
					for ($i= 2018; $i <= $year ; $i++) {
						if ($i==$year) {
							echo'<option selected value="'.$i.'">'.$i.'</option>';
						}else {
							echo'<option value="'.$i.'">'.$i.'</option>';
						}
					}
				?>
				</select> 
			</div>  
		</div>
		<div class="col-md-2">
			<a href="#!" id="filterData" class="btn btn-primary btn-block float-right mt-4 ">Aplicar</a>			
		</div>
	</div>
    <div class="row mt-3">
    	<div class="col-sm-12">
    		<div class="card border-0 shadow-sm">
    			<div class="card-body">
    				<h5 class="card-title">Articulos</h5>
					<div class="row">
			            <div class="col-sm-11">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
								</div>
								<input type="text" id="btnSearchArt" class="form-control" placeholder="Buscar" aria-label="Username" aria-describedby="basic-addon1">
							</div>
			            </div>
			            <div class="col-sm-1">
							<div class="input-group mb-3">
								<select class="custom-select" id="cmbTableArticulos" name="cmbTableArticulos">
									<option value="10" selected>10</option>
									<option value="20">20</option>
									<option value="50">50</option>
									<option value="100">100</option>
									<option value="-1">Todo</option>
								</select>
							</div>
			            </div>
			        </div>

    				<div class="table-responsive mt-3 mb-5">
						<div class="progress" style="height: 1px;">
							<div class="progress-bar bg-danger" style="display: none" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<table id="tblArticulos" class="table table-bordered" width="100%">
							<thead class="bg-blue text-light">
								<tr>
									<th class="text-center">ARTICULO</th>
									<th class="text-center">DESCRIPCION</th>
									<th class="text-center">TOT. FACT</th>
									<th class="text-center">UNIT. FACT.</th>
									<th class="text-center">UNIT. BONIF</th>
									<th class="text-center">PREC. PROM.</th>
									<th class="text-center">COSTO. PROM. UNIT</th>
									<th class="text-center">CONTRIBUCION</th>
									<th class="text-center">% MARGEN BRUTO</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
    				</div>
			        <div class="row">
			            <div class="col-sm-6">
			                <div class="card text-center">
			                  <div class="card-body">
			                    <h5 class="card-title" id="MontoMeta2">C$ 0.00</h5>
			                    <p class="card-text" id="txtMontoMeta">Total articulo</p>
			                  </div>
			                </div>
			            </div>
						<div class="col-sm-6">
			                <div class="card text-center">
			                  <div class="card-body">
			                    <h5 class="card-title" id="MontoUnidad">0.00</h5>
			                    <p class="card-text" id="txtMontoUnidad">Total Unidades</p>
			                  </div>
			                </div>
			            </div>
			        </div>
    			</div>
    		</div>
    	</div>
		
		<div class="col-sm-4" hidden="true">
			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<div id="container01"></div>
				</div>		
			</div>
		</div>
    </div>
    <div class="row mt-3">
    	<div class="col-sm-12">
    		<div class="card border-0 shadow-sm">
    			<div class="card-body">
    				<h5 class="card-title">Clientes</h5>
			        <div class="row">
			            <div class="col-sm-11">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
								</div>
								<input type="text" id="btnSearchCl" class="form-control" placeholder="Buscar" aria-label="Username" aria-describedby="basic-addon1">
							</div>
			            </div>
			            <div class="col-sm-1">
							<div class="input-group mb-3">
								<select class="custom-select" id="cmbTableCant" name="cmbTableCant">
									<option value="10" selected>10</option>
									<option value="20">20</option>
									<option value="50">50</option>
									<option value="100">100</option>
									<option value="-1">Todo</option>
								</select>
							</div>
			            </div>
			        </div>
    				<div class="table-responsive mt-3 mb-5">
						<div class="progress" style="height: 1px;">
							<div class="progress-bar bg-danger" style="display: none" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<table id="tblClientes" class="table table-bordered" width="100%">
							<thead class="bg-blue text-light">
								<tr>
									<th class="text-center">Codigo</th>
									<th class="text-center">Nombre</th>
									<th class="text-center">Ruta</th>
									<th class="text-center">Factura</th>
									<th class="text-center">Fecha</th>
									<th class="text-center">Monto</th>
									<th class="text-center">Unidades</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
    				</div>
			        <div class="row">
			            <div class="col-sm-6">
			                <div class="card text-center">
			                  <div class="card-body">
			                    <h5 class="card-title" id="MontoMeta">C$ 0.00</h5>
			                    <p class="card-text" id="txtMontoMeta">Total cliente</p>
			                  </div>
			                </div>
			            </div>
						<div class="col-sm-6">
			                <div class="card text-center">
			                  <div class="card-body">
			                    <h5 class="card-title" id="MontoUnidades">0.00</h5>
			                    <p class="card-text" id="txtMontoMeta">Total Unidades</p>
			                  </div>
			                </div>
			            </div>
			        </div>
    			</div>
    		</div>
    	</div>
		<div class="col-sm-4" style="display:none">
			<div class="card">
				<div class="card-body">
					<div id="container01"></div>
				</div>		
			</div>
		</div>
    </div>
    <!-- PAGINA TEMPORAL DE DETALLES -->
    <div id="page-details" class="p-4" style="background-color: #f1f5f8">
        <div class="row">
            <div class="col-sm-12">
                <a href="#!" class="active-page-details btn btn-outline-primary btn-sm">Regresar</a>
            </div>
        </div>
        <div class="row center">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-body">
	                    <h5 class="card-title" id="title-page-tem">DETALLE DE FACTURA: <span id="txtNFactDF"> $0.00</span></h5>
	                    <hr>
	                    <div class="row">
	                        <div class="col-2">
	                        	<div class="col-12">
	                        		<h6 >CÓDIGO</h6>
	                        	</div>
	                        	<div class="col-12">
	                        		<span id="txtCodDF"> $0.00</span>
	                        	</div>
	                        </div>
	                        <div class="col-3">
	                        	<div class="col-12">
	                        		<h6>NOMBRE</h6>
	                        	</div>
	                        	<div class="col-12">
	                        		<span id="txtNomDF"> $0.00</span>
	                        	</div>
	                        </div>
	                        <div class="col-2">
	                        	<div class="col-12">
	                        		<h6>RUTA</h6>
	                        	</div>
	                        	<div class="col-12">
	                        		<span id="txtRutaDF">$0.00</span>
	                        	</div>
	                        </div>
	                        <div class="col-2">
	                        	<div class="col-12">
	                        		<h6>FECHA</h6>
	                        	</div>
	                        	<div class="col-12">
	                        		<span id="txtFechaDF">$0.00</span>
	                        	</div>
	                        </div>
	                        <div class="col-2">
	                        	<div class="col-12">
	                        		<h6>MONTO</h6>
	                        	</div>
	                        	<div class="col-12">
	                        		<span id="txtMontoDF">$0.00</span>
	                        	</div>
	                        </div>
	                    </div>
	                    <hr>
	                    <div class="row">
	                        <div class="col-sm-12">
	                        	<div class="table-responsive mt-3 mb-5">
									<table id="tblDetalleFacturaVenta" class="table table-bordered" width="100%">
										<thead class="bg-blue text-light">
											<tr>
												<th>Articulo</th>
												<th>Nombre</th>
												<th class="text-center">Cantidad</th>
												<th>P. Unit</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
			    				</div>
	                        </div>
	                    </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection