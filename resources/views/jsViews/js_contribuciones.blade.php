<script type="text/javascript">
$(document).ready(function () {
    $('#id_txt_buscar').on('keyup', function() {   
        var vTableKardex = $('#table_contribucion').DataTable();     
        vTableKardex.search(this.value).draw();
    });

    $.ajax({
        url: 'canalData',
        type: 'get',
        async: true,
        success: function(response) {
            $('#table_contribucion').DataTable({
                    "data":response,
                    "destroy": true,
                    "info": true,
                    "lengthMenu": [[15,-1], [15,"Todo"]],
                    "language": {
                        "zeroRecords": "-",
                        "paginate": {
                            "first": "Primera",
                            "last": "Última ",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        },
                        "info":       "-",
                        "infoEmpty":  "",
                        "infoPostFix":    "",
                        "infoFiltered":   "",
                        "lengthMenu": "MOSTRAR _MENU_",
                        "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
                        "search": "BUSCAR"
                    },
                    'columns': [
                        {"data": "ARTICULO"},
                        {"data": "DESCRIPCION"},
                        {"data": "FABRICANTE"},
                        {"data": "FARMACIA_CANTIDAD"},
                        {"data": "FARMACIA_PROMEDIO"},
                        {"data": "FARMACIA_VENTA"},
                        {"data": "FARMACIA_COSTO"},
                        {"data": "FARMACIA_CONTRIBUCION"},
                        {"data": "FARMACIA_MARGEN"},
                        {"data": "CADENA_FARMACIA_CANTIDAD"},
                        {"data": "CADENA_FARMACIA_PROMEDIO"},
                        {"data": "CADENA_FARMACIA_VENTA"},
                        {"data": "CADENA_FARMACIA_COSTO"},
                        {"data": "CADENA_FARMACIA_CONTRIBUCION"},
                        {"data": "CADENA_FARMACIA_MARGEN"},
                        {"data": "MAYORISTA_CANTIDAD"},
                        {"data": "MAYORISTA_PROMEDIO"},
                        {"data": "MAYORISTA_VENTA"},
                        {"data": "MAYORISTA_COSTO"},
                        {"data": "MAYORISTA_CONTRIBUCION"},
                        {"data": "MAYORISTA_MARGEN"},
                        {"data": "INSTITUCION_PRIVADA_CANTIDAD"},
                        {"data": "INSTITUCION_PRIVADA_PROMEDIO"},
                        {"data": "INSTITUCION_PRIVADA_VENTA"},
                        {"data": "INSTITUCION_PRIVADA_COSTO"},
                        {"data": "INSTITUCION_PRIVADA_CONTRIBUCION"},
                        {"data": "INSTITUCION_PRIVADA_MARGEN"},
                        {"data": "CRUZ_AZUL_CANTIDAD"},
                        {"data": "CRUZ_AZUL_PROMEDIO"},
                        {"data": "CRUZ_AZUL_VENTA"},
                        {"data": "CRUZ_AZUL_COSTO"},
                        {"data": "CRUZ_AZUL_CONTRIBUCION"},
                        {"data": "CRUZ_AZUL_MARGEN"},
                        {"data": "INSTITUCION_PUBLICA_CANTIDAD"},
                        {"data": "INSTITUCION_PUBLICA_PROMEDIO"},
                        {"data": "INSTITUCION_PUBLICA_VENTA"},
                        {"data": "INSTITUCION_PUBLICA_COSTO"},
                        {"data": "INSTITUCION_PUBLICA_CONTRIBUCION"},
                        {"data": "INSTITUCION_PUBLICA_MARGEN"},            
                    ],
                    "scrollY":        "900px",
                    "scrollX":        true,
                    "scrollCollapse": true,
                    "paging":         true,
                    "fixedColumns":   {
                        "leftColumns": 3,
                    },
                    createdRow: function (row, data, index) {
                        // Obtener la referencia a la tabla DataTable
                        var table = $('#table_contribucion').DataTable();

                        // Obtener las últimas tres celdas de la fila actual
                        var lastCells = $('td', row).slice(-3);

                        // Agregar la clase CSS personalizada a esas celdas
                        lastCells.addClass('bg-soft-success');

                        // Obtener las cabeceras de las últimas tres celdas de la tabla
                        var lastHeaders = $('th', table.table().header()).slice(-3);

                        // Agregar la clase CSS personalizada a esas cabeceras
                        lastHeaders.addClass('bg-soft-success');

                        // Obtener la última cabecera de la tabla (corresponde a las tres ultimas columnas)
                        var lastHeader = $('th:last-child', '#table_contribucion');

                        // Agregar la clase CSS personalizada a esa cabecera
                        lastHeader.addClass('bg-soft-success');
                    }
            });
            $("#table_contribucion_length").hide();
            $("#table_contribucion_filter").hide();
        }

    })
   
    /*$('#table_contribucion').DataTable({
        "ajax": {
            "url": "canalData",
            "type": 'get',
            "dataSrc": function(json) {
                console.log(json);
                return json || [];
            }
        },
        "destroy": true,
        "info": true,
        "lengthMenu": [[10, -1], [10, "Todo"]],
        "language": {
            "zeroRecords": "-",
            "paginate": {
                "first": "Primera",
                "last": "Última",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "info": "-",
            "infoEmpty": "",
            "infoPostFix": "",
            "infoFiltered": "",
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
            "search": "BUSCAR"
        },
        "scrollY": "900px",
        "scrollX": true,
        "scrollCollapse": true,
        "paging": false,
        "fixedColumns": {
            "leftColumns": 3,
        },
        "columns": [
            {"data": "ARTICULO"},
            {"data": "DESCRIPCION"},
            {"data": "FABRICANTE"},
            {"data": "FARMACIA_CANTIDAD"},
            {"data": "FARMACIA_PROMEDIO"},
            {"data": "FARMACIA_VENTA"},
            {"data": "FARMACIA_COSTO"},
            {"data": "FARMACIA_CONTRIBUCION"},
            {"data": "FARMACIA_MARGEN"},
            {"data": "CADENA_FARMACIA_CANTIDAD"},
            {"data": "CADENA_FARMACIA_PROMEDIO"},
            {"data": "CADENA_FARMACIA_VENTA"},
            {"data": "CADENA_FARMACIA_COSTO"},
            {"data": "CADENA_FARMACIA_CONTRIBUCION"},
            {"data": "CADENA_FARMACIA_MARGEN"},    
        ]
    });
        
    $("#table_contribucion_length").hide();
    $("#table_contribucion_filter").hide();*/
    
    

    
    
});

</script>