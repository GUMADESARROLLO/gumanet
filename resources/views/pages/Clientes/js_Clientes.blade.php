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
    $('input[name="dt_range_fact"], input[name="dt_range_cartera"]').daterangepicker({
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
            'Ultm. 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Ultm. 30 Dias': [moment().subtract(29, 'days'), moment()],
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
              "render": function(d) { return 'C$ ' + numeral(d).format('0,0.00'); } },
            { "title": "SALDO",    "data": "SALDO",   "className": "text-right",
              "render": function(d) { return 'C$ ' + numeral(d).format('0,0.00'); } },
            { "title": "DISPONIBLE","data": "DISPONIBLE","className": "text-right",
              "render": function(d) { return 'C$ ' + numeral(d).format('0,0.00'); } },
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

    $('#dtClientes tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        if (!data) return;

        $('#modal_cliente_codigo').text(data.CLIENTE);
        $('#modal_cliente_nombre').text(data.NOMBRE);
        $('#detalle_cliente').text(data.CLIENTE);
        $('#detalle_nombre').text(data.NOMBRE);
        $('#detalle_direccion').text(data.DIRECCION);
        $('#detalle_ruc').text(data.RUC);
        $('#detalle_vendedor').text(data.VENDEDOR);
        $('#detalle_vendedor_header').text(data.VENDEDOR);
        $('#detalle_fecha').text(data.FECHA);

        $('#ModalCliente').modal('show');
    });
}
</script>
