<script>
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Inventario Totalizado</li>`);

    $('#dtInventarioTotal').DataTable({
    	"ajax":{
    		"url": "invTotalizado",
    		'dataSrc': '',
    	},
    	"info":    false,
    	"lengthMenu": [[10,30,50,100,-1], [20,30,50,100,"Todo"]],
    	"language": {
    	    "zeroRecords": "No hay coincidencias",
    	    "paginate": {
    	        "first":      "Primera",
    	        "last":       "Última ",
    	        "next":       "Siguiente",
    	        "previous":   "Anterior"
    	    },
    	    "lengthMenu": "MOSTRAR _MENU_",
    	    "emptyTable": "NO HAY DATOS DISPONIBLES",
    	    "search":     "BUSCAR"
    	},
    	'columns': [
    	    { "title": "ARTICULO",      "data": "ARTICULO" },
    	    { "title": "DESCRIPCION",   "data": "DESCRIPCION" },
    	    { "title": "LABORATORIO",   "data": "LABORATORIO" },
    	    { "title": "UNIDAD",        "data": "UNIDAD_MEDIDA" },
    	    { "title": "BODEGA 002 UNIMARK",        "data": "B_UMK" },
            { "title": "BODEGA INNOVA",        "data": "B_INV" }
    	],
        "columnDefs": [
            {"className": "dt-center", "targets": [ 0, 2, 3 ]},
            {"className": "dt-right", "targets": [ 4, 5 ]},
            { "width": "50%", "targets": [ 1 ] }
        ],
    });

    $("#dtInventarioTotal_length").hide();
    $("#dtInventarioTotal_filter").hide();
});

$('#InputDtShowSearchFilterInvTotal').on( 'keyup', function () {
    var table = $('#dtInventarioTotal').DataTable();
    table.search(this.value).draw();
});

$( "#InputDtShowColumnsInvTotal").change(function() {
    var table = $('#dtInventarioTotal').DataTable();
    table.page.len(this.value).draw();
});

$("#exp-to-excel").click( function() {
    location.href = "desInvTotal";
})

</script>