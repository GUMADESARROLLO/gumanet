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

            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }

    

  


</script>