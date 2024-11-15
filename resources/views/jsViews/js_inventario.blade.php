<script>
$(document).on('click', '.img-fluid', function (e) {
    url_image = $(this).attr('src');
    Swal.fire({
        showCloseButton: true,
        showConfirmButton: false,
        imageUrl: url_image,
    })

    $(".swal2-popup").css('width', '50%');
})
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Inventario</li>`);
    var infoTable = '';
    emp = $("#companny_id").text();

    switch(emp) {
        case '1':        
        columns = [
            { title: "ARTICULO",                data: "ARTICULO" },
            { title: "DESCRIPCION",             data: "DESCRIPCION" },
            { title: "UNIDAD",                  data: "UNIDAD_ALMACEN" },
            { title: "CANT. DISP. B002",        data: "total" },
            { title: "TOTAL UNITS. DISP. B002",           data: "und" },
            { title: "TOTAL UNITS/ MES",        data: "VST_MES_ACTUAL" }, //VALIDADO ES CORRECTO
            { title: "PROM UNITS/ MES {{date('Y')}}",    data: "PROM_VST_ANUAL" },//VALIDADO ES CORRECTO
            { title: "TOTAL UNITS {{date('Y')}}",        data: "VST_ANNO_ACTUAL" },//VALIDADO ES CORRECTO
            { title: "PROM. UNITS/ MES {{date('Y',strtotime('-1 year'))}}",   data: "PROMEDIO_VENTA" },
            { title: "TOTAL UNITS {{date('Y',strtotime('-1 year'))}}",        data: "CANT_ANIO_PAS" },
            { title: "MESES INVENTARIO",        data: "MESES_INVENTARIO" },
            { title: "Nº MESES CON VTA",        data: "COUNT_MONTH" },
            { title: "TOTAL VTA ANUAL",         data: "SUM_ANUAL" },
            { title: "PROM. VTA ANUAL",         data: "AVG_ANUAL" },
            { title: "PROM. 3M. MAS ALTO",      data: "AVG_3M" }
            
        ];
        columnDefs=[
            {"className":"dt-right", "targets": [ 3, 4, 5, 6, 7, 8, 9, 10,12,13,14 ]},
            {"className":"dt-center", "targets": [ 0, 2,11 ]},
            { "width":"50%", "targets": [ 1 ] }
        ]
        infoTable = `Mostrando Articulos solo de Bodega 002`;
        break;
    case '2':
        columns = [
            { title: "ARTICULO",                data: "ARTICULO" },
            { title: "DESCRIPCION",             data: "DESCRIPCION" },
            { title: "UNIDAD",                  data: "UNIDAD_ALMACEN" },
            { title: "CANT. DISP.",             data: "total" },
            { title: "TOTAL UNITS. ",           data: "und" },
            { title: "TOTAL UNITS/ MES",        data: "VST_MES_ACTUAL" },
            { title: "PROM UNITS/ MES {{date('Y')}}",    data: "PROM_VST_ANUAL" },
            { title: "TOTAL UNITS {{date('Y',strtotime('-1 year'))}}",        data: "VST_ANNO_ACTUAL" },
            { title: "PROM. UNITS/ MES {{date('Y',strtotime('-1 year'))}}",   data: "PROMEDIO_VENTA" },
            { title: "TOTAL UNITS 2021",        data: "CANT_ANIO_PAS" },
            { title: "MESES INVENTARIO",        data: "MESES_INVENTARIO" },
            { title: "Nº MESES CON VTA",        data: "COUNT_MONTH" },
            { title: "TOTAL VTA ANUAL",         data: "SUM_ANUAL" },
            { title: "PROM. VTA ANUAL",         data: "AVG_ANUAL" },
            { title: "PROM. 3M. MAS ALTO",      data: "AVG_3M" }
            
        ];
        columnDefs=[
            {"className":"dt-right", "targets": [ 3, 4, 5, 6, 7, 8, 9, 10,11,12,13 ]},
            {"className":"dt-center", "targets": [ 0, 2 ]},
            { "width":"50%", "targets": [ 1 ] }
        ]
    break;
    case '4':
        columns = [
            { title: "ARTICULO",                data: "ARTICULO" },
            { title: "DESCRIPCION",             data: "DESCRIPCION" },
            { title: "UNIDAD",                  data: "UNIDAD_ALMACEN" },
            { title: "CANT. DISP.",             data: "total" },
            { title: "TOTAL UNITS. ",           data: "und" },
            { title: "TOTAL UNITS/ MES",        data: "VST_MES_ACTUAL" },
            { title: "PROM UNITS/ MES {{date('Y')}}",    data: "PROM_VST_ANUAL" },
            { title: "TOTAL UNITS {{date('Y',strtotime('-1 year'))}}",        data: "VST_ANNO_ACTUAL" },
            { title: "PROM. UNITS/ MES {{date('Y',strtotime('-1 year'))}}",   data: "PROMEDIO_VENTA" },
            { title: "TOTAL UNITS 2021",        data: "CANT_ANIO_PAS" },
            { title: "MESES INVENTARIO",        data: "MESES_INVENTARIO" },
            { title: "Nº MESES CON VTA",        data: "COUNT_MONTH" },
            { title: "TOTAL VTA ANUAL",         data: "SUM_ANUAL" },
            { title: "PROM. VTA ANUAL",         data: "AVG_ANUAL" },
            { title: "PROM. 3M. MAS ALTO",      data: "AVG_3M" }
            
        ];
        columnDefs=[
            {"className":"dt-right", "targets": [ 3, 4, 5, 6, 7, 8, 9, 10,11,12,13 ]},
            {"className":"dt-center", "targets": [ 0, 2 ]},
            { "width":"50%", "targets": [ 1 ] }
        ]
        $("#modulo-inventario").empty();
        $("#modulo-inventario-vencido").empty();
        
    break
    default:
        alert('Ups... ocurrio un problema')
    }

    $('#dtInventarioArticulos').DataTable({
        "ajax":{
            "url": "articulos",
            'dataSrc': '',
        },
        "info":    true,
        "lengthMenu": [[10,30,50,100,-1], [20,30,50,100,"Todo"]],
        "language": {
            "info": infoTable,
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
        'columns': columns,
        "columnDefs": columnDefs,
        rowCallback: function (row, data) {
            /*
            if (numeral(data.MESES_INVENTARIO).format('0.00') <= 3) {                
                $("td:eq(10)", row).addClass("alert alert-danger");
            }else if(numeral(data.MESES_INVENTARIO).format('0.00') > 3 &&  data.MESES_INVENTARIO <= 12){
                $("td:eq(10)", row).addClass("alert alert-warning");
            }*/

        }
    });

    liquidacionPorMeses(6)
    inicializaControlFecha();
    InventarioB004(12)
});

function InventarioB004() {
    $('#id_tbl_inventario_b004').DataTable({
        "destroy": true,
        "ajax":{
            "url": "invenVencidos",
            'dataSrc': '',
        },
        "info":    false,
        "lengthMenu": [[5,10,50,-1], [5,10,100,"Todo"]],
        "language": {
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
        'columns': [
            { "title": "ARTICULO",              "data": "ARTICULO" },
            { "title": "DESCRIPCION",           "data": "DESCRIPCION" },
            { "title": "LOTE",                  "data": "LOTE" },            
            { "title": "CANTIDAD",              "data": "CANT_DISPONIBLE" },
            { "title": "COSTO PROM. LOC.",      "data": "COSTO_PROM_LOC",render: $.fn.dataTable.render.number( ',', '.', 2  , 'C$ ' ) },
            { "title": "COSTO ULT. LOC.",       "data": "COSTO_ULT_LOC",render: $.fn.dataTable.render.number( ',', '.', 2  , 'C$ ' ) },
            { "title": "FECHA DE VENCIMIENTO",  "data": "FECHA_VENCIMIENTO" },
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": [ 0, 2 ,6 ]},
            {"className": "dt-right", "targets": [ 3,4,5 ]},
            { "width": "50%", "targets": [ 1 ] }
        ],
    });

    $("#id_tbl_inventario_b004_length ").hide();
    $("#id_tbl_inventario_b004_filter").hide();
}

function liquidacionPorMeses(valor) {
    $('#tblArticulosVencimiento').DataTable({
        "destroy": true,
        "ajax":{
            "url": "liqMeses/"+valor,
            'dataSrc': '',
        },
        "info":    false,
        "lengthMenu": [[5,10,50,-1], [5,10,100,"Todo"]],
        "language": {
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
        'columns': [
            { "title": "ARTICULO",              "data": "ARTICULO" },
            { "title": "DESCRIPCION",           "data": "DESCRIPCION" },
            { "title": "DÍAS PARA VENCERSE",    "data": "DIAS_VENCIMIENTO" },
            { "title": "CANTIDAD DISPONIBLE",   "data": "CANT_DISPONIBLE" },
            { "title": "FECHA VENCE",           "data": "F_VENCIMIENTO" },
            { "title": "LOTE",                  "data": "LOTE" },
            { "title": "BODEGA",                "data": "BODEGA" },
            { "title": "TOTAL UNITS {{date('Y')}}",      "data": "VTS_ANIO_ANT" },
            { "title": "PROM. UNITS/MES {{date('Y')}}",  "data":  "PROMEDIO_VENTA" },
            { "title": "MESES DE INVENTARIO",   "data": "TEMPO_ESTI_VENT" },
            { "title": "COSTO PROM.",           "data": "COSTO_PROM_LOC" },
            { "title": "COSTO ULT.",            "data": "COSTO_ULT_LOC" },
            { "title": "COSTO TOTAL",           "data": "COSTO_TOTAL" },
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": [ 0, 2, 4, 5 ]},
            {"className": "dt-right", "targets": [ 3, 6, 7, 8, 9, 10, 11, 12]},
            { "width": "50%", "targets": [ 1 ] }
        ],
    });

    $("#dtInventarioArticulos_length, #tblArticulosVencimiento_length").hide();
    $("#dtInventarioArticulos_filter, #tblArticulosVencimiento_filter").hide();
}

function descargarArchivo( tipo ) {
    if (tipo=='vencimiento') {        
        valor = $( "#orderByDate" ).val()
        location.href = "desInventario/"+tipo+"/"+valor;
    }else {
        location.href = "desInventario/"+tipo+"/ND";
    }    
}

$('#InputDtShowSearchFilterArt').on( 'keyup', function () {
    var table = $('#dtInventarioArticulos').DataTable();
    table.search(this.value).draw();
});

$('#InputDtShowSearchFilterArtVenc').on( 'keyup', function () {
    var table = $('#tblArticulosVencimiento').DataTable();
    table.search(this.value).draw();
});

$('#InputDtShowSearchFilterArt12M').on( 'keyup', function () {
    var table = $('#dtLiq12Meses').DataTable();
    table.search(this.value).draw();
});

$( "#orderByDate").change(function() {
    valor = $( this ).val()  
    
    liquidacionPorMeses(valor)
});

$( "#orderComportamiento").change(function() {
    valor = $( this ).val()  
    var articulo = $("#idArti").val();
    var fecha = new Date();
    var inicio = new Date(fecha.getFullYear(), (fecha.getMonth() + 1) - 3, 1);

    var fechaIni = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate();
    var fechaFin = fecha.getFullYear()+'-'+(fecha.getMonth()+1)+'-'+fecha.getDate();
    
    comportamientoMensual(fechaIni, fechaFin, articulo, valor);
});

$( "#InputDtShowColumnsArtic").change(function() {
    var table = $('#dtInventarioArticulos').DataTable();
    table.page.len(this.value).draw();
});

$( "#InputDtShowColumnsArtic2").change(function() {
    var table = $('#tblArticulosVencimiento').DataTable();
    table.page.len(this.value).draw();
});

$( "#id_select_inventario_vencido").change(function() {
    var table = $('#id_tbl_inventario_b004').DataTable();
    table.page.len(this.value).draw();
});

$('#id_search_tble_inventario_vencido').on( 'keyup', function () {
    var table = $('#id_tbl_inventario_b004').DataTable();
    table.search(this.value).draw();
});


$('nav .nav.nav-tabs a').click(function(){
    var idNav = $(this).attr('id');
    switch(idNav) {
        case 'navBodega':
            getDataBodega(articulo_g);
        break;
        case 'navPrecios':
            getPrecios(articulo_g);
            break;
        case 'navBonificados':
            getBonificados(articulo_g);
        break;
        case 'navTransaccion':        
        break;
        case 'navCostos':        
            getCostos(articulo_g)
        break;
        case 'navMargen':        
            getMargen(articulo_g)
        break;
        case 'navOtros':        
            getOtros(articulo_g)
        break;
        case 'navIndicadores':        
            getIndicadores(articulo_g)
        break;
        case 'navVinneta':  
            getVineta(articulo_g)
        break;

        case 'navComportamiento':  
            var fecha = new Date();
            var inicio = new Date(fecha.getFullYear(), (fecha.getMonth() + 1) - 3, 1);

            var fechaIni = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate();
            var fechaFin = fecha.getFullYear()+'-'+(fecha.getMonth()+1)+'-'+fecha.getDate();
            comportamientoMensual(fechaIni, fechaFin, articulo_g, 1);
        break;

        case 'navTransito':  
            articuloTransito(articulo_g);
        break;

        case 'navMific':  
            getPrecioMiFic(articulo_g)
        break;

        default:
            alert('Al parecer alguio salio mal :(')
    }    
})

var articulo_g = 0;
function getDetalleArticulo(articulo, descripcion) {
    $("#idArti").val(articulo);
    articulo_g = articulo;

    $.get("dtGraf/"+articulo+"/Todos", function( data ) {
        
        $("#id_product_img").attr("src",data.IMAGE);

        $("#id_cod_articulo").html(articulo);
        $("#tArticulo").html(data.DESCRIPCION);

        $("#IdClaseTerapeutica").html(data.CLASE_TERAPEUTICA);
        $("#IdLaboratorio").html(data.LABORATORIO);
        $("#IdUnidadMedida").html(data.UNIDAD_ALMACEN);
        $("#IdUnidadMedidaSpan").html(data.UNIDAD_ALMACEN);

    });


    
    getDataBodega(articulo);

    var target = '#nav-bod';
    $('a[data-toggle=tab][href=' + target + ']').tab('show');

    $("#tbody1").empty()
    .append(`<tr><td colspan='5'><center>Aún no ha realizado ninguna busqueda</center></td></tr>`);

    $("#mdDetalleArt").modal('show');

   

}

function getDataBodega(articulo) {
    $("#tblBodega").dataTable({
        responsive: true,
        "autoWidth":false,
        "ajax":{
            "url": "objBodega/"+articulo,
            'dataSrc': '',
        },
        "searching": false,
        "destroy": true,
        "paging":   false,
        "columns":[
            { "data": "DETALLE"},
            { "data": "BODEGA" , render: function ( data, type, row ) { 
                if(data === '002'){
                    console.log();
                    $("#id_disponibles").html(row.CANT_DISPONIBLE)
                }
                return data; 
            } },
            { "data": "UNIDAD" },
            { "data": "NOMBRE" },
            { "data": "CANT_DISPONIBLE" }
        ],
        "columnDefs": [
            { "width": "5%", "targets": [ 0, 1 ,2] },
            {"className":"dt-right", "targets": [ 4 ] },
            {"className":"dt-center", "targets": [ 1,2 ] }
        ],
        "info": false,
        "language": {            
            "zeroRecords": "No hay datos que mostrar",
            "emptyTable": "N/D",
            "loadingRecords": "Cargando...",
        }
    });
}
function getMargen(articulo) {
    $("#tblMargen").dataTable({
        responsive: true,
        "autoWidth":false,
        "ajax":{
            "url": "objMargen/"+articulo,
            'dataSrc': '',
        },
        "searching": false,
        "destroy": true,
        "paging":   false,
        "columns":[
            { "data": "NIVEL_PRECIO"},
            { "data": "PRECIO" }
        ],
        "info": false,
        "language": {            
            "zeroRecords": "No hay datos que mostrar",
            "emptyTable": "N/D",
            "loadingRecords": "Cargando...",
        }
    });
}
function getPrecios(articulo) {
    $("#tblPrecios").dataTable({
        responsive: true,
        "autoWidth":false,
        "ajax":{
            "url": "objPrecios/"+articulo,
            'dataSrc': '',
        },
        "searching": false,
        "destroy": true,
        "paging":   false,
        "columns":[
            { "data": "NIVEL_PRECIO"},
            { "data": "PRECIO" }
        ],
        "info": false,
        "language": {            
            "zeroRecords": "No hay datos que mostrar",
            "emptyTable": "N/D",
            "loadingRecords": "Cargando...",
        }
    });
}
function getCostos(articulo) {   
    $.ajax({
        url: "objCostos/"+articulo,
        type: 'get',
        data: {},
        async: true,
        success: function(data) {
            console.log(data);
            $("#id_prec_prom").text(data[0]['COSTO_PROM_LOC']);
            $("#id_ult_prec").text(data[0]['COSTO_ULT_LOC'])
        }
    })
}

function getOtros(articulo) {   
    $.ajax({
        url: "objOtros/"+articulo,
        type: 'get',
        data: {},
        async: true,
        success: function(data) {
            $("#id_clase_abc").text(data[0]['CLASE']);
            $("#id_existencia_minima").text(data[0]['MINIMO'])
            $("#id_punto_de_reoden").text(data[0]['REORDEN']);
            $("#id_plazo_rebast").text(data[0]['REABASTECIMIENTO'])
        }
    })
}

function getPrecioMiFic(articulo) {   
    $.ajax({
        url: "getInfoMific/"+articulo,
        type: 'get',
        data: {},
        async: true,
        success: function(precios) {
            console.log(precios.Precio_mific_farmacia)
            $("#id_precio_mific_farmacia").text(precios.Precio_mific_farmacia);
            $("#id_precio_mific_public").text(precios.Precio_mific_public)
           
        }
    })
}

function getVineta(articulo) {   
    $.ajax({
        url: "objVineta/"+articulo,
        type: 'get',
        data: {},
        async: true,
        success: function(data) {
            $("#id_vineta_valor").text("C$ " + numeral(data[0]['VINNETA']).format("0,00.00"))
        }
    })
}

function getIndicadores(articulo) {   
    $.ajax({
        url: "objIndicadores/"+articulo,
        type: 'get',
        data: {},
        async: true,
        success: function(data) {
            

            $("#id_total_fact").text("C$ " + numeral(data['ANUAL'][0]['data']).format("0,00.00"));
            $("#id_unit_fact").text(numeral(data['ANUAL'][0]['dtUnd']).format("0,00.00"));
            $("#id_unit_bonif").text(numeral(data['ANUAL'][0]['dtUndBo']).format("0,00.00"));
            $("#id_prom_prec").text("C$ " + data['ANUAL'][0]['dtAVG']);
            $("#id_prom_cost_unit").text("C$ " +numeral(data['ANUAL'][0]['dtCPM']).format("0,00.00"));
            $("#id_contribucion").text("C$ " + data['ANUAL'][0]['dtMCO']);
            $("#id_margen_bruto").text(numeral(data['ANUAL'][0]['dtPCO']).format("0,00.00") + " %");

            $("#id_disp_bodega").text(numeral(data['ANUAL'][0]['dtTB2']).format("0,00.00") );
            $("#id_disp_bodega_unds").text(numeral(data['ANUAL'][0]['dtTUB']).format("0,00.00"));

            $("#id_prom_unds_mes").text(numeral(data['ANUAL'][0]['dtPRO']).format("0,00.00") );
            $("#id_cant_disp_mes").text(numeral(data['ANUAL'][0]['dtTIE']).format("0,00.00"));

            $("#id_total_fact_month").text("C$ " + numeral(data['MENSUAL'][0]['data']).format("0,00.00"));
            $("#id_unit_fact_month").text(numeral(data['MENSUAL'][0]['dtUnd']).format("0,00.00"));
            $("#id_unit_bonif_month").text(numeral(data['MENSUAL'][0]['dtUndBo']).format("0,00.00"));
            $("#id_prom_prec_month").text("C$ " + data['MENSUAL'][0]['dtAVG']);
            $("#id_prom_cost_unit_month").text("C$ " +numeral(data['ANUAL'][0]['dtCPM']).format("0,00.00"));
            $("#id_contribucion_month").text("C$ " + data['MENSUAL'][0]['dtMCO']);
            $("#id_margen_bruto_month").text(numeral(data['MENSUAL'][0]['dtPCO']).format("0,00.00") + " %");

            $("#id_disp_bodega_month").text(numeral(data['MENSUAL'][0]['dtTB2']).format("0,00.00") );
            $("#id_disp_bodega_unds_month").text(numeral(data['MENSUAL'][0]['dtTUB']).format("0,00.00"));

            $("#id_prom_unds_mes_month").text(numeral(data['MENSUAL'][0]['dtPRO']).format("0,00.00") );
            $("#id_cant_disp_mes_month").text(numeral(data['MENSUAL'][0]['dtTIE']).format("0,00.00"));
        }
    })
}

function getBonificados(articulo) {
    $("#tblBonificados").dataTable({
        responsive: true,
        "autoWidth":false,
        "ajax":{
            "url": "objBonificado/"+articulo,
            'dataSrc': '',
            async: false
        },
        "searching": false,
        "destroy": true,
        "paging":   false,
        "columns":[
            { "data": "REGLAS"}
        ],
        "info": false,
        "language": {            
            "zeroRecords": "No hay datos que mostrar",
            "emptyTable": "N/D",
            "loadingRecords": "Cargando...",
        }
    });
}

//MOSTRARA LOS DE TALLES FACTURAS 
$(document).on('click', '#id_info_trans', function(ef) {
    var table = $('#tblTrans_dev').DataTable();
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


        detalles_transacciones(row.child,data.APLICACION);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }
})

function detalles_transacciones ( callback, Factura_ ) {
    var thead = tbody = '';            
    thead =`<table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="center">ARTICULO</th>                        
                        <th class="center">DESCRIPCION.</th>
                        <th class="center">CANTIDAD</th>
                        <th class="center">PRECIO UNITARIO</th>
                        <th class="center">TOTAL</th>
                    </tr>
                </thead>
                <tbody>`;
                $.ajax({
        type: "POST",
        url: "getDetFactVenta",
        data:{
            factura: Factura_,
        },
        success: function ( data ) {
            if (data.length==0) {
                tbody +=`<tr><td colspan='6'><center>....</center></td></tr>`;
                callback(thead + tbody).show();
            }
            
            $.each(data['objDt'], function (i, item) {
                tbody +=`<tr>
                            <td class="center">` + item['ARTICULO'] + `</td>
                            <td class="text-left">` + item['DESCRIPCION'] + `</td>
                            <td class="text-left">` +numeral(item['CANTIDAD']).format('0,0.00')  + `</td>
                            <td class="text-center">` + numeral(item['PRECIO_UNITARIO']).format('0,0.00') + `</td>
                            <td class="text-right">` + numeral(item['PRECIO_TOTAL']).format('0,0.00') + `</td>
                        </tr>`;
            });
            tbody += `</tbody></table>`;
            
            temp = thead+tbody;

            callback(temp).show();
        }


    });
}

$("#btnSearch").click(function() {  
    $.ajax({
        type: "POST",
        url: "transacciones",
        data:{
            f1: $("#f1").val(),
            f2: $("#f2").val(),
            art: articulo_g,
            tp: $( "#catArt option:selected" ).val()            
        },
        success: function (data) {

            $("#tblTrans_dev").dataTable({
                "responsive": true,
                "autoWidth":false,
                "data": data,
                "searching": false,
                "destroy": true,
                "paging":   true,
                "columns":[
                    { "data": "DETALLE" },
                    { "data": "FECHA" },
                    { "data": "LOTE" },
                    { "data": "APLICACION" },
                    { "data": "DESCRTIPO" },
                    { "data": "CANT" },
                    { "data": "REFERENCIA" },
                    { "data": "CODIGO_CLIENTE" },
                    { "data": "NOMBRE" },
                    { "data": "CANTIDAD" }
                ],
                "columnDefs": [
                    { "width": "5%", "targets": [ ] },
                    {"className":"dt-center", "targets": [ 1,2,3,4,5,7,8] },
                    {"className":"dt-right", "targets": [ 6 ] },
                    { "visible": false, "targets": [ 9 ] }
                ],
                "info": false,
                "language": {            
                    "zeroRecords": "No hay datos que mostrar",
                    "emptyTable": "N/D",
                    "loadingRecords": "Cargando...",
                },
                "drawCallback": function(settings) {
                    var api = this.api();
                    var data = this.data();
                    

                    var count_venta = 0;
                    var count_bonif = 0;

                    $.each(api.column(4, { page: 'all' }).data(), function (_, group) {
                        var cant = api.column(9, { page: 'all' }).data()[_];
                        cant = parseFloat(cant);
                        console.log(cant)
                        if(cant >= 0){

                            switch (group) {
                                case 'VENTA':  count_venta += cant; break;
                                case 'BONIFICADO': count_bonif += cant; break;
                            }
                        }
                    });

                    $("#id_count_venta").text(count_venta);
                    $("#id_count_bonif").text(count_bonif);
                    $("#id_units_desp").text(count_bonif+count_venta);

                }
            });
            $("#tblTrans_dev_length").hide();
            $("#tblTrans_dev_filter").hide();

        }
    })
    
    
});
    
$(document).on('click', '#exp_more', function(ef) {
    var table = $('#tblBodega').DataTable();
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

        format(row.child,data.BODEGA,articulo_g,data.UNIDAD);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }
});

function format ( callback, bodega_, articulo_, Unidad_ ) {

    
    var thead = tbody = '';            
        thead =`<table class="" width='100%'>
                    <tr>
                        <th class="center">LOTE</th>
                        <th class="center">CANT. DISPONIBLE</th>
                        <th class="center">CANT. INGRESADA POR COMPRA</th>
                        <th class="center">FECHA ULTM. INGRESO COMPRA</th>
                        <th class="center">FECHA DE CREACION</th>
                        <th class="center">FECHA VENCIMIENTO</th>
                    </tr>
                <tbody>`;
    $.ajax({
        type: "POST",
        url: "lotes",
        data:{
            bodega: bodega_,
            articulo: articulo_,
            Unidad: Unidad_,        
        },        
        success: function ( data ) {


            if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='6'><center>Bodega sin existencia</center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }
            $.each(data, function (i, item) {
                tbody +=`<tr class="center">
                            <td>` + item['LOTE'] + `</td>
                            <td>` + item['CANT_DISPONIBLE'] + `</td>
                            <td>` + item['CANTIDAD_INGRESADA'] + `</td>
                            <td>` + item['FECHA_INGRESO'] + `</td>
                            <td>` + item['FECHA_ENTRADA'] + `</td>
                            <td>` + item['FECHA_VENCIMIENTO'] + `</td>
                        </tr>`;
            });
            tbody += `</tbody></table>`;
            callback(thead + tbody).show();
        }
    });
}


function comportamientoMensual(fechaIni, fechaFin, articulo, op) {
    var temporal = "";
    $('#lbl1').text('0');
    $("#lbl2").text('0');
    $("#lbl3").text('0');
    $("#lbl4").text('0');
    $("#comportamientoMen")
    .empty()
    .append(`<div style="height:400px; background:#ffff; padding:20px">
                <div class="d-flex align-items-center">
                    <strong class="text-info">Cargando...</strong>
                    <div class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
                </div>
            </div>`);
    
            
    $.getJSON("getComportamientoMensual/"+fechaIni+"/"+fechaFin+"/"+articulo+"/"+op, function(json) {
        
        newseries = {};
        category = [];
        units = "";
        contr = "";
        
        if(op == 1){
            units = 'UNITS';
        }
        if(op == 2){
            contr = 'C$';
        }

        var temporal = (op==1)? 'UNITS ': temporal = 'C$ ';

        
        $.each(json, function (i, item) { 
            
            $('#lbl1').text(item['precioPromedio']);
            $("#lbl2").text(item['costoUnitario']);
            $("#lbl3").text(item['contribucion']);
            $("#lbl4").text(item['porcentajeContribucion']);

            $("#lbl_promedio").html("Prom " + temporal + item['average'])
            
            newseries.data = item['data'];
            newseries.name = item['title'];
            category = item['categories'];
            newseries.colorIndex = 0;
                            
        })
        
        var chart = new Highcharts.Chart('comportamientoMen',{
            chart: {
                type: 'spline'
            },
            exporting: {enabled: false},
            title: {
                text: `<p class="font-weight-bolder">Comportamiento</p>`
            },
            xAxis: {
                categories: category
            },
            yAxis: {
                title: {
                    text: ''
                }                
            },
            tooltip: {pointFormat : '<b>'+contr+' </b><span style="color:black"><b>{point.y:,.0f} '+units+' </b></span>'},
            plotOptions: {
                series: {
                    allowPointSelect: false,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return FormatPretty(this.y);
                        }
                    },
                    events: {
                        legendItemClick: function() {
                            return false;
                        }
                    }
                },
            },
            legend: {
                align: 'center',
                verticalAlign: 'top',
                borderWidth: 0
            },
            series: [newseries],
            responsive: {
                rules: [{
                    condition: {
                    maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
        
    })
}

$("#btnSearchComport").click(function(){
    var fechaIni = $("#fci").val();
    var fechaFin = $("#fcf").val();
    var articulo = $("#idArti").val();
    var op = $("#orderComportamiento").val();

    comportamientoMensual(fechaIni, fechaFin, articulo, op);

})

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

function articuloTransito(Articulo){


    $('#tbl_transito_articulo').DataTable({
            "paging": true,
            "destroy": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "ajax":{
                "url" : "getInfoArticulo",
                "type" : "post",
                "data": { Articulo  : Articulo, _token  : "{{ csrf_token() }}" }
            },
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
            "columnDefs": [
                {"className": "dt-center", "targets": [0,1,2,3,4,5]},
                {"className": "dt-left", "targets": [6]},
                {"className": "dt-right", "targets": [5]},
            ],
            'columns': [
                {"data": "fecha_pedido"},
                {"data": "fecha_estimada"},
                {"data": "documento"},
                {"data": "mercado"},
                {"data": "mific"},
                {"data": function(data, type, row, meta) {
                    return numeral(data.Precio_mific_farmacia).format('0,0.00'); 
                }, "className": "dt-right"},
                {"data": function(data, type, row, meta) {
                    return numeral(data.cantidad).format('0,0'); 
                }, "className": "dt-right"},
                {"data": function(data, type, row, meta) {
                    return numeral(data.cantidad_pedido).format('0,0'); 
                }, "className": "dt-right"},
                {"data": function(data, type, row, meta) {
                    return numeral(data.cantidad_transito).format('0,0'); 
                }, "className": "dt-right"},
                {"data": "via_transito"},
                {"data": "observaciones"},                
            ],
        });  
        $("#tbl_transito_articulo_length").hide();
        $("#tbl_transito_articulo_filter").hide();

}

</script>