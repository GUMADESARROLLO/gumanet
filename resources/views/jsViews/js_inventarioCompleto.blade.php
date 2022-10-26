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
			{ "data": "DETALLE"},        
			{"title": "ARTICULO", 		"data": "ARTICULO" },
			{"title": "DESCRIPCIÓN", 		"data": "DESCRIPCION", "render": function(data, type, row, meta) { 

				return`<div class="row justify-content-between">
                                <div class="col">
                                  <div class="d-flex">
                                    <div class="avatar avatar-2xl status-online">
                                      <img class="rounded-circle" src="{{ asset('images/item.png') }}" alt="" />
                                    </div>
                                    <div class="flex-1 align-self-center ms-2">
                                        <h6 class="mb-1 fs-1 fw-semi-bold">`+ row.DESCRIPCION +`</h6>
                                        <p class="mb-0 fs--1">
                                            <span class="badge badge-pill badge-primary"><span class="fas fa-check"></span> `+ row.UNIDAD +`</span>
                                        </p>
                                    </div>
                                  </div>
                                </div>
                              </div>`

			}},
			{"title": "CANT.DISPONIBLE", "data": "CANT_DISPONIBLE" },
		],
		"columnDefs": [
			{"className": "dt-center", "targets": [0, 1 ]},
			{"className": "dt-right", "targets": [3]},
			{"width":"20%","targets":[]},
			{"width":"5%","targets":[0,1]}
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
    
    $(document).on('click', '#exp_more', function(ef) {
		var table = $('#dtInvCompleto').DataTable();
		var tr = $(this).closest('tr');
		var row = table.row(tr);
		var data = table.row($(this).parents('tr')).data();


		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
			ef.target.innerHTML = "expand_more";
			ef.target.style.background = '#e2e2e2';
			ef.target.style.color = '#007bff';
		} else {
			//VALIDA SI EN LA TABLA HAY TABLAS SECUNDARIAS ABIERTAS
			table.rows().eq(0).each( function ( idx ) {
				var row = table.row( idx );

				if ( row.child.isShown() ) {
					row.child.hide();
					ef.target.innerHTML = "expand_more";

					var c_1 = $(".expan_more");
					c_1.text('expand_more');
					c_1.css({
						background: '#e2e2e2',
						color: '#007bff',
					});
				}
			} );

			format(row.child,data.UNIDAD,data.ARTICULO);
			tr.addClass('shown');
			
			ef.target.innerHTML = "expand_less";
			ef.target.style.background = '#ff5252';
			ef.target.style.color = '#e2e2e2';
		}
	});
	
	function format ( callback, bodega_, articulo_ ) {

		console.log(bodega_)
    var thead = tbody = '';            
        thead =`<table class="" width='100%'>
                    <tr>
                        <th class="dt-center">BODEGA</th>                        
                        <th class="dt-center">UNIDAD</th>
                        <th class="dt-center">CANT. DISPONIBLE</th>
                    </tr>
                <tbody>`;
    $.ajax({
        type: "POST",
        url: "getAllBodegas",
        data:{
            articulo: articulo_  ,
			UNIDAD: bodega_      
        },        
        success: function ( data ) {
            if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='6'><center>Bodega sin existencia</center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }
            $.each(data, function (i, item) {
				tbody +=`<tr class="center">
								<td class= "dt-center">` + item['BODEGA'] + `</td>								
								<td  class= "dt-center">` + item['UNIDAD'] + `</td>
								<td class="dt-right">` + item['CANT_DISPONIBLE'] + `</td>
							</tr>`;
            });
            tbody += `</tbody></table>`;
            callback(thead + tbody).show();
        }
    });
}
});
</script>