<script type="text/javascript">
onload = tblKardex();

function tblKardex(articulo, unidad) {
    var date = new Date();

    var primerDia = new Date(date.getFullYear(), date.getMonth() + 1, 1);
    var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    $.ajax({
        url: `getKerdex`,
        type: 'get',
        data: { 
            ini : primerDia, 
            end : ultimoDia 
        },
        async: true,
        success: function(data) { console.log(data);
            table =  `<thead>`+
                        `<tr class="bg-blue text-light">`+
                            `<th style="width: 700px;" rowspan="2">ARTICULO</th>`;
                            $.each(data['header_date'], function (i, item) {
                                table += `<th colspan="3" style="text-align:center">`+moment(item).format('DD-MM-YYYY')+`</th>`;
                            })
                        
                table +=`</tr><tr>`;                        
                        $.each(data['header_date'], function (i, item) {
                            table += `<th>Entrada</th>`+
                                    `<th>Salida</th>`+
                                    `<th>Saldo</th>`;
                        })
                    
                table +=`</tr></thead>`+
                    `<tbody>`;
                        $.each(data['header_date_rows'], function (i, item) {
                            table +=`<tr>`+
                                        `<td style="width: 700px; ">`+
                                            `<div class="d-flex position-relative">`+
                                                `<div class="flex-1" style="width: 400px; ">`+
                                                    `<h6 class="mb-0 fw-semi-bold">`+ item.DESCRIPCION +`</h6>`+
                                                    `<p class="text-500 fs--2 mb-0">`+ item.ARTICULO +` | UND</p>`+
                                                `</div>`+
                                            `</div>`+
                                        `</td>`;
                                        $.each(data['header_date'], function (i, kar) {
                                            table += `<td> <p class="text-right">`+numeral(item['IN01_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p> </td>`+
                                                `<td> <p class="text-right">`+numeral(item['OUT02_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p></td>`+
                                                `<td> <p class="text-right">`+numeral(item['STOCK03_'+moment(kar).format('YYYYMMDD')]).format('0,0.00')+`</p></td>`;
                                        });
                                table += `</tr>`;
                                
                                table += `</tr>`;
                    });
                
                table +=`</tbody>`;

   			$('#tbl_kardex')
   			.empty()
   			.append(table).DataTable({
                "destroy": true,
                "info": false,
                "bPaginate": true,
                "lengthMenu": [
                    [5,20, -1],
                    [5,20, "Todo"]
                ],
                "scrollY":        "900px",
                "scrollX":        true,
                "scrollCollapse": true,
                "paging":         true,
                "fixedColumns": {
                    leftColumns: 1,
                    rightColumns: 3
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
                createdRow: function (row, data, index) {
                    // Obtener la referencia a la tabla DataTable
                    var table = $('#tbl_kardex').DataTable();

                    // Obtener las últimas tres celdas de la fila actual
                    var lastCells = $('td', row).slice(-3);

                    // Agregar la clase CSS personalizada a esas celdas
                    lastCells.addClass('bg-success');

                    // Obtener las cabeceras de las últimas tres celdas de la tabla
                    var lastHeaders = $('th', table.table().header()).slice(-3);

                    // Agregar la clase CSS personalizada a esas cabeceras
                    lastHeaders.addClass('bg-success');

                    // Obtener la última cabecera de la tabla (corresponde a las tres ultimas columnas)
                    var lastHeader = $('th:last-child', '#tbl_kardex');

                    // Agregar la clase CSS personalizada a esa cabecera
                    lastHeader.addClass('bg-success');
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