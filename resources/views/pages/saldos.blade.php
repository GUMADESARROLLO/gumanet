@extends('layouts.main')
@section('title', $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_saldos')
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6">
      <h4 class="h4 mb-4">Saldos</h4>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-sm-11">
       <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
            </div>
            <input type="text" id="InputDtShowSearchFilter" class="form-control" placeholder="Buscar en saldos" aria-label="Username" aria-describedby="basic-addon1">
        </div>
    </div>
    <div class="col-sm-1">
         <div class="input-group mb-3">
            <select class="custom-select" id="InputDtShowColumns" name="InputDtShowColumns">
                <option value="5" selected>5</option>
                <option value="10">10</option>
                <option value="-1">Todo</option>
            </select>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
      <table id="tbSaldos" class="table table-bordered mt-3" width="100%">
       <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">TOTALES: </th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
      </table>
      </div>
    </div>
  </div>
</div>
@endsection