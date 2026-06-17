  <script>
  $(document).ready(function() {
      //inicializaControlFecha();
      fullScreen();

      $('input[name="dt_range"]').daterangepicker({
          "autoApply": true,
            ranges: {
              'Hoy': [moment(), moment()],
              'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
              'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
              
              'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
              'Semana Anterior': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
              
              'Este Mes': [moment().startOf('month'), moment()],
              'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
              
              //'1 Año': [moment().subtract(1, 'year'), moment()],
              // '2 Años': [moment().subtract(2, 'year'), moment()],
              // '3 Años': [moment().subtract(3, 'year'), moment()]
            },
          "showCustomRangeLabel": false,
          "alwaysShowCalendars": true,
          "startDate": moment().startOf('month').format('D MMM. YYYY'),
          "endDate": moment().format('D MMM. YYYY'),
          opens: 'left',
          locale: {
              //format: "DD/MM/YYYY",
              format: "D MMM. YYYY",   // Ejemplo: 1 ago. 2025
              separator: " - ",
              applyLabel: "Aplicar",
              cancelLabel: "Cancelar",
              fromLabel: "Desde",
              toLabel: "Hasta",
              customRangeLabel: "Personalizado",
              weekLabel: "S",
              daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
              monthNames: [
                  "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
              ],
              firstDay: 1
          }
      }, function(start, end, label) {
          //console.log('Nuevo rango seleccionado: ' + start.format('YYYY-MM-DD') + ' a ' + end.format('YYYY-MM-DD') + ' (rango: ' + label + ')');
          Filter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
      });


      var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');



      $('#filtrarFechas').on('click', function() {
          var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
          var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

          Filter( desde, hasta );        
      });
      


      $("#id_search_importaciones").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_topsku_clientes').DataTable().search(searchTerm).draw();
      });

      Filter( desde, hasta );  


  });


  function Filter( desde = null, hasta = null ) {

      $('#tl_periodo').html(`<b>${moment(desde).format('D MMM. YYYY')}</b> al <b>${moment(hasta).format('D MMM. YYYY')}</b>`);
      
      GetData(desde, hasta);
    
  }
  
    function abrirModalFacturasVendedor(ruta, nombre) {
        $('#mdl-facturas-vendedor-title').text('' + nombre);

        var table = $('#tbl-facturas-vendedor');
        if ($.fn.DataTable.isDataTable('#tbl-facturas-vendedor')) {
            table.DataTable().destroy();
        }
        table.empty();

        table.DataTable({
            data: [],
            columns: [
                { title: '', data: 'DETALLE', className: 'text-center bg-white text-dark', orderable: false },
                { title: 'FACTURA', data: 'FACTURA', className: 'text-center bg-white text-dark' },
                { title: 'FECHA', data: 'FECHA', className: 'text-center', render: function(data) {
                    if (!data) return '';
                    var d = new Date(data);
                    var day = ('0' + d.getDate()).slice(-2);
                    var month = ('0' + (d.getMonth() + 1)).slice(-2);
                    var year = d.getFullYear();
                    return day + '-' + month + '-' + year;
                } },
                { title: 'COD. CLIENTE', data: 'COD_CLIENTE', className: 'text-center' },
                { title: 'NOMBRE CLIENTE', data: 'NOMBRE_CLIENTE' },
                { title: 'TOTAL', data: 'TOTAL_FACTURA', className: 'text-right', render: function(data) {
                    return 'C$ ' + Number(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                } }
            ],
            searching: false,
            lengthChange: false,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                zeroRecords: 'Cargando...'
            },
            destroy: true,
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var total = api.column(5).data().reduce(function(sum, val) {
                    return sum + parseFloat(val || 0);
                }, 0);
                $(api.column(5).footer()).html('C$ ' + fmtNum(total));
            }
        });

        $('#mdl-facturas-vendedor').modal('show');

        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

        fetch('getFacturasVendedor', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                desde: desde,
                hasta: hasta,
                vendedor: ruta
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            var rows = data.map(function(row) {
                return {
                    DETALLE: '<a href="javascript:void(0)" class="exp-factura" data-factura="' + row.FACTURA + '"><i class="material-icons" style="background:#e2e2e2;color:#007bff;border-radius:50%;padding:4px;font-size:20px;cursor:pointer">expand_more</i></a>',
                    FACTURA: row.FACTURA,
                    FECHA: row.FECHA,
                    COD_CLIENTE: row.COD_CLIENTE,
                    NOMBRE_CLIENTE: row.NOMBRE_CLIENTE,
                    TOTAL_FACTURA: row.TOTAL_FACTURA
                };
            });
            var dt = $('#tbl-facturas-vendedor').DataTable();
            dt.clear();
            dt.rows.add(rows);
            dt.draw();
        })
        .catch(function(error) {
            console.error('Error:', error);
        });
    }

    // Click en RUTA para abrir modal de facturas por vendedor
    $(document).on('click', '#tbl_vendedores .link-ruta', function() {
        var ruta = $(this).data('ruta');
        var nombre = $(this).data('nombre');
        abrirModalFacturasVendedor(ruta, nombre);
    });

    function fmtNum(n) {
        return Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function abrirDetalleProductos(tipo, valor, nombre, fecha) {
        var titulo = (tipo === 'pedido') ? 'PEDIDO: ' + valor : 'FACTURA: ' + valor;
        titulo += '<br>CLIENTE: ' + nombre + '<br>FECHA: ' + fecha;
        var endpoint = (tipo === 'pedido') ? 'getDetallePedidoProductos' : 'getDetalleFacturaProductos';
        var columnas = (tipo === 'pedido')
            ? [
                { title: 'ARTICULO', data: 'ARTICULO', className: 'text-center bg-white text-dark' },
                { title: 'DESCRIPCION', data: 'DESCRIPCION' },
                { title: 'CANTIDAD PEDIDA', data: 'CANTIDAD', className: 'text-right', render: function(d) { return fmtNum(d); } },
                { title: 'PRECIO UNITARIO', data: 'PRECIO_UNITARIO', className: 'text-right', render: function(d) { return 'C$ ' + fmtNum(d); } },
                { title: 'TOTAL', data: 'PRECIO_TOTAL', className: 'text-right', render: function(d) { return 'C$ ' + fmtNum(d); } }
            ]
            : [
                { title: 'ARTICULO', data: 'ARTICULO', className: 'text-center bg-white text-dark' },
                { title: 'DESCRIPCION', data: 'DESCRIPCION' },
                { title: 'CANTIDAD', data: 'CANTIDAD', className: 'text-right', render: function(d) { return fmtNum(d); } },
                { title: 'PRECIO UNITARIO', data: 'PRECIO_UNITARIO', className: 'text-right', render: function(d) { return 'C$ ' + fmtNum(d); } },
                { title: 'PRECIO TOTAL', data: 'PRECIO_TOTAL', className: 'text-right', render: function(d) { return 'C$ ' + fmtNum(d); } }
            ];

        $('#mdl-detalle-pedido-factura-title').html(titulo);
        $('#mdl-detalle-pedido-factura').modal('show');

        var table = $('#tbl-detalle-pedido-factura');
        if ($.fn.DataTable.isDataTable('#tbl-detalle-pedido-factura')) {
            var dt = table.DataTable();
            dt.clear();
            dt.rows.add([]);
            dt.draw();
            dt.destroy();
        }

        table.DataTable({
            data: [],
            columns: columnas,
            searching: false,
            lengthChange: false,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                zeroRecords: 'Cargando...'
            },
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var total = api.column(4).data().reduce(function(sum, val) {
                    return sum + parseFloat(val || 0);
                }, 0);
                $(api.column(4).footer()).html('C$ ' + fmtNum(total));
            }
        });

        var bodyData = {};
        bodyData[tipo === 'pedido' ? 'pedido' : 'factura'] = valor;

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(bodyData)
        })
        .then(function(response) {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(function(data) {
            var dt = $('#tbl-detalle-pedido-factura').DataTable();
            dt.clear();
            dt.rows.add(data);
            dt.draw();
        })
        .catch(function(error) {
            console.error('Error:', error);
            var dt = $('#tbl-detalle-pedido-factura').DataTable();
            dt.clear();
            dt.draw();
        });
    }

    // Click en PEDIDO
    $(document).on('click', '#tbl_pedidos_facturados .link-pedido', function() {
        var pedido = $(this).data('pedido');
        var nombre = $(this).data('nombre');
        var fecha = $(this).data('fecha');
        abrirDetalleProductos('pedido', pedido, nombre, fecha);
    });

    // Click en FACTURA
    $(document).on('click', '#tbl_pedidos_facturados .link-factura', function() {
        var factura = $(this).data('factura');
        var nombre = $(this).data('nombre');
        var fecha = $(this).data('fecha');
        abrirDetalleProductos('factura', factura, nombre, fecha);
    });

    // Expandir/colapsar detalle de factura
    $(document).on('click', '.exp-factura', function(ef) {
        var table = $('#tbl-facturas-vendedor').DataTable();
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var factura = $(this).data('factura');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            $(this).find('.material-icons').text('expand_more').css({ background: '#e2e2e2', color: '#007bff' });
        } else {
            table.rows().eq(0).each(function(idx) {
                var r = table.row(idx);
                if (r.child.isShown()) {
                    r.child.hide();
                    var c = $(r.node()).find('.exp-factura .material-icons');
                    c.text('expand_more').css({ background: '#e2e2e2', color: '#007bff' });
                }
            });

            var thead = '<table class="table table-striped table-bordered table-sm" width="100%">' +
                '<thead><tr>' +
                '<th class="text-center">ARTICULO</th>' +
                '<th class="text-center">DESCRIPCION</th>' +
                '<th class="text-center">CANTIDAD</th>' +
                '<th class="text-center">PRECIO UNITARIO</th>' +
                '<th class="text-center">PRECIO TOTAL</th>' +
                '</tr></thead><tbody>';

            $.ajax({
                type: "POST",
                url: "getDetFactVenta",
                data: { factura: factura },
                success: function(resp) {
                    var tbody = '';
                    if (resp.objDt && resp.objDt.length > 0) {
                        resp.objDt.forEach(function(item) {
                            tbody += '<tr>' +
                                '<td class="text-center">' + item.ARTICULO + '</td>' +
                                '<td>' + item.DESCRIPCION + '</td>' +
                                '<td class="text-right">' + numeral(item.CANTIDAD).format('0,0.00') + '</td>' +
                                '<td class="text-right">C$ ' + numeral(item.PRECIO_UNITARIO).format('0,0.00') + '</td>' +
                                '<td class="text-right">C$ ' + numeral(item.PRECIO_TOTAL).format('0,0.00') + '</td>' +
                                '</tr>';
                        });
                    } else {
                        tbody = '<tr><td colspan="5" class="text-center">Sin detalle</td></tr>';
                    }
                    tbody += '</tbody></table>';
                    row.child(thead + tbody).show();
                    tr.addClass('shown');
                    $(ef.currentTarget).find('.material-icons').text('expand_less').css({ background: '#ff5252', color: '#e2e2e2' });
                },
                error: function() {
                    var tbody = '<tr><td colspan="5" class="text-center text-danger">Error al cargar detalle</td></tr></tbody></table>';
                    row.child(thead + tbody).show();
                    tr.addClass('shown');
                    $(ef.currentTarget).find('.material-icons').text('expand_less').css({ background: '#ff5252', color: '#e2e2e2' });
                }
            });
        }
    });

    function eneableButton(EnableButton, textButton = '<i class="fas fa-filter"></i> Filtrar') {
    $('#filtrarFechas').prop('disabled', EnableButton);
    $('#filtrarFechas').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
  }


    async function GetData(desde, hasta){
      
        try {
            eneableButton(true,'Calc...') ;
            
            const response = await fetch('getDataFacturacion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                  desde: desde, 
                  hasta: hasta,

                })
            });

            const result = await response.json();

            renderClienteBolsonChart(result.FACTURACION);

            let datos = [];
            let totales = [];

            //Declarar la variable como global
            window.datos = datos;
            window.totales = totales;

            // Tabla de Vendedores
            if ($.fn.DataTable.isDataTable('#tbl_vendedores')) {
                $('#tbl_vendedores').DataTable().destroy();
            }
            $('#tbl_vendedores').DataTable({
                data: result.VENDEDORES,
                columns: [
                { title: 'RUTA', data: 'RUTA', className: 'text-center bg-white text-dark', render: function(data, type, row) {
                    return '<a href="javascript:void(0)" class="link-ruta" data-ruta="' + data + '" data-nombre="' + row.NOMBRE + '">' + data + '</a>';
                } },
                    { title: 'VENDEDOR', data: 'NOMBRE' },
                    { title: 'CANT. PEDIDOS', data: 'CANTIDAD_PEDIDOS', className: 'text-right' }
                ],
                searching: false,
                lengthChange: false,
                pageLength: 10,
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' }
            });

            // Tabla de Pedidos Facturados
            if ($.fn.DataTable.isDataTable('#tbl_pedidos_facturados')) {
                $('#tbl_pedidos_facturados').DataTable().destroy();
            }
            $('#tbl_pedidos_facturados').DataTable({
                data: result.PEDIDOS_FACTURADOS,
                columns: [
                    { title: 'PEDIDO', data: 'PEDIDO', className: 'text-center bg-white text-dark', render: function(data, type, row) {
                        var d = new Date(row.FECHAC_PEDIDO);
                        var day = ('0' + d.getDate()).slice(-2);
                        var month = ('0' + (d.getMonth() + 1)).slice(-2);
                        var year = d.getFullYear();
                        var fecha = day + '-' + month + '-' + year;
                        return '<a href="javascript:void(0)" class="link-pedido" data-pedido="' + data + '" data-nombre="' + row.NOMBRE_CLIENTE + '" data-fecha="' + fecha + '">' + data + '</a>';
                    } },
                    { title: 'FACTURA', data: 'FACTURA', className: 'text-center bg-white text-dark', render: function(data, type, row) {
                        var d = new Date(row.FECHA_FACTURA);
                        var day = ('0' + d.getDate()).slice(-2);
                        var month = ('0' + (d.getMonth() + 1)).slice(-2);
                        var year = d.getFullYear();
                        var fecha = day + '-' + month + '-' + year;
                        return '<a href="javascript:void(0)" class="link-factura" data-factura="' + data + '" data-nombre="' + row.NOMBRE_CLIENTE + '" data-fecha="' + fecha + '">' + data + '</a>';
                    } },
                    { title: 'COD. CLIENTE', data: 'COD_CLIENTE', className: 'text-center bg-white text-dark' },
                    { title: 'NOMBRE CLIENTE', data: 'NOMBRE_CLIENTE', className: 'bg-white text-dark' },
                    { title: 'FECHA PEDIDO', data: 'FECHAC_PEDIDO', className: 'text-center bg-white text-dark', render: function(data) {
                        var d = new Date(data);
                        var day = ('0' + d.getDate()).slice(-2);
                        var month = ('0' + (d.getMonth() + 1)).slice(-2);
                        var year = d.getFullYear();
                        return day + '-' + month + '-' + year;
                    } },
                    { title: 'FECHA FACTURA', data: 'FECHA_FACTURA', className: 'text-center bg-white text-dark', render: function(data) {
                        var d = new Date(data);
                        var day = ('0' + d.getDate()).slice(-2);
                        var month = ('0' + (d.getMonth() + 1)).slice(-2);
                        var year = d.getFullYear();
                        return day + '-' + month + '-' + year;
                    } },
                    { title: 'TIEMPO', data: 'TIEMPO_MINUTOS', className: 'text-center bg-white text-dark', render: function(data) {
                        var horas = Math.floor(data / 60);
                        var minutos = data % 60;
                        return ('0' + horas).slice(-2) + ':' + ('0' + minutos).slice(-2);
                    } },
                    { title: 'TOTAL FACTURA C$', data: 'TOTAL_FACTURA', className: 'text-right bg-white text-dark', render: function(data) {
                        return 'C$ ' + Number(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    } }
                ],
                searching: false,
                lengthChange: false,
                pageLength: 10,
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' }
            });

            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }
  


</script>