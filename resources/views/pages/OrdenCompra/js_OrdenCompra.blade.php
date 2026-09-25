<script type="text/javascript">
    $(document).ready(function() {

        $('#tbl_compra_linea, #tbl_compra_monto, #tbl_embarque').DataTable({
            "destroy" : true,
            "info":    false,
            "lengthMenu": [[-1], ["Todo"]],
            "language": {
                "zeroRecords": "NO HAY COINCIDENCIAS",
                "paginate": {
                    "first":      "Primera",
                    "last":       "  ltima ",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "lengthMenu": "MOSTRAR _MENU_",
                "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
                "search":     "BUSCAR"
            }
        });

        $(".dt-length").hide();
        $(".dt-search").hide();
        $(".dt-paging").hide();


        $("#MdlEmbarque").on("click", function() {
            $("#ModalEmbarque").modal("show");
        });

        $("#MdlLiquidacion").on("click", function() {
            $("#ModalLiquidacion").modal("show");
        });


        
        
    })
</script>