
@extends('layouts.lyt_acciones')
@section('content')
<style>
    @media print {
        body { margin: 0; padding: 0; }
        .no-print { display: none !important; }
    }
    .recibo-container {
        width: 300px;
        max-width: 100%;
        margin: 0 auto;
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        line-height: 1.3;
    }
    .recibo-header {
        text-align: center;
        border-bottom: 2px dashed #000;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    .recibo-logo {
        max-width: 140px !important;
        opacity: 0.9;
    }
    .recibo-title {
        font-size: 18px;
        font-weight: 900;
        margin: 10px 0 6px 0;
        letter-spacing: 1px;
    }
    .recibo-info {
        margin-bottom: 12px;
        border-bottom: 2px dashed #000;
        padding-bottom: 12px;
    }
    .recibo-info p {
        margin: 4px 0;
        font-weight: 600;
    }
    .recibo-acciones {
        text-align: center;
        margin: 15px 0;
        padding: 12px 0;
        border: 2px solid #000;
    }
    .recibo-acciones-title {
        font-weight: 900;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .btn-acciones {
        display: inline-block;
        padding: 6px 10px;
        margin: 3px;
        border: 2px solid #000;
        font-weight: 800;
        font-size: 14px;
    }
    .recibo-qr {
        text-align: center;
        margin: 12px 0;
    }
    .recibo-qr img {
        max-width: 110px;
    }
    .recibo-footer {
        text-align: center;
        border-top: 2px dashed #000;
        padding-top: 12px;
        margin-top: 12px;
        font-size: 12px;
        font-weight: 700;
    }
    .recibo-terminos {
        font-size: 10px;
        margin: 12px 0;
        text-align: justify;
        font-weight: 500;
    }
</style>

<table class="body-wrap">
    <tbody>
        <tr>
            <td></td>
            <td class="container" width="320">
                <div class="content" id="content_print">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="content-wrap aligncenter">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <!-- Header con logo -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-header">
                                                        <img 
                                                            src="{{ asset('img/unimark-print.png') }}" 
                                                            alt="Logo"
                                                            class="recibo-logo"
                                                        >
                                                        <div style="font-size:11px; margin:6px 0; font-weight:600;">
                                                            Villa Fontana, Club Terraza<br>
                                                            150 mts. al Oeste<br>
                                                            Managua, Nicaragua
                                                        </div>
                                                        <div style="font-size:11px; font-weight:600;">
                                                            (+505) 2278-8787 | 8574-2828
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Info factura -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-info">
                                                        <p style="font-size:12px;"><strong> {{ $InfoFactura->NOMBRE }} | {{ $InfoFactura->CLIENTE }} </strong></p>
                                                        <p style="font-size:12px;"><strong>Fact.:</strong> {{ $InfoFactura->FACTURA }}</p>
                                                        <p style="font-size:12px;"><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($InfoFactura->FECHA)) }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Acciones -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-acciones">
                                                        <div class="recibo-acciones-title">ACCIONES ({{ count($Acciones) }}):</div>
                                                        @foreach($Acciones as $accion)
                                                            <span class="btn-acciones">{{ $accion->NUMERO }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- QR -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-qr">
                                                        {!! $UrlQR !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Términos -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-terminos">
                                                        <strong>Términos:</strong> Este recibo es válido para participar en el sorteo de Rifas Car. El ganador se anunciará en nuestras redes sociales. No se aceptan cambios ni devoluciones.
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Footer -->
                                            <tr>
                                                <td class="content-block aligncenter">
                                                    <div class="recibo-footer">
                                                        <strong>¡Gracias por su compra!</strong>
                                                    </div>
                                                </td>
                                            </tr>
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
