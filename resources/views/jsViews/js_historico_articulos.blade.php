<script>
var TYPE_STYLES = {
  'Compra':       { label: 'Compra',       cssClass: 'ha-badge-compra',        color: 'var(--ha-compra)' },
  'Venta':        { label: 'Venta',        cssClass: 'ha-badge-venta',         color: 'var(--ha-venta)' },
  'Traspaso':     { label: 'Traspaso',     cssClass: 'ha-badge-traspaso',      color: 'var(--ha-traspaso)' },
  'Consumo':      { label: 'Consumo',      cssClass: 'ha-badge-consumo',       color: 'var(--ha-consumo)' },
  'F\u00edsico':  { label: 'F\u00edsico',  cssClass: 'ha-badge-recepcion',     color: 'var(--ha-recepcion)' },
  'Costo':        { label: 'Costo',        cssClass: 'ha-badge-compra',        color: 'var(--ha-compra)' },
  'Aprobaci\u00f3n':{ label: 'Aprobaci\u00f3n',cssClass: 'ha-badge-traspaso', color: 'var(--ha-traspaso)' },
  'Ensamble':     { label: 'Ensamble',     cssClass: 'ha-badge-almacenamiento',color: 'var(--ha-recepcion)' },
  'Reservaci\u00f3n':{ label: 'Reservaci\u00f3n',cssClass: 'ha-badge-almacenamiento',color: 'var(--ha-recepcion)' },
};

var STATUS_STYLES = {
  vigente:  { label: 'Vigente',    cssClass: 'ha-badge-vigente',  color: 'var(--ha-venta)' },
  atencion: { label: 'Por vencer', cssClass: 'ha-badge-atencion', color: 'var(--ha-consumo)' },
  liquidado:{ label: 'Agotado',   cssClass: 'ha-badge-liquidado',color: 'var(--ha-liquidacion)' },
  vencido:  { label: 'Vencido',   cssClass: 'ha-badge-liquidado',color: 'var(--ha-liquidacion)' },
};

var STAGES = ['Compra','Recepci\u00f3n','Almacenamiento','Dispensaci\u00f3n','Liquidaci\u00f3n'];

var BATCHES = [];
var CURRENT_ARTICULO = '';
var ALL_TRANSACTIONS = [];
var CURRENT_FILTER = '';

function fetchLotes(articulo) {
  if (!articulo) return;
  $.get("{{ url('/getLotesHistorico') }}/" + articulo, function(data) {

    BATCHES = data.map(function(l, i) {

      var vencimiento = new Date(l.FECHA_VENCIMIENTO.split('/').reverse().join('-'));
      var hoy = new Date();
      var diasRestantes = Math.ceil((vencimiento - hoy) / (1000 * 60 * 60 * 24));
      var disponible = parseFloat(String(l.CANT_DISPONIBLE).replace(/,/g, '')) || 0;
      var ingresada = parseFloat(String(l.CANTIDAD_INGRESADA).replace(/,/g, '')) || 0;
      if (ingresada <= 0) ingresada = disponible;
      if (disponible > ingresada) ingresada = disponible;
      var status;
      if (disponible <= 0) {
        status = 'liquidado';
      } else if (l.BODEGA === '004') {
        status = 'vencido';
      } else if (diasRestantes > 0 && diasRestantes <= 60) {
        status = 'atencion';
      } else if (diasRestantes <= 0) {
        status = 'liquidado';
      } else {
        status = 'vigente';
      }
      return {
        id: l.LOTE,
        status: status,
        stage: (status === 'liquidado' || status === 'vencido') ? 5 : (disponible < ingresada ? 3 : 2),
        
        original: ingresada,
        remaining: disponible,
        
        expiry: l.FECHA_VENCIMIENTO,
        location: l.BODEGA,
        fechaIngreso: l.FECHA_INGRESO,
      };
    });
    BATCHES.sort(function(a, b) { return b.remaining - a.remaining; });
    if (BATCHES.length > 0) {
      var liquidados = BATCHES.filter(function(b){ return b.status === 'liquidado' || b.status === 'vencido'; }).length;
      var totalStock = BATCHES.reduce(function(s, b){ return s + (parseFloat(b.remaining) || 0); }, 0);

      $("#ha-lotes-liquidados").text(liquidados);
      $("#ha-total-lotes").text(BATCHES.length + ' LOTES REGISTRADOS');
      $("#ha-lotes-count").text('(' + BATCHES.length + ')');
      $("#ha-stock").text(totalStock.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}));

      selectBatch(BATCHES[0].id);


    } else {
      $("#ha-lotes-liquidados").text('0');
      $("#ha-total-lotes").text('0 LOTES REGISTRADOS');
      document.getElementById('batch-list').innerHTML = '<div class="text-muted text-center py-4">Sin lotes registrados</div>';
      document.getElementById('ledger-title').textContent = '---';
    }
  }).fail(function() {
    $("#ha-lotes-liquidados").text('0');
    $("#ha-total-lotes").text('0 LOTES REGISTRADOS');
    document.getElementById('batch-list').innerHTML = '<div class="text-muted text-center py-4">Error al cargar lotes</div>';
  });
}

