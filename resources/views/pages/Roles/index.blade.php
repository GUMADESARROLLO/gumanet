@extends('layouts.main')
@section('name_user' , 'Administrador')
@section('metodosjs')
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
                <a href="rol/crear" class="btn btn-primary mb-3 float-right">Nuevo rol</a>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>COD. ROL</th>
                            <th>DESCRIPCION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $rol)
                        <tr class="unread">
                            <td>{{$rol->id}}</td>
                            <td>{{$rol->nombre}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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