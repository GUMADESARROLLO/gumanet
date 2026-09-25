<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    dtClientes();

    $('#txtSearch').on('keyup', function() {
        $('#dtClientes').DataTable().search(this.value).draw();
    });

    var minDate = moment('2020-01-01');
    var maxDate = moment();
    var startDate = moment().startOf('month');
    var endDate = moment().endOf('month');
    $('input[name="dt_range_fact"]').daterangepicker({
        autoApply: true,
        minDate: minDate,
        maxDate: maxDate,
        startDate: startDate,
        endDate: endDate,
        showCustomRangeLabel: false,
        alwaysShowCalendars: true,
        opens: 'left',
        ranges: {
            'Hoy': [moment(), moment()],
            'Esta Semana': [
                moment.max(moment().startOf('week'), minDate),
                moment.min(moment().endOf('week'), maxDate)
            ],
            '3 Meses': [moment().subtract(3, 'months'), moment()],
            '6 Meses': [moment().subtract(6, 'months'), moment()],
            'Este Mes': [
                moment.max(moment().startOf('month'), minDate),
                moment.min(moment().endOf('month'), maxDate)
            ],
            'Mes Anterior': [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')
            ],
            '1 Anio': [moment().subtract(1, 'year'), moment()],
        },
        locale: {
            format: "D MMM. YYYY",
            separator: " - ",
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "Desde",
            toLabel: "Hasta",
            customRangeLabel: "Personalizado",
            weekLabel: "S",
            daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie.", "Sab."],
            monthNames: [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ],
            firstDay: 0
        }
    });

    $('#ModalCliente').on('click', '.tab-link', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        $('#ModalCliente .tab-link').removeClass('active');
        $(this).addClass('active');
        $('#ModalCliente .tab-pane').removeClass('show active');
        $(target).addClass('show active');
    });

    $('#filtrarFacturacion').on('click', async function() {
        var cliente = $('#modal_cliente_codigo').text();
        var range = $('input[name="dt_range_fact"]').val();
        if (!range || !cliente) return;

        var parts = range.split(' - ');
        var f1 = moment(parts[0].replace('.', ''), 'D MMM YYYY').format('YYYY-MM-DD');
        var f2 = moment((parts[1] || parts[0]).replace('.', ''), 'D MMM YYYY').format('YYYY-MM-DD');

        var btn = $(this);
        btn.html('<i class="bi bi-arrow-repeat bi-spin"></i>').prop('disabled', true);

        try {
            var response = await $.ajax({
                url: 'getFactura',
                type: 'POST',
                data: { cliente: cliente, f1: f1, f2: f2 }
            });

            if ($('#dtFacturacion').hasClass('dataTable')) {
                $('#dtFacturacion').DataTable().clear().destroy();
            }

            var tbody = $('#dtFacturacion tbody');
            tbody.empty();

            var rows = response.data || response;
            var totals = response.totals || null;

            if (!rows || rows.length === 0) {
                tbody.html('<tr><td colspan="8" class="text-center text-muted">Sin resultados</td></tr>');
                $('#ind_facturas').text('0');
                $('#ind_pagos').text('C$ 0.00');
                $('#ind_saldo').text('C$ 0.00');
                btn.html('<i class="bi bi-filter"></i> Filtrar').prop('disabled', false);
                return;
            }

            if (totals) {
                $('#ind_facturas').text(numeral(totals.count).format('0,0'));
                $('#ind_pagos').text('C$ ' + totals.pagos);
                $('#ind_saldo').text('C$ ' + totals.saldo);
            }

            var data = rows.map(function(row) {
                return [
                    row.FACTURA,
                    row.FECHA,
                    row.VENDEDOR || '-',
                    row.NOMBRE_VENDEDOR || '-',
                    'C$ ' + row.MONTO,
                    'C$ ' + row.MONTO_CORD_CRED,
                    'C$ ' + row.SALDO,
                    '<button class="btn btn-sm btn-outline-primary detalle-factura" data-factura="' + row.FACTURA + '"><i class="bi bi-search"></i></button> ' +
                    '<button class="btn btn-sm btn-outline-success pagos-factura" data-factura="' + row.FACTURA + '"><i class="bi bi-cash"></i></button>'
                ];
            });

            $('#dtFacturacion').DataTable({
                data: data,
                destroy: true,
                info: false,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
                language: {
                    zeroRecords: "NO HAY COINCIDENCIAS",
                    paginate: {
                        first: "Primera",
                        last: "Ultima",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    lengthMenu: "MOSTRAR _MENU_",
                    emptyTable: "SIN DATOS DISPONIBLES",
                    search: "BUSCAR"
                },
                columnDefs: [
                    { className: "text-center", targets: [0, 1, 2, 3, 7] },
                    { className: "text-right", targets: [4, 5, 6] },
                    { orderable: false, targets: [7] }
                ]
            });
            $('#dtFacturacion_length').hide();
            $('#dtFacturacion_filter').hide();
            } catch (err) {
            $('#dtFacturacion tbody').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar datos</td></tr>');

            
        }

        btn.html('<i class="bi bi-filter"></i> Filtrar').prop('disabled', false);
    });

    $(document).on('keyup', '#txtSearchFactura', function() {
        var table = $('#dtFacturacion').DataTable();
        if (table) table.search(this.value).draw();
    });

    $('#dtFacturacion').on('click', '.detalle-factura', async function() {
        var btn = $(this);
        var row = btn.closest('tr');
        var factura = btn.data('factura');
        var nextRow = row.next('.detalle-row');

        if (nextRow.length) {
            nextRow.remove();
            btn.html('<i class="bi bi-search"></i>');
            return;
        }

        btn.html('<i class="bi bi-arrow-repeat bi-spin"></i>');

        try {
            var response = await $.ajax({
                url: 'getFacturaDetalle',
                type: 'POST',
                data: { FACTURA: factura }
            });

            var html = '<tr class="detalle-row"><td colspan="8" class="p-0">';
            html += '<div class="p-3 bg-light">';
            html += '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="bg-secondary text-light"><tr>';
            html += '<th>ARTICULO</th><th>DESCRIPCION</th><th>CANTIDAD</th><th>PRECIO UNIT.</th><th>TOTAL</th>';
            html += '</tr></thead><tbody>';

            if (!response || response.length === 0) {
                html += '<tr><td colspan="5" class="text-center text-muted">Sin lineas</td></tr>';
            } else {
                response.forEach(function(item) {
                    html += '<tr>';
                    html += '<td class="text-center">' + (item.ARTICULO || '') + '</td>';
                    html += '<td>' + (item.DESCRIPCION || '') + '</td>';
                    html += '<td class="text-right">' + (item.CANTIDAD_FACT || '0.00') + '</td>';
                    html += '<td class="text-right">C$ ' + numeral(item.PRECIO_UNITARIO).format('0,0.00') + '</td>';
                    html += '<td class="text-right">C$ ' + numeral(item.VENTA).format('0,0.00') + '</td>';
                    html += '</tr>';
                });
            }

            html += '</tbody></table></div></td></tr>';
            row.after(html);
            btn.html('<i class="bi bi-x"></i>');
        } catch (err) {
            btn.html('<i class="bi bi-search"></i>');
        }
    });

    $('#dtFacturacion').on('click', '.pagos-factura', async function() {
        var btn = $(this);
        var factura = btn.data('factura');

        if (btn.hasClass('active')) return;
        btn.addClass('active').html('<i class="bi bi-arrow-repeat bi-spin"></i>');

        try {
            var response = await $.ajax({
                url: 'getFacturaPagos',
                type: 'POST',
                data: { FACTURA: factura }
            });

            var html = '';
            if (!response || response.length === 0) {
                html = '<div class="alert alert-info mb-0">Sin pagos registrados</div>';
            } else {
                html += '<table class="table table-sm table-bordered mb-0">';
                html += '<thead class="bg-secondary text-light"><tr><th>RECIBO</th><th>FECHA</th><th>MONTO</th><th>FORMA PAGO</th></tr></thead><tbody>';
                response.forEach(function(p) {
                    html += '<tr><td class="text-center">' + (p.RECIBO || '') + '</td><td class="text-center">' + (p.FECHA || '') + '</td><td class="text-right">C$ ' + numeral(p.MONTO).format('0,0.00') + '</td><td class="text-center">' + (p.FORMA_PAGO || '-') + '</td></tr>';
                });
                html += '</tbody></table>';
            }

            Swal.fire({
                title: 'Pagos - Factura ' + factura,
                html: html,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#1B3A6B',
                width: 600
            });
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los pagos' });
        }

        btn.removeClass('active').html('<i class="bi bi-cash"></i>');
    });
});

