@extends('layouts.kardex')
@section('metodosjs')
@include('jsViews.js_inventarioINN')
@endsection
@section('content')
<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <h4 class="h4 mb-4">Inventario Innova</h4>
        </div>
	</div>
  

    <div class="card border-0 shadow-sm mt-3 ">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="flex-between-center scrollbar border border-1 border-300 rounded-2">
            <table id="table_resumen" class="table table-striped table-bordered table-sm mt-3 fs--1" width="100%">
              <thead>
                <tr class="bg-blue text-light">
                  <th >PRODUCTO</th>
                  <th >PT</th>
                  <th >JR</th>
                  <th>MP</th>
                  <th>TOTAL</th>
                  
                </tr>
                
              </thead>
              <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
              <tfoot>
                <tr class="bg-blue text-light">
                    <th colspan="4" style="text-align:right"></th>
                    <th></th>
                </tr>
            </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mt-3 ">
        <div class="card-body col-sm-12 p-0 mb-2">
            <div class="row col-md-12 mb-3 mt-3" >
                <span id="id_form_role" style="display:none">{{ Session::get('user_role') }}</span>                        
                <div class="input-group col-md-9">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                    </div>								
                    <input type="text" id="id_txt_buscar" class="form-control" placeholder="Buscar...">
                </div>
                <div class="col-md-3 border-left">
                    <div class="input-group">
                        <select class="custom-select"  id="id_select_mes">
                            
                                <option value="7" >1 SEMANA</option>
                                <option value="15" >2 SEMANAS</option>
                                <option value="1" >1 MES</option>
                                <option value="3" >3 MESES</option>
                                <option value="6" >6 MESES</option>
                                <option value="12" >12 MESES</option>
                            
                        </select>
                        <div class="btn input-group-text bg-transparent" id="id_btn_new">
                            <span class="fas fa-history fs--1 text-600"></span>
                        </div>
                    </div>
                </div>
            </div>	
            <div class="p-0 px-car">
            <div class="flex-between-center responsive mb-3" id="kardex">
                    
                
            </div>
            </div>
        </div>
    </div>
              

</div>


@endsection('content')