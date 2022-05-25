<script>
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Ordenes de Compra</li>`);

    dataOrdenesCompra(0,0);
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

$("#buscarOrdenes").click( function() {

    f1 = $("#f1").val();
    f2 = $("#f2").val();

    dataOrdenesCompra(f1, f2);
});

$('#buscadorOrden').on( 'keyup', function () {
    var table = $('#dtOrdenesCompra').DataTable();
    table.search(this.value).draw();
});
$( "#dtLength").change(function() {
    var table = $('#dtOrdenesCompra').DataTable();
    table.page.len(this.value).draw();
});


function dataOrdenesCompra(f1, f2) {

    $('#dtOrdenesCompra').DataTable({
        'ajax':{
            'url':'ordenes',
            'dataSrc': '',
            data: {
                'f1' : f1,
                'f2' : f2
            }
        },
        "destroy" : true,
        "info":    false,
        "lengthMenu": [[5,10,20,50,-1], [5,10,20,50,"Todo"]],
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
            { "title": "",              "data": "DETALLE"},
            { "title": "ORDEN COMPRA",  "data": "ORD_COMPRA" },
            { "title": "PROVEEDOR",     "data": "PROVEEDOR" },
            { "title": "NOMBRE",        "data": "NOMBRE" },
            { "title": "FECHA",         "data": "FECHA" },
            { "title": "ESTADO",        "data": "ESTADO" }
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": [ 0, 1, 2, 4, 5 ]},
            {"className": "dt-left", "targets": [ 3 ]},
            { "width": "5%", "targets": [ 0 ] },
        ],
    });

    $("#dtOrdenesCompra_length").hide();
    $("#dtOrdenesCompra_filter").hide();
}

$(document).on('click', '#exp_more', function(ef) {
    var table = $('#dtOrdenesCompra').DataTable();
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

        format(row.child,data.ORD_COMPRA);
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
                        <th class="center">LAB.</th>
                        <th class="center">UND. ALMACEN</th>
                        <th class="center">CANT. ORDENADA</th>
                        <th class="center">CANT. RECIBIDA</th>
                        <th class="center">CANT. PEDIDA</th>
                        <th class="center">CANT. RESTANTE</th>
                        <th class="center">ESTADO</th>
                        <th class="center">FECHA</th>
                        <th class="center">DIAS ACUM.</th>
                        <th class="center">FECHA COTIZACION</th>
                        <th class="center">FECHA OFRECIDA</th>
                        <th class="center">FECHA EMISION</th>
                        <th class="center">FECHA REQ. EMBARQUE</th>
                        <th class="center">FECHA REQUERIDA</th>
                        <th class="center">DIAS ACUM. DESPACHO</th>
                        <th class="center">REF. ORD. COMPRA</th>
                        <th class="center">REF. FACTURA</th>
                        <th class="center">TIPO MERCADO</th>
                        <th class="center">LEYENDA MINSA</th>
                        <th class="center">LOTE</th>
                    </tr>
                </thead>
                <tbody>`;
                
    $.ajax({
        type: "POST",
        url: "lineasOrden",
        data:{
            ordCompra: ordCompra_,
        },
        success: function ( data ) {
            if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='6'><center>Bodega sin existencia</center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }
            $.each(data, function (i, item) {
               tbody +=`<tr>
                            <td class="center">` + item['ARTICULO'] + `</td>
                            <td class="text-left">` + item['DESCRIPCION'] + `</td>
                            <td class="text-left">` + item['LABORATORIO'] + `</td>
                            <td class="text-center">` + item['UNIDAD_ALMACEN'] + `</td>
                            <td class="text-right">` + item['CANTIDAD_ORDENADA'] + `</td>
                            <td class="text-right">` + item['CANTIDAD_RECIBIDA'] + `</td>
                            <td class="text-right">` + item['CANT_PEDIDA'] + `</td>
                            <td class="text-right">` + item['CANT_RESTANTE'] + `</td>
                            <td class="text-center">` + item['ESTADO'] + `</td>
                            <td class="text-center">` + item['Fecha'] + `</td>
                            <td class="text-right">` + item['Dias_acumulados'] + `</td>
                            <td class="text-center">` + item['FECHA_COTIZACION'] + `</td>
                            <td class="text-center">` + item['FECHA_OFRECIDA'] + `</td>
                            <td class="text-center">` + item['FECHA_EMISION'] + `</td>
                            <td class="text-center">` + item['FECHA_REQ_EMBARQUE'] + `</td>
                            <td class="text-center">` + item['FECHA_REQUERIDA'] + `</td>
                            <td class="text-right">` + item['Dias_acumulado_despacho'] + `</td>
                            <td class="text-center">` + item['REF_ORDEN_COMPRA'] + `</td>
                            <td class="text-center">` + item['REF_FACTURA'] + `</td>
                            <td class="text-center">` + item['TIPO_MERCADO'] + `</td>
                            <td class="text-center">` + item['LEYENDA_MINSA'] + `</td>
                            <td class="text-center">` + item['LOTE'] + `</td>
                        </tr>`;
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