function dtClientes() {
    var table = $('#dtClientes').DataTable({
        'ajax': {
            'url': 'getClientes',
            'dataSrc': '',
        },
        "destroy": true,
        "info": false,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
        "language": {
            "zeroRecords": "NO HAY COINCIDENCIAS",
            "paginate": {
                "first": "Primera",
                "last": "Ultima",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "SIN DATOS DISPONIBLES",
            "search": "BUSCAR"
        },
        'columns': [
            { "title": "CLIENTE",   "data": "CLIENTE",   "className": "text-center" },
            { "title": "NOMBRE",   "data": "NOMBRE",   "className": "text-left" },
            { "title": "DIRECCION","data": "DIRECCION","className": "text-left" },
            { "title": "RUC", "data": "RUC", "className": "text-center" },
            { "title": "VENDEDOR", "data": "VENDEDOR", "className": "text-center" },
            { "title": "FECHA REG.","data": "FECHA",  "className": "text-center" },
            { "title": "ACTIVO",   "data": "ACTIVO",  "className": "text-center",
              "render": function(d) { return d === 'S' ? 'Activo' : 'Inactivo'; } },
            { "title": "MOROSO",   "data": "MOROSO",  "className": "text-center",
              "render": function(d) { return d === 'S' ? 'Si' : 'No'; } },
            { "title": "CREDITO",  "data": "LIMITE",  "className": "text-right",
              "render": function(d) { return numeral(d).format('0,0.00'); } },
            { "title": "SALDO",    "data": "SALDO",   "className": "text-right",
              "render": function(d) { return numeral(d).format('0,0.00'); } },
            { "title": "DISPONIBLE","data": "DISPONIBLE","className": "text-right",
              "render": function(d) { return numeral(d).format('0,0.00'); } },
        ],
        "rowCallback": function(row, data) {
            if (data.MOROSO === 'S') {
                $(row).addClass('moroso-row');
            }
        },
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();
            $('#total_clientes_footer').text(numeral(api.rows().count()).format('0,0'));
        },
    });

    $('#dtClientes_length').hide();
    $('#dtClientes_filter').hide();

    $('#dtClientes tbody').on('click', 'tr', async function() {
        var data = table.row(this).data();
        if (!data) return;

        var clienteId = data.CLIENTE;

        $('#modal_cliente_codigo').text(clienteId);
        $('#modal_cliente_nombre').text(data.NOMBRE);
        $('#detalle_vendedor_header').text(data.VENDEDOR);

        if ($('#dtFacturacion').hasClass('dataTable')) {
            $('#dtFacturacion').DataTable().clear().destroy();
        }
        $('#dtFacturacion tbody').html('<tr><td colspan="8" class="text-center text-muted">Seleccione un rango de fechas y presione Filtrar</td></tr>');
        $('#ind_facturas').text('0');
        $('#ind_pagos').text('C$ 0.00');
        $('#ind_saldo').text('C$ 0.00');

        $('#ModalCliente').modal('show');

        try {
            var c = await $.ajax({
                url: 'getClienteById',
                type: 'POST',
                data: { cliente: clienteId }
            });

            if (!c) return;

            $('#detalle_cliente').text(c.CLIENTE);
            $('#detalle_nombre').text(c.NOMBRE);
            $('#detalle_direccion').text(c.DIRECCION);
            $('#detalle_telefono1').text(c.TELEFONO1);
            $('#detalle_telefono2').text(c.TELEFONO2);
            $('#detalle_condicion_pago').text(c.CONDICION_PAGO);
            $('#detalle_fecha_ingreso').text(c.FECHA_INGRESO);
            $('#detalle_nivel_precio').text(c.NIVEL_PRECIO);

            var badgePill = $('#detalle_estado_pill');
            var badgeDot = $('#detalle_dot');
            if (c.ACTIVO === 'S') {
                badgePill.text('CLIENTE ACTIVO');
                badgeDot.removeClass('inactive');
            } else {
                badgePill.text('CLIENTE INACTIVO');
                badgeDot.addClass('inactive');
            }

            var moroso = $('#detalle_moroso');
            if (c.MOROSO === 'S') {
                moroso.text('Si').removeClass('active warning').addClass('inactive');
            } else {
                moroso.text('No').removeClass('inactive warning').addClass('active');
            }

            $('#detalle_limite').text('C$ ' + numeral(c.LIMITE_CREDITO).format('0,0.00'));
            $('#detalle_saldo').text('C$ ' + numeral(c.SALDO).format('0,0.00'));
            $('#detalle_disponible').text('C$ ' + numeral(c.LIMITE_CREDITO - c.SALDO).format('0,0.00'));
        } catch (err) {
            console.log('Error al cargar datos del cliente');
        }
    });
}
</script>
