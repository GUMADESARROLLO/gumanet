<!DOCTYPE html>
<html class="light" lang="es">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>UNIMARK S.A. | Reporte de Acciones y Facturas</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    @media print {
        .no-print { display: none; }
        body { background-color: white; padding: 0; }
        .document-container {
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 20px !important;
            max-width: 100% !important;
        }
    }
    .qr-wrap svg { width: 100% !important; height: auto !important; max-width: 120px; max-height: 120px; }
</style>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "on-primary-fixed": "#001b3c",
                    "tertiary": "#002715",
                    "secondary-fixed": "#dde3eb",
                    "tertiary-fixed-dim": "#83d8a6",
                    "surface-container-low": "#eff4ff",
                    "secondary-fixed-dim": "#c1c7cf",
                    "surface": "#f8f9ff",
                    "on-tertiary-fixed": "#002111",
                    "secondary-container": "#dde3eb",
                    "outline-variant": "#c4c6cf",
                    "tertiary-container": "#003f25",
                    "on-primary": "#ffffff",
                    "on-secondary-fixed-variant": "#41474e",
                    "surface-dim": "#ccdbf4",
                    "on-error": "#ffffff",
                    "primary-fixed": "#d6e3ff",
                    "inverse-primary": "#adc7f7",
                    "on-tertiary-container": "#5caf81",
                    "inverse-surface": "#223144",
                    "on-primary-fixed-variant": "#2d476f",
                    "surface-bright": "#f8f9ff",
                    "surface-variant": "#d4e4fc",
                    "tertiary-fixed": "#9ff5c1",
                    "outline": "#74777f",
                    "primary-fixed-dim": "#adc7f7",
                    "surface-container-lowest": "#ffffff",
                    "surface-container": "#e5eeff",
                    "surface-tint": "#455f88",
                    "surface-container-high": "#dce9ff",
                    "on-tertiary-fixed-variant": "#005231",
                    "on-surface": "#0d1c2e",
                    "inverse-on-surface": "#eaf1ff",
                    "on-secondary": "#ffffff",
                    "on-primary-container": "#86a0cd",
                    "primary-container": "#1a365d",
                    "on-secondary-container": "#5f656c",
                    "surface-container-highest": "#d4e4fc",
                    "error": "#ba1a1a",
                    "on-surface-variant": "#43474e",
                    "primary": "#002045",
                    "error-container": "#ffdad6",
                    "on-error-container": "#93000a",
                    "secondary": "#595f66",
                    "background": "#f8f9ff",
                    "on-secondary-fixed": "#161c22",
                    "on-tertiary": "#ffffff",
                    "on-background": "#0d1c2e"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                "spacing": {
                    "lg": "1.5rem",
                    "xl": "3rem",
                    "sm": "0.5rem",
                    "md": "1rem",
                    "xs": "0.25rem",
                    "gutter": "1.5rem",
                    "container-max": "1140px",
                    "base": "4px"
                },
                "fontFamily": {
                    "table-header": ["Work Sans"],
                    "body-base": ["Work Sans"],
                    "body-sm": ["Work Sans"],
                    "label-bold": ["Work Sans"],
                    "headline-md": ["Work Sans"],
                    "display-lg": ["Work Sans"]
                },
                "fontSize": {
                    "table-header": ["13px", {"lineHeight": "1", "fontWeight": "600"}],
                    "body-base": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "body-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "400"}],
                    "label-bold": ["12px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "headline-md": ["18px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "display-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                }
            }
        }
    }
