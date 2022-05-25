<script>
$(document).ready(function() {
	fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Ventas</li>`);

    $("#tblClientes_length").hide();
    $("#tblClientes_filter,#id-form-filter").hide();

	ventasGraf = {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie',
			renderTo: 'container01'
		},
		title: {
			
		},
		subtitle: {
			
		},
        tooltip: {
            headerFormat: '<span style="font-size:11px">Ventas</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>C${point.y:,.2f}</b>',
            shared: true,
            useHTML: true
        },
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: false
				},
				showInLegend: true
			}
		},
		series: [{
            name: 'MONTO',
            data: []
		}]
	};
    dataVentasClientes(false);
    dataVentasArticulos(false);

	var st = $('#sidebar-menu-left').hasClass('active');
	if (st) {
	    $('#page-details').css('width','100%')
	}
});

$(".active-page-details").click( function() {//Regresar ala ventana anterior
    $("#page-details").toggleClass('active');
});

$('#btnSearchArt').on('keyup', function() {
    var table = $('#tblArticulos').DataTable();
    table.search(this.value).draw();
});

$('#btnSearchCl').on('keyup', function() {
    var table = $('#tblClientes').DataTable();
    table.search(this.value).draw();
});

$( "#cmbTableCant").change(function() {
	var table = $('#tblClientes').DataTable();
	table.page.len(this.value).draw();
});

$( "#cmbTableArticulos").change(function() {
	var table = $('#tblArticulos').DataTable();
	table.page.len(this.value).draw();
});




$('#filterHide').on('click', function() {	
	if ($("#id-form-filter").css("display") == "none") {
        $("#id-form-filter").show("slow")
    } else {
        $("#id-form-filter").hide("slow")
    }
});


$('#cmbLabs').removeAttr('selected').find('option:first').attr('selected', 'selected').trigger("change");

var tiempo = 0;
var tiempo_corriendo = null;
var temp = null;

$("#filterData").click( function() {	
	$(".progress-bar").css({
		'display': 'block',
		'width': '0%'
	});
    $.ajax({
        type: "POST",
        url: "ventasDetalle",
        data:{
            clase 		: $("#cmbClase 		option:selected").val(),
            ruta 		: $("#cmbRutas 		option:selected").val(),
			Labs 		: $("#cmbLabs 		option:selected").val(),
            cliente 	: $("#cmbCliente 	option:selected").val(),
            articulo 	: $("#cmbArticulo 	option:selected").val(),
            mes 		: $('#cmbMes 		option:selected').val(),
            anio 		: $('#cmbAnio 		option:selected').val()
        },
		beforeSend : function(){
			tiempo_corriendo	= setInterval(function() {
									tiempo = tiempo + 30;

									if (tiempo<=95) {
										$(".progress-bar").css('width', tiempo+'%');
									}

								}, 1000);
		},
		complete: function() {
			clearInterval(tiempo_corriendo);			
		},
        success: function (json) {
			if (json['objDt']) {
				
				dataVentasClientes(json['clientes']);
				dataVentasArticulos(json['objDt'], json['meta']);

				$(".progress-bar").css('width', '100%');				
			}else {
				mensaje("No se encontraron registros que coincidan con la busqueda", "error")
				$("#MontoMeta").text('0.00');
				$("#MontoMeta2").text('0.00');
				$('#tblClientes').DataTable()
				.clear()
				.draw();

				$('#tblArticulos').DataTable()
				.clear()
				.draw();
			}
        }
    }).done( function(jqXHR, textStatus) {
		temp = setInterval(function() {		
			$(".progress-bar").css('display', 'none');
		}, 1000);		
    });

    tiempo = 0;
    clearInterval(temp);
})

function dataVentasClientes(json) {
	$('#tblClientes').DataTable ( {
		"data":json,
		"destroy": true,
		"info":    false,
		"lengthMenu": [[5,10,-1], [5,10,"Todo"]],
		"language": {
			"zeroRecords": "Cargando...",
			"paginate": {
				"first":      "Primera",
				"last":       "Última ",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"lengthMenu": "MOSTRAR _MENU_",
			"emptyTable": "Aún no ha realizado ninguna busqueda",
			"search":     "BUSCAR"
		},
		'columns': [
			{ "data": "cliente" },
			{ "data": "nombre" },
			{ "data": "ruta" },
			{ "data": "factura", 
				render: function(data, type, row, meta){
        			if(type === 'display'){
            			data = '<a href="#!" id="facturaLink" value="'+ data +'">' + data + '</a>';
        			}
            		return data;
         		}
	     	},
			{ "data": "fecha02" },
			{ "data": "total", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{ "data": "Cantidad", render: $.fn.dataTable.render.number( ',', '.', 2 ) }
		],

		"columnDefs": [
			{"className": "text-right", "targets": [ 5,6 ]},
			{"className": "text-center", "targets": [ 0, 2, 3, 4 ]},
			{ "width": "30%", "targets": [ 1 ] },
			/*{ "width": "5%", "targets": [ 0, 2, 3, 4 ] }*/
		],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
			
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

			
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			total_unidades = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            $('#MontoMeta').text('C$ '+ numeral(total).format('0,0.00'));
			$('#MontoUnidades').text(numeral(total_unidades).format('0,0.00'));
        },
		"fnInitComplete": function () {
			$("#tblClientes_length").hide();
			$("#tblClientes_filter").hide();
		}
	});
}

$('#tblClientes tbody').on('click', 'td', function() {
	var table = $('#tblClientes').DataTable();
 	/*var data = table.cell(this).data();// devuelve un string de la celda seleccionada
            alert(data);*/
 	var datos = new Array();
 	datos = table.row( this ).data();// retorna un array con los datos de la fila
 	$("#page-details").toggleClass('active');
 	console.log(datos);

 	$('#txtCodDF').text(datos['cliente']);
    $('#txtNomDF').text(datos['nombre']);
    $('#txtRutaDF').text(datos['ruta']);
    $('#txtNFactDF').text(datos['factura']);
    $('#txtFechaDF').text(datos['fecha02']);
    $('#txtMontoDF').text('C$ '+ numeral(datos['total']).format('0,0.00'));

    AgregarDetallefactDT(datos['factura']);
    
});

function AgregarDetallefactDT(nFact){
	
	$.ajax({
		url:'getDetFactVenta',
		type: 'POST',
		data:{factura: nFact},
		success: function (json) {
			if (json['objDt']) {
				llenarDtDetalleFactura(json['objDt']);
			}else {
				mensaje("No se encontraron registros que coincidan con la busqueda", "error")
				
				$('#tblDetalleFacturaVenta').DataTable()
				.clear()
				.draw();
			}
        }

	});
}

function llenarDtDetalleFactura(json){
	table = $('#tblDetalleFacturaVenta').DataTable({
		"data":json,
				"destroy": true,
				"info":    false,
				"lengthMenu": [[5,10,-1], [5,10,"Todo"]],
				"language": {
					"zeroRecords": "Cargando...",
					"paginate": {
						"first":      "Primera",
						"last":       "Última ",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"lengthMenu": "MOSTRAR _MENU_",
					"emptyTable": "Aún no ha realizado ninguna busqueda",
					"search":     "BUSCAR"
				},
				'columns': [
					{ "data": "ARTICULO" },
					{ "data": "DESCRIPCION" },
					{ "data": "CANTIDAD", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
					{ "data": "PRECIO_UNITARIO", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
					{ "data": "PRECIO_TOTAL", render: $.fn.dataTable.render.number( ',', '.', 2 ) }
				],
				"columnDefs": [
					{"className": "text-right", "targets": [ 3, 4 ]},
					{"className": "text-center", "targets": [ 0, 2 ]},
					{ "width": "30%", "targets": [ 1 ] },
					{ "width": "5%", "targets": [ 0, 2, 3, 4 ] }
				],
				"fnInitComplete": function () {
					$("#tblDetalleFacturaVenta_length").hide();
					$("#tblDetalleFacturaVenta_filter").hide();
				}
	})
}

function dataVentasArticulos(json, meta) {
	table = $('#tblArticulos').DataTable ( {
				"data":json,
				"destroy": true,
				"info":    false,
				"lengthMenu": [[5,10,-1], [5,10,"Todo"]],
				"language": {
					"zeroRecords": "Cargando...",
					"paginate": {
						"first":      "Primera",
						"last":       "Última ",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"lengthMenu": "MOSTRAR _MENU_",
					"emptyTable": "Aún no ha realizado ninguna busqueda",
					"search":     "BUSCAR"
				},
				'columns': [
					{ "data": "Articulo" },
					{ "data": "Descripcion" },
					{ "data": "Disponible" },
					{ "data": "TotalFacturado" },
					{ "data": "UndFacturado"},
					{ "data": "UndBoni"},
					{ "data": "PrecProm"},
					{ "data": "CostProm"},
					{ "data": "Contribu"},
					/*{ "data": null, render: function (data, type, row ) {						
						return numeral(row.Cantidad).format('0,0.0') + ' ' + row.UM;
					} },*/
					{ "data": "MargenBruto", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
				],
				"columnDefs": [
					{"className": "text-center", "targets": [ 0 ,3]},
					{"className": "text-right", "targets": [ 2, 4, 5, 6, 7,8 ]},
					{ "width": "60%", "targets": [ 1 ] },
					{ "width": "5%", "targets": [ 0, 2, 3, 4, 5, 6, 7, 8 ] }
				],
		        "footerCallback": function ( row, data, start, end, display ) {
		            var api = this.api(), data;
		            var intVal = function ( i ) {
		                return typeof i === 'string' ?
		                    i.replace(/[\$,]/g, '')*1 :
		                    typeof i === 'number' ?
		                        i : 0;
		            };



		            mta = 0;

		            total = api
		                .column( 3 )
		                .data()
		                .reduce( function (a, b) {
		                    return intVal(a) + intVal(b);
		                }, 0 );
		            $('#MontoMeta2').text('C$ '+ numeral(total).format('0,0.00'));

					total_unidades = api
		                .column( 4 )
		                .data()
		                .reduce( function (a, b) {							
		                    return intVal(a) + intVal(b);
		                }, 0 );

		            $('#MontoMeta2').text('C$ '+ numeral(total).format('0,0.00'));
					$('#MontoUnidad').text(numeral((total_unidades)).format('0,0.00'));

		            dta = [{name: 'Real', y: total},{name:'Meta', y:meta}]
		            subtitle= (total==0)?'':($("#cmbClase option:selected").text());
		            graficaVentas(dta,subtitle)
		        },
				"fnInitComplete": function () {
					$("#tblArticulos_length").hide();
					$("#tblArticulos_filter").hide();
				}
			});
}

var ventasGraf = {};
function graficaVentas(data,subtitle) {    
	ventasGraf.series[0].data = data;
	ventasGraf.title.text = 'Clase Terapeutica';
	ventasGraf.subtitle.text = subtitle;
	chart = new Highcharts.Chart(ventasGraf);	
}
</script>