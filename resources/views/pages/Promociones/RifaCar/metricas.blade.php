@extends('layouts.main')
@section('title', 'PROMOCIONES - METRICAS RIFA')
@section('name_user', 'Administrador')

@section('content')

<style>
  *, *::before, *::after { box-sizing: border-box; }

  :root {
    --bg-primary: #ffffff;
    --bg-secondary: #f5f5f4;
    --bg-info: #e6f1fb;
    --bg-success: #eaf3de;
    --text-primary: #1a1a18;
    --text-secondary: #6b6b67;
    --text-info: #185fa5;
    --text-success: #3b6d11;
    --border: rgba(0,0,0,0.12);
    --border-secondary: rgba(0,0,0,0.22);
    --border-info: #b5d4f4;
    --radius-md: 8px;
    --radius-lg: 12px;
    --font: system-ui, 'Segoe UI', sans-serif;
  }

  .card-metric {
    background: var(--bg-primary);
    border: 0.5px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    width: 100%;
  }

  .card-metric .inner { padding: 1rem 1.25rem; }

  .header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem 0.85rem;
    border-bottom: 0.5px solid var(--border);
    flex-wrap: wrap;
    gap: 8px;
  }
  .header-row h1 { font-size: 18px; font-weight: 600; color: var(--text-primary); margin:0; }
  .header-row p  { font-size: 13px; color: var(--text-secondary); margin: 2px 0 0; }

  .header-right { display: flex; align-items: center; gap: 8px; }

  .date-badge {
    font-size: 13px; color: var(--text-secondary);
    background: var(--bg-secondary);
    border: 0.5px solid var(--border);
    border-radius: var(--radius-md);
    padding: 6px 12px;
    white-space: nowrap;
  }

  .filter-btn {
    display: flex; align-items: center; gap: 5px;
    font-size: 13px; font-weight: 500;
    color: var(--text-info);
    background: var(--bg-info);
    border: 0.5px solid var(--border-info);
    border-radius: var(--radius-md);
    padding: 6px 14px;
    cursor: pointer;
  }
  .filter-btn:hover { opacity: 0.85; }

  /* ── KPI GRID ── */
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0,1fr));
    gap: 10px;
    padding: 1rem 1.25rem;
  }
  @media (max-width: 768px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  }

  .kpi {
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    padding: 0.85rem 1rem;
  }
  .kpi-label {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 6px;
  }
  .kpi-value {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.2;
  }
  .kpi-value.info    { color: var(--text-info); }
  .kpi-value.success { color: var(--text-success); }
  .kpi-value.sm      { font-size: 18px; }

  .prog-wrap { margin-top: 8px; height: 4px; background: var(--border); border-radius: 99px; overflow: hidden; }
  .prog-fill { height: 100%; border-radius: 99px; }
  .prog-fill.success { background: var(--text-success); }

  /* ── PROGRESS BANNER ── */
  .progress-banner {
    margin: 0 1.25rem 1rem;
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    padding: 0.8rem 1rem;
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .progress-banner .big { font-size: 30px; font-weight: 600; color: var(--text-primary); white-space: nowrap; }
  .progress-banner .sub { font-size: 13px; color: var(--text-secondary); margin-bottom: 6px; }
  .progress-banner .sub span { color: var(--text-info); font-weight:500; }
  .banner-bar-wrap { flex: 1; }
  .banner-bar { height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; }
  .banner-bar-fill { height: 100%; background: var(--text-info); border-radius: 99px; }

  /* ── SECTION HEADER ── */
  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 1.25rem 0.65rem;
    border-top: 0.5px solid var(--border);
    flex-wrap: wrap;
    gap: 8px;
  }
  .section-title { font-size: 15px; font-weight: 600; color: var(--text-primary); }

  .tabs { display: flex; gap: 4px; }
  .tab {
    font-size: 13px;
    padding: 6px 14px;
    border-radius: var(--radius-md);
    border: 0.5px solid var(--border);
    background: transparent;
    cursor: pointer;
    color: var(--text-secondary);
    font-family: var(--font);
    transition: background 0.12s, color 0.12s;
  }
  .tab.active {
    background: var(--bg-primary);
    border-color: var(--border-secondary);
    color: var(--text-primary);
    font-weight: 500;
  }
  .tab:hover:not(.active) { background: var(--bg-secondary); }

  /* ── TABLE ── */
  .table-wrap { padding: 0 1.25rem 1.25rem; overflow-x: auto; }

  table { width: 100%; border-collapse: collapse; font-size: 14px; }

  thead tr { border-bottom: 0.5px solid var(--border); }
  thead th {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 6px 8px;
    text-align: center;
  }

  tbody tr { border-bottom: 0.5px solid var(--border); transition: background 0.1s; cursor: pointer; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: var(--bg-secondary); }

  td { padding: 9px 8px; color: var(--text-primary); vertical-align: middle; text-align: center; }
  td.r { text-align: center; }

  .serie-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--bg-secondary); font-size: 12px;
    font-weight: 600; color: var(--text-secondary); margin-right: 6px; flex-shrink: 0;
  }
  .serie-cell { display: flex; align-items: center; }
  .serie-name { font-weight: 600; font-size:14px; }

  .cap-badge {
    font-size: 12px; color: var(--text-secondary);
    background: var(--bg-secondary); padding: 3px 10px;
    border-radius: 99px; border: 0.5px solid var(--border);
  }

  .used-num  { color: var(--text-info); font-weight: 500; }
  .free-num  { color: var(--text-success); font-weight: 500; }
  .avail-num { color: var(--text-success); font-weight: 500; }

  .row-bar { height: 5px; background: var(--border); border-radius: 99px; overflow: hidden; margin-bottom: 3px; }
  .row-bar-fill { height: 100%; background: var(--text-success); border-radius: 99px; }
  .row-bar-label { font-size: 12px; color: var(--text-secondary); }

  .action-btn {
    background: transparent; border: none; color: var(--text-secondary);
    cursor: pointer; padding: 4px 6px; border-radius: 4px; font-size: 18px; line-height: 1;
  }
  .action-btn:hover { background: var(--bg-secondary); color: var(--text-primary); }

  .pagination-wrap {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.65rem 1.25rem; border-top: 0.5px solid var(--border);
    font-size: 13px; color: var(--text-secondary);
  }
  .pagination-wrap .page-btns { display: flex; gap: 4px; }
  .pagination-wrap .page-btn {
    padding: 4px 10px; border: 0.5px solid var(--border);
    border-radius: var(--radius-md); background: transparent;
    cursor: pointer; font-size: 12px; color: var(--text-secondary);
    font-family: var(--font); transition: background 0.12s;
  }
  .pagination-wrap .page-btn:hover { background: var(--bg-secondary); }
  .pagination-wrap .page-btn.active {
    background: var(--bg-info); color: var(--text-info);
    border-color: var(--border-info); font-weight: 500;
  }
  .pagination-wrap .page-btn:disabled { opacity: 0.4; cursor: default; }

  .chart-card { padding: 1rem 1.25rem; }
  .chart-card .chart-title {
    font-size: 12px; color: var(--text-secondary); text-transform: uppercase;
    letter-spacing: 0.04em; margin-bottom: 10px;
  }

  .number-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
  }
  .num-card {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 12px 8px; border-radius: 12px; border: 1px solid #dee2e6;
    background: #fff; transition: all 0.2s; cursor: pointer; position: relative;
  }
  .num-card:hover { border-color: #39b8fd; box-shadow: 0 2px 8px rgba(57,184,253,0.15); transform: scale(0.97); }
  .num-card.used { background: #f1f3f5; border-color: #e0e0e0; opacity: 0.7; }
  .num-card .num { font-size: 15px; font-weight: 700; color: #000; margin-bottom: 6px; }
  .num-card.used .num { color: #999; }
  .num-card .icon-circle {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
    background: #e0e0e0; color: #999;
  }
  .num-card .used-badge {
    position: absolute; top: -4px; right: -4px; padding: 1px 6px;
    background: #999; color: #fff; font-size: 8px; font-weight: 700; border-radius: 6px;
  }
</style>

<div class="card-metric">

  <!-- HEADER -->
  <div class="header-row">
    <div>
      <h1>Métricas de la rifa</h1>
      <p>Seguimiento global de ventas y disponibilidad de series en tiempo real</p>
    </div>
    <div class="header-right">
      <span class="date-badge">
        <input type="text" name="dt_range" style="border:none;background:transparent;outline:none;width:160px;font-size:12px;color:inherit;cursor:pointer" readonly />
      </span>
      <button class="filter-btn" id="filtrarFechas">
        <i class="fas fa-filter"></i> Filtrar
      </button>
    </div>
  </div>

  <!-- CHART -->
  <div class="chart-card" style="position:relative">
    <div class="chart-title">Asignaciones por d&iacute;a</div>
    <div id="chart-asignaciones" style="height:200px"></div>
    <div id="chart-loader" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,0.7);border-radius:var(--radius-lg);z-index:10;display:none;align-items:center;justify-content:center;flex-direction:column;gap:8px">
      <div class="spinner-border text-info" role="status" style="width:2rem;height:2rem"></div>
      <span style="font-size:12px;color:var(--text-secondary)">Cargando...</span>
    </div>
  </div>

  <!-- KPI GRID -->
  <div class="kpi-grid">
    <div class="kpi">
      <div class="kpi-label">Total acciones</div>
      <div class="kpi-value">{{ number_format($totalAcciones) }}</div>
    </div>
    <div class="kpi">
      <div class="kpi-label">Usados</div>
      <div class="kpi-value info">{{ number_format($totalUsados) }}</div>
    </div>
    <div class="kpi">
      <div class="kpi-label">Libres</div>
      <div class="kpi-value success">{{ number_format($totalLibres) }}</div>
    </div>
    <div class="kpi">
      <div class="kpi-label">Disponible</div>
      <div class="kpi-value success">{{ number_format($porcentajeGlobal, 2) }}%</div>
      <div class="prog-wrap"><div class="prog-fill success" style="width:{{ $porcentajeGlobal }}%"></div></div>
    </div>
    <div class="kpi">
      <div class="kpi-label">Monto facturado</div>
      <div class="kpi-value sm">C$ {{ number_format($montoFacturado, 2) }}</div>
    </div>
  </div>

  <!-- PROGRESS BANNER -->
  <div class="progress-banner">
    <span class="big">{{ number_format($porcentajeUsadoGlobal, 2) }}%</span>
    <div class="banner-bar-wrap">
      <div class="sub">Progreso global de ventas — <span>{{ number_format($totalUsados) }} de {{ number_format($totalAcciones) }} usados</span></div>
      <div class="banner-bar"><div class="banner-bar-fill" style="width:{{ $porcentajeUsadoGlobal }}%"></div></div>
    </div>
  </div>

  <!-- SECTION HEADER + TABS -->
  <div class="section-header">
    <span class="section-title">Resumen detallado por serie</span>
    <div class="d-flex align-items-center gap-3">
      <div style="position:relative">
        <i class="fas fa-search" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:11px;color:var(--text-secondary);pointer-events:none"></i>
        <input type="text" id="search-tab" placeholder="Buscar..." style="font-size:12px;padding:6px 10px 6px 26px;border:0.5px solid var(--border);border-radius:var(--radius-md);background:var(--bg-primary);color:var(--text-primary);width:160px;outline:none;font-family:var(--font)" />
      </div>
      <div class="tabs" style="margin-left:12px">
        <button class="tab active" data-tab="todos">Ver todos</button>
        <button class="tab" data-tab="asignados">Asignados</button>
        <button class="tab" data-tab="disponibles">Disponibles</button>
      </div>
    </div>
  </div>

  <!-- TABLE -->
  <div class="tab-content" id="tab-todos">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Serie</th>
          <th class="r">Capacidad</th>
          <th class="r">Usados</th>
          <th class="r">Libres</th>
          <th>Estado / progreso</th>
          <th class="r">Disponible</th>
        </tr>
      </thead>
      <tbody>
        @forelse($stats as $row)
        @php
          $porcUsados = $row->ttACCIONES > 0 ? round(($row->USADOS / $row->ttACCIONES) * 100, 2) : 0;
          $porcLibres = $row->ttACCIONES > 0 ? round(($row->LIBRES / $row->ttACCIONES) * 100, 2) : 0;
        @endphp
        <tr>
          <td>
            <div class="serie-cell">
              <span class="serie-num">{{ str_pad($row->Serie, 2, '0', STR_PAD_LEFT) }}</span>
              <span class="serie-name">Serie {{ str_pad($row->Serie, 2, '0', STR_PAD_LEFT) }}</span>
            </div>
          </td>
          <td class="r"><span class="cap-badge">{{ number_format($row->ttACCIONES) }} acc.</span></td>
          <td class="r"><span class="used-num">{{ number_format($row->USADOS) }}</span></td>
          <td class="r"><span class="free-num"><b>{{ number_format($row->LIBRES) }}</b></span></td>
          <td>
            <div class="row-bar"><div class="row-bar-fill" style="width:{{ $porcUsados }}%"></div></div>
            <div class="row-bar-label" style="text-align:right">{{ number_format($porcUsados, 2) }}% usado</div>
          </td>
          <td class="r"><span class="avail-num"><b>{{ number_format($porcLibres, 2) }}%</b></span></td>
        </tr>
        @empty
        <tr><td colspan="6" class="empty-row" style="text-align:center;color:var(--text-secondary);padding:2rem 0">No hay datos disponibles</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap" id="pagination-wrap">
    <span id="page-info">Mostrando <span id="page-start">1</span>–<span id="page-end">10</span> de <span id="page-total">{{ count($stats) }}</span> series</span>
    <div class="page-btns">
      <button class="page-btn" id="prev-page" disabled>Anterior</button>
      <div id="page-numbers" class="page-btns"></div>
      <button class="page-btn" id="next-page">Siguiente</button>
    </div>
  </div>
  </div>

  <div class="tab-content d-none" id="tab-asignados">
  <div class="table-wrap">
    <div class="number-grid">
      @forelse($asignados as $a)
      <div class="num-card used"
           data-cliente="{{ $a->CLIENTE ?? 'N/D' }}"
           data-nombre="{{ $a->NOMBRE ?? 'N/D' }}"
           data-factura="{{ $a->FACTURA ?? 'N/D' }}"
           data-fecha="{{ isset($a->FECHA_ASIGNACION) ? date('d-m-Y', strtotime($a->FECHA_ASIGNACION)) : 'N/D' }}">
        <span class="num">{{ str_pad($a->NUMERO, 5, '0', STR_PAD_LEFT) }}</span>
        <div class="icon-circle"><i class="fas fa-check"></i></div>
        <span class="used-badge">USADO</span>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;color:var(--text-secondary);padding:2rem 0">No hay n&uacute;meros asignados</div>
      @endforelse
    </div>
  </div>
  </div>

  <div class="tab-content d-none" id="tab-disponibles">
  <div class="table-wrap">
    <div class="number-grid">
      @forelse($disponibles as $num)
      <div class="num-card">
        <span class="num">{{ str_pad($num, 5, '0', STR_PAD_LEFT) }}</span>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;color:var(--text-secondary);padding:2rem 0">No hay n&uacute;meros disponibles</div>
      @endforelse
    </div>
  </div>
  </div>

</div>

@endsection

@section('metodosjs')
<script>
    $('input[name="dt_range"]').daterangepicker({
        autoApply: true,
        minDate: moment(moment().year() + '-06-01', 'YYYY-MM-DD'),
        maxDate: moment(moment().year() + '-09-20', 'YYYY-MM-DD'),
        ranges: {
            'Hoy': [moment(), moment()],
            'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment()]
        },
        showCustomRangeLabel: false,
        alwaysShowCalendars: true,
        startDate: moment(moment().year() + '-06-01', 'YYYY-MM-DD'),
        endDate: moment().isAfter(moment(moment().year() + '-09-20'))
            ? moment(moment().year() + '-09-20')
            : moment(),
        locale: {
            format: 'D MMM. YYYY',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            customRangeLabel: 'Personalizado'
        }
    });

    var chartRifa = Highcharts.chart('chart-asignaciones', {
        chart: { type: 'column', height: 200, backgroundColor: 'transparent' },
        exporting: { enabled: false },
        title: { text: null },
        xAxis: {
            categories: {!! json_encode($dias) !!},
            labels: { style: { fontSize: '10px', color: '#999' } },
            lineColor: '#e0e0e0'
        },
        yAxis: {
            title: { text: null },
            gridLineColor: '#f0f0f0',
            labels: { style: { fontSize: '10px', color: '#999' } }
        },
        legend: { enabled: false },
        tooltip: {
            formatter: function() {
                var pct = {{ $totalAcciones }} > 0 ? (this.y / {{ $totalAcciones }} * 100) : 0;
                return '<b>' + numeral(this.y).format('0,0') + '</b> Acciones (<b>' + numeral(pct).format('0.00') + '</b> %)';
            }
        },
        plotOptions: {
            column: {
                borderRadius: 4,
                color: '#185fa5',
                borderWidth: 0
            }
        },
        series: [{
            name: 'Asignados',
            data: {!! json_encode($totales) !!}
        }]
    });

    function filtrarChart() {
        var picker = $('input[name="dt_range"]').data('daterangepicker');
        if (!picker) return;
        var desde = picker.startDate.format('YYYY-MM-DD');
        var hasta = picker.endDate.format('YYYY-MM-DD');
        $('#chart-loader').css('display', 'flex');
        $.get('getChartRifa', { desde: desde, hasta: hasta }, function(res) {
            chartRifa.update({
                xAxis: { categories: res.dias },
                series: [{ data: res.totales }]
            });
            $('#chart-loader').hide();
        }).fail(function() {
            $('#chart-loader').hide();
        });
    }

    $('input[name="dt_range"]').on('apply.daterangepicker', function() {
        filtrarChart();
    });

    $('#filtrarFechas').on('click', function() {
        filtrarChart();
    });

    // Tab switching
    document.querySelectorAll('.tab').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            var tab = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.add('d-none'); });
            document.getElementById('tab-' + tab).classList.remove('d-none');
        });
    });

    // Search across active tab
    document.getElementById('search-tab').addEventListener('input', function() {
        var q = this.value.toLowerCase().trim();
        var activeTab = document.querySelector('.tab.active');
        if (!activeTab) return;
        var tab = activeTab.getAttribute('data-tab');
        var container = document.getElementById('tab-' + tab);

        if (tab === 'todos') {
            var rows = container.querySelectorAll('tbody tr');
            rows.forEach(function(r) {
                var text = r.textContent.toLowerCase();
                r.style.display = text.indexOf(q) > -1 ? '' : 'none';
            });
        } else {
            var cards = container.querySelectorAll('.num-card');
            cards.forEach(function(c) {
                var text = c.textContent.toLowerCase();
                c.style.display = text.indexOf(q) > -1 ? '' : 'none';
            });
        }
    });

    // Pagination
    (function() {
        var rows = document.querySelectorAll('#tab-todos tbody tr');
        var perPage = 10;
        var total = rows.length;
        var pages = Math.ceil(total / perPage);
        var current = 1;

        function showPage(p) {
            if (p < 1 || p > pages) return;
            current = p;
            var start = (p - 1) * perPage;
            var end = Math.min(start + perPage, total);
            rows.forEach(function(r, i) {
                r.style.display = (i >= start && i < end) ? '' : 'none';
            });
            document.getElementById('page-start').textContent = total > 0 ? start + 1 : 0;
            document.getElementById('page-end').textContent = end;
            document.getElementById('prev-page').disabled = p === 1;
            document.getElementById('next-page').disabled = p === pages;
            document.querySelectorAll('#page-numbers .page-btn').forEach(function(b) {
                b.classList.toggle('active', parseInt(b.dataset.p) === p);
            });
        }

        function renderPageBtns() {
            var container = document.getElementById('page-numbers');
            container.innerHTML = '';
            for (var i = 1; i <= pages; i++) {
                var btn = document.createElement('button');
                btn.className = 'page-btn' + (i === 1 ? ' active' : '');
                btn.dataset.p = i;
                btn.textContent = i;
                btn.addEventListener('click', function() { showPage(parseInt(this.dataset.p)); });
                container.appendChild(btn);
            }
        }

        if (total > 0) {
            renderPageBtns();
            showPage(1);
        } else {
            document.getElementById('pagination-wrap').style.display = 'none';
        }

        document.getElementById('prev-page').addEventListener('click', function() { showPage(current - 1); });
        document.getElementById('next-page').addEventListener('click', function() { showPage(current + 1); });
    })();

    // SweetAlert on assigned numbers
    document.querySelector('#tab-asignados').addEventListener('click', function(e) {
        var card = e.target.closest('.num-card.used');
        if (!card) return;
        var numero = card.querySelector('.num').textContent;
        Swal.fire({
            icon: 'info',
            title: 'N\u00famero ' + numero,
            html: '<div class="text-start">' +
                '<p class="mb-2 fs-5 fw-semibold">' + card.dataset.cliente + ' - ' + card.dataset.nombre + '</p>' +
                '<hr class="my-2">' +
                '<p class="mb-1"><strong>Fac.:</strong> ' + card.dataset.factura + '</p>' +
                '<p class="mb-0"><strong>Asig.:</strong> ' + card.dataset.fecha + '</p></div>',
            confirmButtonText: 'Cerrar'
        });
    });