</script>
</head>
<body class="bg-surface-variant/20 font-body-base text-on-background min-h-screen py-xl px-md">
<div id="id_document_pdf" class="max-w-[1200px] mx-auto document-container bg-surface-container-lowest border border-outline-variant shadow-xl rounded-xl p-lg md:p-xl mb-xl">

    <!-- Report Header -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-xl gap-md">
        <div>
            <h1 class="font-display-lg text-display-lg text-primary">REPORTE DE ACCIONES Y FACTURAS</h1>
            <div class="flex items-center gap-xs mt-xs">
                <span class="font-bold text-primary">UNIMARK S.A.</span>
                <span class="text-secondary">| RIFA CAR 20 ANIVERSARIO</span>
            </div>
        </div>
        <div class="text-right">
            <p class="font-label-bold text-label-bold text-on-surface-variant">Generado: {{ $fechaGeneracion }}</p>
            <p class="font-body-sm text-body-sm text-secondary">Administrador: {{ $admin }}</p>
        </div>
    </div>

    <!-- Summary Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-lg mb-xl">
        <div class="bg-white border border-outline-variant rounded-xl p-lg flex items-center gap-md shadow-sm relative overflow-hidden">
            <div class="bg-surface-container-low rounded-lg p-md">
                <span class="material-symbols-outlined text-primary text-3xl">description</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-secondary uppercase tracking-wider">Facturas</p>
                <p class="text-3xl font-bold text-primary leading-none mt-1">{{ $totalFacturas }}</p>
            </div>
            <span class="material-symbols-outlined absolute right-[-10px] bottom-[-10px] text-surface-container-low text-8xl opacity-30">description</span>
        </div>
        <div class="bg-white border border-outline-variant rounded-xl p-lg flex items-center gap-md shadow-sm relative overflow-hidden">
            <div class="bg-surface-container-low rounded-lg p-md">
                <span class="material-symbols-outlined text-tertiary-container text-3xl">database</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-secondary uppercase tracking-wider">Acciones</p>
                <p class="text-3xl font-bold text-primary leading-none mt-1">{{ $totalAcciones }}</p>
            </div>
            <span class="material-symbols-outlined absolute right-[-10px] bottom-[-10px] text-surface-container-low text-8xl opacity-30">database</span>
        </div>
        <div class="bg-white border border-outline-variant rounded-xl p-lg flex items-center gap-md shadow-sm relative overflow-hidden">
            <div class="bg-surface-container-low rounded-lg p-md">
                <span class="material-symbols-outlined text-primary text-3xl">payments</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-secondary uppercase tracking-wider">Total Comprado</p>
                <p class="text-3xl font-bold text-primary leading-none mt-1">C$ {{ number_format($totalComprado, 2) }}</p>
            </div>
            <span class="material-symbols-outlined absolute right-[-10px] bottom-[-10px] text-surface-container-low text-8xl opacity-30">payments</span>
        </div>
    </div>

    <!-- Client Header Info -->
    <div class="bg-surface-container-low border border-outline-variant p-md rounded-xl mb-md flex flex-wrap gap-xl items-center">
        <div class="flex items-center gap-md">
            <div class="bg-primary text-on-primary w-12 h-12 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">person</span>
            </div>
            <div>
                <span class="text-xs font-bold text-secondary uppercase block leading-none mb-1">Nombre Completo</span>
                <span class="font-bold text-primary text-lg">{{ $nombre }}</span>
            </div>
        </div>
        <div class="border-l border-outline-variant pl-xl">
            <span class="text-xs font-bold text-secondary uppercase block leading-none mb-1">Código de Cliente</span>
            <span class="font-bold text-primary">{{ $cliente }}</span>
        </div>
    </div>

    <!-- Invoices Detail Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden mb-xl">
        <div class="bg-primary text-on-primary px-lg py-sm flex justify-between items-center">
            <div>
                <h2 class="text-[14px] font-bold uppercase flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">list_alt</span>
                    Facturas y Acciones
                </h2>
                <p class="text-[10px] text-on-primary/70 -mt-0.5 ml-6">Desglose detallado de transacciones vinculadas</p>
            </div>
            <span class="bg-on-primary text-primary w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold">{{ $totalFacturas }}</span>
        </div>
        <div class="p-lg space-y-md">
            @foreach($facturas as $fac)
            <div class="border-l-4 border-primary bg-white rounded-lg border border-outline-variant shadow-sm p-md flex flex-col md:flex-row gap-lg">
                <div class="w-full md:w-1/4">
                    <div class="flex items-center gap-2 text-primary font-bold mb-1">
                        <span class="material-symbols-outlined text-sm">receipt_long</span>
                        {{ $fac['FACTURA'] }}
                    </div>
                    <div class="flex items-center gap-2 text-secondary text-xs mb-1">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        {{ isset($fac['FECHA']) ? date('d/m/Y', strtotime($fac['FECHA'])) : '—' }}
                    </div>
                    <div class="text-tertiary-container font-bold text-sm">
                        C$ {{ number_format($fac['TOTAL_FACTURA'], 2) }}
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-sm">
                        <span class="text-[10px] font-bold text-secondary uppercase flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">local_activity</span>
                            Acciones
                        </span>
                        <span class="bg-on-tertiary-container text-tertiary-container px-2 py-0.5 rounded-full text-[10px] font-bold">{{ count($fac['ACCIONES']) }}</span>
                    </div>
                    <div class="flex flex-wrap gap-xs">
                        @foreach($fac['ACCIONES'] as $num)
                        <span class="px-3 py-1 bg-surface-container-low border border-outline-variant text-primary text-[11px] font-bold rounded">{{ $num }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Summary Totals Footer Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-xl">
        <div class="bg-surface-container-high p-lg rounded-xl text-center border border-outline-variant flex flex-col items-center justify-center">
            <p class="text-xs font-bold text-secondary uppercase mb-1">Monto Total Facturado</p>
            <p class="text-3xl font-bold text-primary">C$ {{ number_format($totalComprado, 2) }}</p>
        </div>
        <div class="bg-primary p-lg rounded-xl text-center shadow-md flex flex-col items-center justify-center">
            <p class="text-xs font-bold text-on-primary-container uppercase mb-1">Acciones Totales Vinculadas</p>
            <p class="text-3xl font-bold text-on-primary">{{ $totalAcciones }}</p>
        </div>
        <div class="bg-white p-lg rounded-xl text-center border border-outline-variant flex items-center justify-center">
            <div class="qr-wrap" style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;">{!! $qrSvg !!}</div>
        </div>
    </div>

    <!-- Document Footer -->
    <div class="mt-xl pt-lg border-t border-outline-variant text-center">
        <p class="font-label-bold text-primary">UNIMARK S.A. | RIFA CAR 20 ANIVERSARIO</p>
        <p class="text-xs text-secondary font-semibold leading-relaxed mt-2">
            Villa Fontana, Club Terraza, 150 mts. al Oeste
            Managua, Nicaragua<br>
            (+505) 2278-8787 | 8574-2828
            <br>

            Acciones acumuladas sujetas a validación. Las acciones mostradas en este estado de cuenta podrán ser ajustadas por devoluciones, notas de crédito, anulaciones de facturas o saldos vencidos. La cantidad definitiva de acciones válidas será determinada por UNIMARK S.A. conforme al reglamento oficial de la promoción "CON UNIMARK TE VAS MONTADO".
            &copy; {{ date('Y') }} UNIMARK S.A. Todos los derechos reservados.
        </p>
    </div>
</div>

<div class="fixed bottom-8 right-8 no-print">
    <a href="{{ route('ReporteClientesRifaPDF', $cliente) }}"
       class="bg-tertiary-container text-on-tertiary-container h-14 w-14 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform"
       title="Descargar PDF">
        <span class="material-symbols-outlined">picture_as_pdf</span>
    </a>
</div>

</body>
</html>
