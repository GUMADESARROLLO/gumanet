@extends('layouts.main')
@section('title' , $data['name'])
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_usuario');
@endsection
@section('content')  
<div class="row" style="margin: 0 auto">
    <div class="card mt-3" style="width: 100%">
      <div class="card-body">                
        <h5 class="card-title">{{ $data['page'] }}</h5>
        <div class="row">
            <div class="col-sm-9">
               <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                    </div>
                    <input type="text" id="InputDtShowSearchFilterUser" class="form-control" placeholder="Buscar" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="col-sm-1">
                 <div class="input-group mb-3">
                    <select class="custom-select" id="InputDtShowColumnsUser" name="InputDtShowColumnsUser">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="Todo">Todo</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
               <div class="input-group">
                     <a href="{{ route('register') }}" style="width: 100%" class="btn btn-primary">{{ 'Agregar' }}</a> 

                </div>
            </div>
        </div>
      </div>
    </div>
</div>  
<div class="row">
    <div class="col-12">
        <div class="table-responsive mt-3 mb-5">
            <table class="table table-bordered table-sm" width="100%" id="dtUsuarios">
            	<thead class="text-center">
                    <tr>
                        <th>NOMBRE</th>
                        <th>USUARIO</th>
                        <th>ROL</th>
                        <th>DESCRIPCIÓN</th>
                        <th>FECHA INGRESO</th>
                        <th>ESTADO</th>
                        <th >OPCIONES</th>
                    </tr>
            	</thead>
                <tbody>
                    @csrf
                    @foreach($users as $user)
                        <tr class="post{{ $user->id }}">
                            <td>{{ $user->name." ".$user->surname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{ App\Role::find($user->role)->nombre }}
                            </td>
                            <td>{{ $user->description }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                @if($user->estado == 0)
                                    Activo
                                @else
                                    Inactivo
                                @endif
                            </td>
                            <td style="width: 120px">
                                <center>
                                {{-- <a href='#' class ='show-modal btn  btn-sm' data-id='{{ $user->id }}'><span data-feather='eye'></span></a> --}}
                                <a href='#' class ='btn btn-sm tooltip-test' title="Editar" data-toggle="modal" id="editUserModal" data-target="#modalEditUsuario" data-id='{{ $user->id }}' data-name='{{ $user->name }}' data-surname='{{ $user->surname }}' data-email='{{ $user->email }}' data-role='{{ $user->role }}' data-description='{{ $user->description }}'><span data-feather='edit'></span></a>
                                <a href='#' class ='delete-modal btn btn-sm tooltip-test' title="Eliminar" data-toggle="modal" id="deleteUserModal" data-target="#modalEliminarUsuario" data-id='{{ $user->id }}'><span data-feather='trash-2'></span></a>
                                @if($user->estado == 0)
                                    <a href='#' class ='btn btn-sm tooltip-test estadoBtn' title="Desactivar"  data-id='{{ $user->id }}'  data-status='0'><span data-feather='x'></span></a>
                                @else
                                    <a href='#' class ='btn btn-sm tooltip-test estadoBtn' title="Activar"  data-id='{{ $user->id }}' data-status='1'><span data-feather='check'></span></a>
                                @endif
                            </center>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDITAR USUARIO --}}

<div class="modal fade" id="modalEditUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Editar uausrio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditUser" role="modal">
            @csrf
            <div class="form-group row" hidden>
                <label for="id" class="col-md-3 col-form-label text-md-right">{{ __('ID') }}</label>

                <div class="col-md-8">
                    <input type="text" class="form-control"  id="idUser"  disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Nombre') }}</label>

                <div class="col-md-8">
                    <input id="name" placeholder="Nombres" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="surname" class="col-md-3 col-form-label text-md-right">{{ __('Apellido') }}</label>

                <div class="col-md-8">
                    <input id="surname" placeholder="Apellidos" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" autofocus>

                    @if ($errors->has('surname'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('surname') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="text" placeholder="Email" class="col-md-3 col-form-label text-md-right">{{ __('Email') }}</label>

                <div class="col-md-8">
                    <input id="email" placeholder="Email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="editRole" class="col-md-3 col-form-label text-md-right">{{ __('Rol') }}</label>

                <div class="col-md-8">
                    <select id="editRole" class="form-control{{ $errors->has('editRole') ? ' is-invalid' : '' }} selectpicker" title="Seleccione rol de usuario" name="editRole" autofocus>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                        @endforeach
                        
                    </select>

                    @if ($errors->has('editRole'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('editRole') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="company" class="col-md-3 col-form-label text-md-right">{{ __('Rutas Asignadas') }}</label>

                <div class="col-md-8" >
                    <select id="company" title="Asigne Rutas" size="1" name="rutas" multiple="multiple"  class= "form-control selectpicker" name="rutas" autofocus>
                        @foreach ($rutas as $key)

                            @foreach ( $rutasAsig as $ruta )

                                @if ($ruta->ruta_id==$key['VENDEDOR'] && old('id')==$ruta->user_id)
                                    <option class="options" selected value="{{ $ruta->ruta_id }}">{{ old('name') }}</option>
                                @else
                                <option class="options" value="{{ $ruta->ruta_id }}">{{ old('name') }}</option>
                                @endif
                            @endforeach


                        
                        @endforeach



                    </select>

                    @if ($errors->has('company'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('company') }}</strong>
                        </span>
                    @endif
                </div>
                <input id="edit_company_values" name="edit_company_values" type="text" class="form-control" style="display: none" hidden="true">
            </div>

            <div class="form-group row">
                <label for="company" class="col-md-3 col-form-label text-md-right">{{ __('Compañia') }}</label>

                <div class="col-md-8" >
                    <select id="company" title="Seleccione Compañia" size="1" name="company" multiple="multiple"  class= "form-control{{ $errors->has('company') ? ' is-invalid' : '' }} selectpicker" name="company" autofocus>
                        @foreach ($companies as $company)
                            <option class="options" value="{{  $company->id }}">{{ $company->nombre }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('company'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('company') }}</strong>
                        </span>
                    @endif
                </div>
                <input id="edit_company_values" name="edit_company_values" type="text" class="form-control" style="display: none" hidden="true">
            </div>

            <div class="form-group row">
                <label for="description" class="col-md-3 col-form-label text-md-right">{{ __('Descripción') }}</label>

                <div class="col-md-8">
                    <input id="description" placeholder="Descripción" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" autofocus>

                    @if ($errors->has('description'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif

                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning editActionBtn" data-dismiss="modal">Editar</button>
      </div>
    </div>
  </div>
</div>


{{-- MODAL ELIMINAR USUARIO --}}

<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar uausrio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center><h5>¿Esta seguro de eliminar el registro?</h5></center>
        <span id="idUserToDelete" hidden></span>
        <span id="idCompanyToDelete" hidden></span>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger deleteActionBtn" data-dismiss="modal">Si</button>
      </div>
    </div>
  </div>
</div>
@endsection