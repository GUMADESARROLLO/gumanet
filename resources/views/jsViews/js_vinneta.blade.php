<script>
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Viñetas</li>`);

    dataVinneta(0,0);
    resumenVinneta(0,0);
    inicializaControlFecha();

});

$('#InputDtShowSearchFilterInvTotal').on( 'keyup', function () {
    var table = $('#dtInventarioTotal').DataTable();
    table.search(this.value).draw();
});

$( "#InputDtShowColumnsInvTotal").change(function() {
    var table = $('#dtInventarioTotal').DataTable();
    table.page.len(this.value).draw();
});

$("#exp-to-excel").click( function() {
    location.href = "desInvTotal";
});

$("#BuscarVinneta").click( function() {

    f1 = $("#f1").val();
    f2 = $("#f2").val();

    dataVinneta(f1, f2);
    resumenVinneta(f1, f2);
});

$('#txtSearch').on( 'keyup', function () {
    var table = $('#dtVinneta').DataTable();
    table.search(this.value).draw();
});
$( "#dtLength").change(function() {
    var table = $('#dtVinneta').DataTable();
    table.page.len(this.value).draw();
});


function dataVinneta(f1, f2) {

    $('#dtVinneta').DataTable({
        'ajax':{
            'url':'getVinnetas',
            'dataSrc': '',
            data: {
                'f1' : f1,
                'f2' : f2
            }
        },
        "destroy" : true,
        "info":    false,
        "lengthMenu": [[10,-1], [10,"Todo"]],
        "language": {
            "zeroRecords": "NO HAY COINCIDENCIAS",
            "paginate": {
                "first":      "Primera",
                "last":       "Última ",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
            "search":     "BUSCAR"
        },
        'columns': [
            { "title": "",          "data": "DETALLE"},            
            { "title": "CLIENTE",   "data": "CLIENTE" },
            { "title": "NOMBRE",    "data": "NOMBRE_CLIENTE" },
            { "title": "FACTURA",   "data": "FACTURA" },
            { "title": "FECHA",     "data": "FECHA" },
            { "title": "VENDEDOR",  "data": "VENDEDOR" },
            { "title": "TOTAL",     "data": "TOTAL" ,render: $.fn.dataTable.render.number( ',', '.', 0  , 'C$ ' )}
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": [1,3,4,5 ]},
            {"className": "dt-right", "targets": [ 6 ]},
            { "width": "5%", "targets": [0,1,3,4,5,6 ] },
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
            varCount = api.rows().count()

            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[^0-9.]/g, '')*1 :
                typeof i === 'number' ?
                i : 0;
            };
            $('#numero_factura').text(numeral(varCount).format('0,0.00'));

            total = api.column( 6 ).data().reduce( function (a, b){
                return intVal(a) + intVal(b);
            }, 0 );

            $('#MontoVinneta').text('C$ ' + numeral(total).format('0,0.00'));
        },
    });

    $("#dtVinneta_length").hide();
    $("#dtVinneta_filter").hide();
}

function resumenVinneta(f1, f2) {

$('#dtResumenVinneta').DataTable({
    'ajax':{
        'url':'getVinnetasResumen',
        'dataSrc': '',
        data: {
            'f1' : f1,
            'f2' : f2
        }
    },
    "destroy" : true,
    "info":    false,
    "scrollX": false,
    "lengthMenu": [[20,-1], [20,"Todo"]],
    "language": {
        "zeroRecords": "NO HAY COINCIDENCIAS",
        "paginate": {
            "first":      "Primera",
            "last":       "Última ",
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "lengthMenu": "MOSTRAR _MENU_",
        "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
        "search":     "BUSCAR"
    },
    'columns': [
        { "title": "RUTA",       "data": "VENDEDOR"},
        { "title": "VIÑETA 5",   "data": "V_5" },
        { "title": "VIÑETA 10",  "data": "V_10" },
        { "title": "VIÑETA 20",  "data": "V_20" },
        { "title": "VIÑETA 30",  "data": "V_30" },
        { "title": "VIÑETA 35",  "data": "V_35" },
        { "title": "VIÑETA 40",  "data": "V_40" },
        { "title": "VIÑETA 50",  "data": "V_50" },
        { "title": "VIÑETA 70",  "data": "V_70" },
        { "title": "TOTAL",  "data": "TOTAL" },
        { "title": "VALOR",  "data": "VALOR" }
    ],
    "columnDefs": [
        {"className": "dt-center", "targets": [ 0]},
        {"className": "dt-right", "targets": [1,2,3,4,5,6,7,8,9,10]}
        
    ],
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;                        
        var intVal = function ( i ) {
            return typeof i === 'string' ?
            i.replace(/[^0-9.]/g, '')*1 :
            typeof i === 'number' ?
            i : 0;
        };

        total = api.column( 10 ).data().reduce( function (a, b) 
        {
            return intVal(a) + intVal(b);
        }, 0 );

        $( api.column( 4 ).footer() ).html(
                'C$ '+ numeral(total).format('0,0.00') +' TOTAL'
            );

       
    },
});

$("#dtResumenVinneta_length").hide();
$("#dtResumenVinneta_filter").hide();
}

$(document).on('click', '#exp_more', function(ef) {
    var table = $('#dtVinneta').DataTable();
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

        format(row.child,data.FACTURA);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }

    

});

function format ( callback, ordCompra_ ) {
    var thead = tbody = '';            
    thead =`<table class="table table-striped table-bordered table-sm">
                <thead class="text-center bg-secondary text-light">
                    <tr>
                        <th class="center">ARTICULO</th>
                        <th class="center">DESCRIP.</th>
                        <th class="center">CANTIDAD</th>
                        <th class="center">PRECIO UNITARIO</th>
                        <th class="center">PRECIO TOTAL</th>
                        <th class="center">VALOR</th>
                        
                    </tr>
                </thead>
                <tbody>`;
                
    $.ajax({
        type: "POST",
        url: "getDetFactVenta",
        data:{
            factura: ordCompra_,
        },
        success: function ( data ) {
            if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='6'><center>Bodega sin existencia</center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }
            $.each(data['objDt'], function (i, item) {

                 valor_ninneta =(item['ARTICULO'].substr(0,2) == 'VU') ? 'C$ ' + numeral( item['CANTIDAD'] * parseInt(item['ARTICULO'].substr(2,6)) ).format('0,0.00') : " - ";

                tbody +='<tr>'+
                            '<td class="text-center">' + item['ARTICULO'] + '</td>'+
                            '<td class="text-left">' + item['DESCRIPCION'] + '</td>'+
                            '<td class="text-right">' + numeral(item['CANTIDAD']).format('0,0.00') + '</td>'+
                            '<td class="text-right">' + numeral(item['PRECIO_UNITARIO']).format('0,0.00')  + '</td>'+
                            '<td class="text-right">' + numeral(item['PRECIO_TOTAL']).format('0,0.00')  + '</td>'+
                            '<td class="text-right"> ' + valor_ninneta  + '</td>'+
                        '</tr>';
            });
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
        }


    });
}

</script>