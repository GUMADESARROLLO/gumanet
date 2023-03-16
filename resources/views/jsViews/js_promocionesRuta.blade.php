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
    
    function OpenModal(Promo){
        var addMultiRow = document.querySelector(Selectors.ADD_ITEM_RUTA);
        var modal = new window.bootstrap.Modal(addMultiRow);
        modal.show();

        
        $('#id_num_prom').html(Promo.id);
        $('#id_lbl_nombre').html(Promo.Titulo);
        
        $('#nombre_ruta_modal').html(Promo.vendor.NOMBRE);        
        $('#nombre_ruta_zona_modal').html(Promo.vendor.VENDEDOR + " | " + Promo.zona.Zona);
        $('#id_lbl_fechas').html("Valido desde " + Promo.fecha_ini + " al " + Promo.fecha_end);
        
        //BluidTable(Detalles)
        getDetalles(Promo.id)
    }
    function getDetalles(IdPromo) {
        $.ajax({
                url: "getDetalles",
                type: 'GET',
                data: {
                    IdPromo         : IdPromo
                },
                async: true,
                success: function(response) {
                    BluidTable(response)
                }
            })
    }
    
    function BluidTable(Obj) {
        data_array = Obj
        dta_table_header = [
            {"title": "Index","data": "id"},
            {"data": "Articulo",
                "render": function(data, type, row, meta) {
                return `<div class="d-flex align-items-center position-relative">                                        
                            <div class="flex-1 ">
                                <h6 class="mb-0 fw-semi-bold"><div class="stretched-link text-primary">`+ row.Descripcion +`</div></h6>
                                <p class="text-500 fs--2 mb-0">`+ row.Articulo +`  </p>
                            </div>
                        </div>`
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">                                    
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+ numeral(row.Precio).format('0,0,00.00')  +` </h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">                                    
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+ row.NuevaBonificacion +` </h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+  numeral(row.ValorVinneta).format('0,0,00.00')  +` </h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+ numeral(row.Promedio_VAL).format('0,0,00.00') +`</h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+  numeral(row.ValMeta).format('0,0,00.00') +` </h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+  numeral(row.Venta).format('0,0,00.00') +` </h5>
                                        <span class="badge rounded-pill bg-primary text-light">`+  numeral(row.PromVenta).format('0,0,00.00') +`%</span>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+ row.Promedio_UND +`</h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2">`+ row.MetaUnd +` </h5>
                                    </div>
                                </div> `
            }},
            {"data": "",
                "render": function(data, type, row, meta) {
                return `<div class="pe-4 border-sm-end border-200">
                                    <div class="d-flex align-items-center">
                                        <h5 class="fs-0 text-900 mb-0 me-2"> `+ row.VentaUND +` </h5>
                                        <span class="badge rounded-pill bg-primary text-light">`+ numeral(row.PromVentaUND).format(0,0.00) +` %</span>
                                    </div>
                                </div> `
            }},                    
        ]
        table_render('#tbl_excel',data_array,dta_table_header,false)
    }

    function table_render(Table,datos,Header,Filter){
        
        var txt_ttMetaValor = 0 ;
        var txt_ttVenta     = 0 ;
        var txt_ttMetaUND   = 0 ;
        var txt_ttVentaUND  = 0 ;

        $(Table).DataTable({
            "data": datos,
            "destroy": true,
            "info": false,
            "bPaginate": true,
            "order": [
                [0, "asc"]
            ],
            "lengthMenu": [
                [10, -1],
                [10, "Todo"]
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
            'columns': Header,
            "columnDefs": [
                {
                    "visible": false,
                    "searchable": false,
                    "targets": [0]
                },
                { "width":"150%", "targets": [ 1 ] }
            ],
            "createdRow": function( row, data, dataIndex ) {    
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[^0-9.]/g, '')*1 :
                    typeof i === 'number' ?
                    i : 0;
                };
                txt_ttMetaValor += intVal(data.ValMeta)
                txt_ttVenta += intVal(data.Venta)
                txt_ttMetaUND += intVal(data.MetaUnd)
                txt_ttVentaUND += intVal(data.VentaUND)
                

              
            },
            rowCallback: function( row, data, index ) {
                if ( data.Index < 0 ) {
                    $(row).addClass('table-danger');
                } 
            }
        });
        $('#id_ttMetaValor').text("C$ " + numeral(txt_ttMetaValor).format('0,0.00'));
        $('#id_ttVenta').text("C$ " + numeral(txt_ttVenta).format('0,0.00'));
        $('#id_ttMetaUND').text(numeral(txt_ttMetaUND).format('0,0'));
        $('#id_ttVentaUND').text(numeral(txt_ttVentaUND).format('0,0'));
        if(!Filter){
            $(Table+"_length").hide();
            $(Table+"_filter").hide();
        }

    }

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

    /*tabla = `<table class="table table-striped table-bordered table-sm">
        <thead class="text-center bg-secondary text-light">
            <tr>
                <div id="sending" class="col-lg-12" style="display:none;">
                    <h3>Procesando...</h3>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" data-progress="0" style="width: 0%;">
                            0%
                        </div>
                    </div>
                    <div class="counter-sending">
                        (<span id="done">0</span>/<span id="total">0</span>)
                    </div>
                
                    <div class="execute-time-content">
                        Tiempo transcurrido: <span class="execute-time">0 segundos</span>
                    </div>
                
                    <div class="end-process" style="display:none;">
                        <div class="alert alert-success">El proceso ha sido completado.</a></div>
                    </div>    
                </div>
            </tr>
        </thead>
    </table>`;*/


    thead =`<table class="table table-striped table-bordered table-sm">
                <thead class="text-center bg-secondary text-light">
                    <tr>
                        <th class="center">`+anno.getFullYear()+`</th>
                        <th class="center">ENERO</th>
                        <th class="center">FEBRERO.</th>
                        <th class="center">MARZO</th>
                        <th class="center">ABRIL</th>
                        <th class="center">MAYO</th>
                        <th class="center">JUNIO</th>
                        <th class="center">JULIO</th>
                        <th class="center">AGOSTO</th>
                        <th class="center">SEPTIEMBRE</th>
                        <th class="center">OCTUBRE</th>
                        <th class="center">NOVIEMBRE</th>
                        <th class="center">DICIEMBRE</th>
                        
                    </tr>
                </thead>
                <tbody>`;                
    $.ajax({
        type: "get",
        url: "getPromoMes",
        data:{
            articulo: articulo
        },
        success: function ( data ) {
            if (data.length==0) {
                tbody +=`<tr>
                            <td colspan='6'><center>Cero ventas</center></td>
                        </tr>`;
                callback(thead + tbody).show();
            }

                tbody +='<tr>'+
                            '<td class="text-center bg-secondary text-light">C$</td>'+
                            '<td class="text-center">' + data[1]['venta'] + '</td>'+
                            '<td class="text-center">' + data[2]['venta'] + '</td>'+
                            '<td class="text-center">' + data[3]['venta'] + '</td>'+
                            '<td class="text-center">' + data[4]['venta'] + '</td>'+
                            '<td class="text-center">' + data[5]['venta'] + '</td>'+
                            '<td class="text-center">' + data[6]['venta'] + '</td>'+
                            '<td class="text-center">' + data[7]['venta'] + '</td>'+
                            '<td class="text-center">' + data[8]['venta'] + '</td>'+
                            '<td class="text-center">' + data[9]['venta'] + '</td>'+
                            '<td class="text-center">' + data[10]['venta'] + '</td>'+
                            '<td class="text-center">' + data[11]['venta'] + '</td>'+
                            '<td class="text-center">' + data[12]['venta'] + '</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td class="text-center bg-secondary text-light">UND</td>'+
                            '<td class="text-center">' + data[1]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[2]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[3]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[4]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[5]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[6]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[7]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[8]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[9]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[10]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[11]['unidad'] + '</td>'+
                            '<td class="text-center">' + data[12]['unidad'] + '</td>'+
                        '</tr>';
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
