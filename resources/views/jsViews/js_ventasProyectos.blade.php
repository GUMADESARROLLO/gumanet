<script type="text/javascript">
$(document).ready(function() {
	fullScreen();
	$("#item-nav-01").after(`<li class="breadcrumb-item active">Ventas por Proyectos</li>`);

	var date = new Date();

	anio1 = parseInt( date.getFullYear() );
	anio2 = parseInt( date.getFullYear() ) - 1;
	mes1 = parseInt( date.getMonth() + 1 );
	mes2 = ( mes1==1 )?( 12 ):( mes1-1 );

	$("select#cmbMes1").prop("selectedIndex", mes1);
	$("select#cmbMes2").prop("selectedIndex", mes2);

	$("#lblMesActual").text( meses[mes1].toUpperCase() );
	$("#lblMesAntero").text( meses[mes2].toUpperCase() );

	$("#lblMesActual_").text( meses[mes1].toUpperCase() );
	$("#lblMesAnteri_").text( meses[mes2].toUpperCase() );
	
	data = {
		'anio1' : anio1,
		'anio2' : anio2,
		'mes1'	: mes1,
		'mes2'	: mes2
	}

	loadDataVTS(data)
});

var colors = ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
var groupBy = function (miarray, prop) {
    return miarray.reduce(function(groups, item) {
        var val = item[prop];
		groups[val] = groups[val] || { grupo:item.groupColumn, anioActual:0, anioAnterior:0 };
		groups[val].anioActual = parseFloat(item.data.mes1.anioActual);
		groups[val].anioAnterior = parseFloat(item.data.mes1.anioAnterior);
        return groups;
    }, {});
}

function loadGrafica(response) {
	dta1 = [];
	dta2 = [];	
	var series_ = [];
	var categories_ = [];
	var filterGrupo = [];
	var hash = {};

	array = response.filter(function(current) {
	  var exists = !hash[current.groupColumn];
	  hash[current.groupColumn] = true;
	  return exists;
	});

	$.each(array, function(i, x) {
		filterGrupo = response.filter(function(obj) {
		    return (obj.groupColumn === x['groupColumn']);
		});

		$.each(filterGrupo, function(j, y) {
	    	val1 = parseFloat(y.data.mes1.anioActual);
	    	val2 = parseFloat(y.data.mes1.anioAnterior);
	    	crec = ((val1/val2)-1)*100;
	    	dta1.push(y.data.mes1.anioActual);
	    	dta2.push(y.data.mes1.anioAnterior);

			categories_.push({
				name: numeral(crec).format('0,0.00'),
				categories: [{
					name: numeral(y.data.mes1.anioAnterior).format('0,0.00'),
					categories: [{
						name: numeral(y.data.mes1.anioActual).format('0,0.00'),
						categories: [y['ruta']]
					}]
				}]
			});
		});		

		var anioActual_ = filterGrupo.reduce(function (u, z) {
		  return u + z.data.mes1.anioActual;
		}, 0);

		var anioAnterior_ = filterGrupo.reduce(function (u, z) {
		  return u + z.data.mes1.anioAnterior;
		}, 0);

    	val1 = parseFloat(anioActual_);
    	val2 = parseFloat(anioAnterior_);
    	crec = ((val1/val2)-1)*100;
    	dta1.push(anioActual_);
    	dta2.push(anioAnterior_);

		categories_.push({
			name: numeral(crec).format('0,0.00'),
			categories: [{
				name: numeral(anioAnterior_).format('0,0.00'),
				categories: [{
					name: numeral(anioActual_).format('0,0.00'),
					categories: ['Proyecto']
				}]
			}]
		});
	});
	
	series_.push({
		name: anio1,
		data: dta1,
		color: colors[0]},{
		name: anio2,
		data: dta2,
		color: colors[2] 
	});

	Highcharts.chart('container3', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'COMPARATIVA DE VENTAS POR RUTAS'
	    },
		xAxis: {
		    categories: categories_
		},
	    tooltip: {
	        headerFormat: '<span style="font-size:10px"></span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>C$ {point.y:,.2f}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
        legend:{
        	layout: 'vertical',
        	align: 'left',
        	verticalAlign: 'bottom',
        	floating: true,
        	backgroundColor: '#FFFFFF',
        	itemMarginTop: 2,
        	itemMarginBottom: 2,
        	x: -10,
        	y: -25
        },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: series_
	});
}

