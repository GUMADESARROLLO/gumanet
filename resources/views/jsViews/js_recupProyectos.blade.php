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
	
	data = {};
	data = {
		'anio1' : anio1,
		'anio2' : anio2,
		'mes1'	: mes1,
		'mes2'	: mes2
	}
	loadDataVTS(data);


})

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

	$('#tblRecupProyectos thead tr:eq(0) th:eq(4)').html(meses[mes1].toUpperCase());
	$("#lblMesActual_").text( meses[mes1].toUpperCase() );

	$('#tblRecupProyectos thead tr:eq(0) th:eq(5)').html(meses[mes2].toUpperCase());
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
	$('.lblAnioActual').text( anio1 );
	$('.lblAnioAnteri').text( anio2 );

	$('#tblRecupProyectos').DataTable({
		'ajax':{
			'url':'dataRECUP',
			'dataSrc': '',
			data: {
				'anio1' : data['anio1'],
				'mes1'	: data['mes1'],
				'anio2' : data['anio2'],
				'mes2'	: data['mes2']
			}
		},
		"destroy" : true,
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


					var temp = (temp==0)?0:(( row.data.mes1.anioActual / temp )-1)*100;

					if (temp <0) {
						return "<span style = 'color:red;font-weight: bold'>" + $.fn.dataTable.render.number(',', '.', 2).display(temp)+ "% </span>";
					}else{
						return "<span style = 'font-weight: bold'>" + $.fn.dataTable.render.number(',', '.', 2).display( temp )+"% </span>";	
					}
				
				} 
			},
			{"data": "data.mes1.anioActual", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{"data": "data.mes1.anioAnterior", render: $.fn.dataTable.render.number( ',', '.', 2 ) },
			{"data": null,
				render: function(data, type, row) { 
					var temp = (row.data.mes1.anioAnterior==0)?0: ((row.data.mes1.anioActual /  row.data.mes1.anioAnterior)-1)*100;

					if (temp <0) {
						return "<span style = 'color:red;font-weight: bold'>" + $.fn.dataTable.render.number(',', '.', 2).display( temp )+"% </span>";
					}else{
						return "<span style = 'font-weight: bold'>" + $.fn.dataTable.render.number(',', '.', 2).display( temp )+"% </span>";
					}
					
				} 
			},
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
			startRender: function(rows, group) {
          var style = group == 4 ? 'background-color: #b8daff;' : 'background-color: #b8daff;';
          var td = `<td style='${style}' colspan=12>${group}</td>`;
          return $(`<tr>${td}</tr>`);
        }
		},
		"footerCallback": function ( row, data, start, end, display ) { //Agregael footer al total de todas las rutas
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

			

			if(total04 == 0 && total03>0){
				crece02_ = 0;
			}else{
				crece02_ = ( total04==0 )?0:((total03/total04)-1)*100;
			}

			if(total02 == 0 && total01>0){
				crece03_ = 0;
			}else{
			crece03_ = ( total02==0 )?0:((total01/total02)-1)*100;
			}
		

			$( api.column( 4 ).footer() ).html(
				numeral(total01).format('0,0.00')
			);

			$( api.column( 5 ).footer() ).html(
				numeral(total02).format('0,0.00')
			);

			$( api.column( 6 ).footer() ).html("<span style = 'color:red'>" +
				numeral(crece03_).format('0,0.00')+'%' + "<span>"
			);

			$( api.column( 7 ).footer() ).html(
				numeral(total03).format('0,0.00')
			);

			$( api.column( 8 ).footer() ).html(
				numeral(total04).format('0,0.00')
			);

			$( api.column( 9 ).footer() ).html("<span style = 'color:red'>" +
				numeral(crece02_).format('0,0.00')+'%' + "<span>"
			);
		}
	});

	$('#tblRecupProyectos_length').hide();
	$('#tblRecupProyectos_filter').hide();
}
</script>