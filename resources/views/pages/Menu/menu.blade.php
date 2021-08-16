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
                <a href="menu/crear" class="btn btn-primary mb-3 float-right">Nuevo menu</a>
                <table class="table table-bordered table-sm" width="100%" id="tblMenu">
                  <thead class="text-center">
                    <tr class="text-center">
                      <th>MENUS</th>
                      @foreach ($roles as $id => $nombre)
                        <th>{{$nombre}}</th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($menus as $key => $menu) 
                      <tr class="unread">
                        <td>{{$menu['nombre']}}</td>
                        @foreach ($roles as $id => $descripcion)
                        <td class="text-center">
                          <input type="checkbox" class="menu_rol" name="menu_rol[]" data-menuid={{$menu["id"]}}  value="{{$id}}" {{ in_array($id, array_column($menusRoles[$menu["id"]], "id"))? "checked":" "}}>
                        </td>
                        @endforeach
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