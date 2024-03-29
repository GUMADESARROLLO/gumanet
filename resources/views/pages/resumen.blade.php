@extends('layouts.lytresumen')
@section('content')
@php 
    $recibido   = 0; 
    $count_linea = 1;
@endphp
    <table width="100%">
        <tr>
            <td valign="top"></td>
            <td align ="right">
                <h3>Nº {{$data['IdLiq']}}</h3>
                <h3>UNIMARK, S.A.</h3>
                    <pre>
                        Villa Fontana, Club Terraza 150m Oeste
                        Managua, Nicaragua
                        Tel.: 2278-8787 - E-mail : info@unimarksa.com
                        RUC: J0310000121249
                    </pre>
            </td>
        </tr>
    </table>

    <div class="text-center ml ">
        <strong>LIQUIDACION DE VIÑETAS:</strong>
    </div>

    <table width="100%">  
        <tr >
            <td><strong>EJECUTIVO:</strong> {{$data['Ejecutivo']}}</td>
            <td><strong>FECHA:</strong> {{$data['Fecha']}}</td>
        </tr>
        <tr>
            <td><strong>RUTA:</strong> {{$data['Ruta']}}</td>
            <td><strong>FONDO INICIAL C$:</strong> {{number_format($data['Fondo'],2)}}</td>
        </tr>
    </table>
    
    <div class="text-center">
        <strong><br>DETALLE DE RECIBOS DE PAGOS DE VIÑETAS</strong>
    </div>

    <br/>

    <table width="100%" >
        <thead style="background-color: lightgray;">
            <tr>
                <th>Item</th>
                <th>Fecha</th>
                <th>No. de Recibo pago</th>
                <th>Nombre del cliente</th>
                <th>Codigo</th>
                <th>Concepto</th>
                <th>Total C$</th>
            </tr>
        </thead>
        <tbody>
            @if (count($resumen) > 0)
                @foreach($resumen as $key)
                @if ($key['STATUS']  != 0)

                @php 
                    $suma        = 0; 
                @endphp
                
                    @foreach($key['DETALLES'] as $dt)
                        @php
                            $suma+=$dt['CANTIDAD'];
                        @endphp
                    @endforeach 

                    @php
                        $recibido += preg_replace('/[^0-9-.]+/', '', $key['TOTAL']);
                        
                    @endphp
                    
                    <tr>
                        <th scope="row">{{$count_linea}}</th>
                        <td>{{ $key['FECHA'] }}</td>
                        <td>{{ $key['RECIBO'] }}</td>
                        <td>{{ $key['NOMBRE_CLIENTE'] }}</td>
                        <td align="right">{{ $key['CLIENTE'] }}</td>
                        <td align="right">Pago Viñeta ( {{$suma}} ) </td>
                        <td align="right">{{ $key['TOTAL'] }}</td>
                    </tr>
                    
                    @php 
                        $count_linea++;
                    @endphp
                    @endif
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
            <tr>
                <td colspan="2" align="right">SALDO C$ </td>
                <td align="right" >
                    {{ number_format(($data['Fondo'] - $recibido),2) }}    
                    
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">MONTO A REEMBOLSAR C$</td>
                <td  align="right">
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