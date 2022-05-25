<script>
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active"><a href="{{url('/Inventario')}}">Inventario</a></li><li class="breadcrumb-item active">Inventario completo</li>`);

    $('#dtInvCompleto').DataTable({
    	"ajax":{
    		"url": "invTotalizadoDT",
    		'dataSrc': '',
    	},
    	'info': false,
    	"lengthMenu": [[100,200,300,400,-1], [100,200,300,400,"Todo"]],
    	"language": {
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
    	    "zeroRecords": "No hay coincidencias",
            "loadingRecords": "Cargando datos...",
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
	        {"title": "BODEGA", 			"data": "BODEGA" },		        
	        {"title": "ARTICULO", 		"data": "ARTICULO" },
			{"title": "DESCRIPCIÓN", 		"data": "DESCRIPCION" },
	        {"title": "ACTIVO", "data": "ACTIVO" },
	        {"title": "LABORATORIO", "data": "LABORATORIO" },
	        {"title": "UNIT.MED.", "data": "UNIDAD_MEDIDA" },
	        {"title": "LOTE", "data": "LOTE" },
	        {"title": "CANT.DISPONIBLE", "data": "CANT_DISPONIBLE" },
	        {"title": "FCH.VENCIMIENTO", "data": "FECHA_VENCIMIENTO" },
	        {"title": "COD.BARRAS.VENT.", 	"data": "CODIGO_BARRAS_VENT" }
	    ],
	    "columnDefs": [
	        {"className": "dt-center", "targets": [ 0, 3, 4, 5, 6, 8, 9 ]},
	        {"className": "dt-right", "targets": [ 7 ]},
	        {"width":"20%","targets":[ 2 ]},
	        {"width":"5%","targets":[ 0, 1, 3, 4, 5, 6, 7, 8, 9 ]}
	    ],
    });

    $("#dtInvCompleto_length").hide();
    $("#dtInvCompleto_filter").hide();

	$('#InputDtShowSearchFilterArt').on( 'keyup', function () {
	    var table = $('#dtInvCompleto').DataTable();
	    table.search(this.value).draw();
	});

	$( "#InputDtShowColumnsArtic").change(function() {
	    var table = $('#dtInvCompleto').DataTable();
	    table.page.len(this.value).draw();
	});

	$("#exp-to-excel").click( function() {
	    location.href = "desInvTotal2";
	})
    
    
});
</script>