function stageDots(stage, status) {
  var color = STATUS_STYLES[status].color;
  return STAGES.map(function(_, i) {
    var filled = i < stage;
    return '<span class="ha-stage-dot" style="background:' + (filled ? color : 'var(--ha-hairline)') + '"></span>';
  }).join('');
}

function renderBatchList(activeId) {
  var el = document.getElementById('batch-list');
  el.innerHTML = BATCHES.map(function(b) {
    
    var st = STATUS_STYLES[b.status];
    var isActive = b.id === activeId;
    var pct = b.original > 0 ? Math.round((b.remaining / b.original) * 100) : 0;





    return '<button class="ha-batch-card text-left w-100 rounded border p-3 mb-2 ' + (isActive ? 'active' : '') + '"' +
      ' style="border-color:' + (isActive ? 'var(--ha-compra)' : 'var(--ha-hairline)') + '"' +
      ' onclick="selectBatch(\'' + b.id + '\')"' +
      ' aria-pressed="' + isActive + '">' +
      '<div class="d-flex justify-content-between align-items-center mb-2">' +
        '<span class="font-weight-bold" style="font-size:13px">' + b.id + '</span>' +
        '<span class="ha-badge-status ' + st.cssClass + '">' + st.label + '</span>' +
      '</div>' +
      '<div class="d-flex justify-content-between align-items-center" style="font-size:12px;color:var(--ha-ink-soft)">' +
        '<span>' + b.remaining.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}) + '/' + b.original.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}) + '</span>' +
        '<span style="font-size:11px">' + (b.status === 'liquidado' || b.status === 'vencido' ? 'Venc.' : 'Venc.') + ' ' + (function(d){ if(!d) return '--'; var p=d.split('/'); return p[1]+'/'+p[2].slice(-2); })(b.expiry) + '</span>' +
      '</div>' +
      '<div class="ha-progress-bar mt-2">' +
        '<div class="ha-progress-fill" style="width:' + pct + '%;background:' + st.color + '"></div>' +
      '</div>' +
    '</button>';
  }).join('');
}

function renderLedger(id) {
  var b = BATCHES.find(function(x) { return x.id === id; });
  if (!b) return;
  var st = STATUS_STYLES[b.status];

  var elTitle = document.getElementById('ledger-title');
  var elBadge = document.getElementById('ledger-badge');
  var elMeta = document.getElementById('ledger-meta');
  var elBody = document.getElementById('ledger-body');
  if (!elBody) return;

  elTitle.textContent = 'Lote ' + b.id;
  elBadge.innerHTML = '<span class="ha-badge-status ' + st.cssClass + '">' + st.label.toUpperCase() + '</span>';

  elMeta.innerHTML =
    '<div class="col-6 col-sm-3 mb-2"><p class="mb-0 font-weight-bold" style="font-size:16px">' + b.original.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}) + ' u.</p><small style="color:var(--ha-ink-soft)">Cantidad original</small></div>' +
    '<div class="col-6 col-sm-3 mb-2"><p class="mb-0 font-weight-bold" style="font-size:16px;color:' + st.color + '">' + b.remaining.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}) + ' u.</p><small style="color:var(--ha-ink-soft)">Saldo actual</small></div>' +
    '<div class="col-6 col-sm-3 mb-2"><p class="mb-0 font-weight-bold" style="font-size:16px">' + b.expiry + '</p><small style="color:var(--ha-ink-soft)">' + (b.status === 'liquidado' || b.status === 'vencido' ? 'Fecha de baja' : 'Vencimiento') + '</small></div>' +
    '<div class="col-6 col-sm-3 mb-2"><p class="mb-0 font-weight-bold" style="font-size:16px">' + b.location + '</p><small style="color:var(--ha-ink-soft)">Ubicaci\u00f3n</small></div>';

  elBody.innerHTML = '<div class="text-muted text-center py-3">Cargando bit\u00e1cora...</div>';

  $.get("{{ url('/getTransaccionesLote') }}/" + CURRENT_ARTICULO + "/" + encodeURIComponent(id), function(data) {
    ALL_TRANSACTIONS = Array.isArray(data) ? data : [];
    CURRENT_FILTER = '';
    $('.ha-legend-item').removeClass('active');
    $('.ha-legend-item[data-tipo=""]').addClass('active');
    renderLedgerBody();
  }).fail(function() {
    if (elBody) elBody.innerHTML = '<div class="text-muted text-center py-3">Error al cargar transacciones</div>';
  });
}

