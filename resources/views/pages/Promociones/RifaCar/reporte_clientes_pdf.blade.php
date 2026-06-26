<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>UNIMARK S.A. | Reporte de Acciones y Facturas</title>
<style>
    @page { margin: 8mm 10mm; }
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #0d1c2e;
        margin: 0;
        padding: 0;
    }
    h1 {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #002045;
        margin: 0 0 2px 0;
    }
    h2 {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        color: #ffffff;
        margin: 0;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    /* Header */
    .report-header { width: 100%; margin-bottom: 15px; }
    .report-header td { vertical-align: top; }

    /* Summary cards */
    .cards-table { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 15px; }
    .cards-table td {
        width: 33.33%;
        background: #ffffff;
        border: 1px solid #c4c6cf;
        border-radius: 6px;
        padding: 12px 10px;
        vertical-align: middle;
        text-align: center;
    }
    .card-label { font-size: 10px; color: #595f66; letter-spacing: 0.05em; text-align: center; margin-bottom: 2px; }
    .card-value { font-size: 26px; font-weight: 700; color: #002045; line-height: 1.1; text-align: center; }

    /* Client info */
    .client-box {
        background: #eff4ff;
        border: 1px solid #c4c6cf;
        border-radius: 6px;
        padding: 10px 16px;
        margin-bottom: 12px;
    }
    .client-inner { width: 100%; }
    .client-inner td { vertical-align: middle; }
    .client-label { font-size: 9px; color: #595f66; letter-spacing: 0.05em; margin-bottom: 1px; }
    .client-name { font-size: 15px; font-weight: 700; color: #002045; }
    .client-code-wrap { border-left: 1px solid #c4c6cf; padding-left: 16px; }
    .client-code { font-size: 15px; font-weight: 700; color: #002045; }

    /* Invoices section */
    .invoices-box {
        border: 1px solid #c4c6cf;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .invoices-header {
        background: #002045;
        color: #ffffff;
        padding: 8px 14px;
    }
    .invoices-header table { width: 100%; }
    .invoices-header td { vertical-align: middle; }
    .invoices-header .badge {
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
    }
    .invoices-body { padding: 8px 14px; }

    /* Invoice row */
    .invoice-row {
        border-left: 4px solid #002045;
        background: #ffffff;
        border: 1px solid #c4c6cf;
        padding: 10px 12px;
        margin-bottom: 8px;
        page-break-inside: avoid;
    }
    .invoice-row table { width: 100%; border-collapse: collapse; }
    .invoice-row td { vertical-align: top; }
    .invoice-info-cell { width: 26%; padding-right: 12px; }
    .invoice-acciones-cell { width: 74%; }
    .invoice-num {
        font-weight: 700;
        color: #002045;
        font-size: 13px;
        margin-bottom: 1px;
    }
    .invoice-date { color: #595f66; font-size: 11px; margin-bottom: 1px; }
    .invoice-amount {
        color: #003f25;
        font-weight: 700;
        font-size: 13px;
    }

    /* Acciones section */
    .acciones-header { width: 100%; margin-bottom: 4px; }
    .acciones-header table { width: 100%; }
    .acciones-label {
        font-size: 10px;
        font-weight: 700;
        color: #595f66;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .acciones-badge {
        font-size: 10px;
        font-weight: 700;
        color: #595f66;
    }
    .pill {
        font-size: 10px;
        font-weight: 700;
        color: #002045;
        background: #eff4ff;
        border: 1px solid #c4c6cf;
        border-radius: 3px;
        padding: 2px 6px;
        text-align: center;
    }
    .acciones-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .acciones-table td {
        width: 12.5%;
        padding: 1px;
        vertical-align: middle;
    }
    .acciones-table td .pill {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #002045;
        background: #eff4ff;
        border-radius: 4px;
        padding: 2px 4px;
        text-align: center;
    }

    /* Totals */
    .totals-table { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 15px; }
    .totals-table td {
        padding: 14px;
        border-radius: 6px;
        text-align: center;
        vertical-align: middle;
    }
    .totals-label { font-size: 11px; letter-spacing: 0.05em; }
    .totals-value { font-size: 22px; font-weight: 700; }
    .totals-card-1 { background: #dce9ff; border: 1px solid #c4c6cf; }
    .totals-card-1 .totals-label { color: #595f66; }
    .totals-card-1 .totals-value { color: #002045; }
    .totals-card-2 { background: #002045; border: 1px solid #002045; }
    .totals-card-2 .totals-label { color: #86a0cd; }
    .totals-card-2 .totals-value { color: #ffffff; }

    /* Footer */
    .report-footer {
        text-align: center;
        border-top: 1px solid #c4c6cf;
        padding-top: 12px;
        margin-top: 6px;
    }
    .report-footer p { margin: 2px 0; font-size: 11px; }
</style>
</head>
<body>

    <!-- Header -->
    <table class="report-header">
        <tr>
            <td>
                <h1>REPORTE DE ACCIONES Y FACTURAS</h1>
                <div style="color:#002045;font-size:10px;font-weight:700;margin-top:2px;">
                    UNIMARK S.A. <span style="color:#595f66;font-weight:400;">RIFA CAR 20 ANIVERSARIO</span>
                </div>
            </td>
            <td class="text-right" style="vertical-align:top;">
                <div style="color:#43474e;font-size:10px;font-weight:600;">Generado: {{ $fechaGeneracion }}</div>
                <div style="color:#595f66;font-size:10px;">Administrador: {{ $admin }}</div>
            </td>
        </tr>
    </table>

    <!-- Summary Cards -->
    <table class="cards-table">
        <tr>
            <td>
                <div class="card-label">Facturas</div>
                <div class="card-value">{{ $totalFacturas }}</div>
            </td>
            <td>
                <div class="card-label">Acciones</div>
                <div class="card-value">{{ $totalAcciones }}</div>
            </td>
            <td>
                <div class="card-label">Total Comprado</div>
                <div class="card-value">C$ {{ number_format($totalComprado, 2) }}</div>
            </td>
        </tr>
    </table>

    <!-- Client Info -->
    <div class="client-box">
        <table class="client-inner">
            <tr>
                <td style="width:auto;">
                    <div class="client-label">Nombre Completo</div>
                    <div class="client-name">{{ $nombre }}</div>
                </td>
                <td style="width:1px;white-space:nowrap;" class="client-code-wrap">
                    <div class="client-label">Código de Cliente</div>
                    <div class="client-code">{{ $cliente }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Invoices Detail -->
    <div class="invoices-box">
        <div class="invoices-header">
            <table>
                <tr>
                    <td>
                        <h2>Facturas y Acciones</h2>
                        <div style="font-size:9px;color:rgba(255,255,255,0.7);">Desglose detallado de transacciones vinculadas</div>
                    </td>
                    <td style="width:50px;text-align:center;vertical-align:middle;">
                        <span class="badge">({{ $totalFacturas }})</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="invoices-body">
            @foreach($facturas as $fac)
            <div class="invoice-row">
                <table>
                    <tr>
                        <td class="invoice-info-cell">
                            <div class="invoice-num">{{ $fac['FACTURA'] }}</div>
                            <div class="invoice-date">{{ isset($fac['FECHA']) ? date('d/m/Y', strtotime($fac['FECHA'])) : '—' }}</div>
                            <div class="invoice-amount">C$ {{ number_format($fac['TOTAL_FACTURA'], 2) }}</div>
                        </td>
                        <td class="invoice-acciones-cell">
                            <div class="acciones-header">
                                <table>
                                    <tr>
                                        <td style="width:99%;"><span class="acciones-label">ACCIONES</span></td>
                                        <td style="width:auto;text-align:right;">                                <span class="acciones-badge">({{ count($fac['ACCIONES']) }})</span></td>
                                    </tr>
                                </table>
                            </div>
                            @php $rows = array_chunk($fac['ACCIONES'], 8); @endphp
                            <table class="acciones-table">
                                <colgroup><col span="8"></colgroup>
                                @foreach($rows as $row)
                                <tr>
                                    @foreach($row as $num)
                                    <td><span class="pill">{{ $num }}</span></td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Totals -->
    <table class="totals-table">
        <tr>
            <td class="totals-card-1">
                <div class="totals-label">Monto Total Facturado</div>
                <div class="totals-value">C$ {{ number_format($totalComprado, 2) }}</div>
            </td>
            <td class="totals-card-2">
                <div class="totals-label">Acciones Totales Vinculadas</div>
                <div class="totals-value">{{ $totalAcciones }}</div>
            </td>
            <td style="background:#ffffff;border:1px solid #c4c6cf;border-radius:6px;padding:10px;text-align:center;vertical-align:middle;">
                <img src="{{ $qrSvgBase64 }}" alt="QR" style="width:100px;height:100px;">
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <p style="font-weight:700;color:#002045;">UNIMARK S.A. | Promoci&oacute;n Rifa Car</p>
        <div style="font-size:9px;color:#595f66;font-weight:600;line-height:1.4;margin:4px 0;">
            Villa Fontana, Club Terraza, 150 mts. al Oeste<br>
            Managua, Nicaragua<br>
            (+505) 2278-8787 | 8574-2828
        </div>
    </div>

    <!-- T&eacute;rminos y Condiciones -->
    <div style="border-top:1.5px solid #000;border-bottom:1.5px solid #000;padding:6px 0;margin-top:12px;">
        <p style="font-weight:700;font-size:11px;margin:0 0 4px 0;">T&Eacute;RMINOS Y CONDICIONES</p>
        <p style="font-size:10px;margin:0;line-height:1.5;">
            Promoci&oacute;n v&aacute;lida del <strong>08 de junio al 21 de noviembre de 2026</strong>. Por cada <strong>C$1,500.00 netos en compras</strong> de productos participantes, recibe <strong>una (1) acci&oacute;n electr&oacute;nica</strong>. Aplica &uacute;nicamente para clientes del canal farmacia privada con c&oacute;digo activo en UNIMARK S.A. Las acciones anuladas por devoluciones, notas de cr&eacute;dito o falta de pago no participan. El sorteo se realizar&aacute; con base en los resultados de la <strong>Loter&iacute;a Nacional del 24 de noviembre de 2026</strong>. El ganador deber&aacute; estar solvente con UNIMARK S.A. Aplican restricciones. Consulte el reglamento completo en UNIMARK S.A.
        </p>
    </div>

</body>
</html>
