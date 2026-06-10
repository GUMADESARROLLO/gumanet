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
                    { title: 'RUTA', data: 'RUTA' },
                    { title: 'VENDEDOR', data: 'NOMBRE', className: 'text-center' },
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
                    { title: 'PEDIDO', data: 'PEDIDO', render: function(data) {
                        return '<a href="javascript:void(0)" class="link-pedido" data-pedido="' + data + '">' + data + '</a>';
                    } },
                    { title: 'FECHA PEDIDO', data: 'FECHAC_PEDIDO', className: 'text-center', render: function(data) {
                        var d = new Date(data);
                        var day = ('0' + d.getDate()).slice(-2);
                        var month = ('0' + (d.getMonth() + 1)).slice(-2);
                        var year = d.getFullYear();
                        return day + '-' + month + '-' + year;
                    } },
                    { title: 'TOTAL FACTURADO', data: 'TOTAL_A_FACTURAR', className: 'text-right', render: function(data) {
                        return Number(data).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    } }
                ],
                searching: false,
                lengthChange: false,
                pageLength: 10,
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' }
            });

            $('#tbl_pedidos_facturados').on('click', '.link-pedido', function() {
                var pedido = $(this).data('pedido');
                $('#mdl-detalle-pedido-factura-title').text('PEDIDO: ' + pedido);
                $('#tbl-detalle-pedido-factura-body').html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>');
                $('#mdl-detalle-pedido-factura').modal('show');

                fetch('getDetallePedidoFactura', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pedido: pedido })
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    var html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="6" class="text-center">No se encontraron registros</td></tr>';
                    } else {
                        data.forEach(function(row) {
                            var horas = Math.floor(row.TIEMPO_MINUTOS / 60);
                            var minutos = row.TIEMPO_MINUTOS % 60;
                            var tiempo = ('0' + horas).slice(-2) + ':' + ('0' + minutos).slice(-2);
                            var total = Number(row.TOTAL_FACTURA).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            html += '<tr>' +
                                '<td>' + row.CLIENTE + '</td>' +
                                '<td>' + row.NOMBRE_CLIENTE + '</td>' +
                                '<td>' + row.PEDIDO + '</td>' +
                                '<td>' + row.FACTURA + '</td>' +
                                '<td class="text-center">' + tiempo + '</td>' +
                                '<td class="text-right">C$ ' + total + '</td>' +
                            '</tr>';
                        });
                    }
                    $('#tbl-detalle-pedido-factura-body').html(html);
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    $('#tbl-detalle-pedido-factura-body').html('<tr><td colspan="6" class="text-center text-danger">Error al cargar los datos</td></tr>');
                });
            });

            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }
  


</script>