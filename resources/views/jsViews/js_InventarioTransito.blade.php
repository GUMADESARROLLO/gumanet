<script>
$(document).ready(function() {
    fullScreen();
    inicializaControlFecha();
    var articulo_g = 0;
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active"><a href="{{url('../Inventario')}}">Inventario</a></li><li class="breadcrumb-item active">Inventario completo</li>`);

    $('#dtInvCompleto').DataTable({
		"ajax":{
			"url": "../getTransito",
			'dataSrc': '',
		},
		'info': false,
		"lengthMenu": [[10,20,30,40,-1], [10,20,30,40,"Todo"]],
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
			{"title": "ARTICULO","data": "ARTICULO", "render": function(data, type, row, meta) { 
				return`<a href="#!" id="idArticulo" onclick="getDetalleArticulo(`+ "'" +row.ARTICULO + "'" +` , ` + "'" +row.DESCRIPCION + "'" +` ,`+ "'" +row.UNIDAD + "'" +`)" >`+ row.ARTICULO +`</a>`
			}},
			{"title": "DESCRIPCIÓN", 		"data": "DESCRIPCION"},
            {"title": "UNIDAD", 		"data": "UNIDAD"},
            {"title": "FECHA ESTIMADA", "data": "FECHA_ESTIMADA" },
            {"title": "FECHA PEDIDO", "data": "FECHA_PEDIDO" },
            {"title": "CANT.DISPONIBLE", "data": "CANT_DISPONIBLE" },
            {"title": "CANTIDAD", "data": "CANTIDAD" },
		],
		"columnDefs": [
			{"className": "dt-center", "targets": [0, 1, 2,3,4 ]},
			{"className": "dt-right", "targets": [5,6]},
			{"width":"20%","targets":[]},
			{"width":"5%","targets":[]}
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


});
function getDetalleArticulo(Articulos,Descripcion,Undiad) {
    $("#idArti").val(Articulos);
    articulo_g = Articulos;
	$("#tArticulo").html(Descripcion+`<p class="text-muted" id="id_cod_articulo">`+Articulos+`</p>`);
	
	var target = '#nav-bod';
    $('a[data-toggle=tab][href=' + target + ']').tab('show');

    //$("#tbody1").empty().append(`<tr><td colspan='5'><center>Aún no ha realizado ninguna busqueda</center></td></tr>`);
	$("#mdDetalleArt").modal('show');


}


</script>