function renderLedgerBody() {
  var elBody = document.getElementById('ledger-body');
  if (!elBody) return;
  elBody.innerHTML = buildLedgerBodyHtml();
}

function buildLedgerBodyHtml() {
  var filtered = CURRENT_FILTER ? ALL_TRANSACTIONS.filter(function(e) { return (e.DESCRTIPO || '').trim() === CURRENT_FILTER; }) : ALL_TRANSACTIONS;


  if (!filtered || filtered.length === 0) {
    return '<div class="text-muted text-center py-4">Sin transacciones para este filtro</div>';
  }
  return '<div class="position-relative pl-4">' +
      '<div class="position-absolute ha-spine" style="left:7px;top:4px;bottom:4px"></div>' +
      '<div class="d-flex flex-column" style="gap:20px">' +
        filtered.map(function(e, i) {
          if (!e) return '';
          var t = TYPE_STYLES[e.TIPO] || TYPE_STYLES['Compra'];
          var qty = parseFloat(String(e.CANTIDAD || 0).replace(/,/g, '')) || 0;
          var qtyLabel = (qty < 0 ? '' : '+') + qty.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2});
          var qtyColor = qty < 0 ? 'var(--ha-liquidacion)' : t.color;
          var fecha = '--';
          if (e.FECHA) {
            var raw = (typeof e.FECHA === 'object' && e.FECHA.date) ? e.FECHA.date : String(e.FECHA);
            var clean = raw.split('.')[0].split(' ')[0];
            var parts = clean.split('-');
            if (parts.length === 3) fecha = parts[2] + '/' + parts[1] + '/' + parts[0];
            else fecha = clean;
          }
          return '<div class="ha-ledger-row position-relative" style="animation-delay:' + (i * 45) + 'ms">' +
            '<span class="ha-node-dot position-absolute rounded-circle" style="left:-19px;top:4px;width:10px;height:10px;background:' + t.color + '"></span>' +
            '<div class="d-flex justify-content-between align-items-start flex-wrap">' +
              '<div>' +
                '<div class="d-flex align-items-center mb-1" style="gap:6px">' +
                  '<span class="ha-badge ' + t.cssClass + '">' + (e.DESCRTIPO || e.TIPO || t.label) + '</span>' +
                  '<small style="color:var(--ha-ink-soft);font-size:11px">' + fecha + '</small>' +
                '</div>' +
                '<small style="color:var(--ha-ink-soft);font-size:13px">' + (e.REFERENCIA || '') + (e.APLICACION ? ' \u00b7 Aplicaci\u00f3n: ' + e.APLICACION : '') + (e.CODIGO_CLIENTE ? ' \u00b7 Cliente: ' + e.CODIGO_CLIENTE : '') + (e.BONIFICADO === 'S' ? ' \u00b7 Bonificado' : '') + '</small>' +
              '</div>' +
              '<span class="font-weight-bold" style="font-size:14px;color:' + qtyColor + '">' + qtyLabel + '</span>' +
            '</div>' +
          '</div>';
        }).join('') +
      '</div>' +
    '</div>';
}

function selectBatch(id) {
  renderBatchList(id);
  renderLedger(id);
}

