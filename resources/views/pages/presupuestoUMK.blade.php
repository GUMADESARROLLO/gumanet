@extends('layouts.main')
@section('content')
<div class="container-fluid">	
    <div class="row">
        <div class="col-sm-2">
            <div class="input-group">
                <select class="custom-select" id="InputCanales" name="InputCanales">
                <option value="acumulada" selected>ACUMULADA</option>
                <option value="diciembre">DICIEMBRE</option>
                <option value="20">20</option>
                <option value="100">100</option>
                <option value="-1">Todo</option>
                </select>
            </div>
        </div>
        <!--<div class="col-sm-1" >
        <a id="exp-to-excel-canales" href="#!" class="btn btn-light btn-block text-success"><i class="fas fa-file-excel"></i> Exportar</a>
        </div>-->   
  </div>

  <div class="card border-0 shadow-sm mt-5">
      <div class="card-body col-sm-12 p-0 mb-2">	
        <div class="p-0 px-car">
          <div class="table-responsive flex-between-center scrollbar border border-1 border-300 rounded-2">
          
            <table id="table_presupuesto" class="table table-bordered table-sm" width="100%">
              <thead>
                <tr class="bg-blue text-light">
                  <th ></th>
                  <th >EJECUTADO</th>
                  <th >%</th>
                  <th >PRESUPUESTO</th>
                  <th >%</th>
                  <th >DIFERENCIA ABSOLUTA</th>
                  <th >DIFERENCIA RELATIVA</th>
                </tr>               
              </thead>
              <tbody>
                <tr>
                    <td class="bg-blue text-light">VENTAS BRUTAS</td>
                    <td>{{$presupuesto['VentaBruta']['Ejecutado']}}</td>
                    <td>100%</td>
                    <td>{{$presupuesto['VentaBruta']['Presupuesto']}}</td>
                    <td>100%</td>
                    <td>{{$presupuesto['VentaBruta']['DifAbsoluta']}}</td>
                    <td>{{$presupuesto['VentaBruta']['DifRelativa']}}</td>
                </tr>
                <tr>
                    <td class="bg-blue text-light">VENTAS PRIVADO</td>
                    <td>{{$presupuesto['TotalPrivado']['Ejecutado']}}</td>
                    <td>100%</td>
                    <td>{{$presupuesto['TotalPrivado']['Presupuesto']}}</td>
                    <td>100%</td>
                    <td>{{$presupuesto['TotalPrivado']['DifAbsoluta']}}</td>
                    <td>{{$presupuesto['TotalPrivado']['DifRelativa']}}</td>
                </tr>
                <tr>
                    <td class="bg-blue text-light">VENTAS PRIMARIOS UMK</td>
                    <td>{{$presupuesto['Primario']['Ejecutado']}}</td>
                    <td>{{$presupuesto['Primario']['PorcientoEje']}}</td>
                    <td>{{$presupuesto['Primario']['Presupuesto']}}</td>
                    <td>100%</td>
                    <td>{{9001552.13 - 12887968}}</td>
                    <td>({{@number_format(((9001552.13 - 12887968)/12887968)*100,2)}})</td>
                </tr>
                <tr>
                    <td class="bg-blue text-light">VENTAS SECUNADARIOS UMK</td>
                    <td>{{$presupuesto['TotalPrivado']['Secundario']}}</td>
                    <td></td>
                    <td>2,785,449</td>
                    <td>100%</td>
                </tr>
                <tr>
                    <td class="bg-blue text-light">VENTAS NUEVOS</td>
                    <td>{{$presupuesto['TotalPrivado']['Nuevo']}}</td>
                    <td></td>
                    <td>0</td>
                    <td>100%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection('content')