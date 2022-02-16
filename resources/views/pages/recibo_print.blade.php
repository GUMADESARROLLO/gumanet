@extends('layouts.lytreciboprint')
@section('content')
@php 
    $recibido   = 0; 
    $valor_recibido   = 0; 
    $count_linea = 1;
@endphp
    <div class="text-center ml ">
        <strong>RECIBOS PAGADOS:</strong>
    </div>

    <table width="100%" style=" border: 1px solid #fff !important">  
        <tr >
            <td><strong>EJECUTIVO:</strong> {{$data['Ejecutivo']}}</td>
            <td><strong>FECHA:</strong> {{$data['Fecha']}}</td>
        </tr>
        <tr>
            <td><strong>RUTA:</strong> {{$data['Ruta']}}</td>
        </tr>
    </table>
    
    <div class="text-center">
        <strong><br>DETALLE DE RECIBOS DE PAGADOS</strong>
    </div>

    <br/>

    <table width="100%" style=" border: 1px solid #fff !important">
        <thead style="background-color: lightgray;" >
            <tr >
                <th>Item</th>
                <th>Fecha</th>
                <th>No. de Recibo pago</th>
                <th colspan="2">Nombre del cliente</th>
                <th>Codigo</th>
                <th>Total C$</th>
            </tr>
        </thead>
        <tbody>
            @if (count($resumen) > 0)
                @foreach($resumen as $key)

                @php 
                    $suma       = 0; 
                    $total_dtl  = 0;

                    $thead_dtl  = "";
                    $tbody_dtl  = "";
                    $temp_dtl   = "";

                    $thead_dtl ='<table  width="100%">
                    <thead style="background-color: lightgray;">
                            <tr >
                                <th class="text-center">FACTURA</th>
                                <th class="text-center">VALOR FACTURA</th>
                                <th class="text-center">VALOR N/C</th>
                                <th class="text-center">RETENCION</th>
                                <th class="text-center">DESCUENTO</th>
                                <th class="text-center">VALOR RECIBIDO</th>
                                <th class="text-center">SALDO</th>
                                <th class="text-center">TIPO</th>
                                
                            </tr>
                        </thead>
                    <tbody>';


                @endphp
                
                    @foreach($key['DETALLES'] as $dt)
                        @php
                            $suma++;
                            $valor_recibido += preg_replace('/[^0-9-.]+/', '', $dt['VALORRECIBIDO']);

                            $total_dtl = $dt['VALORFACTURA'] - $dt['NOTACREDITO'] - $dt['RETENCION'] - $dt['DESCUENTO'] - $dt['VALORRECIBIDO'];

                            $tbody_dtl .= '<tr >
                                <td class="text-center">' . $dt['FACTURA'] .'</td>
                                <td class="text-center">C$ ' . number_format($dt['VALORFACTURA'],2) . '</td>
                                <td class="text-center">C$ ' . number_format($dt['NOTACREDITO'],2) . '</td>
                                <td class="text-center">C$ ' . number_format($dt['RETENCION'],2) . '</td>
                                <td class="text-center">C$ ' . number_format($dt['DESCUENTO'],2)  . '</td>
                                <td class="text-center">C$ ' . number_format($dt['VALORRECIBIDO'],2) . '</td>
                                <td class="text-center">C$ ' . number_format($total_dtl,2). '</td>
                                <td class="text-center">' . $dt['TIPO'] .'</td>
                            </tr>';
                        @endphp
                        
                    @endforeach 

                    @php
                        $tbody_dtl .='<tr >
                                <td colspan="5"></td>
                                <td class="text-center" style="background-color: lightgray;"> C$ ' . number_format($valor_recibido,2). ' </td>
                                <td colspan="2"> </td>
                            </tr>';
                        $tbody_dtl .= '</tbody></table>';

                        /*if ($key['STATUS'] == "Ingresado"){
                            $recibido += preg_replace('/[^0-9-.]+/', '', $key['TOTAL']);
                        }*/
                        $recibido += preg_replace('/[^0-9-.]+/', '', $key['TOTAL']);
                        
                    @endphp
                    <tr class="tblMarginTop"  >
                        <th >{{$count_linea}}</th>
                        <td align="center">{{ $key['FECHA'] }}</td>
                        <td align="center">{{ $key['RECIBO'] }}</td>
                        <td colspan="2">{{ $key['NOMBRE_CLIENTE'] }}</td>
                        <td align="center">{{ $key['CLIENTE'] }}</td>
                        <td align="center">{{ $key['TOTAL'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-center" > <?php echo "$thead_dtl  $tbody_dtl"; ?><br></td>
                    </tr>
                    

                    
                    
                    @php 
                        $count_linea++;
                        $thead_dtl = '';
                        $tbody_dtl = '';
                        $valor_recibido = 0;
                    @endphp
                @endforeach
                @else
                <tr>
                    <th colspan="6">Sin RECIBOS</th>                    
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7"><br></td>
            </tr>
            <tr>
                <td rowspan="4" colspan="4" >
                    <div class="w3-border text-center" >
                        <h2>Notas: {{ $data['Nota'] }}</h2><br>                                        
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">TOTAL RECIBIDO C$</td>
                <td align="right">
                    {{number_format($recibido,2)}}
                </td>
            </tr>
        </tfoot>
    </table
    
    <div class="text-center ml "><br><br><br><br><br><br></div>

    <table width="100%" class="text-center">  
        <tr>            
            <td>_____________________________________<br><strong>Entregué Conforme:</strong></td>
            <td>_____________________________________<br><strong>Recibí Conforme</strong> </td>
        </tr>
        <tr>
            <td><strong>Ejecutivo de Ventas</strong> </td>
            <td><strong>Cartera y Cobro</strong></td>
        </tr>
    </table>
@endsection