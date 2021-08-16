@extends('layouts.main')

@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_menus');
@endsection
@section('content')
<div class="container-fluid">
  <div class="row mb-5">
    <div class="col-md-10">
      <h4 class="h4">Roles</h4>
    </div>
  </div>
  <div class="row">
      <div class="col-12">
        <!--{{ Auth::User()->activeRole() }}-->
        <div class="card border-light mb-3 shadow-sm bg-white rounded">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form method="post" action="{{url('rol/guardar')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                        <small id="nombreHelp" class="form-text text-muted">Escriba el nombre del nuevo rol</small>
                    </div>
                    <div class="form-group">
                        <label for="desc">Descripcion</label>
                        <input type="text" class="form-control" name="desc" id="desc">
                        <small id="urlHelp" class="form-text text-muted">Escriba la descripcion</small>
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