function fetchCosto(articulo) {
  if (!articulo) return;
  $.get("{{ url('/objCostos') }}/" + articulo, function(data) {
    if (data && data.length > 0) {
      if (data[0].COSTO_ULT_LOC) $("#ha-ultimo-costo").text(data[0].COSTO_ULT_LOC);
      if (data[0].COSTO_PROM_LOC) $("#ha-costo-promedio").text(data[0].COSTO_PROM_LOC);
    }
  });
}

function fetchProductoInfo(articulo) {
  if (!articulo) return;

  $.get("{{ url('/dtGraf') }}/" + articulo + "/Todos", function(data) {
    if (data.DESCRIPCION) {
      $("#ha-descripcion").text(data.DESCRIPCION);
    }
    if (data.CLASE_TERAPEUTICA || data.LABORATORIO || data.UNIDAD_ALMACEN) {
      var partes = [];
      if (data.CLASE_TERAPEUTICA && data.CLASE_TERAPEUTICA !== ' - ') partes.push(data.CLASE_TERAPEUTICA);
      if (data.LABORATORIO && data.LABORATORIO !== ' - ') partes.push(data.LABORATORIO);
      if (data.UNIDAD_ALMACEN && data.UNIDAD_ALMACEN !== ' - ') partes.push(data.UNIDAD_ALMACEN);
      $("#ha-presentacion").text(partes.join(' · '));
    }
    if (data.CANT_TOTAL_DISP) {
      var stockVal = parseFloat(String(data.CANT_TOTAL_DISP).replace(/,/g, '')) || 0;
      $("#ha-stock").text(stockVal.toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}));
    }
    if (data.IMAGE && data.IMAGE !== '') {
      $("#ha-product-img").attr("src", data.IMAGE).show();
      $("#ha-product-svg").hide();
    }
  }).fail(function() {
    console.log('No se pudo cargar info del producto');
  });
}

function fetchPrecios(articulo) {
  if (!articulo) return;
  $.get("{{ url('/objPrecios') }}/" + articulo, function(data) {
    if (data && data.length > 0) {
      $("#ha-precios-count").text(data.length + ' canales');
      var half = Math.ceil(data.length / 2);
      var cols = [data.slice(0, half), data.slice(half)];
      var html = '<div class="row">' +
        cols.map(function(col) {
          return '<div class="col-lg-6 ha-price-col">' +
            col.map(function(p) {
              return '<div class="ha-price-row">' +
                '<span class="ha-price-channel">' + p.NIVEL_PRECIO + '</span>' +
                '<span class="ha-price-leader"></span>' +
                '<span class="ha-price-value">C$ ' + p.PRECIO + '</span>' +
              '</div>';
            }).join('') +
          '</div>';
        }).join('') +
      '</div>';
      $("#ha-precios").html(html);
    } else {
      $("#ha-precios-count").text('');
      $("#ha-precios").html('<div class="text-muted text-center py-3">Sin precios definidos</div>');
    }
  }).fail(function() {
    $("#ha-precios").html('<div class="text-muted text-center py-3">Error al cargar precios</div>');
  });
}

function fetchBonificado(articulo) {
  if (!articulo) return;
  $.get("{{ url('/objBonificado') }}/" + articulo, function(data) {
    if (data && data.length > 0) {
      var html = data.map(function(b) {
        return '<span class="ha-badge ha-badge-venta mr-1 mb-1" style="font-size:12px;padding:4px 10px">' + b.REGLAS + '</span>';
      }).join('');
      $("#ha-bonificado").html(html || 'Sin reglas');
    } else {
      $("#ha-bonificado").text('Sin reglas definidas');
    }
  }).fail(function() {
    $("#ha-bonificado").text('Error');
  });
}

