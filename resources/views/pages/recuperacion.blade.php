@extends('layouts.main')
@section('title' , $data['name'])
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_recuperacion');
@endsection
@section('content')  
<div class="row" style="margin: 0 auto">
    <div class="card mt-3 border-0 shadow-sm" style="width: 100%">
      <div class="card-body">                
        {{-- <h5 class="card-title">{{ $data['name']}}</h5> --}}
       
                
        <div class="row">
          <div class="col-sm-4">
            <h4 class="h4 pb-0">Recuperación</h4>
          </div>
          <div class="col-md-3 mb-2">
            <div class="form-group">
                <label for="selectMesIntroRecup" class="text-muted m-0">Filtrar por mes</label>
                <select class="form-control form-control-sm" id="selectMesIntroRecup">
                <?php
                    setlocale(LC_ALL, 'es_ES');
                    $mes = date("m");

                    for ($i= 1; $i <= 12 ; $i++) {
                        $dateObj   = DateTime::createFromFormat('!m', $i);
                        $monthName = strftime('%B', $dateObj->getTimestamp());
                        
                        if ($i==$mes) {
                            echo'<option selected value="'.$i.'">'.$monthName.'</option>';
                        }else {
                            echo'<option value="'.$i.'">'.$monthName.'</option>';
                        }
                    }
                ?>
                </select>
            </div>
          </div>
          <div class="col-md-2 mb-2">
             <div class="form-group">
                <label for="selectAnnoIntroRecup" class="text-muted m-0">por año</label>
                <select class="form-control form-control-sm" id="selectAnnoIntroRecup">
                    <?php
                        $year = date("Y");
                        for ($i= 2018; $i <= $year ; $i++) {
                          if ($i==$year) {
                            echo'<option selected value="'.$i.'">'.$i.'</option>';
                          }else {
                            echo'<option value="'.$i.'">'.$i.'</option>';
                          }
                         
                        }
                    ?>
                </select>  
            </div>
          </div>

          <div class="col-sm-3 mt-3">
              <div class="form-group">
                  <a href="#!" style="width: 100%" id="btnCargardtIntroRecup" class="btn btn-primary float-right mb-3">Aplicar</a>
              </div>
          </div> 
        </div>
        <div class="row justify-content-left border-top pt-3">    

          {{--<div class="col-sm-12 mb-2">
             <div class="input-group">
                  <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                  </div>
                  <input type="text" id="InputDtSearchIntroRecup" class="form-control" placeholder="Buscar" aria-label="Username" aria-describedby="basic-addon1">
              </div>
          </div>--}}

                              
          {{-- <div class="col-sm-2 mb-2">
             <div class="input-group mb-3">
                <select class="custom-select" id="InputDtShowColumnsIntroRecupa" name="InputDtShowColumnsIntroRecup">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">Todo</option>
                </select>
            </div>
        </div> --}}


        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="table-responsive mt-3 mb-5">
                    <table class="table table-bordered table-sm" width="100%" id="dtIntroRecup"></table>
                </div>
            </div>
        </div>
        <div class="row text-right">
          <div class="col-sm-12 ">
              <div class="form-group">
                  <a href="#!" style="width: 23%" id="btnSaveIntroRecup" class="btn btn-primary float-right"></a>
              </div>
          </div> 
        </div>
      </div>
    </div>
  </div>

@endsection