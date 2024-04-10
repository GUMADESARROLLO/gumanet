<script>
$(document).ready(function() {
    fullScreen();
    inicializaControlFecha();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active"><a href="{{url('../Inventario')}}">Inventario</a></li><li class="breadcrumb-item active">Inventario completo</li>`);

	InitTable();

    

	$('#InputDtShowSearchFilterArt').on( 'keyup', function () {
        var table = $('#dtInvCompleto').DataTable();
        table.search(this.value).draw();
	});

	$( "#InputDtShowColumnsArtic").change(function() {
        var table = $('#dtInvCompleto').DataTable();
        table.page.len(this.value).draw();
	});

	$("#btn_add_item").click(function(){
		Swal.fire({
			input: "textarea",
			inputLabel: "Nuevo Articulo",
			inputPlaceholder: "Nombre del Artiulo nuevo...",
			inputAttributes: {
				"aria-label": "Type your message here"
			},
			showCancelButton: true,
			confirmButtonText: "Guardar",
			showLoaderOnConfirm: true,
			preConfirm: async (Transito) => {
				try {					
					$.ajax({
						url: "../SaveTransitoNew",
						data: {
							Articulo  : Transito,
							_token  : "{{ csrf_token() }}" 
						},
						type: 'post',
						async: true,
						success: function(response) {
							
						},
						error: function(response) {
							Swal.fire("Oops", "No se ha podido guardar!", "error");
						}
					}).done(function(data) {
					});
        
				} catch (error) {
					Swal.showValidationMessage(`Request failed: ${error}`);
				}
			},
			allowOutsideClick: () => !Swal.isLoading()
			}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: "Articulo Fue Agregado"
				});
				InitTable()
			}
			});
	})


});
function isValue(value, def, is_return) {
    if ( $.type(value) == 'null'
        || $.type(value) == 'undefined'
        || $.trim(value) == '(en blanco)'
        || $.trim(value) == ''
        || ($.type(value) == 'number' && !$.isNumeric(value))
        || ($.type(value) == 'array' && value.length == 0)
        || ($.type(value) == 'object' && $.isEmptyObject(value)) ) {
        return ($.type(def) != 'undefined') ? def : false;
    } else {
        return ($.type(is_return) == 'boolean' && is_return === true ? value : true);
    }
}
function InitTable(){
	$(".text-danger").hide();
	$('#dtInvCompleto').DataTable({
		"ajax":{
			"url": "../getTransito",
			'dataSrc': '',
		},
		'destroy' : true,
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
				return`<a href="#!" id="idArticulo" onclick="getDetalleArticulo(`+ "'" +row.ARTICULO + "'" +`)" >`+ row.ARTICULO +`</a>`
				
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
}
function getDetalleArticulo(Articulo) {

	$("#txtArticulo").val("")
	$("#txtDescripcion").val("")
	
	$("#date_estimada").val("")
	$("#date_pedido").val("")
	$("#txtDocuments").val("")
	$("#txtCantidad").val("")
	$("#slcMercado").val('N/D').change();
    $("#slcMIFIC").val('N/D').change();
	$("#txtObservacion").val("")
	try {					
		$.ajax({
			url: "../getInfoArticulo",
			data: {
				Articulo  : Articulo,
				_token  : "{{ csrf_token() }}" 
			},
			type: 'post',
			async: true,
			success: function(a) {
				a = isValue(a,0,true)
				if (a !=0 ) {

					console.log(a)

					var FechaPedido = moment(a.fecha_pedido, 'YYYY-MM-DD');
					var FechaEstimada = moment(a.fecha_estimada, 'YYYY-MM-DD');
				
					$("#txtArticulo").val(a.Articulo)
					$("#txtDescripcion").val(a.Descripcion)

					$("#date_estimada").val(FechaEstimada.format('YYYY-MM-DD'))
					$("#date_pedido").val(FechaPedido.format('YYYY-MM-DD'))
					$("#txtDocuments").val(a.documento)
					$("#txtCantidad").val(a.cantidad)
					$("#slcMercado").val(a.mercado).change();
					$("#slcMIFIC").val(a.mific).change();
					$("#txtObservacion").val(a.observaciones)
					
					
				}
			},
			error: function(response) {
				Swal.fire("Oops", "No se ha podido guardar!", "error");
			}
		}).done(function(data) {
		});

	} catch (error) {
		Swal.showValidationMessage(`Request failed: ${error}`);
	}

	var target = '#nav-bod';
	$('a[data-toggle=tab][href=' + target + ']').tab('show');
	$("#mdDetalleArt").modal('show');
	$(".text-danger").hide();
}


new Vue({
	el: '#id_form_save',
	methods: {
		SaveInformacion() {
			$(".text-danger").hide();

			let formData = new FormData();

			formData.append('Articulo', document.getElementById('txtArticulo').value);
			formData.append('Descripcion', document.getElementById('txtDescripcion').value);

			formData.append('fecha_estimada', document.getElementById('date_estimada').value);
			formData.append('fecha_pedido', document.getElementById('date_pedido').value);


			formData.append('documento', document.getElementById('txtDocuments').value);
			formData.append('cantidad', document.getElementById('txtCantidad').value);
			formData.append('mercado', document.getElementById('slcMercado').value);
			formData.append('mific', document.getElementById('slcMIFIC').value);
			formData.append('observaciones', document.getElementById('txtObservacion').value);

			// axios.post('{{ route("SaveTransito") }}', formData)
			// 	.then(response => {						
			// 		Swal.fire({
			// 			title: 'Correcto',
			// 			text: response.data.message,
			// 			icon: 'success',
			// 			showCancelButton: false,
			// 			confirmButtonColor: '#3085d6',
			// 			cancelButtonColor: '#d33',
			// 			confirmButtonText: 'OK'
			// 			}).then((result) => {
			// 				if (result.isConfirmed) {
			// 					InitTable();
			// 				}   
			// 			})
			// 	}).catch(e => {
			// 		if (e.response.status === 422) {
			// 			var eLabel = this.errors = e.response.data.errors;

			// 			Object.entries(eLabel).forEach(([key, value]) => {
			// 				$("#alert_" + key).show();
			// 				$("#alert_" + key).html(value[0]);

			// 			})


			// 		}
			// 		// Manejo de errores
			// 		//alert('Error al guardar la información');
			// 	});
		}
	}
});


</script>