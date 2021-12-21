<script>
	$(document).ready(function() {
		$('#dtDetalleOrdenes').DataTable({
			"ajax": {
				"url": "DetalleOrdenesDT",
				'dataSrc': '',
			},
			'info': false,
			"language": {
				"infoFiltered": "(Filtrado de _MAX_ total entradas)",
				"zeroRecords": "No hay coincidencias",
				"loadingRecords": "Cargando datos...",
				"paginate": {
					"first": "Primera",
					"last": "Última ",
					"next": "Siguiente",
					"previous": "Anterior"
				},
				"lengthMenu": "MOSTRAR _MENU_",
				"emptyTable": "NO HAY DATOS DISPONIBLES",
				"search": "BUSCAR"
			},
			'columns': [{
					"title": "Nº.ORDEN",
					"data": "numOrden"
				},
				{
					"title": "PRODUCTO",
					"data": "producto"
				},
				{
					"title": "FECHA INICIO",
					"data": "fechaInicio"
				},
				{
					"title": "FECHA FINAL",
					"data": "fechaFinal"
				},
				{
					"title": "PRO.REAL KG",
					"data": "prod_real"
				},
				{
					"title": "PRO.REAL TON.",
					"data": "prod_real_ton"
				},
				{
					"title": "PROD.TOTAL KG",
					"data": "prod_total"
				},
				{
					"title": "COSTO TOTAL C$",
					"data": "costo_total"
				},
				{
					"title": "COSTO TOTAL $",
					"data": "ct_dolar"
				},
				{
					"title": "T.C",
					"data": "tipo_cambio"
				},
				{
					"title": "COSTO TON. $",
					"data": "costo_real_ton"
				},
				{
					"title": "DETALLE",
					"data": "ver"
				},
			],

			"columnDefs": [{
					"className": "dt-center",
					"targets": [0, 1, 2, 3, 7]
				},
				{
					"className": "dt-right",
					"targets": [4, 5, 6,7,8,9,10]
				},
				{
					"width": "7%",
					"targets": [9]
				},
			],
		});

		$("#dtDetalleOrdenes_length").hide();
		$("#dtDetalleOrdenes_filter").hide();
		$('#InputDtShowSearchFilterArt').on('keyup', function() {
			var table = $('#dtDetalleOrdenes').DataTable();
			table.search(this.value).draw();
		});

		$("#InputDtShowColumnsArtic").change(function() {
			var table = $('#dtInventarioArticulos').DataTable();
			table.page.len(this.value).draw();
		});
	})

	var numOrden_g = 0;

	function getMoreDetail(numOrden, descripcion) {
		numOrden_g = numOrden;
		$("#tDetalleOrdenes").html(descripcion + `<p class="text-white ml-1">` + numOrden + `</p>`);
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
					$('#EtotalConsumo').text(element.EtotalConsumo + " Kwh");
					$('#EtotalCordobas').text("C$ " + element.EtotalCordobas);
					$('#Einicial').text(element.Einicial);
					$('#Efinal').text(element.Efinal);
					$('#Efinal').text(element.Efinal);
					//Consumo de Gas	
					/*$('#Ginicial').text(element.Ginicial);
					$('#Gfinal').text(element.Gfinal);*/
					$('#GtotalConsumo').text(element.GtotalConsumo + " Glns");
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
					$('#factor-fibral').text(element.factorFibral + " %");
					$('#porcentaje_merma').text(element.porcentMermaYankeeDry + " %");
					$('#porcentaje_tpack').text(element.porcentLavadoraTetrapack + " %");
					$('#porcentaje_rp').text(element.porcentResiduosPulper + " %");
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
</script>