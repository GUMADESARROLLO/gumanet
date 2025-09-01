@extends('layouts.ly_reorder')
@section('title' , $NamePath )
@section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.AnalisisIMS.JS_AnalisisIMS')    
    @include('pages.AnalisisIMS.CSS_AnalisisIMS')
@endsection
@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">       
            <h4 class="h4 text-umk"> ANALISIS IMS </h4>
            <p class="text-muted" > Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
        </div>
        <div class="col-sm-12 ">
            <div class="table-responsive">
                <table id="tbl_analisis_ims" class="table table-striped table-bordered" width="100%"></table>
            </div>
        </div>
    </div>
</div> 


<div class="modal fade" id="mdlIMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title text-umk" id="exampleModalLongTitle">Base de registros </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-sm-11">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                            </div>
                            <input type="text" id="id_search_Origin_ims" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    
                    <div class="col-sm-1">
                        <a id="exp-to-excel" href="#!" class="btn btn-success btn-block text-light float-right button_export_excel"><i class="fas fa-file-excel"></i> </a>
                    </div>      
                </div>

                <div class="table-responsive">
                    <table id="tbl_origin_ims" class="table table-striped table-bordered dt-responsive" width="100%">
                        <thead>
                            <tr class="text-center bg-umk text-white">
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection