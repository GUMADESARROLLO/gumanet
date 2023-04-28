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
    <div class="card border-0 shadow-sm mt-3">			
        <div class="card-body col-sm-12">
            <h5 class="card-title"></h5>
            <div class="card border-0 shadow-sm mt-3 ">
                <div class="card-body col-sm-12 p-0 mb-2">
                    <div class="row col-md-12 mb-3" >
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
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ Carbon\Carbon::createFromFormat('m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                                <div class="btn input-group-text bg-transparent" id="id_btn_new">
                                    <span class="fas fa-history fs--1 text-600"></span>
                                </div>
                            </div>
                        </div>
                    </div>	
                    <div class="p-0 px-car">
                    <div class="flex-between-center mb-3" id="kardex">
                        <table class="table table-bordered" id="tbl_kardex" style="width:100%;">

                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>      

</div>


@endsection('content')