var anio1 = 0;
var anio2 = 0;
var mes1 = 0;
var mes2 = 0;
var meses = ['none','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

$("#cmbMes1").change( function( event ) {
	anio1 = parseInt( $("#cmbAnio option:selected").val() );
	anio2 = parseInt( $("#cmbAnio option:selected").val() ) - 1;
	mes1 = parseInt( $("#cmbMes1 option:selected").val() );
	mes2 = ( mes1==1 )?( 12 ):( mes1-1 );

	$('#tblVtsProyectos thead tr:eq(0) th:eq(4)').html(meses[mes1].toUpperCase());
	$("#lblMesActual_").text( meses[mes1].toUpperCase() );

	$('#tblVtsProyectos thead tr:eq(0) th:eq(5)').html(meses[mes2].toUpperCase());
	$("#lblMesAnteri_").text( meses[mes2].toUpperCase() );
});

$("#compararMeses").click( function() {
	data = {};
	anio1 = parseInt( $("#cmbAnio option:selected").val() );
	anio2 = parseInt( $("#cmbAnio option:selected").val() ) - 1;
	mes1 = parseInt( $("#cmbMes1 option:selected").val() );
	mes2 = ( mes1==1 )?( 12 ):( mes1-1 );

	data = {
		'anio1' : anio1,
		'anio2' : anio2,
		'mes1'	: mes1,
		'mes2'	: mes2
	}

	loadDataVTS(data);
})

function loadDataVTS(data) {
    $("#container3")
    .empty()
    .append(`<div style="height:400px; background:#ffff; padding:20px">
                <div class="d-flex align-items-center">
                  <strong class="text-info">Cargando comparativa de ventas por Rutas...</strong>
                  <div class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
                </div>
            </div>`);

	$.ajax({
        type: "get",
        url: "dataVTS",
        dataSrc: '',
		data: {
			'anio1' : data['anio1'],
			'mes1'	: data['mes1'],
			'anio2' : data['anio2'],
			'mes2'	: data['mes2']
		},
        success: function (response) {
        	loadTable(response);
        	loadGrafica(response)
        }
    }).done( function() {

    });
}

function loadTable(response) {
	$('.lblAnioActual').text( anio1 );
	$('.lblAnioAnteri').text( anio2 );

	$('#tblVtsProyectos').DataTable({
		"data":response,
		"destroy":true,
		"aaSorting": [],
		"ordering": false,
		"lengthMenu": [[30], [30]],
		"info":    false,
		"paging": false,
		"language": {
			"zeroRecords": "Cargando...",
			"emptyTable": "NO HAY DATOS DISPONIBLES",
			"search":     "BUSCAR"
		},
		'columns': [
			{"data": "groupColumn" },
			{"data": "nombre" },
			{"data": "ruta" },
			{"data": "zona" },
			{"data": "data.mes1.anioActual", render: $.fn.dataTable.render.number( ',', '.', 2 ) },			
			{"data": null, render: function(data, type, row) {
				if (mes1==1) {
					temp = row.data.mes2.anioAnterior;
				}else {
					temp = row.data.mes2.anioActual;
				}
					
				return $.fn.dataTable.render.number(',', '.', 2).display( temp );
			} },
			{"data": null, render: function(data, type, row) {
				if (mes1==1) {
					temp = row.data.mes2.anioAnterior;
				}else {
					temp = row.data.mes2.anioActual;
				}

				var temp = (row.data.mes1.anioActual==0)?0:(( row.data.mes1.anioActual / temp )-1)*100;
				return $.fn.dataTable.render.number(',', '.', 2).display( temp )+'%'
				} 
			},
			{"data": "data.mes1.anioActual", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{"data": "data.mes1.anioAnterior", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{"data": null,
				render: function(data, type, row) { 
					var temp = (row.data.mes1.anioAnterior==0)?0:((row.data.mes1.anioActual /  row.data.mes1.anioAnterior)-1)*100;
					return $.fn.dataTable.render.number(',', '.', 2).display( temp )+'%'
				} 
			},
			/*{"data": "data.mes2.anioActual", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{"data": "data.mes2.anioAnterior", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{"data": null,
				render: function(data, type, row) { 
					var temp = (row.data.mes2.anioAnterior==0)?0:((row.data.mes2.anioActual /  row.data.mes2.anioAnterior)-1)*100;
					return $.fn.dataTable.render.number(',', '.', 2).display( temp )+'%'
				} 
			},*/
		],
		"columnDefs": [
			{ "visible": false, "targets": 0 },
			{ "width":"20%","targets":[1] },
			{ "width":"5%","targets":[] },
			{ "className": "dt-center", "targets": [ 2 ]},
			{ "className": "dt-right", "targets": [ 4, 5, 6, 7, 8, 9 ]},
		],
		rowGroup: {
			dataSrc: 'groupColumn',
			startRender: null,
			endRender: function ( rows, group ) {
				var mes2_01 = rows
					.data()
					.pluck('data')
					.pluck('mes2')
					.pluck('anioActual')
					.reduce( function (a, b) {
						return a + b;                        
					}, 0);

				var mes2_02 = rows
					.data()
					.pluck('data')
					.pluck('mes2')
					.pluck('anioAnterior')
					.reduce( function (a, b) {
						return a + b;                        
					}, 0);

				var mes1_01 = rows
					.data()
					.pluck('data')
					.pluck('mes1')
					.pluck('anioActual')
					.reduce( function (a, b) {
						return a + b;
					}, 0);

				var mes1_02 = rows
					.data()
					.pluck('data')
					.pluck('mes1')
					.pluck('anioAnterior')
					.reduce( function (a, b) {
						return a + b;                        
					}, 0);

				crece01 = ( mes2_02==0 )?0:((mes2_01/mes2_02)-1)*100;
				crece02 = ( mes1_02==0 )?0:((mes1_01/mes1_02)-1)*100;
				crece03 = ( mes1_01==0 )?0:((mes1_01/mes2_02)-1)*100;

				mes2_01 = $.fn.dataTable.render.number(',', '.', 2).display( mes2_01 );
				mes2_02 = $.fn.dataTable.render.number(',', '.', 2).display( mes2_02 );
				mes1_01 = $.fn.dataTable.render.number(',', '.', 2).display( mes1_01 );
				mes1_02 = $.fn.dataTable.render.number(',', '.', 2).display( mes1_02 );				

				crece01 = $.fn.dataTable.render.number(',', '.', 2).display( crece01 )+'%';
				crece02 = $.fn.dataTable.render.number(',', '.', 2).display( crece02 )+'%';
				crece03 = $.fn.dataTable.render.number(',', '.', 2).display( crece03 )+'%';

				return $('<tr/>')
				.append( `<td class="table-primary font-weight-bold" colspan="3">Total</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+mes1_01+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+mes2_02+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+crece03+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+mes1_01+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+mes1_02+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+crece02+`</td>` );
				/*.append( `<td class="dt-right table-primary font-weight-bold">`+mes2_01+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+mes2_02+`</td>` )
				.append( `<td class="dt-right table-primary font-weight-bold">`+crece01+`</td>` )*/;
			}
		},
		"footerCallback": function ( row, data, start, end, display ) {
			var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
				i.replace(/[\C$,]/g, '')*1 :
				typeof i === 'number' ?
				i : 0;
			};

			total01 = api
			.column( 4 )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );

			total02 = api
				.column( 5 )
				.data()
				.pluck('data')
				.pluck('mes2')
				.pluck('anioAnterior')
				.reduce( function (a, b) {
					return a + b;                        
				}, 0);

			if ( mes1==1 ) {
				total02 = api
					.column( 5 )
					.data()
					.pluck('data')
					.pluck('mes2')
					.pluck('anioAnterior')
					.reduce( function (a, b) {
						return a + b;                        
					}, 0);
			} else {
				total02 = api
					.column( 5 )
					.data()
					.pluck('data')
					.pluck('mes2')
					.pluck('anioActual')
					.reduce( function (a, b) {
						return a + b;                        
					}, 0);
			}

			total03 = api
			.column( 7 )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );

			total04 = api
			.column( 8 )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );

			/*total05 = api
			.column( 10 )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );

			total06 = api
			.column( 11 )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );*/

			//crece01_ = ( total02==0 )?0:((total05/total06)-1)*100;
			crece02_ = ( total04==0 )?0:((total03/total04)-1)*100;
			crece03_ = ( total02==0 )?0:((total01/total02)-1)*100;

			$( api.column( 4 ).footer() ).html(
				numeral(total01).format('0,0.00')
			);

			$( api.column( 5 ).footer() ).html(
				numeral(total02).format('0,0.00')
			);

			$( api.column( 6 ).footer() ).html(
				numeral(crece03_).format('0,0.00')+'%'
			);

			$( api.column( 7 ).footer() ).html(
				numeral(total03).format('0,0.00')
			);

			$( api.column( 8 ).footer() ).html(
				numeral(total04).format('0,0.00')
			);

			$( api.column( 9 ).footer() ).html(
				numeral(crece02_).format('0,0.00')+'%'
			);

			/*$( api.column( 10 ).footer() ).html(
				numeral(total05).format('0,0.00')
			);

			$( api.column( 11 ).footer() ).html(
				numeral(total06).format('0,0.00')
			);

			$( api.column( 12 ).footer() ).html(
				numeral(crece01_).format('0,0.00')+'%'
			);*/
		}
	});

	$('#tblVtsProyectos_length').hide();
	$('#tblVtsProyectos_filter').hide();
}
</script>

