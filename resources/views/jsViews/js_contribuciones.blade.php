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
                        {"data": "FARMACIA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "FARMACIA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "FARMACIA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "FARMACIA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "FARMACIA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "FARMACIA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CADENA_FARMACIA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "CADENA_FARMACIA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CADENA_FARMACIA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CADENA_FARMACIA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CADENA_FARMACIA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CADENA_FARMACIA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "MAYORISTA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "MAYORISTA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "MAYORISTA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "MAYORISTA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "MAYORISTA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "MAYORISTA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PRIVADA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "INSTITUCION_PRIVADA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PRIVADA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PRIVADA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PRIVADA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PRIVADA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CRUZ_AZUL_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "CRUZ_AZUL_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CRUZ_AZUL_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CRUZ_AZUL_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CRUZ_AZUL_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "CRUZ_AZUL_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PUBLICA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "INSTITUCION_PUBLICA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PUBLICA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PUBLICA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PUBLICA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "INSTITUCION_PUBLICA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "TOTAL_VENTAS_PACK",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
                        {"data": "TOTAL_PRECIO_PROM",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "TOTAL_VENTAS_C$",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "TOTAL_COSTOS_C$",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "TOTAL_CONTRIBUCION_C$",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
                        {"data": "TOTAL_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},         
                    ],
                    "columnDefs": [                       
                        {"className": "dt-right", "targets": [ 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44 ]},
                        { "width": "150px", "targets": [ 1 ] }
                    ],
                    "scrollY":        "900px",
                    "scrollX":        true,
                    "scrollCollapse": true,
                    "paging":         true,
                    "fixedColumns":   {
                        "leftColumns": 3,
                    },
                    /*createdRow: function (row, data, index) {
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
                    }*/
            });
            $("#table_contribucion_length").hide();
            $("#table_contribucion_filter").hide();
        }

    })
    
});

</script>