</script>
@endsection

@section('metodosjs')
<script>
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) {
                b.classList.remove('active');
                b.style.background = 'transparent';
                b.style.boxShadow = 'none';
                b.style.fontWeight = '500';
                b.classList.add('text-muted');
            });
            this.classList.add('active');
            this.style.background = '#fff';
            this.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
            this.style.fontWeight = '600';
            this.classList.remove('text-muted');

            var tab = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-content').forEach(function(c) {
                c.classList.add('d-none');
            });
            document.getElementById('tab-' + tab).classList.remove('d-none');
        });
    });

    var chartRifa = Highcharts.chart('chart-asignaciones', {
        chart: { type: 'column', height: 220, backgroundColor: 'transparent' },
        exporting: { enabled: false },
        title: { text: null },
        xAxis: {
            categories: {!! json_encode($dias) !!},
            labels: { style: { fontSize: '10px', color: '#999' } },
            lineColor: '#e0e0e0'
        },
        yAxis: {
            title: { text: null },
            gridLineColor: '#f0f0f0',
            labels: { style: { fontSize: '10px', color: '#999' } }
        },
        legend: { enabled: false },
        tooltip: {
            formatter: function() {
                var pct = {{ $totalAcciones }} > 0 ? (this.y / {{ $totalAcciones }} * 100) : 0;
                return '<b>' + numeral(this.y).format('0,0') + '</b> Acciones (<b>' + numeral(pct).format('0.00') + '</b> %)';
            }
        },
        plotOptions: {
            column: {
                borderRadius: 4,
                color: '#39b8fd',
                borderWidth: 0
            }
        },
        series: [{
            name: 'Asignados',
            data: {!! json_encode($totales) !!}
        }]
    });

    var yearActual = moment().year();

    $('input[name="dt_range"]').daterangepicker({
        autoApply: true,
        minDate: moment(yearActual + '-06-01', 'YYYY-MM-DD'),
        maxDate: moment(yearActual + '-09-20', 'YYYY-MM-DD'),
        ranges: {
            'Hoy': [moment(), moment()],
            '\u00daltm. 7 D\u00edas': [moment().subtract(6, 'days'), moment()],
            '\u00daltm. 30 D\u00edas': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment()]
        },
        showCustomRangeLabel: false,
        alwaysShowCalendars: true,
        startDate: moment(yearActual + '-06-01', 'YYYY-MM-DD'),
        endDate: moment().isAfter(moment(yearActual + '-09-20'))
            ? moment(yearActual + '-09-20')
            : moment(),
        opens: 'left',
        locale: {
            format: 'D MMM. YYYY',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            customRangeLabel: 'Personalizado'
        }
    });

    document.querySelector('#tab-asignados').addEventListener('click', function(e) {
        var card = e.target.closest('.num-card.used');
        if (!card) return;
        var numero = card.querySelector('.num').textContent;
        Swal.fire({
            icon: 'info',
            title: 'N\u00famero ' + numero,
            html:
                '<div class="text-start">' +
                '<p class="mb-2 fs-5 fw-semibold">' + card.dataset.cliente + ' - ' + card.dataset.nombre + '</p>' +
                '<hr class="my-2">' +
                '<p class="mb-1"><strong>Fact.:</strong> ' + card.dataset.factura + '</p>' +
                '<p class="mb-0"><strong>Asig.:</strong> ' + card.dataset.fecha + '</p>' +
                '</div>',
            confirmButtonText: 'Cerrar'
        });
    });
</script>
@endsection