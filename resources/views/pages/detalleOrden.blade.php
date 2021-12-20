@extends('layouts.main')
@section('metodosjs')
@include('jsViews.js_detalleOrdenes');
@endsection
@section('content')
<div class="container-fluid">
    <!-- [ Main Content ] start -->
    <div class="row my-3">
        <div class="col-sm-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
                </div>
                <input type="text" id="InputDtShowSearchFilterArt" class="form-control" placeholder="Buscar en ordenes de produccion" aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
        <!--<div class="col-sm-1">
            <div class="input-group mb-3">
                <select class="custom-select" id="InputDtShowColumnsArtic" name="InputDtShowColumnsArtic">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">Todo</option>
                </select>
            </div>
        </div> -->
    </div>
    <!--<div class="row">
        <div class="col-12">
            <div class="table-responsive mt-3 mb-2">
                <table class="table table-bordered table-sm" width="100%" id="dtDetalleOrdenes"></table>
            </div>
        </div>
    </div>-->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header mt-3">
                    <h5 class="text-secondary">ORDEN DE PRODUCCIÓN</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="table-responsive mt-3 mb-2">
                            <table class="table table-bordered table-sm table-hover" width="100%" id="dtDetalleOrdenes"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal detalle Orden -->
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" data-keyboard="false" id="mdDetalleOrd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-block">
                    <h5 class="modal-title text-center" id="tDetalleOrdenes"></h5>
                    <div class="row">
                        <div class="col">
                            <div class="card card-social">
                                <div class="card-header text-center p-2 bg-blue">
                                    <h5  class="text-white m-0">Agua</h5>
                                </div>
                                <div class="card-block  mx-2  my-1 border-bottom">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Consumo en M3</h6>
                                        </div>
                                        <div class="col text-right">
                                            <h6 id="AtotalConsumo"> </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="row mx-2 my-1 align-items-center justify-content-center card-active">
                                        <div class="col-2 m-0 p-0">
                                            <h6 class="text-left m-b-10"><span class="text-muted m-r-5">Inicial:</span></h6>
                                        </div>
                                        <div class="col-4 m-0 p-0">
                                            <h6 class="text-muted text-right m-b-10"><span class="text-muted m-r-5" id="Ainicial"></span> m<sup>3</sup></h6>
                                        </div>
                                        <div class="col-2 m-0 p-0">
                                            <h6 class="text-right m-b-10"><span class="text-muted m-r-5">Final:</span></h6>
                                        </div>
                                        <div class="col-4 m-0 p-0">
                                            <h6 class="text-muted text-right  m-b-10"><span class="text-muted m-r-5" id="Afinal"></span> m<sup>3</sup></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-social">
                                <div class="card-header text-center p-2 bg-blue">
                                    <h5 class="m-0 text-white">Electricidad</h5>
                                </div>
                                <div class="card-block mx-2  my-1 border-bottom">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Factor de conversión: </h6>
                                        </div>
                                        <div class="col text-right">
                                            <h6 >560</h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Consumo en C$</h6>
                                        </div>
                                        <div class="col text-right">
                                            <h6 id="EtotalCordobas"></h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Consumo en Kw/Hrs</h6>
                                        </div>
                                        <div class="col text-right">
                                            <h6 id="EtotalConsumo"></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="row mx-2 my-1 align-items-center justify-content-center card-active">
                                        <div class="col-2 m-0 p-0">
                                            <h6 class="text-left m-b-10"><span class="text-muted m-r-5">Inicial:</span>
                                            </h6>
                                        </div>
                                        <div class="col-4 m-0 p-0">
                                            <h6 class="text-muted text-right m-b-10"><span id="Einicial" class="text-muted m-r-5"></span > Kwh
                                            </h6>
                                        </div>
                                        <div class="col-2 m-0 p-0">
                                            <h6 class="text-right m-b-10"><span class="text-muted m-r-5">Final:</span>
                                            </h6>
                                        </div>
                                        <div class="col-4 m-0 p-0">
                                            <h6 class="text-muted text-right m-b-10"><span id="Efinal" class="text-muted m-r-5"></span > Kwh
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-social">
                                <div class="card-header text-center p-2 bg-blue">
                                    <h5 class="m-0 text-white">Gas Butano</h5>
                                </div>
                                <div class="card-block mx-2 my-1 border-bottom">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Consumo en Glns</h6>
                                        </div>
                                        <div class="col text-right">
                                            <h6 id="GtotalConsumo"> Glns</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-2 my-1 align-items-center justify-content-center card-active">
                                            <div class="col-2 m-0 p-0">
                                                <h6 class="text-left m-b-10"><span class="text-muted m-r-5">Inicial:</span>
                                                </h6>
                                            </div>
                                            <div class="col-4 m-0 p-0">
                                                <h6 class="text-muted text-right m-b-10"><span  id="Ginicial" class="text-muted m-r-5"></span> Glns
                                                </h6>
                                            </div>
                                            <div class="col-2 m-0 p-0">
                                                <h6 class="text-right  m-b-10"><span class="text-muted m-r-5">Final:</span>
                                                </h6>
                                            </div>
                                            <div class="col-4 m-0 p-0">
                                                <h6 class="text-muted text-right  m-b-10"><span id="Gfinal" class="text-muted m-r-5"></span> Glns
                                                </h6>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="navMP" data-toggle="tab" href="#nav-mp" role="tab" aria-controls="nav-mp" aria-selected="true">Materia Prima</a>
                            <a class="nav-item nav-link" id="navMOD" data-toggle="tab" href="#nav-mod" role="tab" aria-controls="nav-mod" aria-selected="false">Mano de obra directa</a>
                            <a class="nav-item nav-link" id="navQuimicos" data-toggle="tab" href="#nav-quimicos" role="tab" aria-controls="nav-quimicos" aria-selected="false">Quimicos</a>
                            <a class="nav-item nav-link" id="navCIF" data-toggle="tab" href="#nav-cif" role="tab" aria-controls="nav-cif" aria-selected="false">Costos indirectos de Fabricación</a>
                            <a class="nav-item nav-link" id="navCostos" data-toggle="tab" href="#nav-costos" role="tab" aria-controls="nav-costos" aria-selected="false">Costos Por OP</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-mp" role="tabpanel" aria-labelledby="navMP">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tblMP" class="table table-bordered mt-3">
                                        <thead class="bg-blue text-light">
                                            <tr>
                                                <th>Maquina</th>
                                                <th>Fibra</th>
                                                <th>cantidad</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-mod" role="tabpanel" aria-labelledby="navMOD">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tblMOD" class="table table-bordered mt-3">
                                        <thead class="bg-blue text-light">
                                            <tr>
                                                <th>Descripción de la actividad</th>
                                                <th>Dia</th>
                                                <th>Noche</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-quimicos" role="tabpanel" aria-labelledby="navQuimicos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tblQuimicos" class="table table-bordered mt-3">
                                        <thead class="bg-blue text-light">
                                            <tr>
                                                <th>Maquina</th>
                                                <th>Quimico</th>
                                                <th>cantidad</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-cif" role="tabpanel" aria-labelledby="navCIF">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tblCIF" class="table table-bordered mt-3">
                                        <thead class="bg-blue text-light">
                                            <tr>
                                                <th>Descripción de la actividad</th>
                                                <th>Horas</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-costos" role="tabpanel" aria-labelledby="navCostos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tblCostos" class="table table-bordered mt-3">
                                        <thead class="bg-blue text-light">
                                            <tr>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Unidad de Medida</th>
                                                <th>Cantidad</th>
                                                <th>Costo Unitario</th>
                                                <th>Costo Total</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endsection