
@extends('layouts.lyt_acciones')
@section('content')
<style>
    @page {
        size: 72.1mm 210mm;
        margin: 0;
    }
    @media print {
        html, body {
            margin: 0 !important;
            padding: 0 !important;
        }
        .no-print { display: none !important; }
        body {
            width: 72.1mm !important;
        }
        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .content {
            max-width: 100% !important;
            padding: 2mm !important;
            margin: 0 !important;
        }
        .body-wrap {
            width: 100% !important;
        }
        .main {
            border: none !important;
        }
        .content-wrap {
            padding: 0 !important;
        }
        .content-block {
            padding: 0 !important;
        }
        .recibo-logo {
            max-width: 50mm !important;
        }
        .recibo-qr img {
            max-width: 35px !important;
        }
        .accion-cell.zebra{ background:#fff !important; }
    }
    .recibo-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        line-height: 1.3;
    }
    .container {
        max-width: 72.1mm;
        margin: 0 auto;
    }
    .recibo-header {
        text-align: center;
        border-bottom: 2px dashed #000;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    .recibo-logo {
        max-width: 120px !important;
        opacity: 0.9;
    }
    .recibo-title {
        font-size: 18px;
        font-weight: 900;
        margin: 10px 0 6px 0;
        letter-spacing: 1px;
    }
    .recibo-info {
        border-bottom: 2px dashed #000;
    }
    .recibo-info p {
        margin: 4px 0;
        font-weight: 600;
    }
    .acciones-table{
        width:100%;
        border-spacing:0;
        table-layout:fixed;
    }
    .accion-cell{
        border-bottom:1px solid #000;
        border-right:1px solid #000;
        padding:1mm .3mm;
        text-align:center;
        width:25%;
    }
    .acciones-table tbody tr td:first-child{
        border-left:1px solid #000;
    }
    .acciones-table tbody tr:first-child td{
        border-top:1px solid #000;
    }
    .accion-cell.zebra{ background:#eef2f8; }
    .accion-num{
        font-weight:800;
        font-size:13px;
    }
    .recibo-acciones-title {
        font-weight: 900;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .recibo-qr img {
        max-width: 28px;
    }
    .recibo-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 2px dashed #000;
        padding-top: 12px;
        margin-top: 12px;
        font-size: 12px;
        font-weight: 700;
    }
    .content-block {
        padding: 0 !important;
    }
    .recibo-terminos {
        font-size: 9px;
        text-align: justify;
        font-weight: 500;
    }
    .recibo-qr-wrap {
        float: right;
        margin: 0 0 4px 8px;
    }
</style>

<table class="body-wrap" width="100%">
    <tbody>
        <tr>
            <td class="container">
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
                                                            Villa Fontana, Club Terraza 
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
                                                        <p style="font-size:12px;"><strong>Fact.:</strong> {{ $InfoFactura->FACTURA }} &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Fecha:</strong> {{ date('d/m/Y', strtotime($InfoFactura->FECHA)) }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Acciones -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-acciones-title">ACCIONES ({{ count($Acciones) }}):</div>
                                                    <table class="acciones-table">
                                                        @foreach($Acciones as $i => $accion)
                                                            @if($i % 4 === 0)<tr>@endif
                                                                <td class="accion-cell{{ floor($i / 4) % 2 === 1 ? ' zebra' : '' }}">
                                                                    <span class="accion-num">{{ $accion->NUMERO }}</span>
                                                                </td>
                                                            @if($i % 4 === 3 || $loop->last)</tr>@endif
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <!-- T&eacute;rminos + QR -->
                                            <tr>
                                                <td class="content-block">
                                                    <div class="recibo-terminos">
                                                        <strong>T&eacute;rminos:</strong> Promoci&oacute;n v&aacute;lida del 15 de junio al 21 de noviembre de 2026. Por cada C$1,500.00 netos en compras de productos participantes, recibe una (1) acci&oacute;n electr&oacute;nica. Aplica &uacute;nicamente para clientes del canal farmacia privada con c&oacute;digo activo en UNIMARK S.A. Las acciones anuladas por devoluciones, notas de cr&eacute;dito o falta de pago no participan. El sorteo se realizar&aacute; con base en los resultados de la Loter&iacute;a Nacional del 24 de noviembre de 2026. El ganador deber&aacute; estar solvente con UNIMARK S.A. Aplican restricciones. Consulte el reglamento completo en UNIMARK S.A.
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- Footer -->
                                            <tr>
                                                <td class="content-block aligncenter">
                                                    <div class="recibo-footer">
                                                        <span><strong>¡Gracias por su compra!</strong></span>
                                                        <div class="recibo-qr-wrap">{!! $UrlQR !!}</div>
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
