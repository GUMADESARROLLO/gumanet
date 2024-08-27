<script type="text/javascript">
    
    var colors_ = ['#407EC9', '#D19000', '#00A376', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
    grafiacas_productos_Diarios = {
        chart: {
            type: 'spline',
            renderTo: 'grafVtsDiario',
        },      

        title: {
            text: 'Comportamiento'
        },
        subtitle: {
            text: 'C$ 0.00',
            align: 'right',
            x: -10
        },
        exporting: {enabled: false},
        xAxis: [{type: 'category' }],
        legend: {enabled: false},
        yAxis:{
            title: {
                text: ''
            },
        },
        plotOptions: {
            series: {
                allowPointSelect: false,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        if (this.y > 1000) {
                            return Highcharts.numberFormat(this.y / 1000, 1) + " K";
                        } else {
                            return this.y
                        }
                    }
                }
            }
        }, 
        tooltip: {
            pointFormat: '<span style="color:black">0.0<b>C$ {point.y}</b></span>'
        },
        series: [{
            data: [],
            point: {
                events: {
                    click: function(e) {
                        //detalles_ventas_diarias(this.name,this.mAVG);
                    }
                }
            },
        }]
    }; 
    $('[data-toggle="tooltip"]').tooltip();
    //fullScreen();
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
$(document).ready(function() {

    
    var articulo_g = 0;
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active"><a href="{{url('/Inventario')}}">Inventario</a></li><li class="breadcrumb-item active">Reorder Point</li>`);

    $('#dt_articulos').DataTable({
		"ajax":{
			"url": "getData",
			'dataSrc': '',
		},
        
		"lengthMenu": [[5,30,50,100,-1], [5,30,50,100,"Todo"]],
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
       "scrollY":        "900px",
        "scrollX":        true,
        "scrollCollapse": true,
        "paging":         true,
        "fixedColumns":   {
            "leftColumns": 2,
        },
		'columns': [	
			{"data": "ARTICULO"},
			{"data": "DESCRIPCION"},
			{"data": "LEADTIME"},
			{"data": "FACTOR_STOCK_SEGURIDAD"},
			{"data": "ROTACION_PREVISTA_EXISTENCIAS_VENCER"},
			{"data": "TOTAL_UMK"},
			{"data": "TOTAL_GP"},
			{"data": "TOTAL_DISP"},
			{"data": "VENCE_MENOS_IGUAL_12"},
			{"data": "VENCE_MAS_IGUAL_7"},
			{"data": "LOTE_MAS_PROX_VENCER"},
			{"data": "EXIT_LOTE_PROX_VENCER"},
			{"data": "FECHA_ENTRADA_LOTE"},
			{"data": "CANTIDAD_INGRESADA"},
			{"data": "EJECUTADO_UND_YTD"},
			{"data": "PEDIDO"},
			{"data": "TRANSITO"},
			{"data": "VENTAS_YTD"},
			{"data": "CONTRIBUCION_YTD"},
			{"data": "ROTACION_CORTA"},
			{"data": "ROTACION_MEDIA"},
			{"data": "ROTACION_LARGA"},
			{"data": "MOQ"},
			{"data": "REORDER"},
			{"data": "CANTIDAD_ORDENAR"},
			{"data": "CANTIDAD_ORDENAR", "render": function(data, type, row, meta) {

				var _ReOrder = numeral(row.REORDER).format('00.00');
				var _MOQ     = numeral(row.MOQ).format('00.00')
				
				let color_cant_order = _ReOrder / _MOQ;

				color_cant_order = isValue(color_cant_order,0,true);

				return numeral(color_cant_order).format('0.00');


			}},
			{"data": "COSTO_PROMEDIO_LOC"},
			{"data": "COSTO_PROMEDIO_USD"},
			{"data": "ULTIMO_COSTO_USD"},
			{"data": "DEMANDA_ANUAL_CA_NETA"},
			{"data": "DEMANDA_ANUAL_CA_AJUSTADA"},
			{"data": "FACTOR"},
			{"data": "LIMITE_LOGISTICO_MEDIO"},
			{"data": "CLASE"},
			{"data": "VALUACION"},
			{"data": "REORDER1"},
			{"data": "ESTIMACION_SOBRANTES_UND"},
			
		],
        "columnDefs": [
            {"className": "dt-center", "targets": []},
            {"className": "dt-right", "targets": [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,23,24,25,26,27,28,29,30]},
            {"className": "dt-right-color", "targets": [24]},
            { "width": "50%", "targets": [  ] },
        ],
       
        "createdRow": function( row, data, dataIndex){

            $("#id_UpdateAt").html(data.UPDATED_AT);

            var _ReOrder = numeral(data.REORDER).format('00.00');
            var _MOQ     = numeral(data.MOQ).format('00.00')
            
            let color_cant_order = _ReOrder / _MOQ;

            color_cant_order = isValue(color_cant_order,0,true);

            $(row).find('td:eq(24)').addClass( (color_cant_order <= 0.5 ) ? 'dt-cant-ordenar-red' : 'dt-cant-ordenar-green');
            
        
            if( data["IS_CA"] ==  `S`){
                $(row).addClass('dt-is-ca-background');
            } 

        },
        
    });

    $("#dt_articulos_length").hide();
    $("#dt_articulos_filter").hide();

	$('#txt_search').on( 'keyup', function () {
        var table = $('#dt_articulos').DataTable();
        table.search(this.value).draw();
	});

	
    $( "#select_rows").change(function() {
        var table = $('#dt_articulos').DataTable();
        table.page.len(this.value).draw();
    });



});


