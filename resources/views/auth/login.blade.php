@extends('layouts.login')
@section('title', $name)
@section('version', $version)
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-12">
            <img src="img/gumanet-logo.png" id="img-logo">
        </div>
        <div class="col-12">
            <form class="form-signin" action="{{ route('login') }}" method="POST">
                <h4 style="color: white; margin-top: 30px">Inicia sesión</h4>
               
                <div class="row form-group">
                    <div class="col-sm-8 input-group-sm">
                        <input  type="text" id="campoEmailLogin" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" value="{{ old('email') }}"  autofocus>
                        @if ($errors->has('email'))
                            <span style="color: white" class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-sm-8 input-group-sm">
                        <input type="password" id="campoPasswordLogin" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Contraseña" >
                         @if ($errors->has('password'))
                            <span style="color: white" class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>           
                <div class="row ">
                    <div class="col-sm-8">
                        <select id="campoEmpresa" name="campoEmpresa" class="form-control">
                            <option value="">Seleccione compañia</option>
                            @foreach ($companies as $company)
                                <option value="{{  $company->id }}">{{ $company->nombre }}</option>
                            @endforeach
                            
                        </select>
                       <span style="color: white">{{ $errors->first('campoEmpresa') }}</span>
                    </div>
                    <div class="col-sm-4">
                        <button id="btn-web" type="submit" style="margin-top: 5px; color: transparent; background-color: transparent; border: 0; padding: 0"><img src="svg/siguiente.svg" style="background-image: transparent; width: 35px;"></button>
                        <button type="submit" class="btn btn-primary" id="btn-mobile">Siguiente</button>
                    </div>
                </div>
                @csrf  {{-- Crear token --}}
            </form>
        </div>
    </div>
</div>
@endsection