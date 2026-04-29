
@extends('layouts.lyt_acciones')
@section('content')
<table class="body-wrap">
    <tbody>
        <tr>
            <td></td>
            <td class="container" width="600">
                <div class="content" id="content_print">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="content-wrap aligncenter">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td class="content-block">
                                                    <div style="text-align:center; width:100%;">
    
                                                        <img 
                                                            src="{{ asset('img/unimark-print.png') }}" 
                                                            alt="Logo"
                                                            style="opacity:.8; display:block; margin:0 auto 15px auto; max-width:200px;"
                                                        >

                                                        <h4 style="margin:5px 0;">
                                                            <strong>Villa Fontana, Club Terraza 150 mts. al Oeste</strong>
                                                        </h4>

                                                        <h4 style="margin:5px 0;">
                                                            Managua, Nicaragua
                                                        </h4>

                                                        <h4 style="margin:5px 0;">
                                                            (+505) 2278 - 8787 | 8574 - 2828
                                                        </h4>

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block">
                                                    <table class="invoice">
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $InfoFactura->CLIENTE }} | {{ $InfoFactura->NOMBRE }}<br>FACT. #{{ $InfoFactura->FACTURA }}<br> {{ $InfoFactura->FECHA }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                        <tbody>
                                                                            
                                                                            @foreach($Acciones as $accion)
                                                                            
                                                                                <span class="btn-acciones">{{ $accion->NUMERO }}</span>
                                                                            
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block">
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis hic sunt cumque ullam nemo deleniti, 
                                                    animi repudiandae eveniet maxime adipisci voluptate nam quidem non modi aut accusantium nobis a voluptatem!
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="content-block aligncenter">
                                                    <img src="https://quickchart.io/qr?text=https://unimarksa.com&size=200" alt="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block aligncenter">
                                                    <h4 style="margin:5px 0;">
                                                        <strong>¡Gracias por su compra!</strong>
                                                    </h4>
                                                </td>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection
