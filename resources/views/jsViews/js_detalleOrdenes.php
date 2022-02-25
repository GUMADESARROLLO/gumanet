<script>

	$(document).ready(function(){
		$('#dtData > thead').addClass('bg-blue text-white');
		$('#tbdetalles > thead').addClass('bg-blue text-white');
		inicializaControlFecha();
		dataOrden(0,0);

	});
	var numOrden_g = 0;

	function getMoreDetail(numOrden, descripcion, fechaInicio, fechaFin) {
		numOrden_g = numOrden;
		$("#tDetalleOrdenes").html(`<p class="text-white m-1">` + "#" + numOrden + "-" + descripcion + `</p>` + `<p class="text-white m-1">` + fechaInicio + " - " + fechaFin + `</p>`);
		getMateriaPrima(numOrden);
		getOtrosConsumos(numOrden);
		getDetailSumary(numOrden);
		var target = '#nav-mp';
		$('a[data-toggle=tab][href=' + target + ']').tab('show');

		$("#tbody1")
			.empty()
			.append(`<tr><td colspan='5'><center>Aún no ha realizado ninguna busqueda</center></td></tr>`);

		$("#mdDetalleOrd").modal('show');
	}

	$('nav .nav.nav-tabs a').click(function() {
		var idNav = $(this).attr('id');
		console.log(idNav);
		switch (idNav) {
			case 'navMP':
				getMateriaPrima(numOrden_g);
				break;
			case 'navMOD':
				getMOD(numOrden_g);
				break;
			case 'navQuimicos':
				getQuimicos(numOrden_g);
				break;
			case 'navCIF':
				getCIF(numOrden_g);
				break;
			case 'navCostos':
				getSubCostos(numOrden_g);
				break;
			case 'navHrsEfect':
				getHrsEfect(numOrden_g);
				break;
			default:
				alert('Al parecer alguio salio mal :(')
		}
	});

	//TABLA DE MP
	function getMateriaPrima(numOrden) {
		$("#tblMP").dataTable({
			responsive: true,
			"autoWidth": false,
			"ajax": {
				"url": "getMateriaPrima/" + numOrden,
				'dataSrc': '',
			},
			"searching": false,
			"destroy": true,
			"paging": false,
			"columns": [{
					"data": "maquina"
				},
				{
					"data": "fibra"
				},
				{
					"data": "cantidad"
				},
			],
			"columnDefs": [{
				"className": "dt-right",
				"targets": [2]
			}, ],
			"info": false,
			"language": {
				"zeroRecords": "No hay datos que mostrar",
				"emptyTable": "N/D",
				"loadingRecords": "Cargando...",
			}

		});
	}

	function getMOD(numOrden) {
		$("#tblMOD").dataTable({
			responsive: true,
			"autoWidth": false,
			"ajax": {
				"url": "getMOD/" + numOrden,
				'dataSrc': '',
			},
			"searching": false,
			"destroy": true,
			"paging": false,
			"columns": [{
					"data": "actividad"
				},
				{
					"data": "dia"
				},
				{
					"data": "noche"
				},
				{
					"data": "total"
				},
			],
			"columnDefs": [{
				"className": "dt-center",
				"targets": [1, 2, 3]
			}, ],
			"info": false,
			"language": {
				"zeroRecords": "No hay datos que mostrar",
				"emptyTable": "N/D",
				"loadingRecords": "Cargando...",
			}
		});
	}

	function getQuimicos(numOrden) {
		$("#tblQuimicos").dataTable({
			responsive: true,
			"autoWidth": false,
			"ajax": {
				"url": "getQuimicos/" + numOrden,
				'dataSrc': '',
			},
			"searching": false,
			"destroy": true,
			"paging": false,
			"columns": [{
					"data": "maquina"
				},
				{
					"data": "quimico"
				},
				{
					"data": "cantidad"
				},
			],
			"columnDefs": [{
					"className": "dt-center",
					"targets": [2]
				},
				{
					"width": "45%",
					"targets": [0]
				},

			],
			"info": false,
			"language": {
				"zeroRecords": "No hay datos que mostrar",
				"emptyTable": "N/D",
				"loadingRecords": "Cargando...",
			}
		});
	}

	function getCIF(numOrden) {
		$("#tblCIF").dataTable({
			responsive: true,
			"autoWidth": false,
			"ajax": {
				"url": "getMOD/" + numOrden,
				'dataSrc': '',
			},
			"searching": false,
			"destroy": true,
			"paging": false,
			"columns": [{
					"data": "actividad"
				},
				{
					"data": "total"
				},
			],
			"columnDefs": [{
					"className": "dt-center",
					"targets": [1]
				},
				{	
					"width": "50%",
					"targets": [0]
				},
			],
			"info": false,
			"language": {
				"zeroRecords": "No hay datos que mostrar",
				"emptyTable": "N/D",
				"loadingRecords": "Cargando...",
			}
		});
	}

	function getSubCostos(numOrden) {
		$("#tblCostos").dataTable({
			responsive: true,
			"autoWidth": false,
			"ajax": {
				"url": "getSubCostos/" + numOrden,
				'dataSrc': '',
			},
			"searching": false,
			"destroy": true,
			"paging": false,
			"columns": [{
					"data": "codigo"
				},
				{
					"data": "descripcion"
				},
				{
					"data": "unidad_Medida"
				},
				{
					"data": "cantidad"
				},
				{
					"data": "costo_Unitario"
				},
				{
					"data": "costo_Total"
				},
			],
			"columnDefs": [{
					"className": "dt-center",
					"targets": [2]
				},
				{
					"className": "dt-right",
					"targets": [3, 4, 5]
				},
			],
			"footerCallback": function(row, data, start, end, display) {
				var api = this.api(),
					data;

				var intVal = function(i) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};

				total = api
					.column(5)
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				totalUnitario = api
					.column(4)
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);

				$('#costoTotal').text('C$ ' + numeral(total).format('0,0.00'));
				$('#CT_Unitario').text('C$ ' + numeral(totalUnitario).format('0,0.00'));

			},
			"info": false,
			"language": {
				"zeroRecords": "No hay datos que mostrar",
				"emptyTable": "N/D",
				"loadingRecords": "Cargando...",
			}
		});
	}

	function getOtrosConsumos(numOrden) {
		texto = "3";
		suTexto = texto.sup().toString();
		$.ajax({
			type: 'GET',
			url: 'getOtrosConsumos/' + numOrden,
			dataType: "json",
			data: {},
			success: function(data) {
				count = Object.keys(data).length;
				data.forEach(element => {
					//Agua
					$('#AtotalConsumo').text(element.AtotalConsumo + " m").append(suTexto);
					$('#Ainicial').text(element.Ainicial);
					$('#Afinal').text(element.Afinal);
					//Electricidad
					if (parseFloat(element.E_ConsumoSTD) > 560.00) {
						$("#E_ConsumoSTD").css("color", "#FF0000");
					} else if (parseFloat(element.E_ConsumoSTD) <= 560 && parseFloat(element.E_ConsumoSTD) > 0) {
						$("#E_ConsumoSTD").css("color", "#02b841");
					} else if (parseFloat(element.E_ConsumoSTD) == 0) {
						$("#E_ConsumoSTD").css("color", "#000000");
					}
					$('#EtotalConsumo').text(element.EtotalConsumo + " Kwh");
					$('#Einicial').text(element.Einicial);
					$('#Efinal').text(element.Efinal);
					$('#E_ConsumoSTD').text(element.E_ConsumoSTD + " kw/ton"); // consumo standard
					$('#E_ConsumoPH').text(element.E_ConsumoPH + " kwh"); //proceso seco
					$('#E_ConsumoTTestimado').text(element.E_ConsumoTTestimado + " kwh");
					//Consumo de Gas	
					if (parseFloat(element.G_totalConsumoTon) > 145) {
						$("#G_totalConsumoTon").css("color", "#FF0000");
					} else if (parseFloat(element.G_totalConsumoTon) <= 145 && parseFloat(element.G_totalConsumoTon) > 0) {
						$("#G_totalConsumoTon").css("color", "#02b841");
					} else if (parseFloat(element.G_totalConsumoTon) == 0) {
						$("#G_totalConsumoTon").css("color", "#000000");
					}
					$('#GtotalConsumo').text(element.GtotalConsumo + " Glns");
					$('#G_totalConsumoTon').text(element.G_totalConsumoTon + " gln/ton");
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + '\r\n' +
					xhr.statusText + '\r\n' +
					xhr.responseText + '\r\n' +
					ajaxOptions);
			}
		});
	}

	function getDetailSumary(numOrden) {
		$.ajax({
			type: 'GET',
			url: 'getDetailSumary/' + numOrden,
			dataType: "json",
			data: {},
			success: function(data) {
				data.forEach(element => {
					$('#fechaInicio').text(element.fechaInicio);
					$('#fechaFinal').text(element.fechaFinal);
					$('#horaInicio').text(element.horaInicio);
					$('#horaFinal').text(element.horaFinal);
					$('#lav-tetrapack').text(element.lavadora_total + " kg");
					$('#residuos-pulper').text(element.residuo_total + " kg");
					$('#fechaFinal').text(element.fechaFinal);
					$('#merma-yankee-dry').text(element.merma_total + " kg");
					$('#hrsTrabajadas').text(element.hrsTrabajadas + " hrs");
					$('#produccionNeta').text(element.prod_real + " kg");
					$('#produccionReal').text(element.prod_total + " kg");
					// porcentajes
					if (parseFloat(element.factorFibral) > 1.3) {
						$("#factor-fibral").css("color", "#FF0000");
					} else if (parseFloat(element.factorFibral) <= 1.3 && parseFloat(element.factorFibral) > 0) {
						$("#factor-fibral").css("color", "#02b841");
					} else if (parseFloat(element.factorFibral) == 0) {
						$("#factor-fibral").css("color", "#000");
					}
					$('#factor-fibral').text(element.factorFibral + " %");
					$('#porcentaje_merma').text(element.porcentMermaYankeeDry + " %");
					$('#porcentaje_tpack').text(element.porcentLavadoraTetrapack + " %");
					$('#porcentaje_rp').text(element.porcentResiduosPulper + " %");
					//ultimos datos agregados
					$('#costoBolson').text(element.costoBolson);
					$('#bolsones').text(element.bolsones);
					$('#ton_dia').text(element.Tonelada_dia);

					if (parseFloat(element.Tonelada_dia) > 10) {
						$('#ton_dia').css("color", "#02b841");
					} else if (parseFloat(element.Tonelada_dia) <= 10 && parseFloat(element.Tonelada_dia) > 0) {
						$('#ton_dia').css("color", "#FF0000");
					} else if (parseFloat(element.Tonelada_dia) == 0) {
						$('#ton_dia').css("color", "#000000");
					}
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + '\r\n' +
					xhr.statusText + '\r\n' +
					xhr.responseText + '\r\n' +
					ajaxOptions);
			}
		});
	}

	function getHrsEfect(numOrden) {
		$("#tblHrsEfect").dataTable({
			responsive: true,
			"autoWidth": false,
			"ajax": {
				"url": "getHrasProducidas/" + numOrden,
				'dataSrc': '',
			},
			"searching": false,
			"destroy": true,
			"paging": false,
			"columns": [{
					"data": "nombre"
				},
				{
					"data": "dia"
				},
				{
					"data": "noche"
				},
				{
					"data": "total"
				},

			],
			"columnDefs": [{
					"className": "dt-center",
					"targets": [0]
				},
				{
					"className": "dt-right",
					"targets": [1, 2, 3]
				},
			],
			"footerCallback": function(row, data, start, end, display) {
				var api = this.api(),
					data;

				var intVal = function(i) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};
				total3 = api.cells(0, 3).render('display').reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);
				total = api.column(3).data().reduce(function(a, b) {
					return intVal(a) + intVal(b);
				}, 0);

				//$('#hrasTotales').text(numeral(total / 2).format('0,0.00') + ' Hras');
				$('#hrasTotales').text(numeral((total + total3) / 3).format('0,0.00') + ' Hras');
			},
			"info": false,
			"language": {
				"zeroRecords": "No hay datos que mostrar",
				"emptyTable": "N/D",
				"loadingRecords": "Cargando...",
			}
		});
	}

	$("#BuscarOrden").click(function() {
		Requestdata()
	});


	function Requestdata() {

		f1 = $("#f1").val();
		f2 = $("#f2").val();

		dataOrden(f1, f2);


	}

	function dataOrden(f1, f2) {

		$('#dtData').DataTable({
			'ajax': {
				'url': 'getData',
				'dataSrc': '',
				data: {
					'f1': f1,
					'f2': f2,
				}
			},
			"destroy": true,
			"info": false,
			"language": {
				"zeroRecords": "NO HAY COINCIDENCIAS",
				"paginate": {
					"first": "Primera",
					"last": "Última ",
					"next": "Siguiente",
					"previous": "Anterior"
				},
				"infoFiltered": "(Filtrado de _MAX_ total entradas)",
				"loadingRecords": "Cargando datos...",
				"lengthMenu": "MOSTRAR _MENU_",
				"emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
				"search": "BUSCAR"
			},
			'columns': [{
					"title": "Detalle",
					"data": "detalle_general"
				},
				{
					"title": "PROD.REAL KG",
					"data": "prod_real_total"
				},
				{
					"title": "PROD.TOTAL KG (Real + merma)",
					"data": "prod_total_total"
				},
				{
					"title": "PROD.REAL TON.",
					"data": "prod_real_ton_total"
				},
				{
					"title": "COSTO TOTAL C$",
					"data": "costo_total_total",
					"render": function(data, type, row) {
                        return 'C$ ' + data;
                    }
				},
				{
					"title": "COSTO TOTAL $",
					"data": "ct_dolar_total",
					"render": function(data, type, row) {
                        return '$ ' + data;
                    }
				},
				{
					"title": "COSTO TOTAL TON $",
					"data": "costo_real_ton_total",
					"render": function(data, type, row) {
                        return '$ ' + data;
                    }
				}, 
			],
			"columnDefs": [{
				"className": "dt-center",
				"targets": [0, 1, 2, 3, 4, 5, 6]
			}],

		});

		$("#dtData_length").hide();
		$("#dtData_filter").hide();
		$('#InputDtShowSearchFilterArt').on('keyup', function() {
			var table = $('#dtData').DataTable();
			table.search(this.value).draw();
		});
	}

	//DETALLES DE LIQUITACION
	$(document).on('click', '#exp_more', function(ef) {
		var table = $('#dtData').DataTable();
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
			table.rows().eq(0).each(function(idx) {
				var row = table.row(idx);

				if (row.child.isShown()) {
					row.child.hide();
					ef.target.innerHTML = "expand_more";

					var c_1 = $(".expan_more");
					c_1.text('expand_more');
					c_1.css({
						background: '#e2e2e2',
						color: '#007bff',
					});
				}
			});

			format_detalle(row.child, data);
			tr.addClass('shown');

			ef.target.innerHTML = "expand_less";
			ef.target.style.background = '#ff5252';
			ef.target.style.color = '#e2e2e2';
		}
	});

	function format_detalle(callback, dta) {

		var thead = tbody = tNule = '';

		thead = `<table class="table table-striped table-bordered table-sm">
			<thead class="text-center bg-secondary text-dark" id="tbdetalles">
				<tr>
					<th class="center">N°.ORDEN</th>
					<th class="center">PRODUCTO</th>
					<th class="center">DESCRIPCION P.H EN O.P</th>
					<th class="center">AÑO</th>
					<th class="center">MES</th>
					<th class="center">FECHA INICIO </th>
					<th class="center">FECHA FINAL </th>
					<th class="center">PROD.REAL </th>		
					<th class="center">PROD.TOTAL (REAL + MERMA) </th>		
					<th class="center">PROD.REAL TON</th>	
					<th class="center">COSTO TOTAL C$</th>	
					<th class="center">T.C</th>		
					<th class="center">COSTO TOTAL $</th>		
					<th class="center">COSTO.TON $</th>	
					<th class="center">DETALLE</th>															
				</tr>
			</thead>
			<tbody>`;


		if (dta.length == 0) {
			tbody += `<tr>
				<td colspan='6'><center>Bodega sin existencia</center></td>
			</tr>`;
			callback(thead + tbody).show();
		}

		$.each(dta.all_detalles, function(i, item) {
			tbody += '<tr>' +
				'<td class="text-center">' + item['numOrden'] + '</td>' +
				'<td class="text-center">' + item['producto'] + '</td>' +
				'<td class="text-center">' + item['descripcion'] + '</td>' +
				'<td class="text-center">' + item['anio'] + '</td>' +
				'<td class="text-center">' + item['mes'] + '</td>' +
				'<td class="text-center">' + item['fechaInicio'] + '</td>' +
				'<td class="text-center">' + item['fechaFinal'] + '</td>' +
				'<td class="text-center">' + numeral(item['prod_real']).format('0,0.00') + '</td>' +
				'<td class="text-center">' + numeral(item['prod_total']).format('0,0.00') + '</td>' +
				'<td class="text-center">' + numeral(item['prod_real_ton']).format('0,0.00') + '</td>' +
				'<td class="text-right"> C$ ' + numeral(item['costo_total']).format('0,0.0000') + '</td>' +
				'<td class="text-center"> C$' + numeral(item['tipo_cambio']).format('0,0.0000') + '</td>' +
				'<td class="text-center"> $' + numeral(item['ct_dolar']).format('0,0.0000') + '</td>' +
				'<td class="text-center">  $' + numeral(item['costo_real_ton']).format('0,0.0000') + '</td>' +
				'<td class="text-center">' + item['ver'] + '</td>' +
				'</tr>';
		});


		tbody += `</tbody></table>`;
		temp = `<div style="margin: 0 auto; height: auto; width:100%; overflow: auto">
				<pre dir="ltr" style="margin: 0px;padding:6px;">
					` + thead + tbody + `
				</pre>
				</div>
				`;
		callback(temp).show();

	}
</script>