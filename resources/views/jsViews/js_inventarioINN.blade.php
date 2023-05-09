<script type="text/javascript">
$(document).ready(function() {
    var date = new Date();

    var inicio = new Date(date.getFullYear(), date.getMonth()-2, 1);
    var final = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    var primerDia = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate();
    var ultimoDia = final.getFullYear()+'-'+(final.getMonth()+1)+'-'+final.getDate();

    tblKardex(primerDia, ultimoDia);
});

$("#id_btn_new").click( function() {
    var date = new Date();

    var mes = $('#id_select_mes').val();

    var invM = (mes == 1) ? date.getMonth() : (date.getMonth() + 1) - mes;

    var inicio = new Date(date.getFullYear(), invM, 1);
    var final = new Date(date.getFullYear(),date.getMonth() + 1, 0);

    var primerDia = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate();
    var ultimoDia = final.getFullYear()+'-'+(final.getMonth()+1)+'-'+final.getDate();

    tblKardex(primerDia, ultimoDia);
});


function tblKardex(primerDia, ultimoDia) {
    tblResumen();
    $.ajax({
        url: `getKerdex`,
        type: 'get',
        data: { 
            ini : primerDia, 
            end : ultimoDia
        },
        async: true,
        success: function(data) {
            table =  `<thead>`+
                        `<tr class="bg-blue text-light">`+
                            `<th style="width: 700px;" rowspan="2">ARTICULO</th>`;
                            $.each(data['header_date'], function (i, item) {
                                table += `<th colspan="3" style="text-align:center;" width="10px">`+ ((item == "Ult. Registro.") ? item : moment(item).format('DD-MMM-YYYY'))+`</th>`;                                
                                })
                            
                        
                table +=`</tr><tr>`;                        
                        $.each(data['header_date'], function (i, item) {
                            table += `<th>Entrada</th>`+
                                    `<th>Salida</th>`+
                                    `<th>Saldo</th>`;
                                   
                        })
                    
                table +=`</tr></thead>`+
                    `<tbody style="scrollbar: collapse;">`;
                        $.each(data['header_date_rows'], function (i, item) {
                            table +=`<tr>`+
                                        `<td style="width: 700px; ">`+
                                            `<div class="d-flex position-relative">`+
                                                `<div class="flex-1" style="width: 400px; ">`+
                                                    `<h6 class="mb-0 fw-semi-bold">`+ item.DESCRIPCION +`</h6>`+
                                                    `<p class="text-500 fs--2 mb-0">`+ item.ARTICULO +` | `+item.UND+` | <span class="badge rounded-pill badge-primary">`+item.USUARIO  +`</span></b></p>`+
                                                `</div>`+
                                            `</div>`+
                                        `</td>`;
                                        $.each(data['header_date'], function (i, kar) {
                                            if(kar != 'Ult. Registro.'){
                                                table += `<td> <p class="text-right" style="width: 60px;">`+numeral(item['IN01_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p> </td>`+
                                                    `<td> <p class="text-right" style="width: 60px;">`+numeral(item['OUT02_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p></td>`+
                                                    `<td> <p class="text-right" style="width: 60px;">`+numeral(item['STOCK03_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p></td>`;
                                            }else{
                                                table += `<td> <p class="text-right" style="width: 60px;">`+numeral(item['IN_TODAY']).format('0,0.00')+`</p> </td>`+
                                                `<td> <p class="text-right" style="width: 60px;">`+numeral(item['OUT_TODAY']).format('0,0.00')+`</p></td>`+
                                                `<td> <p class="text-right" style="width: 60px;">`+numeral(item['STOCK_TODAY']).format('0,0.00')+`</p></td>`;
                                            }
                                        });
                                        table += `</tr>`;
                                
                               
                    });
                
                table +=`</tbody>`;

   			$('#tbl_kardex')
   			.empty()
   			.append(table).DataTable({
                "destroy" : true,
                        "info":    false,
                        "lengthMenu": [[15,10,-1], [15,10,"Todo"]],
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
                "scrollY":        "1200px",
                "scrollX":        true,
                "scrollCollapse": true,
                "fixedColumns": {
                    leftColumns: 1,
                    rightColumns: 3
                },
                createdRow: function (row, data, index) {
                    // Obtener la referencia a la tabla DataTable
                    var table = $('#tbl_kardex').DataTable();

                    // Obtener las últimas tres celdas de la fila actual
                    var lastCells = $('td', row).slice(-3);

                    // Agregar la clase CSS personalizada a esas celdas
                    lastCells.addClass('colorTable');
                    lastCells.addClass('encabezadoInv');
                    

                    // Obtener las cabeceras de las últimas tres celdas de la tabla
                    var lastHeaders = $('th', table.table().header()).slice(-3);

                    // Agregar la clase CSS personalizada a esas cabeceras
                    lastHeaders.addClass('colorTable');
                    

                    // Obtener la última cabecera de la tabla (corresponde a las tres ultimas columnas)
                    var lastHeader = $('th:last-child', '#tbl_kardex');

                    // Agregar la clase CSS personalizada a esa cabecera
                    lastHeader.addClass('colorTable');
                    lastHeader.addClass('text-dark');
                }
            });
            $("#tbl_kardex_length").hide();
            $("#tbl_kardex_filter").hide();

            $('#id_txt_buscar').on('keyup', function() {        
                var vTablePedido = $('#tbl_kardex').DataTable();
                vTablePedido.search(this.value).draw();
            });
        }
    });

    
}

function tblResumen(){

    $.ajax({
        url: `getResumenKardex`,
        type: 'get',
        async: true,
        success: function(data) {
            console.log(data);
            $('#table_resumen').DataTable({
                "data":data,
                "destroy" : true,
                "info":    false,
                "lengthMenu": [[5,10,-1], [5,10,"Todo"]],
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
                    { "data": "Product","render": function(data, type, row, meta) {

                        return  `<div class="pe-4 border-sm-end border-200" >
                                    <div class="d-flex align-items-center">
                                    <h6 class="fs-0 text-900 mb-0 me-2">`+row.Product+`</h6>
                                    </div>
                                </div>`

                    }  },
                    { "data": "PT","render": function(data, type, row, meta) {

                        return  `<div class="pe-4 border-sm-end border-200" >
                                    <div class="text-right">
                                    `+numeral(row.PT).format('0,0.00')+`
                                    </div>
                                </div>`

                    } },
                    { "data": "JR","render": function(data, type, row, meta) {

                        return  `<div class="pe-4 border-sm-end border-200" >
                                    <div class="text-right">
                                    `+numeral(row.JR).format('0,0.00')+`
                                    </div>
                                </div>`

                    } },
                    { "data": "MP","render": function(data, type, row, meta) {

                        return  `<div class="pe-4 border-sm-end border-200" >
                                    <div class="text-right">
                                    `+numeral(row.MP).format('0,0.00')+`
                                    </div>
                                </div>`

                    } },
                    { "data": "TE","render": function(data, type, row, meta) {

                        return  `<div class="pe-4 border-sm-end border-200" >
                                    <div class="text-right">
                                    `+numeral(row.TE).format('0,0.00')+`
                                    </div>
                                </div>`

                    } },
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api();
                        var Total  = 0;

                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[^0-9.]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        total = api.column( 4 ).data().reduce( function (a, b){
                            return intVal(a) + intVal(b);
                        }, 0 );

                        for (var i = 0; i < data.length; i++) {
                            Total += intVal(data[i].TE);
                        }
                        
                        $(api.column(3).footer()).html('<h6 class="fs-0 text-900 mb-0 me-2">TOTAL: </h6>');
                        $(api.column(4).footer()).html('<h6 class="text-right">'+numeral(Total).format('0,0.00')+'</h6>');
                    },
                             
            })

            //OCULTA DE LA PANTALLA EL FILTRO DE PAGINADO Y FORM DE BUSQUEDA
            $("#table_resumen_length").hide();
            $("#table_resumen_filter").hide();
            $("#table_resumen_paginate").hide();
        }
    })
}

</script>