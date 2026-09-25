<script>
    $(document).ready(function() {
      //inicializaControlFecha();
    fullScreen();

    window.DT_LANG_ES = {
        emptyTable: 'No hay datos disponibles en la tabla',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoEmpty: 'Mostrando 0 a 0 de 0 registros',
        infoFiltered: '(filtrado de _MAX_ registros totales)',
        infoThousands: ',',
        lengthMenu: 'Mostrar _MENU_ registros',
        loadingRecords: 'Cargando...',
        processing: 'Procesando...',
        search: 'Buscar:',
        zeroRecords: 'No se encontraron registros coincidentes',
        thousands: ',',
        paginate: {
            first: 'Primero',
            last: 'Último',
            next: 'Siguiente',
            previous: 'Anterior'
        },
        aria: {
            sortAscending: ': Activar para ordenar la columna de manera ascendente',
            sortDescending: ': Activar para ordenar la columna de manera descendente'
        }
    };

    $('input[name="dt_range"]').daterangepicker({
        "autoApply": true,
        ranges: {
            'Hoy': [moment(), moment()],
            'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
            
            'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
            'Semana Anterior': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
            
            'Este Mes': [moment().startOf('month'), moment()],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            
            //'1 Año': [moment().subtract(1, 'year'), moment()],
            // '2 Años': [moment().subtract(2, 'year'), moment()],
            // '3 Años': [moment().subtract(3, 'year'), moment()]
        },
        "showCustomRangeLabel": false,
        "alwaysShowCalendars": true,
        "startDate": moment().startOf('month').format('D MMM. YYYY'),
        "endDate": moment().format('D MMM. YYYY'),
        opens: 'left',
        locale: {
            //format: "DD/MM/YYYY",
            format: "D MMM. YYYY",   // Ejemplo: 1 ago. 2025
            separator: " - ",
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "Desde",
            toLabel: "Hasta",
            customRangeLabel: "Personalizado",
            weekLabel: "S",
            daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
            monthNames: [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ],
            firstDay: 1
        }
    }, function(start, end, label) {
        //console.log('Nuevo rango seleccionado: ' + start.format('YYYY-MM-DD') + ' a ' + end.format('YYYY-MM-DD') + ' (rango: ' + label + ')');
        Filter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });


    var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');



    $('#filtrarFechas').on('click', function() {
        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

        Filter( desde, hasta );        
    });
    


    $("#id_search_importaciones").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_topsku_clientes').DataTable().search(searchTerm).draw();
    });

    Filter( desde, hasta );  


    });


    function Filter( desde = null, hasta = null ) {

        $('#tl_periodo').html(`<b>${moment(desde).format('D MMM. YYYY')}</b> al <b>${moment(hasta).format('D MMM. YYYY')}</b>`);
        
        GetData(desde, hasta);

    }

    function abrirModalFacturasVendedor(ruta, nombre) {

        var table = $('#tbl-facturas-vendedor');
        if ($.fn.DataTable.isDataTable('#tbl-facturas-vendedor')) {
            table.DataTable().destroy();
        }
        table.empty();

        table.DataTable({
            data: [],
            columns: [
                { title: '', data: 'DETALLE', className: 'text-center bg-white text-dark', orderable: false },
                { title: 'FACTURA', data: 'FACTURA', className: 'text-center bg-white text-dark' },
                { title: 'FECHA', data: 'FECHA', className: 'text-center', render: function(data) {
                    if (!data) return '';
                    var d = new Date(data);
                    var day = ('0' + d.getDate()).slice(-2);
                    var month = ('0' + (d.getMonth() + 1)).slice(-2);
                    var year = d.getFullYear();
                    return day + '-' + month + '-' + year;
                } },
                { title: 'COD. CLIENTE', data: 'COD_CLIENTE', className: 'text-center' },
                { title: 'NOMBRE CLIENTE', data: 'NOMBRE_CLIENTE' },
                { title: 'TOTAL', data: 'TOTAL_FACTURA', className: 'text-right', render: function(data) {
                    return 'C$ ' + Number(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                } }
            ],
            searching: true,
            lengthChange: false,
            pageLength: 10,
            dom: 'rtip',
            language: $.extend(true, {}, DT_LANG_ES, { zeroRecords: 'Cargando...' }),
            destroy: true,
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var total = api.column(5).data().reduce(function(sum, val) {
                    return sum + parseFloat(val || 0);
                }, 0);
                $(api.column(5).footer()).html('C$ ' + fmtNum(total));
            }
        });

        $('#txt_busqueda_facturas_vendedor').on('keyup', function() {
            $('#tbl-facturas-vendedor').DataTable().search(this.value).draw();
        });

        $('#mdl-facturas-vendedor').modal('show');

        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

        fetch('getFacturasVendedor', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                desde: desde,
                hasta: hasta,
                vendedor: ruta
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            var rows = data.map(function(row) {
                return {
                    DETALLE: '<a href="javascript:void(0)" class="exp-factura" data-factura="' + row.FACTURA + '"><i class="material-icons" style="background:#e2e2e2;color:#007bff;border-radius:50%;padding:4px;font-size:20px;cursor:pointer">expand_more</i></a>',
                    FACTURA: row.FACTURA,
                    FECHA: row.FECHA,
                    COD_CLIENTE: row.COD_CLIENTE,
                    NOMBRE_CLIENTE: row.NOMBRE_CLIENTE,
                    TOTAL_FACTURA: row.TOTAL_FACTURA
                };
            });
            var dt = $('#tbl-facturas-vendedor').DataTable();
            dt.clear();
            dt.rows.add(rows);
            dt.draw();
        })
        .catch(function(error) {
            console.error('Error:', error);
        });
    }

    // Click en RUTA para abrir modal de facturas por vendedor
    $(document).on('click', '#tbl_vendedores .link-ruta', function() {
        var ruta = $(this).data('ruta');
        var nombre = $(this).data('nombre');
        abrirModalFacturasVendedor(ruta, nombre);
    });

    function fmtNum(n) {
        return Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function esBonificado(d) {
        return parseFloat(d) === 0;
    }

    function renderPrecioUnitario(d) {
        return esBonificado(d) ? '<span class="badge-bonificado">BONIFICADO</span>' : 'C$ ' + fmtNum(d);
    }

    function fechaDDMMYYYY(data) {
        if (!data) return '';
        var d = new Date(data);
        return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
    }

    function renderPillTiempo(data) {
        if (data === null || data === undefined) return '';
        var horas = Math.floor(data / 60);
        var minutos = data % 60;
        var texto = ('0' + horas).slice(-2) + ':' + ('0' + minutos).slice(-2);
        var clase = 'tiempo-verde';
        if (data > 1440) clase = 'tiempo-rojo';
        else if (data > 720) clase = 'tiempo-ambar';
        return '<span class="tiempo-pill ' + clase + '">' + texto + '</span>';
    }

    function renderPaginacion(dt, $nav, $info, $count) {
        var info = dt.page.info();
        var desde = info.recordsDisplay === 0 ? 0 : info.start + 1;
        $info.text('Mostrando ' + desde + ' a ' + info.end + ' de ' + info.recordsDisplay + ' registros');
        if ($count && $count.length) $count.text(info.recordsDisplay + ' registros');

        var pages = info.pages;
        var cur = info.page;
        var html = '<a href="#" class="pagina-nav' + (cur === 0 || pages === 0 ? ' disabled' : '') + '" data-page="' + (cur - 1) + '">Anterior</a>';

        if (pages > 0) {
            var inicio = Math.max(0, cur - 2);
            var fin = Math.min(pages - 1, cur + 2);
            if (inicio > 0) {
                html += '<a href="#" class="pagina" data-page="0">1</a>';
                if (inicio > 1) html += '<span class="paginas-ellipsis">…</span>';
            }
            for (var p = inicio; p <= fin; p++) {
                html += '<a href="#" class="pagina' + (p === cur ? ' activa' : '') + '" data-page="' + p + '">' + (p + 1) + '</a>';
            }
            if (fin < pages - 1) {
                if (fin < pages - 2) html += '<span class="paginas-ellipsis">…</span>';
                html += '<a href="#" class="pagina" data-page="' + (pages - 1) + '">' + pages + '</a>';
            }
        }

        html += '<a href="#" class="pagina-nav' + (cur === pages - 1 || pages === 0 ? ' disabled' : '') + '" data-page="' + (cur + 1) + '">Siguiente</a>';
        $nav.html(html);
    }

    function onTablaDraw(settings) {
        var dt = this.api();
        var $panel = $(dt.table().node()).closest('.panel-fact');
        renderPaginacion(
            dt,
            $panel.find('.paginacion-custom'),
            $panel.find('.panel-footer > span').first(),
            $panel.find('.panel-header .count')
        );
    }

    $(document).on('click', '.panel-fact .paginacion-custom .pagina, .panel-fact .paginacion-custom .pagina-nav', function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;
        var page = parseInt($(this).data('page'), 10);
        var dt = $(this).closest('.panel-fact').find('table.dataTable').DataTable();
        dt.page(page).draw('page');
    });

    function abrirDetalleProductos(tipo, valor, row) {
        var titulo = (tipo === 'pedido') ? 'PEDIDO: ' + valor : 'FACTURA: ' + valor;
        var endpoint = (tipo === 'pedido') ? 'getDetallePedidoProductos' : 'getDetalleFacturaProductos';
        var columnas = (tipo === 'pedido')
            ? [
                { title: 'ARTICULO', data: 'ARTICULO', className: 'text-center bg-white text-dark' },
                { title: 'DESCRIPCION', data: 'DESCRIPCION' },
                { title: 'CANTIDAD', data: 'CANTIDAD', className: 'text-right', render: function(d) { return fmtNum(d); } },
                { title: 'PRECIO UNIT.', data: 'PRECIO_UNITARIO', className: 'text-right', render: renderPrecioUnitario },
                { title: 'TOTAL', data: 'PRECIO_TOTAL', className: 'text-right', render: function(d) { return (d === null || d === undefined) ? 'C$ 0.00' : 'C$ ' + fmtNum(d); } }
            ]
            : [
                { title: 'ARTICULO', data: 'ARTICULO', className: 'text-center bg-white text-dark' },
                { title: 'DESCRIPCION', data: 'DESCRIPCION' },
                { title: 'CANTIDAD', data: 'CANTIDAD', className: 'text-right', render: function(d) { return fmtNum(d); } },
                { title: 'PRECIO UNIT.', data: 'PRECIO_UNITARIO', className: 'text-right', render: renderPrecioUnitario },
                { title: 'TOTAL', data: 'PRECIO_TOTAL', className: 'text-right', render: function(d) { return (d === null || d === undefined) ? 'C$ 0.00' : 'C$ ' + fmtNum(d); } }
            ];

        $('#mdl-detalle-pedido-factura-title').html(titulo);
        $('#detalle_tipo').text(tipo === 'factura' ? 'Factura' : 'Pedido');

        // info-grid con datos de la fila
        $('#detalle_nombre').text(row.COD_CLIENTE + ' - ' + row.NOMBRE_CLIENTE);

        if (row.FECHAC_PEDIDO) {
            var dp = new Date(row.FECHAC_PEDIDO);
            $('#fecha_pedidio').text(('0' + dp.getDate()).slice(-2) + '-' + ('0' + (dp.getMonth() + 1)).slice(-2) + '-' + dp.getFullYear() + ' ' + ('0' + dp.getHours()).slice(-2) + ':' + ('0' + dp.getMinutes()).slice(-2));
        }
        if (row.FECHA_FACTURA) {
            var df = new Date(row.FECHA_FACTURA);
            $('#fecha_factura').text(('0' + df.getDate()).slice(-2) + '-' + ('0' + (df.getMonth() + 1)).slice(-2) + '-' + df.getFullYear() + ' ' + ('0' + df.getHours()).slice(-2) + ':' + ('0' + df.getMinutes()).slice(-2));
        }
        if (row.TIEMPO_MINUTOS !== null && row.TIEMPO_MINUTOS !== undefined) {
            var mins = row.TIEMPO_MINUTOS;
            var horas = Math.floor(mins / 60);
            var minutos = mins % 60;
            var label = ('0' + horas).slice(-2) + 'h ' + ('0' + minutos).slice(-2) + 'm';
            var pillClass = 'active';
            if (mins > 1440) pillClass = 'inactive';
            else if (mins > 720) pillClass = 'warning';
            $('#detalle_tiempo').html('<i class="bi bi-clock"></i> ' + label).removeClass('active inactive warning').addClass(pillClass);
        } else {
            $('#detalle_tiempo').html('<i class="bi bi-check-circle-fill"></i> Al dia');
        }

        $('#mdl-detalle-pedido-factura').modal('show');

        var table = $('#tbl-detalle-pedido-factura');
        if ($.fn.DataTable.isDataTable('#tbl-detalle-pedido-factura')) {
            var dt = table.DataTable();
            dt.clear();
            dt.rows.add([]);
            dt.draw();
            dt.destroy();
        }

        table.DataTable({
            data: [],
            columns: columnas,
            searching: true,
            lengthChange: false,
            pageLength: 10,
            dom: 'rt',
            language: $.extend(true, {}, DT_LANG_ES, { zeroRecords: 'Cargando...' }),
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var total = api.column(4).data().reduce(function(sum, val) {
                    return sum + parseFloat(val || 0);
                }, 0);
                $('#detalle_total').text('C$ ' + fmtNum(total));
                $(api.column(4).footer()).html('C$ ' + fmtNum(total));
            },
            drawCallback: function(settings) {
                var info = this.api().page.info();
                $('#detalle_pagina_actual').text(info.page + 1);
                var desde = info.recordsDisplay === 0 ? 0 : info.start + 1;
                $('#detalle_info_pagina').text('Mostrando ' + desde + ' a ' + info.end + ' de ' + info.recordsDisplay + ' registros');
            }
        });

        $('#btn_detalle_anterior').off('click').on('click', function(e) {
            e.preventDefault();
            $('#tbl-detalle-pedido-factura').DataTable().page('previous').draw('page');
        });
        $('#btn_detalle_siguiente').off('click').on('click', function(e) {
            e.preventDefault();
            $('#tbl-detalle-pedido-factura').DataTable().page('next').draw('page');
        });

        $('#txt_busqueda_detalle_pedido').on('keyup', function() {
            $('#tbl-detalle-pedido-factura').DataTable().search(this.value).draw();
        });

        var bodyData = {};
        bodyData[tipo === 'pedido' ? 'pedido' : 'factura'] = valor;

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(bodyData)
        })
        .then(function(response) {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(function(data) {
            var dt = $('#tbl-detalle-pedido-factura').DataTable();
            dt.clear();
            dt.rows.add(data);
            dt.draw();
        })
        .catch(function(error) {
            console.error('Error:', error);
            var dt = $('#tbl-detalle-pedido-factura').DataTable();
            dt.clear();
            dt.draw();
        });
    }

    // Click en PEDIDO
    $(document).on('click', '#tbl_pedidos_facturados .link-pedido', function() {
        var tr = $(this).closest('tr');
        var row = $('#tbl_pedidos_facturados').DataTable().row(tr).data();
        var pedido = row.PEDIDO;
        abrirDetalleProductos('pedido', pedido, row);
    });

    // Click en FACTURA
    $(document).on('click', '#tbl_pedidos_facturados .link-factura', function() {
        var tr = $(this).closest('tr');
        var row = $('#tbl_pedidos_facturados').DataTable().row(tr).data();
        var factura = row.FACTURA;
        abrirDetalleProductos('factura', factura, row);
    });

    // Expandir/colapsar detalle de factura
    $(document).on('click', '.exp-factura', function() {
        var table = $('#tbl-facturas-vendedor').DataTable();
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var factura = $(this).data('factura');
        var $icon = $(this).find('.material-icons');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            $icon.text('expand_more').css({ background: '#e2e2e2', color: '#007bff' });
        } else {
            table.rows().every(function() {
                var r = this;
                if (r.child.isShown()) {
                    r.child.hide();
                    $(r.node()).find('.exp-factura .material-icons').text('expand_more').css({ background: '#e2e2e2', color: '#007bff' });
                }
            });

            var thead = '<table class="table table-striped table-bordered table-sm" width="100%">' +
                '<thead><tr>' +
                '<th class="text-center">ARTICULO</th>' +
                '<th class="text-center">DESCRIPCION</th>' +
                '<th class="text-center">CANTIDAD</th>' +
                '<th class="text-center">PRECIO UNITARIO</th>' +
                '<th class="text-center">PRECIO TOTAL</th>' +
                '</tr></thead><tbody>';

            $.ajax({
                type: "POST",
                url: "getDetFactVenta",
                data: { factura: factura },
                success: function(resp) {
                    var tbody = '';
                    if (resp.objDt && resp.objDt.length > 0) {
                        resp.objDt.forEach(function(item) {
                            tbody += '<tr>' +
                                '<td class="text-center">' + item.ARTICULO + '</td>' +
                                '<td>' + item.DESCRIPCION + '</td>' +
                                '<td class="text-right">' + numeral(item.CANTIDAD).format('0,0.00') + '</td>' +
                                '<td class="text-right">C$ ' + numeral(item.PRECIO_UNITARIO).format('0,0.00') + '</td>' +
                                '<td class="text-right">C$ ' + numeral(item.PRECIO_TOTAL).format('0,0.00') + '</td>' +
                                '</tr>';
                        });
                    } else {
                        tbody = '<tr><td colspan="5" class="text-center">Sin detalle</td></tr>';
                    }
                    tbody += '</tbody></table>';
                    row.child(thead + tbody).show();
                    tr.addClass('shown');
                    $icon.text('expand_less').css({ background: '#ff5252', color: '#e2e2e2' });
                },
                error: function() {
                    var tbody = '<tr><td colspan="5" class="text-center text-danger">Error al cargar detalle</td></tr></tbody></table>';
                    row.child(thead + tbody).show();
                    tr.addClass('shown');
                    $icon.text('expand_less').css({ background: '#ff5252', color: '#e2e2e2' });
                }
            });
        }
    });

    function eneableButton(EnableButton, textButton = '<i class="fas fa-filter"></i> Filtrar') {
    $('#filtrarFechas').prop('disabled', EnableButton);
    $('#filtrarFechas').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
  }


    async function GetData(desde, hasta){
        try {
            eneableButton(true,'Calc...') ;
            
            const response = await fetch('getDataFacturacion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    desde: desde, 
                    hasta: hasta,

                })
            });

            const result = await response.json();

            renderClienteBolsonChart(result.FACTURACION);

            let datos = [];
            let totales = [];

            //Declarar la variable como global
            window.datos = datos;
            window.totales = totales;

            // Tabla de Vendedores
            if ($.fn.DataTable.isDataTable('#tbl_vendedores')) {
                $('#tbl_vendedores').DataTable().destroy();
            }
            $('#tbl_vendedores').DataTable({
                data: result.VENDEDORES,
                columns: [
                { title: 'RUTA', data: 'RUTA', className: 'link-cell', render: function(data, type, row) {
                    return '<a href="javascript:void(0)" class="link-ruta" data-ruta="' + data + '" data-nombre="' + row.NOMBRE + '">' + data + '</a>';
                }, createdCell: function(td) { $(td).attr('data-label', 'Ruta'); } },
                    { title: 'VENDEDOR', data: 'NOMBRE', createdCell: function(td) { $(td).attr('data-label', 'Vendedor'); } },
                    { title: 'CANT. PEDIDOS', data: 'CANTIDAD_PEDIDOS', className: 'num-cell', render: function(d) {
                        return '<span class="cant-pill">' + d + '</span>';
                    }, createdCell: function(td) { $(td).attr('data-label', 'Cant. pedidos'); } }
                ],
                searching: true,
                lengthChange: false,
                pageLength: 10,
                dom: 'rt',
                language: $.extend(true, {}, DT_LANG_ES),
                drawCallback: onTablaDraw
            });

            $('#txt_busqueda_vendedores').on('keyup', function() {
                $('#tbl_vendedores').DataTable().search(this.value).draw();
            });

            // Tabla de Pedidos Facturados
            if ($.fn.DataTable.isDataTable('#tbl_pedidos_facturados')) {
                $('#tbl_pedidos_facturados').DataTable().destroy();
            }
            $('#tbl_pedidos_facturados').DataTable({
                data: result.PEDIDOS_FACTURADOS,
                columns: [
                    { title: 'PEDIDO', data: 'PEDIDO', className: 'link-cell', render: function(data, type, row) {
                        return '<a href="javascript:void(0)" class="link-pedido" data-pedido="' + data + '">' + data + '</a>';
                    }, createdCell: function(td) { $(td).attr('data-label', 'Pedido'); } },
                    { title: 'FACTURA', data: 'FACTURA', className: 'link-cell', render: function(data, type, row) {
                        return '<a href="javascript:void(0)" class="link-factura" data-factura="' + data + '">' + data + '</a>';
                    }, createdCell: function(td) { $(td).attr('data-label', 'Factura'); } },
                    { title: 'CLIENTE', data: 'NOMBRE_CLIENTE', render: function(data, type, row) {
                        if (type === 'display') {
                            return '<span class="cliente-nombre">' + row.NOMBRE_CLIENTE + '</span><span class="cliente-ruc">' + row.COD_CLIENTE + '</span>';
                        }
                        return data;
                    }, createdCell: function(td) { $(td).attr('data-label', 'Cliente'); } },
                    { title: 'FECHA PEDIDO', data: 'FECHAC_PEDIDO', render: fechaDDMMYYYY, createdCell: function(td) { $(td).attr('data-label', 'Fecha pedido'); } },
                    { title: 'FECHA FACTURA', data: 'FECHA_FACTURA', render: fechaDDMMYYYY, createdCell: function(td) { $(td).attr('data-label', 'Fecha factura'); } },
                    { title: 'TIEMPO', data: 'TIEMPO_MINUTOS', className: 'num-cell', render: renderPillTiempo, createdCell: function(td) { $(td).attr('data-label', 'Tiempo'); } },
                    { title: 'TOTAL C$', data: 'TOTAL_FACTURA', className: 'num-cell', render: function(data) {
                        return 'C$ ' + Number(data === null || data === undefined ? 0 : data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }, createdCell: function(td) { $(td).attr('data-label', 'Total'); } }
                ],
                searching: true,
                lengthChange: false,
                pageLength: 10,
                dom: 'rt',
                language: $.extend(true, {}, DT_LANG_ES),
                drawCallback: onTablaDraw
            });

            $('#txt_busqueda_pedidos_facturados').on('keyup', function() {
                $('#tbl_pedidos_facturados').DataTable().search(this.value).draw();
            });

            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }

</script>