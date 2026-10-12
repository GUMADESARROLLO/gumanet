@extends('layouts.ly_reorder')
@section('metodosjs')
@include('jsViews.js_contribuciones')
@endsection
@section('content')
<style>
  span.btn-change-color {
    background-color: #28a745;
  }
</style>
<div class="container-fluid"> 
  <div class="row">
    <div class="col-sm-6">
      <p class="font-italic text-muted pt-0 mt-0">Actualizado del <span id="tl_periodo"> - </span></p>	
    </div>
    <div class="col-sm-6 text-right">
      <p class="font-italic text-muted pt-0 mt-0 mr-3"><b>Todos los valores estan en C$.</b></p>	
    </div>
  </div>
  <div class="row">
    <div class="col">		      
      <div class="input-group"> 
        <input type="text" id="id_txt_buscar" class="form-control" aria-describedby="basic-addon1" placeholder="Buscar...">
          <div class="input-group-prepend">
            <span class="btn-change-color text-white input-group-text" id="BtnClick"><i data-feather="refresh-cw"></i></span>
          </div>
      </div>
    </div>
    <div class="col-sm-1">
      <div class="input-group">
        <select class="custom-select" id="InputCanales" name="InputCanales">
          <option value="5" selected>5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="100">100</option>
          <option value="-1">Todo</option>
        </select>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="row ">
        <div class="col mt-1">
          <div class="form-group">  
            <input type="text" class="input-fecha" id="f1">
          </div>
        </div>
        <div class="col mt-1 ">
          <div class="form-group">  
            <input type="text" class="input-fecha" id="f2">
          </div>
        </div>
        
      </div>
    </div>
    <!--<div class="col-sm-1" >
      <a id="exp-to-excel-canales" href="#!" class="btn btn-light btn-block text-success"><i class="fas fa-file-excel"></i> Exportar</a>
    </div>-->   
      
  </div>

    <div class="card border-0 shadow-sm ">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="table-responsive flex-between-center scrollbar border border-1 border-300 rounded-2">
          
            @php
              // Canales para las columnas de año anterior y mensuales: prefijo => [nombre, color]
              $canalesExtra = [
                'F'   => ['FARMACIA',            '#ffc107'],
                'C'   => ['CADENA FARMACIA',     'peru'],
                'M'   => ['MAYORISTA',           'burlywood'],
                'PRI' => ['INSTITUCION PRIVADA', 'limegreen'],
                'CA'  => ['CRUZ AZUL',           'cornflowerblue'],
                'PUB' => ['INSTITUCION PUBLICA', 'limegreen'],
                'ML'  => ['MINSA - LICIT',       'lightskyblue'],
              ];
            @endphp
            <table id="table_contribucion" class="table table-bordered table-sm" width="100%">
              <thead>
                <tr class="bg-blue text-light">
                  <th class="bg-blue text-light" colspan="8">SKU</th>
                  <th colspan="6">FARMACIA</th>
                  <th colspan="6">CADENA FARMACIA</th>
                  <th colspan="6">MAYORISTA</th>
                  <th colspan="6">INSTITUCION PRIVADA</th>
                  <th colspan="6">CRUZ AZUL</th>
                  <th colspan="6">INSTITUCION PUBLICA</th>
                  <th colspan="6">MINSA - LICIT</th>
                  <th colspan="6">TOTAL</th>
                  {{-- Columnas adicionales: año anterior (7 canales + total) y venta mensual por canal --}}
                  <th colspan="16">AÑO ANTERIOR</th>
                  @foreach ($canalesExtra as $canal)
                  <th colspan="24">{{ $canal[0] }} - MENSUAL</th>
                  @endforeach
                  <th colspan="2">LOTE PROXIMO A VENCER</th>
                  <th colspan="1">CLIENTES</th>
                  <th colspan="3">ULTIMA COMPRA</th>
                  <th colspan="1">BONIFICADO</th>
                </tr>
                <tr>
                  <th class="bg-blue" colspan="8"></th>
                  <th id="Farmacia_Cantidad"></th>
                  <th id="Farmacia_Promedio"></th>
                  <th id="Farmacia_Venta"></th>
                  <th id="Farmacia_Costo"></th> 
                  <th id="Farmacia_Contribucion"></th>
                  <th id="Farmacia_Margen"></th>
                  <th id="Cadena_Farmacia_Cantidad"></th>
                  <th id="Cadena_Farmacia_Promedio"></th>
                  <th id="Cadena_Farmacia_Venta"></th>
                  <th id="Cadena_Farmacia_Costo"></th>
                  <th id="Cadena_Farmacia_Contribucion"></th>
                  <th id="Cadena_Farmacia_Margen"></th>
                  <th id="Mayorista_Cantidad"></th>
                  <th id="Mayorista_Promedio"></th>
                  <th id="Mayorista_Venta"></th>
                  <th id="Mayorista_Costo"></th>
                  <th id="Mayorista_Contribucion"></th>
                  <th id="Mayorista_Margen"></th>
                  <th id="Institucion_Privada_Cantidad"></th>
                  <th id="Institucion_Privada_Promedio"></th>
                  <th id="Institucion_Privada_Venta"></th>
                  <th id="Institucion_Privada_Costo"></th>
                  <th id="Institucion_Privada_Contribucion"></th>
                  <th id="Institucion_Privada_Margen"></th>
                  <th id="Cruz_Azul_Cantidad"></th>
                  <th id="Cruz_Azul_Promedio"></th>
                  <th id="Cruz_Azul_Venta"></th>
                  <th id="Cruz_Azul_Costo"></th>
                  <th id="Cruz_Azul_Contribucion"></th>
                  <th id="Cruz_Azul_Margen"></th>
                  <th id="Institucion_Publica_Cantidad"></th>
                  <th id="Institucion_Publica_Promedio"></th>
                  <th id="Institucion_Publica_Venta"></th>
                  <th id="Institucion_Publica_Costo"></th>
                  <th id="Institucion_Publica_Contribucion"></th>
                  <th id="Institucion_Publica_Margen"></th>
                  <th id="Licitacion_Cantidad"></th>
                  <th id="Licitacion_Promedio"></th>
                  <th id="Licitacion_Venta"></th>
                  <th id="Licitacion_Costo"></th>
                  <th id="Licitacion_Contribucion"></th>
                  <th id="Licitacion_Margen"></th>
                  <th id="Total_Cantidad"></th>
                  <th id="Total_Promedio"></th>
                  <th id="Total_Venta"></th>
                  <th id="Total_Costo"></th>
                  <th id="Total_Contribucion"></th>
                  <th id="Total_Margen"></th>
                  @for ($i = 0; $i < 191; $i++)
                  <th></th>
                  @endfor
                </tr>
                <tr>
                    <th class="bg-blue text-light">ARTICULO</th>
                    <th class="bg-blue text-light">DESCRIPCION</th>
                    <th class="bg-blue text-light">FABRICANTE</th>
                    <th class="bg-blue text-light">CATEGORIA</th>
                    <th class="bg-blue text-light">INVENTARIO</th>
                    <th class="bg-blue text-light">PEDIDO</th>
                    <th class="bg-blue text-light">TRANSITO</th>
                    <th class="bg-blue text-light">PROM. NORMAL</th>
                    <th class="bg-warning text-black">F. UNDS</th>
                    <th class="bg-warning text-black">F. PREC. P</th>
                    <th class="bg-warning text-black">F. VTA</th>
                    <th class="bg-warning text-black">F. CTO</th>
                    <th class="bg-warning text-black">F. CTB</th>
                    <th class="bg-warning text-black">F. %</th>
                    <th style="background-color:peru">C. UNDS</th>
                    <th style="background-color:peru">C. PREC. P</th>
                    <th style="background-color:peru">C. VTA</th>
                    <th style="background-color:peru">C. CTO</th>
                    <th style="background-color:peru">C. CTB</th>
                    <th style="background-color:peru">C. %</th>
                    <th style="background-color:burlywood">M. UNDS</th>
                    <th style="background-color:burlywood">M. PREC. P</th>
                    <th style="background-color:burlywood">M. VTA</th>
                    <th style="background-color:burlywood">M. CTO</th>
                    <th style="background-color:burlywood">M. CTB</th>
                    <th style="background-color:burlywood">M. %</th>
                    <th style="background-color:limegreen">PRI. UNDS</th>
                    <th style="background-color:limegreen">PRI. PREC. P</th>
                    <th style="background-color:limegreen">PRI. VTA</th>
                    <th style="background-color:limegreen">PRI. CTO</th>
                    <th style="background-color:limegreen">PRI. CTB</th>
                    <th style="background-color:limegreen">PRI. %</th>
                    <th style="background-color:cornflowerblue">CA. UNDS</th>
                    <th style="background-color:cornflowerblue">CA. PREC. P</th>
                    <th style="background-color:cornflowerblue">CA. VTA</th>
                    <th style="background-color:cornflowerblue">CA. CTO</th>
                    <th style="background-color:cornflowerblue">CA. CTB</th>
                    <th style="background-color:cornflowerblue">CA. %</th>
                    <th style="background-color:limegreen">PUB. UNDS</th>
                    <th style="background-color:limegreen">PUB. PREC. P</th>
                    <th style="background-color:limegreen">PUB. VTA</th>
                    <th style="background-color:limegreen">PUB. CTO</th>
                    <th style="background-color:limegreen">PUB. CTB</th>
                    <th style="background-color:limegreen">PUB. %</th>
                    <th style="background-color:lightskyblue">ML. UNDS</th>
                    <th style="background-color:lightskyblue">ML. PREC. P</th>
                    <th style="background-color:lightskyblue">ML. VTA</th>
                    <th style="background-color:lightskyblue">ML. CTO</th>
                    <th style="background-color:lightskyblue">ML. CTB</th>
                    <th style="background-color:lightskyblue">ML. %</th>
                    <th style="background-color:gold">T. UNDS</th>
                    <th style="background-color:gold">T. PREC. P</th>
                    <th style="background-color:gold">T. VTA</th>
                    <th style="background-color:gold">T. CTO</th>
                    <th style="background-color:gold">T. CTB</th>
                    <th style="background-color:gold">T. %</th>
                    @foreach ($canalesExtra as $pref => $canal)
                    <th style="background-color:{{ $canal[1] }}" class="th-ant">{{ $pref }}. ANT UNDS</th>
                    <th style="background-color:{{ $canal[1] }}" class="th-ant">{{ $pref }}. ANT VTA</th>
                    @endforeach
                    <th style="background-color:gold" class="th-ant">T. ANT UNDS</th>
                    <th style="background-color:gold" class="th-ant">T. ANT VTA</th>
                    @foreach ($canalesExtra as $pref => $canal)
                    @for ($i = 1; $i <= 12; $i++)
                    <th style="background-color:{{ $canal[1] }}" class="th-mes" data-mes="{{ $i - 1 }}" data-prefijo="{{ $pref }}. U">{{ $pref }}. U M{{ $i }}</th>
                    @endfor
                    @for ($i = 1; $i <= 12; $i++)
                    <th style="background-color:{{ $canal[1] }}" class="th-mes" data-mes="{{ $i - 1 }}" data-prefijo="{{ $pref }}. C$">{{ $pref }}. C$ M{{ $i }}</th>
                    @endfor
                    @endforeach
                    <th style="background-color:khaki">LOTE VENCE</th>
                    <th style="background-color:khaki">LOTE CANT.</th>
                    <th style="background-color:plum" title="Clientes distintos que compraron el SKU en el periodo">N° CLIENTES</th>
                    <th style="background-color:lightgreen" title="Fecha del ultimo ingreso por compra (embarque)">ULT. COMPRA</th>
                    <th style="background-color:lightgreen" title="Costo por unidad de la ultima compra, en C$">ULT. COSTO C$</th>
                    <th style="background-color:lightgreen" title="Costo por unidad de la ultima compra, en US$">ULT. COSTO US$</th>
                    <th style="background-color:lightsalmon" title="Unds. bonificadas / Unds. vendidas x 100&#10;Solo canales privados, en el periodo">% BONIF.</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>

  
    <div class="modal fade bd-example-modal-xl" data-backdrop="static" data-keyboard="false" id="mdDetalleArt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-block ">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-right align-items-right pt-1 pb-0 bg-blue">
                                  <div class="flex-1"><p></p>
                                    <h6 class="fw-semi-bold">
                                      <div class="text-light text-uppercase" id="id_descripcion"></div>
                                      <span id="id_articulo" style="display: none"></span>
                                    </h6><p></p>
                                  </div>
                                </div>
                                <div class="card-body">
                                  <div class="row" id="info1">

                                    <div class="col-sm-2">
                                        <div class="card card-social" style="height: 120px">
                                            <div class="card-header text-center bg-blue">
                                                <h6 class="text-white m-0">COSTO PROM. PRIV. PACK C$</h6>
                                            </div>
                                            <div class="card-body ">
                                              <h6 class="text-center  font-weight-bold" style="font-size: 1.3rem!important"  id="idCostoPriv"> 0.00</h6>
                                              
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="card card-social" style="height: 120px">
                                            <div class="card-header text-center bg-blue">
                                                <h6 class="text-white m-0">COSTO PROM. MINSA C$</h6>
                                            </div>
                                            <div class="card-body ">
                                              <h6 class="text-center  font-weight-bold" style="font-size: 1.3rem!important"  id="idCostoMinsa"> 0.00</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="card card-social" style="height: 120px">
                                            <div class="card-header text-center bg-blue">
                                                <h6 class="text-white m-0">VALOR USD INVENTARIO ONHAND-PRIVADO</h6>
                                            </div>
                                            <div class="card-body ">
                                              <h6 class="text-center font-weight-bold" style="font-size: 1.3rem!important"  id="idValorInventario">  0.00</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">

                                      <div class="card card-social" style="height: 120px">
                                          <div class="card-header text-center bg-blue">
                                              <h6 class="text-white m-0">VALOR USD TOTAL DISPONIBILIDAD ONHAND+TRANSITO - PRIVADO</h6>
                                          </div>
                                          <div class="card-body ">
                                              <h6 class="text-center font-weight-bold" style="font-size: 1.3rem!important"  id="idValorDisponible">  0.00</h6>
                                            </div>
                                      </div>
                                    </div>
                                        
                                       
                                        
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                        <!-- [ Header orden produccion ] end -->
                    </div>
                    <div class="row mt-3" id="info2">
                        <div class="col-4">
                            <div class="card card-social" style="height: 120px">
                                <div class="card-header text-center bg-blue">
                                    <h6 class="text-white m-0">DISPONIBILIDAD PACKS-PRIVADO >= 6 MESES</h6>
                                </div>
                                <div class="card-body ">
                                  <h6 class="text-center font-weight-bold" style="font-size: 1.3rem!important"  id="idCantDisponible">  0.00</h6>
                                </div>                     
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-social" style="height: 120px">
                                <div class="card-header text-center bg-blue">
                                    <h6 class="text-white m-0">LOTE MAS PROXIMO A VENCER PRIVADO >= 6 MESES</h6>
                                </div>
                                <div class="card-body ">
                                  <h6 class="text-center font-weight-bold" style="font-size: 1.3rem!important"  id="idLoteVencer">  --/--/----</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-social" style="height: 120px">
                                <div class="card-header text-center p-2 bg-blue">
                                    <h6 class="m-0 text-white">EXISTENCIA EN LOTE MAS PROXIMO A VENCER PRIVADO >= 6 MESES</h6>
                                </div>
                                <div class="card-body ">
                                  <h6 class="text-center font-weight-bold" style="font-size: 1.3rem!important"  id="idCantProxima">  0.00</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-6 text-center">
                      <p class="text-muted m-0">PROM. NORMAL</p>
                      <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="prom_normal">0.00</p>
                    </div>

                    <div class="col-sm-6 text-center">
                      <p class="text-muted m-0">UNITS DESPLAZADAS.</p>
                      <p class="font-weight-bolder" style="font-size: 1.3rem!important" id="units_desplazadas">0.00</p>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-12 col-md-12 col-xs-12">
                          <div class="container-vms" id="grafMeses" style="width: 100%; margin: 0 auto"></div>
                      </div>  
                  </div>
                <div class="modal-footer">
                  
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    
              

</div>


@endsection('content')