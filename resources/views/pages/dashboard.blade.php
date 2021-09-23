@extends('layouts.main')
@section('title' , $name)
@section('name_user' , 'Administrador')
@section('metodosjs')
    @include('jsViews.js_dashboard')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="h4 mt-4">Dashboard</h4>
                @if( Session::get('company_id')==4 )
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                        <label class="custom-control-label" for="customSwitch1">Bolsones</label>
                    </div>
                @endif
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="opcMes" class="text-muted m-0">Filtrar por mes</label>
                            <select class="form-control form-control-sm" id="opcMes">
                                <?php
                                $mes = date("m");
                                $meses = array('none', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

                                for ($i = 1; $i <= 12; $i++) {
                                    if ($i == $mes) {
                                        echo '<option selected value="' . $i . '">' . $meses[$i] . '</option>';
                                    } else {
                                        echo '<option value="' . $i . '">' . $meses[$i] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="opcAnio" class="text-muted m-0">por año</label>
                            <select class="form-control form-control-sm" id="opcAnio">
                                <?php
                                $year = date("Y");
                                for ($i = 2018; $i <= $year; $i++) {
                                    if ($i == $year) {
                                        echo '<option selected value="' . $i . '">' . $i . '</option>';
                                    } else {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <a href="#!" style="width: 100%" id="filterM_A" class="btn btn-primary float-right mt-3">Aplicar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-graf mb-5">
            <div class="row" id="ct04">
                <div class="graf col-sm-12 mt-3">
                    <div class="container-vms" id="grafVtsDiario" style="width: 100%; margin: 0 auto"></div>
                </div>
            </div>


            <div class="row" id="ct04">
                <div class="graf col-sm-12 mt-3">
                    <div class="container-rvts" id="grafRealVentas" style="width: 100%; margin: 0 auto"></div>
                </div>
            </div>

            <div class="row" id="ct04">
                <div class="graf col-sm-12 mt-3">
                    <div class="container-vms" id="grafVtsMes" style="width: 100%; margin: 0 auto"></div>
                </div>
            </div>

            <div class="row mt-3" id="cardAnioActual">
                <div class="col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white bg-secondary border-0">
                            <p class="text-white m-0 p-0 divSpinner text-center">ACUMULADO AÑO</p>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3" id="anioAcumulado"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white bg-secondary border-0">
                            <p class="text-white m-0 p-0 divSpinner text-center">PROMEDIO MENSUAL</p>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3" id="porcentaje"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3" id="cardAnioAntePasa">
                <div class="col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white bg-secondary border-0">
                            <p class="text-white m-0 p-0 divSpinner text-center">ACUMULADO AÑO ANTERIOR</p>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3" id="acumuladoAnioAnte"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white bg-secondary border-0">
                            <p class="text-white m-0 p-0 divSpinner text-center">PROMEDIO MENSUAL AÑO ANTERIOR</p>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3" id="porcentajeAnioAnte"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3" id="crecimientoxruta">
                <div class="col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white bg-secondary border-0">
                            <p class="text-white m-0 p-0 divSpinner text-center">CUMPLIMIENTO DE META</p>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered mt-3">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="font-size: 1rem!important">TOTAL</th>
                                    <th scope="col" class="text-center" style="font-size: 1rem!important">GENERAL</th>
                                </tr>
                                </thead>
                                <tbody id="tbody01"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white bg-secondary border-0">
                            <p class="text-white m-0 p-0 divSpinner text-center">ALCANCE DE VENTA POR SEGMENTO</p>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered mt-3">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center">CANAL</th>
                                    <th scope="col" class="text-center">META</th>
                                    <th scope="col" class="text-center">VENTA</th>
                                    <th scope="col" class="text-center">ALCANCE</th>
                                </tr>
                                </thead>
                                <tbody id="tbody02"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if( Session::get('company_id')==40 )
                <div class="row" id="ct05" style="display: none">
                    <div class="graf col-sm-12 mt-3 text-right">
                        <figure class="highcharts-figure">
                            <select class="selectpicker col-sm-4 form-control form-control-sm mb-2 mt-3"
                                    id="select-cate" data-show-subtext="false" data-live-search="true"></select>
                            <div class="container-cat" id="grafVtsXCateg"></div>
                        </figure>
                    </div>
                </div>
            @else
                <div class="row" id="ct05">
                    <div class="graf col-sm-12 mt-3 text-right">
                        <figure class="highcharts-figure">
                            <select class="selectpicker col-sm-4 form-control form-control-sm mb-2 mt-3"
                                    id="select-cate" data-show-subtext="false" data-live-search="true"></select>
                            <div class="container-cat" id="grafVtsXCateg"></div>
                        </figure>
                    </div>
                </div>
            @endif

            <div class="row" id="ct01">
                <div class="graf col-sm-4 mt-3">
                    <div class="container-vm" id="grafVentas"></div>
                </div>
                <div class="graf col-sm-4 mt-3">
                    <div class="container-rm" id="grafRecupera"></div>
                </div>
                <div class="graf col-sm-4 mt-3">
                    <div class="container-vb" id="grafBodega"></div>
                </div>
            </div>
            <div class="row" id="ct03">
                <div class="graf col-sm-6 mt-3">
                    <div class="container-cv" id="grafCompMontos"></div>
                </div>
                <div class="graf col-sm-6 mt-3">
                    <div class="container-cc" id="grafCompCantid"></div>
                </div>
            </div>
            <div class="row" id="ct02">
                <div class="graf col-sm-6 mt-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <a href="#!" style="width: 100%" class="btn btn-primary float-right mt-3"
                                           onclick="detailAllClients()" type="button" id="btnclick">Todos</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group ">
                                        <label for="opcMes" class="text-muted m-0">SEGMENTO</label>
                                        <select class="form-control form-control-sm" id="listClt" onchange="selectListClients()">
                                            <option value="0">Todas</option>
                                            <option value="1">Farmacias</option>
                                            <option value="2">Instituciones</option>
                                            <option value="3">Mayoristas</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="container-tc" id="grafClientes"></div>
                </div>
                <div class="graf col-sm-6 mt-3">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="opcMes" class="text-muted m-0">SEGMENTO</label>
                                        <select class="form-control form-control-sm" id="opcMes">
                                            <option selected value="">Farmacia</option>
                                            <option selected value="">Instituciones</option>
                                            <option selected value="">Mayoristas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <a href="#!" style="width: 100%" class="btn btn-primary float-right mt-3"
                                           onclick="Todos_Los_Items()">Todas</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-tp" id="grafProductos"></div>
                </div>
            </div>
        </div>
        <!-- PAGINA TEMPORAL DE DETALLES -->
        <div id="page-details" class="p-4 border-left" style="background-color: #f1f5f8">
            <div class="row">
                <div class="col-lg-12">
                    <a href="#!" class="active-page-details btn btn-outline-primary btn-sm">Regresar</a>
                </div>
            </div>
            <div class="row center">
                <div class="col-sm-12">


                    <div class="card mt-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row" id="lbl_id_articulos">
                                <div class="col-sm-4 border-right">
                                    <h5 class="card-title" id="title-page-tem"></h5>
                                    <p class="text-muted" id="fechaFiltrada"></p>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row ml-3">
                                        <div class="col-sm-4" id="montoMetaContent">
                                            <p class="text-muted m-0" id="txtMontoMeta"></p>
                                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                                               id="MontoMeta"></p>
                                        </div>
                                        <div class="col-sm-4" id="montoRealContent">
                                            <p class="text-muted m-0" id="txtMontoReal"></p>
                                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                                               id="MontoReal"></p>
                                        </div>
                                        <div class="col-sm-4" id="cumplMetaContent">
                                            <p class="text-muted m-0" id="id_detall_unit_bonif">% Cumpl.</p>
                                            <p class="font-weight-bolder text-info" style="font-size: 1.3rem!important"
                                               id="cumplMeta"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="id_div_detalles_vendedores">
                                <div class="col-sm-11 mt-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    data-feather="search"></i></span>
                                        </div>
                                        <input type="text" id="filterDtTemp" class="form-control" placeholder="Buscar">
                                    </div>
                                </div>
                                <div class="col-sm-1 mt-3">
                                    <div class="input-group">
                                        <select class="custom-select" id="cantRowsDtTemp">
                                            <option value="5" selected>5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="-1">Todo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6 mt-2">
                                    <div class="table-responsive" id="id_div_titulo_Ventas_Rutas">
                                        <div id="cjRutVentasRutas">
                                            <table class="table table-bordered table-sm" width="100%"
                                                   id="dtVentaRuta"></table>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6 mt-2 align-self-center">
                                    <div id="id_grafica_pie_ventas_ruta"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row" id="id_detalles_articulos">
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <P class="text-muted m-0">PREC. PROM. :</P>
                                    <p class="font-weight-bolder text-center" style="font-size: 1.3rem!important"
                                       id="id_detall_prec_prom"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <p class="text-muted mb-0">COST. PROM. UNIT. :</p>
                                    <p class="font-weight-bolder text-center" style="font-size: 1.3rem!important"
                                       id="id_detall_cost_unit"></p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <p class="text-muted mb-0">CONTRIBUCION:</p>
                                    <p class="font-weight-bolder text-center" style="font-size: 1.3rem!important"
                                       id="id_detall_marg_contrib"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center gx-0">
                                <div class="col">
                                    <p class="text-muted mb-0">% MARGEN BRUTO. :</p>
                                    <p class="font-weight-bolder text-center" style="font-size: 1.3rem!important"
                                       id="id_detall_porc_contrib"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-12 mt-2">

                </div>

                <div class="col-sm-12 mt-2">
                    <div class="table-responsive">
                        <div id="cjRutVentas">
                            <table class="table table-bordered table-sm" width="100%" id="dtTotalXRutaVent"></table>
                        </div>
                    </div>
                </div>


                <div class="col-sm-12 mt-2">
                    <div class="table-responsive">
                        <div id="cjRecuperacion">
                            <table class="table table-bordered table-sm" width="100%" id="dtRecuperacion"></table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 mt-2">
                    <div class="table-responsive">
                        <div id="cjRecu_GumaPharma">
                            <table class="table table-bordered table-sm" width="100%" id="dtRecu_GumaPharma"></table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 mt-2">
                    <div class="table-responsive">
                        <div id="cjCliente">
                            <p class="font-weight-bold">ARTICULOS FACTURADOS</p>
                            <table class="table table-bordered table-sm" width="100%" id="dtCliente"></table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-2">
                    <div class="table-responsive">
                        <div id="cjArticulo">
                            <p class="font-weight-bold">CLIENTES</p>
                            <table class="table table-bordered table-sm" width="100%" id="dtArticulo"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal:Detalles de ventas de todos los articulos-->
    <div class="modal fade modal-fullscreen" id="mdDetailsAllItems" tabindex="-1" role="dialog"
         aria-labelledby="titleModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bolder text-info" id="id_titulo_modal_all_items"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyModal">
                    <div class="row">
                        <div class="col-sm-11 mt-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            data-feather="search"></i></span>
                                </div>
                                <input type="text" id="id_txt_all_item" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                        <div class="col-sm-1 mt-2">
                            <div class="input-group">
                                <select class="custom-select" id="id_select_all_items">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="-1">Todo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <div class="table-responsive">
                                <table id="tblAllItems" class="table table-bordered table-sm" width="100%">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal:Lista de clientes -->
    <div class="modal fade modal-fullscreen" id="mdClientDetail" tabindex="-1" role="dialog"
         aria-labelledby="titleModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bolder text-info" id="id_titulo_modal_all_clients"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyModal">
                    <div class="row">
                        <div class="col-sm-11 mt-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            data-feather="search"></i></span>
                                </div>
                                <input type="text" id="id_txt_all_clients" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                        <div class="col-sm-1 mt-2">
                            <div class="input-group">
                                <select class="custom-select" id="id_select_all_clients">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="-1">Todo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <div class="table-responsive">
                                <table id="tblAllClients" class="table table-bordered table-sm" width="100%">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal:Detalle Venta Comparación Meta, Real Cumplimineto -->
    <div class="modal fade" id="mdDetailsVentas" tabindex="-1" role="dialog" aria-labelledby="titleModal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bolder text-info" id="vendedorNombre"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyModal">
                    <div class="row" id="id_detalles_ventas">
                        <div class="col-sm-2">
                            <p class="text-muted m-0">Meta Units.</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                               id="total_Meta_Unidad"></p>
                        </div>
                        <div class="col-sm-2">
                            <p class="text-muted m-0">Real Units.</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                               id="total_Real_Unidad"></p>
                        </div>
                        <div class="col-sm-2 border-right">
                            <p class="text-muted m-0">Diferencia en %</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="total_Dif_Unidad"></p>
                        </div>
                        <div class="col-sm-2">
                            <p class="text-muted m-0">Real Vtas.</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                               id="total_Real_Efectivo"></p>
                        </div>
                        <div class="col-sm-2">
                            <p class="text-muted m-0">Meta Vtas.</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                               id="total_Meta_Efectivo"></p>
                        </div>
                        <div class="col-sm-2">
                            <p class="text-muted m-0">Diferencia en %</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important"
                               id="total_Dif_Efectivo"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="id_div_Detalles_venta">
                        <div class="col-sm-11 mt-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            data-feather="search"></i></span>
                                </div>
                                <input type="text" id="filterDtDetalle" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                        <div class="col-sm-1 mt-2">
                            <div class="input-group">
                                <select class="custom-select" id="cantRowsDtDetalle">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="-1">Todo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <div class="table-responsive">
                                <div id="cjVentas">
                                    <table class="table table-bordered table-sm" width="100%" id="dtVentas"></table>
                                </div>
                                <div id="cjVentasFacturas">
                                    <table class="table table-bordered table-sm" width="100%"
                                           id="dtVentasFacturas"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal:Detalle -->
    <div class="modal fade" id="mdDetails" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal-01"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="bodyModal">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="text-muted m-0" id="text-mes-actual"></p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="val-mes-actual"></p>
                        </div>
                        <div class="col-sm-3">
                            <p class="text-muted m-0" id="text-anio-pasado"></p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="val-anio-pasado"></p>
                        </div>
                        <div class="col-sm-2 border-right">
                            <p class="text-muted m-0">Diferencia en %</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="dif-porcen-vts"></p>
                        </div>
                        <div class="col-sm-2">
                            <p class="text-muted m-0" id="text-mes-pasado"></p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="val-mes-pasado"></p>
                        </div>
                        <div class="col-sm-2">
                            <p class="text-muted m-0">Diferencia en %</p>
                            <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="dif-porcen-its"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
