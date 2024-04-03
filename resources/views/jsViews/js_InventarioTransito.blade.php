<script>
$(document).ready(function() {
    fullScreen();
    inicializaControlFecha();
	
    var articulo_g = 0;
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
			title: "Articulo Nuevo",
			input: "text",
			inputAttributes: {
				autocapitalize: "off"
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
}
function getDetalleArticulo(Articulos,Descripcion,Undiad) {
    $("#idArti").val(Articulos);
    articulo_g = Articulos;
	
	$("#txtArticulo").val(Articulos)
	$("#txtDescripcion").val(Descripcion)
	
	var target = '#nav-bod';
    $('a[data-toggle=tab][href=' + target + ']').tab('show');

    //$("#tbody1").empty().append(`<tr><td colspan='5'><center>Aún no ha realizado ninguna busqueda</center></td></tr>`);
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

                axios.post('{{ route("SaveTransito") }}', formData)
                    .then(response => {						
						Swal.fire({
                            title: 'Correcto',
							text: response.data.message,
							icon: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'OK'
							}).then((result) => {
								if (result.isConfirmed) {
									InitTable();
								}   
							})
                    })
                    .catch(e => {
                        if (e.response.status === 422) {
							var eLabel = this.errors = e.response.data.errors;

							Object.entries(eLabel).forEach(([key, value]) => {
								$("#alert_" + key).show();
								$("#alert_" + key).html(value[0]);

							})


						}
                        // Manejo de errores
                        //alert('Error al guardar la información');
                    });
            }
        }
    });


</script>