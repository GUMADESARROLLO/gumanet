<script type="text/javascript">
    var dta_table_header = [];
    // INICIALIZA Y ASIGNA LA FECHA EN EL DATEPICKER
    const RangerStartOfMonth  = moment().subtract(1,'days').format('YYYY-MM-DD');
    const RangerEndOfMonth    = moment().subtract(0, "days").format("YYYY-MM-DD");
    var lblRange              = RangerStartOfMonth + " to " + RangerEndOfMonth;      
    $('#id_range_select').val(lblRange);

    var Selectors = {        
        ADD_ITEM_RUTA: '#modl_view_detalles_ruta',
        ADD_PROMOCION: '#modl_add_promocion',
    };

    // INICIALIZA LA DATATABLE CON LOS VALORES POR DEFECTO 
    $("#table_promociones").DataTable({
        "destroy": true,
        "info": false,
        "bPaginate": true,
        "lengthMenu": [
            [5,20, -1],
            [5,20, "Todo"]
        ],
        "language": {
            "zeroRecords": "NO HAY COINCIDENCIAS",
            "paginate": {
                "first": "Primera",
                "last": "Última ",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "-",
            "search": "BUSCAR"
        },
    });

    //OCULTA DE LA PANTALLA EL FILTRO DE PAGINADO Y FORM DE BUSQUEDA
    $("#table_promociones_length").hide();
    $("#table_promociones_filter").hide();

    //NUMERO DE REGISTROS MOSTRADOS EN PANTALLA
    $( "#frm_lab_row").change(function() {
        var table = $('#table_promociones').DataTable();
        table.page.len(this.value).draw();
    });

    //HABILITA LA BUSQUEDA DENTRO DE LA TABLA
    $('#id_txt_buscar').on('keyup', function() {        
        var vTablePedido = $('#table_promociones').DataTable();
        vTablePedido.search(this.value).draw();
    });
    
if ( $("#id_spinner_load").hasClass('visible') ) {
        $("#id_spinner_load").removeClass('visible');
        $("#id_spinner_load").addClass('invisible');
}


$(document).on('click', '#exp_more', function(ef) {
    var table = $('#table_promociones').DataTable();
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var articulo = $(this).attr('idArt');
    var data = table.row($(this).parents('tr')).data();

    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        
    } else {
        //VALIDA SI EN LA TABLA HAY TABLAS SECUNDARIAS ABIERTAS
        table.rows().eq(0).each( function ( idx ) {
            var row = table.row( idx );

            if ( row.child.isShown() ) {
                row.child.hide();                
            }
        } );

        format(row.child, articulo);
        tr.addClass('shown');
        
    }

    

});



function format ( callback, articulo) {
    var thead = tbody = '';
    const anno = new Date();
    
    tabla = `<table class="table table-striped table-bordered table-sm">
        <thead class="text-center bg-secondary text-light">
            <tr> <th class="text-center"><b>Cargando...</b></th>
            </tr>
        </thead>
    </table>`;

    callback(tabla).show();

                   
    $.ajax({//AGREGAR PARAMETRO DE FECHA DE INICIO Y FINAL
        type: "get",
        url: "getPromoMes",
        data:{
            articulo: articulo
        },
        success: function ( data ) { 
            var meses = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];

            thead +=`<table class="table table-striped table-bordered table-sm">
                <thead class="text-center bg-secondary text-light">
                    <tr>
                        <th class="center"></th>`;

                        $.each(data[0],function(key, registro) { 
                            thead +=  '<th class="text-center">' + meses[registro.mes-1] + '</th>';
                        });
                        
                        
            thead += `</tr>
                </thead>
                <tbody>`; 
           if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='13'><center><b>CERO VENTAS</b></center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }

                tbody +='<tr>' +
                        '<td class="text-center bg-secondary text-light">C$</td>';
                        $.each(data[0],function(key, registro) { 
                            tbody +=  '<td class="text-center">' + Number(Number(registro.VENTA_NETA).toFixed(2)).toLocaleString('en') + '</td>';
                        });
                tbody += '</tr>';
                /*tbody +=
                        '<tr>'+
                            '<td class="text-center bg-secondary text-light">UND</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['ENE']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['FEB']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['MAR']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['ABR']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['MAY']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['JUN']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['JUL']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['AGO']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['SEP']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['OCT']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['NOV']).toFixed(0)).toLocaleString('en') + '</td>'+
                            '<td class="text-center">' + Number(Number(data[0][0]['DIC']).toFixed(0)).toLocaleString('en') + '</td>'+
                        '</tr>';*/
            tbody += `</tbody></table>`;
            
            temp = `
                <div style="
                margin: 0 auto;
                height: auto;
                width:100%;
                overflow: auto">
                <pre dir="ltr" style="margin: 0px;padding:6px;">
                    `+thead+tbody+`
                </pre>
                </div>`;

            callback(temp).show();            
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (textStatus == 'parsererror') {
                textStatus = 'Technical error: Unexpected response returned by server. Sending stopped.';
            }
            alert(textStatus);
       }

    });
}

function executeProcess(offset, batch = false) {
    
 
    $.ajax({ 
        type: 'POST',
        dataType: "json",
        url : "process.php", 
        data: {
            id_process: 1,
            offset: offset,
            batch: batch
        },
        success: function(response) {
           
 
          
        }
    });
}
    
</script>
