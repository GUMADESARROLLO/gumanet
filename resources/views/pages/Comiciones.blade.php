@extends('layouts.main')
@section('metodosjs')
@include('jsViews.js_comiciones')
@endsection
@section('content')
<div class="container-fluid">	
		<div class="card border-0 shadow-sm mt-3 ">
			<div class="col-sm-auto">
				<div class="card-body">					
						<div class="row">
						<div class="col-md-5">
						  <span id="id_form_role" style="display:none">{{ Session::get('user_role') }}</span>
							
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
								</div>								
								<input type="text" id="id_txt_buscar" class="form-control" placeholder="Buscar...">
							</div>
						</div>
            <div class="col-md-1"></div>
              <div class="col-md-2 border-left">
                <select class="custom-select"  id="id_select_mes">
                  
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ Carbon\Carbon::createFromFormat('m', $i)->format('F') }}</option>
                @endfor
                </select>
              </div>
              <div class="col-md-3">
                <div class="input-group" >
                  <select class="custom-select"  id="id_select_year">
                      @foreach (range(date('Y'),date('Y')-1) as $year)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                      @endforeach  
                  </select>
                  <div class="btn input-group-text bg-transparent" id="id_btn_new">
                        <span class="fas fa-history fs--1 text-600"></span>
                    </div>
                    <!--<div class="btn input-group-text bg-transparent" onClick="{{url('creditosIndex')}}" id="id_credito_new">
                        <span class="fas fa-file-invoice fs--1 text-600"></span>
                    </div>-->
                </div>
              </div> 
              <div class="col-md-1 ">
                <div class="input-group">
                  <select class="custom-select" id="frm_lab_row" name="frm_lab_row">
                  <option value="5" selected>5</option>
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="-1">*</option>
                  </select>
                </div>
              </div>
					</div>
				</div>
			</div>
		</div>		
		
    <div class="card border-0 shadow-sm mt-3 ">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="flex-between-center border border-1 border-300 rounded-2">
            <table id="table_comisiones" class="table table-striped table-bordered table-sm mt-3 fs--1" width="100%">
              <thead>
                <tr class="bg-blue text-light">
                  <th colspan="2">VENDEDOR</th>
                  <th colspan="2">COMISIÓN DE VENTA</th>
                  <th colspan="4">TOTAL BONOS Y COMISIONES</th>
                  <th>TOTAL BONOS Y COMISIONES</th>
                  <th>TOTAL</th>
                  
                </tr>
                <tr>                       
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody><tr><td colspan="10" style="text-align: center;"><b>Cargando...</b></td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
			
</div>
<!--=====================================
MODAL 
======================================-->

<div class="modal fade" id="modalHistoryItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
		<div class="modal-header bg-blue text-light" >
      <div class="row col-md-5">
      <b><label for="f2">Listado de Articulos que Confirman el 80 / 20 de la ruta</label></b></br>
      <div class="input-group" style="padding-top: 10px;">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
        </div>								
        <input type="text" id="id_txt_History" class="form-control" placeholder="Buscar...">
      </div>
    </div>
      <div class="col-sm-7 border-left">
        <div class="row ">
        <div class="col-sm-2 ">
            <div class="form-group">                
              <b><label for="f2">SKUS</label></b></br>
              <label for="f1" id="lbl_80"> </label> <span class="badge rounded-pill badge-light text-primary"><span class="fas fa-caret-up text-primary" ></span> 80 %</span>
            </div>
          </div>	<div class="col-sm-2 border-left">
            <div class="form-group">                
              <b><label for="f2">SKUS</label></b></br>
              <label for="f1" id="lbl_20"> </label> <span class="badge rounded-pill badge-light text-primary"><span class="fas fa-caret-up text-primary"></span> 20 %</span>
            </div>
          </div>	
          <div class="col-sm-2 border-left">
            <div class="form-group">                
              <b><label for="f1">META UND</label></b></br>
              <label for="f1" id="lbl_meta"></label>
            </div>
          </div>
          <div class="col-sm-3 border-left">
            <div class="form-group">                
              <b><label for="f1">VENTA UND</label></b></br>
              <label for="f1" id="lbl_venta"></label>
            </div>
          </div>
          <div class="col-sm-3 border-left">
            <div class="form-group">                
              <b><label for="f2">VENTA VALOR</label></b></br>
              C$<label for="f1" id="lbl_val"> </label>
            </div>
          </div>
                      
        </div>
			</div>
		</div>
		<div class="modal-body">	
      
			<div class="mt-3" id="dtViewHistory"></div>

		</div>
		<div class="modal-footer">			
		</div>
		</div>
	</div>
</div>

@endsection('content')