function renderEstadistica() {
  var colors = Highcharts.getOptions().colors;
  var colorsLine = ['#407EC9', '#D19000', '#00A376', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];

  // Bar chart placeholder
  if (typeof Highcharts !== 'undefined' && document.getElementById('ha-chart-barras')) {
    Highcharts.chart('ha-chart-barras', {
      chart: { type: 'column', backgroundColor: 'transparent', style: { fontFamily: 'inherit' } },
      title: { text: '' },
      xAxis: { categories: ['Ene/25','Feb/25','Mar/25','Abr/25','May/25','Jun/25','Jul/25','Ago/25','Sep/25','Oct/25','Nov/25','Dic/25'], labels: { style: { fontSize: '10px' } } },
      yAxis: { title: { text: 'C$' }, labels: { style: { fontSize: '10px' } } },
      legend: { itemStyle: { fontSize: '10px' } },
      series: [
        { name: '2024', data: [42000,38000,51000,47000,55000,62000,59000,53000,48000,61000,72000,68000], color: colors[0] },
        { name: '2025', data: [48000,44000,58000,52000,61000,70000,65000,59000,53000,68000,80000,76000], color: colors[1] },
      ],
      credits: { enabled: false },
      plotOptions: { column: { pointPadding: 0.1, groupPadding: 0.1 } }
    });
  }

  // Top 12 clientes placeholder
  var clientes = [
    { cliente: 'Farmacia San Juan',     monto: 'C$ 285,400.00' },
    { cliente: 'Hospital Central',       monto: 'C$ 241,200.00' },
    { cliente: 'Distribuidora El Sol',   monto: 'C$ 198,750.00' },
    { cliente: 'Cl\u00ednica Managua',   monto: 'C$ 175,300.00' },
    { cliente: 'Cadena Farma Express',   monto: 'C$ 162,100.00' },
    { cliente: 'Droguer\u00eda Nacional',monto: 'C$ 148,900.00' },
    { cliente: 'Farmacia Santa Luc\u00eda',monto: 'C$ 135,600.00' },
    { cliente: 'Centro M\u00e9dico Oriental',monto: 'C$ 122,450.00' },
    { cliente: 'Botica Popular',         monto: 'C$ 109,800.00' },
    { cliente: 'MediFarma S.A.',         monto: 'C$ 98,200.00' },
    { cliente: 'Salud y Vida',           monto: 'C$ 87,500.00' },
    { cliente: 'FarmaVital',             monto: 'C$ 76,300.00' },
  ];
  var html = '<table class="table table-sm table-borderless mb-0" style="font-size:12px">';
  clientes.forEach(function(c, i) {
    html += '<tr><td style="width:28px;color:var(--ha-ink-soft);font-size:11px">' + (i+1) + '</td><td>' + c.cliente + '</td><td class="text-right font-weight-bold" style="color:var(--ha-ink)">' + c.monto + '</td></tr>';
  });
  html += '</table>';
  document.getElementById('ha-top-clientes').innerHTML = html;

  // Line chart placeholder
  if (typeof Highcharts !== 'undefined' && document.getElementById('ha-chart-linea')) {
    Highcharts.chart('ha-chart-linea', {
      chart: { type: 'spline', backgroundColor: 'transparent', style: { fontFamily: 'inherit' } },
      title: { text: '' },
      xAxis: { categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'], labels: { style: { fontSize: '10px' } } },
      yAxis: { title: { text: 'Unidades' }, labels: { style: { fontSize: '10px' } } },
      legend: { enabled: false },
      series: [{
        name: '2026',
        data: [320,280,350,410,380,450,0,0,0,0,0,0],
        color: colorsLine[0],
        marker: { symbol: 'circle', radius: 4 },
        lineWidth: 2,
      }],
      credits: { enabled: false },
      plotOptions: { spline: { marker: { enabled: true } } }
    });
  }

  // Daterange picker global (afecta las 3 secciones)
  if (typeof $.fn.daterangepicker !== 'undefined') {
    $('#ha-fecha-global').daterangepicker({
      autoApply: true,
      showCustomRangeLabel: false,
      alwaysShowCalendars: true,
      opens: 'left',
      startDate: moment().startOf('year'),
      endDate: moment(),
      locale: {
        format: 'DD/MM/YYYY',
        separator: ' - ',
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'Desde',
        toLabel: 'Hasta',
        customRangeLabel: 'Personalizado',
      },
      ranges: {
        'Hoy': [moment(), moment()],
        'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
        'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
        'Este Mes': [moment().startOf('month'), moment().endOf('month')],
        'Este Año': [moment().startOf('year'), moment().endOf('year')],
      }
    });
  }
}

function renderEstadisticaPlaceholder() {
  var el = document.getElementById('ha-chart-barras');
  if (!el) return;
  el.innerHTML = '<div class="text-muted text-center py-5">Cargando gráfica...</div>';
  document.getElementById('ha-top-clientes').innerHTML = '<div class="text-muted text-center py-5">Cargando top clientes...</div>';
  var elLinea = document.getElementById('ha-chart-linea');
  if (elLinea) elLinea.innerHTML = '<div class="text-muted text-center py-5">Cargando gráfica...</div>';
}

function fetchIndicadores(articulo) {
  if (!articulo) return;
  $.get("{{ url('/objIndicadores') }}/" + articulo, function(data) {
    if (!data || !data.ANUAL) { $("#ha-indicadores").html('<div class="text-muted text-center py-3">Sin indicadores</div>'); return; }
    var M = data.MENSUAL[0];
    var A = data.ANUAL[0];
    var rows = [
      ['TOTAL. FACT.',       'C$ ' + numeral(M.data).format('0,00.00'),       'C$ ' + numeral(A.data).format('0,00.00')],
      ['UNIT. FACT.',        numeral(M.dtUnd).format('0,00.00'),                numeral(A.dtUnd).format('0,00.00')],
      ['UNIT. BONIF.',       numeral(M.dtUndBo).format('0,00.00'),              numeral(A.dtUndBo).format('0,00.00')],
      ['PREC. PROM.',        'C$ ' + M.dtAVG,                                   'C$ ' + A.dtAVG],
      ['COST. PROM. UNIT',   'C$ ' + numeral(A.dtCPM).format('0,00.00'),        'C$ ' + numeral(A.dtCPM).format('0,00.00')],
      ['CONTRIBUCION',       'C$ ' + M.dtMCO,                                   'C$ ' + A.dtMCO],
      ['% MARGEN BRUTO',     numeral(M.dtPCO).format('0,00.00') + ' %',         numeral(A.dtPCO).format('0,00.00') + ' %'],
      ['CANT. DISP. B002',   numeral(M.dtTB2).format('0,00.00'),                numeral(A.dtTB2).format('0,00.00')],
      ['CANT. DISP. UNDS. B002', numeral(M.dtTUB).format('0,00.00'),            numeral(A.dtTUB).format('0,00.00')],
      ['PROM. UNDS. MES 2022', numeral(M.dtPRO).format('0,00.00'),              numeral(A.dtPRO).format('0,00.00')],
      ['CANT. DISP. MES',    numeral(M.dtTIE).format('0,00.00'),                numeral(A.dtTIE).format('0,00.00')],
    ];
    var html = '<table class="table table-sm mb-0" style="font-size:12px">' +
      '<thead style="background:var(--ha-paper)"><tr>' +
        '<th style="border-bottom:2px solid var(--ha-hairline)">Descripción</th>' +
        '<th class="text-right" style="border-bottom:2px solid var(--ha-hairline)">Mes Actual</th>' +
        '<th class="text-right" style="border-bottom:2px solid var(--ha-hairline)">Acumulado</th>' +
      '</tr></thead><tbody>';
    rows.forEach(function(r) {
      html += '<tr><td style="color:var(--ha-ink-soft)">' + r[0] + '</td><td class="text-right font-weight-bold">' + r[1] + '</td><td class="text-right font-weight-bold">' + r[2] + '</td></tr>';
    });
    html += '</tbody></table>';
    $("#ha-indicadores").html(html);
  }).fail(function() {
    $("#ha-indicadores").html('<div class="text-muted text-center py-3">Error al cargar indicadores</div>');
  });
}

$(document).ready(function() {
  fullScreen();
  $("#item-nav-01").after('<li class="breadcrumb-item active">Historial del Art\u00edculo</li>');

  $(document).on('click', '.ha-legend-item', function() {
    $('.ha-legend-item').removeClass('active');
    $(this).addClass('active');
    CURRENT_FILTER = ($(this).data('tipo') || '').trim();
    renderLedgerBody();
  });

  $('a[data-toggle="tab"][href="#ha-pane-estadistica"]').on('shown.bs.tab', function() {
    renderEstadistica();
  });

  var urlParams = new URLSearchParams(window.location.search);
  var articulo = urlParams.get('art');
  if (articulo) {
    CURRENT_ARTICULO = articulo;
    $("#ha-sku-label").text('SKU-' + articulo + ' · Medicamento');
    fetchProductoInfo(articulo);
    fetchCosto(articulo);
    fetchPrecios(articulo);
    fetchBonificado(articulo);
    fetchIndicadores(articulo);
    fetchLotes(articulo);
  }

});
</script>