$("#exp-to-excel").click(function() {    
    location.href = "ExportToExcel";
})
$("#BtnClick").click(function() {

    
    Swal.fire({
        title: "Recalcular Reorder Point",
        inputAttributes: {
            autocapitalize: "off"
        },
        showCancelButton: true,
        confirmButtonText: "Calcular",
        showLoaderOnConfirm: true,
        preConfirm: async (login) => {
            try {
            const githubUrl = `CalcReorder`;
            const response = await fetch(githubUrl);
            if (!response.ok) {
                return Swal.showValidationMessage(`${JSON.stringify(await response.json())}`);
            }
            return response.json();
            } catch (error) {
            Swal.showValidationMessage(`
                Request failed: ${error}
            `);
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Calculos completados",
                confirmButtonText: "Ok",
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    } 
                });
        }
    });

})


function getDetalleArticulo(Articulos,Descripcion,Undiad) {
    articulo_g = Articulos;
	$("#id_titulo_modal_all_items").html(Descripcion+` | `+Articulos);
	
	var target = '#nav-bod';
    $('a[data-toggle=tab][href=' + target + ']').tab('show');

    //$("#tbody1").empty().append(`<tr><td colspan='5'><center>Aún no ha realizado ninguna busqueda</center></td></tr>`);
	$("#mdDetalleArt").modal('show');
    grafVentasMensuales(Articulos)
    //dataVinneta(0,0,'','');

}

function dataVinneta(f1, f2,Ruta,Cliente,Stat) {
    $('#dtInfo,#dtEstimacion').DataTable({
        responsive: true,
        "info":    false,
        "bPaginate": true,
        
    });
    $("#dtInfo_length,#dtEstimacion_length").hide();
    $("#dtInfo_filter,#dtEstimacion_filter").hide();
    
}

function FormatPretty(number) {
    var numberString;
    var scale = '';
    if( isNaN( number ) || !isFinite( number ) ) {
        numberString = 'N/A';
    } else {
        var negative = number < 0;
        number = negative? -number : number;

        if( number < 1000 ) {
            scale = '';
        } else if( number < 1000000 ) {
            scale = 'K';
            number = number/1000;
        } else if( number < 1000000000 ) {
            scale = 'M';
            number = number/1000000;
        } else if( number < 1000000000000 ) {
            scale = 'B';
            number = number/1000000000;
        } else if( number < 1000000000000000 ) {
            scale = 'T';
            number = number/1000000000000;
        }
        var maxDecimals = 0;
        if( number < 10 && scale != '' ) {
            maxDecimals = 1;
        }
        number = negative ? -number : number;
        numberString = number.toFixed( maxDecimals );
        numberString += scale
    }
    return numberString;
}  
function grafVentasMensuales(Articulos) {

var temporal = "";
$("#grafVtsDiario").empty().append(`<div style="height:400px; background:#ffff; padding:20px">
            <div class="d-flex align-items-center">
                <strong class="text-info">Cargando...</strong>
                <div class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
            </div>
        </div>`);

$(".divSpinner")
.before(`<div class="spinner-border text-white float-right spinner-acum spinner-border-sm" role="status"></div>`);

$("#anioAcumulado").empty();
$("#porcentaje").empty();

$.getJSON("dtGraf/" +Articulos, function(json) {
        dta = [];
        title = [];
        tmp_total = 0;
        Day_Max = [];

        var vVtsDiarias;
        
        $("#id_leadtime").html(json['LEADTIME']);
        $("#id_demanda_neta").html(json['DEMANDA_ANUAL_CA_NETA']);
        $("#id_demanda_ajustada").html(json['DEMANDA_ANUAL_CA_AJUSTADA']);
        $("#id_limite_logistico_medio").html(json['LIMITE_LOGISTICO_MEDIO']);
        $("#id_contribucion").html(json['CONTRIBUCION_YTD']);

        $("#id_reorder1").val(json['REORDER1']);
        $("#id_reordenar").val(json['REORDER']);
        $("#id_cant_ordenar").val(json['CANTIDAD_ORDENAR']);

        $("#id_clase").val(json['CLASE']);
        $("#id_pedido_transito").html(json['PEDIDO_TRANSITO']);
        $("#id_moq").val(json['MOQ']);

        $("#id_R_corta").html(json['ROTACION_CORTA']);
        $("#id_R_media").html(json['ROTACION_MEDIA']);
        $("#id_R_larga").html(json['ROTACION_LARGA']);
        $("#id_ventas").html(json['VENTAS_YTD']);
        $("#id_costo").html(json['COSTO_PROMEDIO_USD']);
        $("#id_ultimo_costo").html(json['ULTIMO_COSTO_USD']);

        $("#id_transito").val(json['TRANSITO']);
        $("#id_pedido").val(json['PEDIDO']);
        $("#id_promedio_mensual").html(json['EJECUTADO_UND_YTD']);
        
        
        $.each(json['VENTAS'], function(i, x) {

            tmp_total = tmp_total + parseFloat(x['data']);

            dta.push({
                name  : x['Mes'],
                y     : x['data'], 
            });

            title.push(x['name']); 
            Day_Max.push(x['data']); 
        }); 

        temporal = '<span style="color:black">\u25CF</span><b>{point.y} </b> UNITS<br/>';                
        grafiacas_productos_Diarios.tooltip = {
            pointFormat : temporal
        }

        vVtsDiarias = numeral(tmp_total).format('0,0.00');
        
        grafiacas_productos_Diarios.xAxis.categories = title;
        grafiacas_productos_Diarios.subtitle.text = vVtsDiarias + " UNITS";
        grafiacas_productos_Diarios.series[0].data = dta;

        chart = new Highcharts.Chart(grafiacas_productos_Diarios);
        
        chart.yAxis[0].update();

})
}
</script>