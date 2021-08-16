@extends('layouts.main')

@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_menus');
@endsection
@section('content')
<div class="container-fluid">
  <div class="row mb-5">
    <div class="col-md-10">
      <h4 class="h4">Menus</h4>
    </div>
  </div>
  <div class="row">
      <div class="col-12">
        <!--{{ Auth::User()->activeRole() }}-->
        <div class="card border-light mb-3 shadow-sm bg-white rounded">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form method="post" action="{{url('menu/guardar')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                        <small id="nombreHelp" class="form-text text-muted">Escriba el nombre del nuevo menu</small>
                    </div>
                    <div class="form-group">
                        <label for="url">Url</label>
                        <input type="text" class="form-control" name="url" id="url">
                        <small id="urlHelp" class="form-text text-muted">Escriba direccion url</small>
                    </div>
                    <div class="form-group">
                        <label for="icono">Icono</label>
                        <input type="text" class="form-control" name="icono" id="icono">
                        <small id="iconoHelp" class="form-text text-muted">Icono del item</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
              </div>
            </div>
          </div>
          <div class="card-footer bg-white border-0">
          </div>
        </div>
      </div>
  </div>
</div>
@endsection