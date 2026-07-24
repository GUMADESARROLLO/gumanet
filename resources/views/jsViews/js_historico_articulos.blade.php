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

$(document).ready(function() {
  fullScreen();
  $("#item-nav-01").after('<li class="breadcrumb-item active">Historial del Art\u00edculo</li>');

  $(document).on('click', '.ha-legend-item', function() {
    $('.ha-legend-item').removeClass('active');
    $(this).addClass('active');
    CURRENT_FILTER = ($(this).data('tipo') || '').trim();

    console.log(CURRENT_FILTER);

    renderLedgerBody();
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
    fetchLotes(articulo);
  }

});
</script>
