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
              'Este Mes': [moment().startOf('month'), moment()],
              'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
              "3 Meses": [moment().subtract(3, 'month'), moment()],
              "6 Meses": [moment().subtract(6, 'month'), moment()],
              
              '1 Año': [moment().subtract(1, 'year'), moment()],
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
          CallFilter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
      });


      var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');



      $('#filtrarFechas').on('click', function() {
          var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
          var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

          CallFilter( desde, hasta );        
      });
      
      CallFilter( desde, hasta );  


      $('#txt_busqueda_orden_compra').on('keyup', function() {   
          var vTableArticulos = $('#tbl_ordenes_compras').DataTable();     
          vTableArticulos.search(this.value).draw();
      });


  });


  function CallFilter( desde = null, hasta = null ) {

      $('#tl_periodo').html(`<b>${moment(desde).format('D MMM. YYYY')}</b> al <b>${moment(hasta).format('D MMM. YYYY')}</b>`);
      
      GetData(desde, hasta);
    
  }

  function eneableButton(EnableButton, textButton = '<i class="fas fa-filter"></i> Filtrar') {
    $('#filtrarFechas').prop('disabled', EnableButton);
    $('#filtrarFechas').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
  }

  

    
    function TablaOrdenesCompras(selector, data) {
      var table = $(selector).DataTable({
        data: data.ORDEN_COMPRA,
        destroy: true,
        paging: true,
        pageLength: 17,
        info: false,
        searching: true,
        ordering: true,
        columns: [
          { title : 'FECHA ORDEN', data: 'FECHA', className: 'text-center'},
          { title: 'ORDEN COMPRA', data: 'ORDEN_COMPRA', className: 'text-center', render: function(data, type, row) {
              return `<strong><a href=OrdenCompraDetalle/${data} target='_blank'>${data}</a></strong>`;
          }},
          { title: 'EMBARQUE', data: 'ORD_EMBARQ', className: 'text-center',render: function(data, type, row) {
              return `<div class="item-center"><strong>${data}</strong></div>`;
            }          
          },
          { title: 'LIQUIDACION', data: 'ORD_LIQUID', className: 'text-center',render: function(data, type, row) {
              return `<div class="item-center"><strong>${data}</strong></div>`;
            }          
          },
          { title: 'ESTADO', data: 'ESTADO', className: 'text-center',render: function(data, type, row) {
              return `<div class="item-center"><strong>${data}</strong></div>`;
            }          
          },
          { title: 'PRIORIDAD', data: 'PRIORIDAD',className: 'text-center', render: function(data, type, row) {
              return `<div class="item-center"><strong>${data}</strong></div>`;
            }          
          },
          { title: 'PROVEEDOR', data: 'PROVEEDOR', className: 'text-left', render: function(data, type, row) {
              return `<div class="item-left"> ${data} </div>`;
            }          
          },
          { title: 'NOMBRE PROVEEDOR', data: 'NOMBRE_PROVEEDOR', className: 'text-left', render: function(data, type, row) {
              return `<div class="item-left"> ${data} </div>`;
            }          
          },
          { title : 'FECHA COTIZACION', data: 'FECHA_COTIZACION', className: 'text-center'},
          { title : 'FECHA OFRECIDA', data: 'FECHA_OFRECIDA', className: 'text-center'},
          { title : 'FECHA REQUERIDA', data: 'FECHA_REQUERIDA', className: 'text-center'},
          { title : 'FECHA REQ. EMBARQUE', data: 'FECHA_REQ_EMBARQUE', className: 'text-center'},
          { title: 'TOTAL $', data: 'TOTAL_A_COMPRAR', render: function(data, type, row) {
              return `<div class="item-right">${numeral(data).format('0,0.00')} </div>`;
            }          
          },

        ],
        createdRow: function (row, rowData) {
          $(row).on('click', function() {

            
          });
        },
      });


      $("#total_ordenes").html(`C$. ${numeral(data.TOTAL_ORDENES).format('0,0.00')}`);

    
      $(selector + '_length').hide();
      $(selector + '_filter').hide();
    }


    async function GetData(desde, hasta){
      
        try {
            eneableButton(true,'Calc...') ;

            
            const response = await fetch('getDataOrdenesCompra', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                  desde: desde, 
                  hasta: hasta

                })
            });

            const result = await response.json();

            TablaOrdenesCompras('#tbl_ordenes_compras', result);


            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }
</script>