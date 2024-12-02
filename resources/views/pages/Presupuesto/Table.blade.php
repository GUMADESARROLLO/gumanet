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
  <div>
    <nav >
        <div class="nav nav-tabs mt-5 justify-content-end" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="navMes" data-toggle="tab" href="#nav-mes" role="tab" aria-controls="nav-mes" aria-selected="true">MES</a>
            <a class="nav-item nav-link" id="navAnio" data-toggle="tab" href="#nav-anio" role="tab" aria-controls="nav-anio" aria-selected="false">TODOS</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-mes" role="tabpanel" aria-labelledby="navMes">
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
                    <tr>
                        <td class="bg-blue text-light">GASTOS DE COMERCIALIZACION</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">COMISIONES</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">GASTO DE VENTA</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">GASTO DE MERCADEO</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr class="text-light" style="background-color: dodgerblue;">
                        <td >MARGEN COMERCIAL</td>
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
        <div class="tab-pane fade" id="nav-anio" role="tabpanel" aria-labelledby="navAnio">
            <table id="table_presupuesto_anual" class="table table-bordered" >
                <thead>
                    <tr class="bg-blue text-light">
                    <th >CONSOLIDADO {{$presupuesto['ANIO']}}</th>
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
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])}}</td>
                        <td style="text-align:right">100.00%</td>
                        <td style="text-align:right">29,358,407</td>
                        <td style="text-align:right">100.00%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'] - 29358407)}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'] - 29358407)/29358407)*100,2)}}%)</td>
                    </tr>
                    <tr class="text-light" style="background-color: dodgerblue;">
                        <td>VENTAS PRIVADO</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_PRIVADO']['EJECUTADO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['VENTAS_PRIVADO']['EJECUTADO']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_PRIVADO']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['VENTAS_PRIVADO']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_PRIVADO']['EJECUTADO'] - $presupuestoAnual['VENTAS_PRIVADO']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['VENTAS_PRIVADO']['EJECUTADO'] - $presupuestoAnual['VENTAS_PRIVADO']['PRESUPUESTO'])/$presupuestoAnual['VENTAS_PRIVADO']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS PRIMARIOS UMK</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['PRIMARIOS UMK']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['PRIMARIOS UMK']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['PRIMARIOS UMK']['PRESUPUESTO']),2)}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['PRIMARIOS UMK']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['PRIMARIOS UMK']['VENTA'] - $presupuestoAnual['PRIMARIOS UMK']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['PRIMARIOS UMK']['VENTA'] - $presupuestoAnual['PRIMARIOS UMK']['PRESUPUESTO'])/$presupuestoAnual['PRIMARIOS UMK']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS SECUNDARIOS UMK</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['SECUNDARIOS']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['SECUNDARIOS']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['SECUNDARIOS']['PRESUPUESTO']),2)}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['SECUNDARIOS']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['SECUNDARIOS']['VENTA'] - $presupuestoAnual['SECUNDARIOS']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['SECUNDARIOS']['VENTA'] - $presupuestoAnual['SECUNDARIOS']['PRESUPUESTO'])/$presupuestoAnual['SECUNDARIOS']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS NUEVOS</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['NUEVOS']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['NUEVOS']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['NUEVOS']['PRESUPUESTO']),2)}}</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['NUEVOS']['VENTA'] - $presupuestoAnual['NUEVOS']['PRESUPUESTO'])}}</td>
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
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_PROYECTOS']['EJECUTADO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['VENTAS_PROYECTOS']['EJECUTADO']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_PROYECTOS']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['VENTAS_PROYECTOS']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_PROYECTOS']['EJECUTADO'] - $presupuestoAnual['VENTAS_PROYECTOS']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['VENTAS_PROYECTOS']['EJECUTADO'] - $presupuestoAnual['VENTAS_PROYECTOS']['PRESUPUESTO'])/$presupuestoAnual['VENTAS_PROYECTOS']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS ONCO</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['ONCO']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['ONCO']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['ONCO']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['ONCO']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['ONCO']['VENTA'] - $presupuestoAnual['ONCO']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['ONCO']['VENTA'] - $presupuestoAnual['ONCO']['PRESUPUESTO'])/$presupuestoAnual['ONCO']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS GUMAPHARMA</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['GPHARMA']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['GPHARMA']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['GPHARMA']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['GPHARMA']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['GPHARMA']['VENTA'] - $presupuestoAnual['GPHARMA']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['GPHARMA']['VENTA'] - $presupuestoAnual['GPHARMA']['PRESUPUESTO'])/$presupuestoAnual['GPHARMA']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr class="text-light" style="background-color: dodgerblue;">
                        <td>VENTAS INSTITUCIONALES</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_INSTITUCIONES']['EJECUTADO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['VENTAS_INSTITUCIONES']['EJECUTADO']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_INSTITUCIONES']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['VENTAS_INSTITUCIONES']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['VENTAS_INSTITUCIONES']['EJECUTADO'] - $presupuestoAnual['VENTAS_INSTITUCIONES']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['VENTAS_INSTITUCIONES']['EJECUTADO'] - $presupuestoAnual['VENTAS_INSTITUCIONES']['PRESUPUESTO'])/$presupuestoAnual['VENTAS_INSTITUCIONES']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS CRUZ AZUL</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['CRUZ AZUL']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['CRUZ AZUL']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['CRUZ AZUL']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['CRUZ AZUL']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['CRUZ AZUL']['VENTA'] - $presupuestoAnual['CRUZ AZUL']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['CRUZ AZUL']['VENTA'] - $presupuestoAnual['CRUZ AZUL']['PRESUPUESTO'])/$presupuestoAnual['CRUZ AZUL']['PRESUPUESTO'])*100,2)}}%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">VENTAS LICITACIÓN</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['LICITACIONES']['VENTA'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['LICITACIONES']['VENTA']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['LICITACIONES']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['LICITACIONES']['PRESUPUESTO']/29358407)*100,2)}}%</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['LICITACIONES']['VENTA'] - $presupuestoAnual['LICITACIONES']['PRESUPUESTO'])}}</td>
                        <td style="text-align:right">({{@number_format((($presupuestoAnual['LICITACIONES']['VENTA'] - $presupuestoAnual['LICITACIONES']['PRESUPUESTO'])/$presupuestoAnual['LICITACIONES']['PRESUPUESTO'])*100,2)}}%)</td>
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
                        <td style="text-align:right">{{@number_format($presupuestoAnual['PRIMARIOS UMK']['CONTRIBUCION'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['PRIMARIOS UMK']['CONTRIBUCION']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">CONTRIBUCION SECUNDARIOS UMK</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['SECUNDARIOS']['CONTRIBUCION'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['SECUNDARIOS']['CONTRIBUCION']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">CONTRIBUCION NUEVOS</td>
                        <td style="text-align:right">{{@number_format($presupuestoAnual['NUEVOS']['CONTRIBUCION'])}}</td>
                        <td style="text-align:right">{{@number_format(($presupuestoAnual['NUEVOS']['CONTRIBUCION']/$presupuestoAnual['VENTAS_BRUTAS']['EJECUTADO'])*100,2)}}%</td>
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
                    <tr>
                        <td class="bg-blue text-light">GASTOS DE COMERCIALIZACION</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">COMISIONES</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">GASTO DE VENTA</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr>
                        <td class="bg-blue text-light">GASTO DE MERCADEO</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">0%</td>
                        <td style="text-align:right">0</td>
                        <td style="text-align:right">(0%)</td>
                    </tr>
                    <tr class="text-light" style="background-color: dodgerblue;">
                        <td >MARGEN COMERCIAL</td>
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
    </div>
  </div>
  
</div>

@endsection('content')