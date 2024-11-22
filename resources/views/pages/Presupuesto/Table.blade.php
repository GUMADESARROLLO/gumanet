@extends('layouts.main')
@section('content')
<div class="container-fluid">	
    <div class="row justify-content-end">
        <div class="col-sm-2">
            <div class="input-group">
                <select class="custom-select" id="InputMeses" name="InputMeses">
                <option value="1">ENERO</option>
                <option value="2">FEBRERO</option>
                <option value="3">MARZO</option>
                <option value="4">ABRIL</option>
                <option value="5">MAYO</option>
                <option value="6">JUNIO</option>
                <option value="7">JULIO</option>
                <option value="8">AGOSTO</option>
                <option value="9">SEPTIEMBRE</option>
                <option value="10">OCTUBRE</option>
                <option value="11">NOVIEMBRE</option>
                <option value="12">DICIEMBRE</option>
                </select>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <select class="custom-select" id="InputMeses" name="InputMeses">
                <option value="2023" selected>2023</option>                
                </select>
            </div>
        </div>
        <!--<div class="col-sm-1" >
        <a id="exp-to-excel-canales" href="#!" class="btn btn-light btn-block text-success"><i class="fas fa-file-excel"></i> Exportar</a>
        </div>-->   
  </div>
  <div class="card mt-5">
    <div class="card-header d-flex flex-between-center ps-0 py-0 border-bottom justify-content-end">
        <ul class="nav nav-tabs border-0 flex-nowrap tab-active-caret" id="crm-revenue-chart-tab" role="tablist" data-tab-has-echarts="data-tab-has-echarts">
            <li class="nav-item" role="presupuesto"><a class="nav-link py-3 mb-0 active" id="crm-mes-tab" data-bs-toggle="tab" href="#crm-mes" role="tab" aria-controls="crm-mes" aria-selected="false">MES</a></li>
            <li class="nav-item" role="presupuesto"><a class="nav-link py-3 mb-0" id="crm-anio-tab" data-bs-toggle="tab" href="#crm-anio" role="tab" aria-controls="crm-anio" aria-selected="false">TODOS</a></li> 
        </ul>
    </div>
    <div class="card-body">
        <div class="row g-1">                   
            <div class="col-sm-12">
                <div class="tab-content">
                    <div class="tab-pane active" id="crm-mes" role="tabpanel" aria-labelledby="crm-mes-tab">
                        <table id="table_presupuesto" class="table table-bordered" >
                            <thead>
                                <tr class="bg-blue text-light">
                                <th >CONSOLIDADO {{$presupuesto['MES']}} {{$presupuesto['ANIO']}}</th>
                                <th >EJECUTADO</th>
                                <th >%</th>
                                <th >PRESUPUESTO</th>
                                <th >%</th>
                                <th >DIF. ABSOLUTA</th>
                                <th >DIF. RELATIVA</th>
                                </tr>               
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS BRUTAS</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_BRUTAS']['EJECUTADO'])}}</td>
                                    <td style="text-align:right">100.00%</td>
                                    <td style="text-align:right">29,358,407</td>
                                    <td style="text-align:right">100.00%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_BRUTAS']['EJECUTADO'] - 29358407)}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['VENTAS_BRUTAS']['EJECUTADO'] - 29358407)/29358407)*100,2)}}%)</td>
                                </tr>
                                <tr class="text-light" style="background-color: dodgerblue;">
                                    <td>VENTAS PRIVADO</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_PRIVADO']['EJECUTADO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['VENTAS_PRIVADO']['EJECUTADO']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_PRIVADO']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['VENTAS_PRIVADO']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_PRIVADO']['EJECUTADO'] - $presupuesto['VENTAS_PRIVADO']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['VENTAS_PRIVADO']['EJECUTADO'] - $presupuesto['VENTAS_PRIVADO']['PRESUPUESTO'])/$presupuesto['VENTAS_PRIVADO']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS PRIMARIOS UMK</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['PRIMARIOS UMK']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['PRIMARIOS UMK']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['PRIMARIOS UMK']['PRESUPUESTO']),2)}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['PRIMARIOS UMK']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['PRIMARIOS UMK']['VENTA'] - $presupuesto['PRIMARIOS UMK']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['PRIMARIOS UMK']['VENTA'] - $presupuesto['PRIMARIOS UMK']['PRESUPUESTO'])/$presupuesto['PRIMARIOS UMK']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS SECUNDARIOS UMK</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['SECUNDARIOS']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['SECUNDARIOS']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['SECUNDARIOS']['PRESUPUESTO']),2)}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['SECUNDARIOS']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['SECUNDARIOS']['VENTA'] - $presupuesto['SECUNDARIOS']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['SECUNDARIOS']['VENTA'] - $presupuesto['SECUNDARIOS']['PRESUPUESTO'])/$presupuesto['SECUNDARIOS']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS NUEVOS</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['NUEVOS']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['NUEVOS']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['NUEVOS']['PRESUPUESTO']),2)}}</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['NUEVOS']['VENTA'] - $presupuesto['NUEVOS']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS LIQUIDACION</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr class="text-light" style="background-color: dodgerblue;">
                                    <td>VENTAS PROYECTOS ESPECIALES</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_PROYECTOS']['EJECUTADO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['VENTAS_PROYECTOS']['EJECUTADO']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_PROYECTOS']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['VENTAS_PROYECTOS']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_PROYECTOS']['EJECUTADO'] - $presupuesto['VENTAS_PROYECTOS']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['VENTAS_PROYECTOS']['EJECUTADO'] - $presupuesto['VENTAS_PROYECTOS']['PRESUPUESTO'])/$presupuesto['VENTAS_PROYECTOS']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS ONCO</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['ONCO']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['ONCO']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['ONCO']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['ONCO']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['ONCO']['VENTA'] - $presupuesto['ONCO']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['ONCO']['VENTA'] - $presupuesto['ONCO']['PRESUPUESTO'])/$presupuesto['ONCO']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS GUMAPHARMA</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['GPHARMA']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['GPHARMA']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['GPHARMA']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['GPHARMA']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['GPHARMA']['VENTA'] - $presupuesto['GPHARMA']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['GPHARMA']['VENTA'] - $presupuesto['GPHARMA']['PRESUPUESTO'])/$presupuesto['GPHARMA']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr class="text-light" style="background-color: dodgerblue;">
                                    <td>VENTAS INSTITUCIONALES</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_INSTITUCIONES']['EJECUTADO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['VENTAS_INSTITUCIONES']['EJECUTADO']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_INSTITUCIONES']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['VENTAS_INSTITUCIONES']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['VENTAS_INSTITUCIONES']['EJECUTADO'] - $presupuesto['VENTAS_INSTITUCIONES']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['VENTAS_INSTITUCIONES']['EJECUTADO'] - $presupuesto['VENTAS_INSTITUCIONES']['PRESUPUESTO'])/$presupuesto['VENTAS_INSTITUCIONES']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS CRUZ AZUL</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['CRUZ AZUL']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['CRUZ AZUL']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['CRUZ AZUL']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['CRUZ AZUL']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['CRUZ AZUL']['VENTA'] - $presupuesto['CRUZ AZUL']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['CRUZ AZUL']['VENTA'] - $presupuesto['CRUZ AZUL']['PRESUPUESTO'])/$presupuesto['CRUZ AZUL']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS LICITACIÓN</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['LICITACIONES']['VENTA'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['LICITACIONES']['VENTA']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['LICITACIONES']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['LICITACIONES']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['LICITACIONES']['VENTA'] - $presupuesto['LICITACIONES']['PRESUPUESTO'])}}</td>
                                    <td style="text-align:right">({{@number_format((($presupuesto['LICITACIONES']['VENTA'] - $presupuesto['LICITACIONES']['PRESUPUESTO'])/$presupuesto['LICITACIONES']['PRESUPUESTO'])*100,2)}}%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">DESCUENTOS Y DEVOLUCIONES (4%)</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">VENTAS NETAS</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">COSTO DE VENTA</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">CONTRIBUCION NETA</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr class="text-light" style="background-color: dodgerblue;">
                                    <td class="bg-blue text-light">CONTRIBUCION PRIVADA</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">CONTRIBUCION PRIMARIOS UMK</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['PRIMARIOS UMK']['CONTRIBUCION'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['PRIMARIOS UMK']['CONTRIBUCION']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">CONTRIBUCION SECUNDARIOS UMK</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['SECUNDARIOS']['CONTRIBUCION'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['SECUNDARIOS']['CONTRIBUCION']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">CONTRIBUCION NUEVOS</td>
                                    <td style="text-align:right">{{@number_format($presupuesto['NUEVOS']['CONTRIBUCION'])}}</td>
                                    <td style="text-align:right">{{@number_format(($presupuesto['NUEVOS']['CONTRIBUCION']/$presupuesto['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                <tr>
                                    <td class="bg-blue text-light">CONTRIBUCION LIQUIDACION</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">0%</td>
                                    <td style="text-align:right">0</td>
                                    <td style="text-align:right">(0%)</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="crm-anio" role="tabpanel" aria-labelledby="crm-anio-tab">
                        <table id="table_presupuesto" class="table table-bordered" >
                            <thead>
                              <tr class="bg-blue text-light">
                                <th >CONSOLIDADO {{$presupuesto['MES']}} {{$presupuesto['ANIO']}}</th>
                              </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  
</div>

@endsection('content')