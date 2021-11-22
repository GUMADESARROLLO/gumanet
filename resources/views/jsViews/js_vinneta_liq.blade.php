<script>
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Viñetas</li>`);

    dataVinneta(0,0,'','');
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

$( "#txt-fondo-inicial").on( 'keyup',function() {
    var subTotal = $('#id-mdl-subtotal').text();

    subTotal     = subTotal.replace(/\s/g, '').replace(',','')
    var dmlTotal = parseFloat(this.value)  - parseFloat(subTotal)

    dmlTotal = numeral(dmlTotal).format('0,0.00')
    
    $('#id-dml-disponible').html(dmlTotal)
    

    
});

$("#exp-to-excel").click( function() {
    location.href = "desInvTotal";
});

function Requestdata(){
    f1      = $("#f1").val();
    f2      = $("#f2").val();
    Ruta    = $("#dtRutas").val();
    Clie    = $("#dtCliente").val();
    Stat    = $("#dtStatus").val();
    Opt     = $('input[name=inlineRadioOptions]:checked', '#FrmOptns').val();

    
    if(Opt=='optRec'){
        dataVinneta(f1, f2,Ruta,Clie,Stat);
    }else{
        dataLiquidaciones(f1, f2,Ruta);
    }

    
}

function dataLiquidaciones(f1, f2,Ruta) {

$('#dtVinneta').DataTable({
    'ajax':{
        'url':'getLiquidaciones',
        'dataSrc': '',
        data: {
            'f1' : f1,
            'f2' : f2,
            'RU' : Ruta,
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
        { "title": "",                      "data": "DETALLE"},            
        { "title": "RECIBO",                "data": "RECIBO" },
        { "title": "FECHA",                 "data": "FECHA" },
        { "title": "VENDEDOR",              "data": "VENDEDOR" },
        { "title": "NOMBRE",                "data": "RUTA_NAME" },
        { "title": "FONDO INICIAL",         "data": "FONDO" ,render: $.fn.dataTable.render.number( ',', '.', 0  , 'C$ ' )},
        { "title": "TOTAL A REEMBOLSAR",    "data": "REEMBOLSO" ,render: $.fn.dataTable.render.number( ',', '.', 0  , 'C$ ' )},
        { "title": "SALDO",                 "data": "SALDO" ,render: $.fn.dataTable.render.number( ',', '.', 0  , 'C$ ' )},
        { "title": "",                      "data": "BOTONES" }
    ],
    "columnDefs": [
        {"className": "dt-center", "targets": [0,1,2,3,8]},
        {"className": "dt-right", "targets": [ 5,6,7 ]},
        { "width": "2%", "targets": [0, 1,2,3,5,6,7,8] },
        { "width": "20%", "targets": [ 4 ] }
    ],
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api();
    },
});

$("#dtVinneta_length").hide();
$("#dtVinneta_filter").hide();
}

function dataVinneta(f1, f2,Ruta,Cliente,Stat) {

$('#dtVinneta').DataTable({
    'ajax':{
        'url':'getSolicitudes',
        'dataSrc': '',
        data: {
            'f1' : f1,
            'f2' : f2,
            'RU' : Ruta,
            'CL' : Cliente,
            'St' : Stat,
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
        { "title": "RECIBO",    "data": "RECIBO" },
        { "title": "CLIENTE",   "data": "CLIENTE" },
        { "title": "NOMBRE",    "data": "NOMBRE_CLIENTE" },
        { "title": "FECHA",     "data": "FECHA" },
        { "title": "VENDEDOR",  "data": "VENDEDOR" },
        { "title": "TOTAL",     "data": "TOTAL" ,render: $.fn.dataTable.render.number( ',', '.', 0  , 'C$ ' )},
        { "title": "",          "data": "BOTONES"},
        { "title": "RECIBO",    "data": "DETALLE" },
        

    ],
    "columnDefs": [
        {"className": "dt-center", "targets": [0,1,2,3,4,5,7 ]},
        {"className": "dt-right", "targets": [ 6 ]},
        { "width": "5%", "targets": [0,1,2,4,5,6 ] },
        { "width": "12%", "targets": [ 7 ] },
        { "visible":false, "searchable": false,"targets": [ 8 ] }
    ],
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api();
    },
});

$("#dtVinneta_length").hide();
$("#dtVinneta_filter").hide();
}

$("#BuscarVinneta").click( function() {
    Requestdata()
});

$("#resument").click( function() {

    Opt     = $('input[name=inlineRadioOptions]:checked', '#FrmOptns').val();

    $("#txt-fondo-inicial").val("")    
    $("#id-nota").val("")
    var table = $('#dtVinneta').DataTable();

    var form_data  = table.rows().data().toArray();
    
        
    var time = moment().format('DD/MM/YYYY');
    var data  = table.rows().data();
    Ruta      = $("#dtRutas").val();
    Ruta_name = $( "#dtRutas option:selected" ).text();

    Ruta_name = Ruta_name.substr(7, Ruta_name.length + 3)
    $('#mdlResumen').modal('show')
    

    $("#id-form-ruta").html(Ruta)
    $("#id-form-ruta-name").html(Ruta_name)
    $("#id-form-time").html(time)

    if(form_data.length > 0 ){
        if(Opt=='optRec'){

            let Subtotal  = 0;

            var thead = tbody = tfooter = '';

            thead =`<table <table class="table table-striped table-bordered table-sm post_back" width="100%">
                        <thead c class="bg-blue text-light">
                            <tr>
                                <th class="center" width="10%">Fecha</th>
                                <th class="center" width="10%">No. de Recibo pago</th>
                                <th class="center" width="30%">Nombre del cliente</th>
                                <th class="center" width="10%">Codigo</th>
                                <th class="center" width="10%">Concepto</th>
                                <th class="center" width="10%">Total C$</th>
                                
                            </tr>
                        </thead>
                        <tbody>`;


        
            $.each( form_data, function( key, item ) {

                let suma = 0
                
                $.each( item['DETALLES'], function( key, item ) {
                    suma += parseFloat(item['CANTIDAD']);

                })

                strTotal = item['TOTAL'];

                Total = parseFloat(strTotal.replace('C$',' ').replace(',',''))

                tbody +='<tr>'+
                        '<td class="text-center" >' + item['FECHA'] + '</td>'+
                        '<td class="text-center">' + item['RECIBO'] + '</td>'+
                        '<td class="text-center">' + item['NOMBRE_CLIENTE'] + '</td>'+
                        '<td class="text-center">' + item['CLIENTE'] + '</td>'+
                        '<td class="text-center"> Pago Viñeta ( ' + suma + ' ) </td>'+
                        '<td class="text-center">' + strTotal + '</td>'+
                    '</tr>';

                    Subtotal +=  Total;
            });

        
        

            tfooter = `<tfoot>
                    <tr>
                        <td colspan="6"><br></td>
                    </tr>            
                    <tr>
                        <td colspan="5" align="right">TOTAL RECIBIDO C$</td>
                        <td align="right" id="id-mdl-subtotal">
                            ` + numeral(Subtotal).format('0,0.00') + `
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">SALDO C$ </td>
                        <td align="right" id="id-dml-disponible"></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">MONTO A REEMBOLSAR C$</td>
                        <td  align="right">
                        ` + numeral(Subtotal).format('0,0.00') + `
                        </td>
                    </tr>
                </tfoot>`

                tbody += `</tbody> `+tfooter +`</table>`;


            temp = thead + tbody;
            $("#dtViewLiquidacion").empty().append(temp);
        
        }else{
            alert("Opcion no disponible")    
        }
    }else{
    var thead = tbody = tfooter = '';

    thead =`<table <table class="table table-striped table-bordered table-sm post_back" width="100%">
                <thead c class="bg-blue text-light">
                    <tr>
                        <th class="center" width="10%">Fecha</th>
                        <th class="center" width="10%">No. de Recibo pago</th>
                        <th class="center" width="30%">Nombre del cliente</th>
                        <th class="center" width="10%">Codigo</th>
                        <th class="center" width="10%">Concepto</th>
                        <th class="center" width="10%">Total C$</th>
                        
                    </tr>
                </thead>
                <tbody>`;
    tbody +='<tr>'+
        '<td colspan="6" class="text-center" >SIN RECIBOS</td></tr>';
    tfooter = `<tfoot>
                    <tr>
                        <td colspan="6"><br></td>
                    </tr>            
                    <tr>
                        <td colspan="5" align="right">TOTAL RECIBIDO C$</td>
                        <td align="right" id="id-mdl-subtotal">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">SALDO C$ </td>
                        <td align="right" id="id-dml-disponible"></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">MONTO A REEMBOLSAR C$</td>
                        <td  align="right">0.00</td>
                    </tr>
                </tfoot>`

                tbody += `</tbody> `+tfooter +`</table>`;


            temp = thead + tbody;
            $("#dtViewLiquidacion").empty().append(temp);


    }
});

$("#id-print-pdf").click( function() {

    f1      = $("#f1").val();
    f2      = $("#f2").val();
    Ruta    = $("#id-form-ruta").text();
    Clie    = $("#dtCliente").val();
    Fondo   = $("#txt-fondo-inicial").val();
    Nota    = $("#id-coment").val();
    Stat    = $("#dtStatus").val();
    
    if (Ruta=='' || Fondo == '') {
        alert(" Tiene Información pendiente ")        
    } else {
        location.href = "resumen?f1="+f1+"&f2="+f2+"&RU="+Ruta+"&CL="+Clie+"&Fondo="+Fondo+"&nota="+Nota+"&St="+Stat;
        $('#mdlResumen').modal('hide')
    }
    

});

function rePrint(Id){
    location.href = "rePrint?Id="+Id;
}


$('#txtSearch').on( 'keyup', function () {
    var table = $('#dtVinneta').DataTable();
    table.search(this.value).draw();
});




function Liquidar(Id){
    $.ajax({
        url: 'PushLiq',
        type: 'post',
        data: {
            id :Id
        },
        async: true,
        success: function(response) {

            Requestdata()
        }
    })
}
function open_modal_anulacion(Id){
    $('#message-text').html("")

    $('#mdlAnulacion').modal('show');

    $('#id_request').html(Id);

    $('#id_request').hide();

    
}



$("#id_frm_save_anulacion").click( function() {

    var Id      = $('#id_request').html();

    var mess    = $('#message-text').val();


    $.ajax({
        url: 'cancelarliq',
        type: 'post',
        data: {
            id : Id,
            me : mess
        },
        async: true,
        success: function(response) {
            
            Requestdata()

            $('#mdlAnulacion').modal('hide')

        }
    })

});

function Cancelar(Id){
    
}

//DETALLES DE RECIBOS
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

        format(row.child,data);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }
});

function format ( callback, dta ) {    

var thead = tbody = tNule = '';

thead =`<table class="table table-striped table-bordered table-sm">
            <thead class="text-center bg-secondary text-light">
                <tr>
                    <th class="center">FACTURA</th>
                    <th class="center">CODIGO DE VIÑETA</th>
                    <th class="center">CANTIDAD DE VIÑETA</th>
                    <th class="center">VALOR UNIT. C$ VIÑETA</th>
                    <th class="center">VALOR TOTAL. C$ VIÑETA</th>
                    
                </tr>
            </thead>
            <tbody>`;
            

if (dta.length==0) {
    tbody +=`<tr>
                <td colspan='6'><center>Bodega sin existencia</center></td>
            </tr>`;
    callback(thead + tbody).show();
}

$.each(dta.DETALLES, function (i, item) {

    var total = item['CANTIDAD'] * item['VALOR_UNIT'];


    tbody +='<tr>'+
                '<td class="text-center">' + item['FACTURA'] + '</td>'+
                '<td class="text-center">' + item['VOUCHER'] + '</td>'+
                '<td class="text-center">' + numeral(item['CANTIDAD']).format('0,0') + '</td>'+
                '<td class="text-right">C$ ' + numeral(item['VALOR_UNIT']).format('0,0.00')  + '</td>'+
                '<td class="text-right">C$ ' + numeral(total).format('0,0.00')  + '</td>'+
            '</tr>';

});


tbody += `</tbody></table>`;

if( dta.COMMENT_ANUL.length != 0 ){

    console.log(dta.COMMENT_ANUL.length)

    

    tNule = `<div class="col-sm-12 mt-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="card-title" id="numero_factura">`+dta.COMMENT_ANUL+`</h2>								
                        </div>
                    </div>
                </div>`
}
temp = `
    <div style="margin: 0 auto; height: auto; width:100%; overflow: auto">
    <pre dir="ltr" style="margin: 0px;padding:6px;">
        `+thead+tbody+`
    </pre>
    </div>
    <div class="row mt-3">
                
                <div class="col-sm-6">						
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="card-title" id="numero_factura">`+dta.BENEFIC+`</h2>
                            <p class="card-text" id="">BENEFICIARIO:</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h2 class="card-title" id="MontoVinneta">`+dta.COMMENT+`</h2>
                            <p class="card-text">OBSERVACIONES:</p>
                        </div>
                    </div>
                </div>	
                `+ tNule+`
            </div>
    `;
callback(temp).show();
    
}

//DETALLES DE LIQUITACION
$(document).on('click', '#exp_more_liq', function(ef) {
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

        format_liq(row.child,data);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }
});

function format_liq ( callback, dta ) {    

    var thead = tbody = tNule = '';
    
    thead =`<table class="table table-striped table-bordered table-sm">
                <thead class="text-center bg-secondary text-light">
                    <tr>
                        <th class="center">FECHA</th>
                        <th class="center">Nº de Recibo Pago</th>
                        <th class="center">Nombre Cliente</th>
                        <th class="center">Codigo</th>
                        <th class="center">Concepto</th>
                        <th class="center">Total C$ </th>
                        
                    </tr>
                </thead>
                <tbody>`;
                

    if (dta.length==0) {
        tbody +=`<tr>
                    <td colspan='6'><center>Bodega sin existencia</center></td>
                </tr>`;
        callback(thead + tbody).show();
    }
    
    $.each(dta.DETALLES, function (i, item) {
        tbody +='<tr>'+
                    '<td class="text-center">' + item['FECHA'] + '</td>'+
                    '<td class="text-center">' + item['RECIBO'] + '</td>'+
                    '<td class="text-center">' + item['CLIENTE_NAME'] + '</td>'+
                    '<td class="text-center">' + item['CLIENTE_COD'] + '</td>'+
                    '<td class="text-center">' + item['CONCEPTO'] + '</td>'+
                    '<td class="text-right">C$ ' + numeral(item['TOTAL']).format('0,0.00')  + '</td>'+
                '</tr>';
    });


    tbody += `</tbody></table>`;
    temp = `
        <div style="margin: 0 auto; height: auto; width:100%; overflow: auto">
        <pre dir="ltr" style="margin: 0px;padding:6px;">
            `+thead+tbody+`
        </pre>
        </div>
        <div class="row mt-3">
                    
					<div class="col-sm-12">						
						<div class="card text-center">
							<div class="card-body">
								<h4 class="card-title" id="numero_factura">`+dta.COMMENT+`</h4>
								<p class="card-text" id="">Nota:</p>
							</div>
						</div>
					</div>					
                    `+ tNule+`
				</div>
        `;
    callback(temp).show();
        
}

</script>