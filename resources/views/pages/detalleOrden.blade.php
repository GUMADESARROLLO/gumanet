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
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header mt-3">
                    <h5 class="text-secondary">ORDEN DE PRODUCCIÓN</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="table-responsive mt-3 mb-2">
                            <table class="table table-bordered table-sm table-hover" width="99.9%" id="dtDetalleOrdenes"></table>
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
                    <div class="row">
                        <!-- [ Header orden produccion ] start -->
                        <div class="col-xl-12">
                            <div class="card"> 
                                <div class="card-header d-flex justify-content-center align-items-center pt-1 pb-0 bg-blue">
                                    <h5 class=" d-flex modal-title text-center align-self-center p-0 bg-blue text-white" id="tDetalleOrdenes"></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="fechaInicio" class="col-sm-6 col-form-label">Fecha
                                                    Inicio:</label>
                                                <div class="col-sm-6">
                                                    <p type="text" readonly="" class="form-control-plaintext" id="fechaInicio"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="fechaFinal" class="col-sm-6 col-form-label">Fecha
                                                    Final:</label>
                                                <div class="col-sm-6">
                                                    <p type="text" readonly="" class="form-control-plaintext" id="fechaFinal" value=""></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="merma-yankee-dry" class="col-sm-6 col-form-label">Merma
                                                    Yankee Dry (kg):</label>
                                                <div class="col-sm-6 input-group">
                                                    <h6 class="mt-2 mb-0" id="merma-yankee-dry">
                                                    </h6>
                                                    <span class="float-right mt-2  ml-1" id="porcentaje_merma"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-6 col-form-label">Horas
                                                    Trabajadas</label>
                                                <div class="col-sm-6">
                                                    <h6 class="mt-2 text-left" id="hrsTrabajadas"> </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="horaInicio" class="col-sm-6 col-form-label">Hora
                                                    Inicio:</label>
                                                <div class="col-sm-6">
                                                    <p type="text" readonly="" class="form-control-plaintext" id="horaInicio" value=""></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="horaFinal" class="col-sm-6 col-form-label">Hora
                                                    Final:</label>
                                                <div class="col-sm-6">
                                                    <p type="text" readonly="" class="form-control-plaintext" id="horaFinal"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="residuos-pulper" class="col-sm-6 col-form-label">Residuos
                                                    del Pulper (kg):</label>
                                                <div class="col-sm-6 input-group">
                                                    <h6 class="mt-2 mb-0" id="residuos-pulper"> </h6>
                                                    <span class="float-right mt-2  ml-1" id="porcentaje_rp"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="factorFibral" class="col-sm-6 col-form-label">Factor
                                                    fibral</label>
                                                <div class="col-sm-6">
                                                    <h6 class="mt-2 mb-0 text-left" id="factor-fibral"> </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="produccionNeta" class="col-sm-6 col-form-label">PROD.
                                                    REAL (kg):</label>
                                                <div class="col-sm-6">
                                                    <p type="text" readonly="" class="form-control-plaintext" id="produccionNeta"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="produccionReal" class="col-sm-6 col-form-label">PROD.
                                                    TOTAL (kg):</label>
                                                <div class="col-sm-6">
                                                    <p type="text" readonly="" class="form-control-plaintext" id="produccionReal"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="lav-tetrapack" class="col-sm-6 col-form-label">Lavadora
                                                    de Tetrapack (kg):</label>
                                                <div class="col-sm-6 input-group">
                                                    <h6 class="mt-2 mb-0" id="lav-tetrapack"> </h6>
                                                    <span class="float-right ml-1  mt-2" id="porcentaje_tpack"> </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- [ Header orden produccion ] end -->
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card card-social">
                                <div class="card-header text-center p-2 bg-blue">
                                    <h5 class="text-white m-0">Agua</h5>
                                </div>
                                <div class="card-block  mx-2  my-1 border-bottom">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Consumo en m<sup>3</sup></h6>
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
                                            <h6>560</h6>
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
                                            <h6 class="text-muted text-right m-b-10"><span id="Einicial" class="text-muted m-r-5"></span> Kwh
                                            </h6>
                                        </div>
                                        <div class="col-2 m-0 p-0">
                                            <h6 class="text-right m-b-10"><span class="text-muted m-r-5">Final:</span>
                                            </h6>
                                        </div>
                                        <div class="col-4 m-0 p-0">
                                            <h6 class="text-muted text-right m-b-10"><span id="Efinal" class="text-muted m-r-5"></span> Kwh
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
                                <div class="card-block mx-2 my-1">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-auto">
                                            <h6 class="mb-0">Consumo en Glns</h6>
                                        </div>
                                        <div class="col text-right">
                                            <h6 id="GtotalConsumo"> Glns</h6>
                                        </div>
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
                                                <th>Costo Unitario (C$)</th>
                                                <th>Costo Total (C$)</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>TOTAL</td>
                                                <td id="CT_Unitario"></td>
                                                <td id="costoTotal"> </td>
                                            </tr>
                                        </tfoot>
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