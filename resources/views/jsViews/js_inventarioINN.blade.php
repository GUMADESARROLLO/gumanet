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
                                table += `<th colspan="3" style="text-align:center;" width="10px">`+moment(item).format('DD-MMM-YYYY')+`</th>`;                                
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
                                                    `<p class="text-500 fs--2 mb-0">`+ item.ARTICULO +` | `+item.UND+` | <b>`+item.USUARIO  +`</b></p>`+
                                                `</div>`+
                                            `</div>`+
                                        `</td>`;
                                        $.each(data['header_date'], function (i, kar) {
                                            table += `<td> <p class="text-right" style="width: 60px;">`+numeral(item['IN01_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p> </td>`+
                                                `<td> <p class="text-right" style="width: 60px;">`+numeral(item['OUT02_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p></td>`+
                                                `<td> <p class="text-right" style="width: 60px;">`+numeral(item['STOCK03_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p></td>`;
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

</script>