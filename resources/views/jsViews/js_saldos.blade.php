<script type="text/javascript">
$(document).ready(function() {
	fullScreen();
	loadDataSaldos();

	$("#item-nav-01").after(`<li class="breadcrumb-item active">Saldos</li>`);
})

function loadDataSaldos() {
		$('#tbSaldos').dataTable({
		    responsive: true,
		    "autoWidth":false,
		    'ajax':{
		        'url':'saldoAlls',
		        'async' : true,
		        'dataSrc': '',
		    },
		    "destroy" : true,
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
		        "emptyTable": "NO HAY DATOS DISPONIBLES",
		        "search":     "BUSCAR"
		    },
		    'columns': [
		        {"title": "", 			"data": "OPC" },		        
		        {"title": "RUTA", 		"data": "RUTA" },
				{"title": "RUTA", 		"data": "RUTA01" },
		        {"title": "NOMBRE", 	"data": "NOMBRE" },
		        {"title": "VENCIDO", 	"data": "VENCIDO" },
		        {"title": "NO VENCIDO", "data": "N_VENCIDO" }
		    ],
		    "columnDefs": [
		        {"className": "dt-center", "targets": [ 0, 2 ]},
		        {"className": "dt-n-vencido", "targets": [ 5 ]},
		        {"className": "dt-vencido", "targets": [ 4 ]},
		        {"width":"10%","targets":[ 2 ]},
		        {"width":"6%","targets":[ 0 ]},
                {
                    "targets": [ 1 ],
                    "visible": false
                }
		    ],
		    "footerCallback": function ( row, data, start, end, display ) {
		        var api = this.api(), data;
		        var intVal = function ( i ) {
		            return typeof i === 'string' ?
		            i.replace(/[\C$,]/g, '')*1 :
		            typeof i === 'number' ?
		            i : 0;
		        };

		        total_vencido = api
		        .column( 4 )
		        .data()
		        .reduce( function (a, b) {
		        return intVal(a) + intVal(b);
		        }, 0 );

		        total_no_vencido = api
		        .column( 5 )
		        .data()
		        .reduce( function (a, b) {
		        return intVal(a) + intVal(b);
		        }, 0 );

	            $( api.column( 4 ).footer() ).html(
	                'C$ '+numeral(total_vencido).format('0,0.00')
	            );

	            $( api.column( 5 ).footer() ).html(
	                'C$ '+numeral(total_no_vencido).format('0,0.00')
	            );
		    }
		});
		$('#tbSaldos_length').hide();
		$('#tbSaldos_filter').hide();
}

$(document).on('click', '#exp_more', function(ef) {
    var table = $('#tbSaldos').DataTable();
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

        format(row.child,data.RUTA, data.N_VENCIDO);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }
});

function format ( callback, ruta, n_vencido ) {
	callback(`<div style="height:400px; background:#ffff; padding:20px">
                <div class="d-flex align-items-center">
                  <strong class="text-info">Cargando...</strong>
                  <div class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
                </div>
            </div>`).show();
    var thead = tbody = tfooter = '';
    var tVencido = 0;
        thead =`
		<div class="row mt-3 p-4">
			<div class="col-sm-12">
				<p class="font-weight-bold text-info">No vencido: `+n_vencido+`</p>
				<hr>
		        <table class="table table-striped" width='80%'>
	                <tr>
	                    <th class="text-center">Saldos</th>
	                    <th class="text-center">Montos</th>
	                </tr>
				<tbody>`;
    $.ajax({
        type: "POST",
        url: "saldoxRuta",
        async: true,
        data:{
            ruta_: ruta,
        },        
        success: function ( data ) {
            if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='6'><center>No se encontraron registros</center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }

            $.each(data, function(i, item) {

 				if (item['desc']!=='N_VENCIDOS') {
					tbody +=
					`<tr>
					<td>`+item['desc']+`</td>
					<td>C$<span class="float-right">`+numeral(item['value']).format('0,0.00')+`</span></td>
					</tr>`;
 					
 					tVencido = tVencido + parseFloat(item['value']);
 				}
            });

			tfooter += `
               <tfoot>
                    <tr>
                        <th style="text-align:right">TOTAL VENCIDO: </th>
                        <th style="text-align:right">C$ `+numeral(tVencido).format('0,0.00')+`</th>
                    </tr>
                </tfoot>`;
            
            tbody += `</tbody>`+tfooter+`</table>
            </div>
		</div>`;
		callback(thead + tbody).show();
        }
    });
}

$('#InputDtShowSearchFilter').on( 'keyup', function () {
	var table = $('#tbSaldos').DataTable();
	table.search(this.value).draw();
});

$( "#InputDtShowColumns").change(function() {
	var table = $('#tbSaldos').DataTable();
	table.page.len(this.value).draw();
});

</script>