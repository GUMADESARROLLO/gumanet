<script type="text/javascript">

// INICIALIZA LA DATATABLE CON LOS VALORES POR DEFECTO 
$("#table_inventario").DataTable({
    "destroy": true,
    "info": false,
    "bPaginate": true,
    "lengthMenu": [
        [10,20, -1],
        [10,20, "Todo"]
    ],
    "language": {
        "zeroRecords": "NO HAY COINCIDENCIAS",
        "paginate": {
            "first": "Primera",
            "last": "Última ",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "lengthMenu": "MOSTRAR _MENU_",
        "emptyTable": "-",
        "search": "BUSCAR"
    },"columnDefs": [
           { "width": "700px", "targets": [ 0 ] },
           { "width": "160px", "targets": [ 1,3 ] },
           { "width": "120px", "targets": [ 2 ] }
        ],
});

//OCULTA DE LA PANTALLA EL FILTRO DE PAGINADO Y FORM DE BUSQUEDA
$("#table_inventario_length").hide();
$("#table_inventario_filter").hide();

//HABILITA LA BUSQUEDA DENTRO DE LA TABLA
$('#id_txt_buscar').on('keyup', function() {        
    var vTablePedido = $('#table_inventario').DataTable();
    vTablePedido.search(this.value).draw();
});
</script>