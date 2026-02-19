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
          CallFilter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
      });


      var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');



      $('#filtrarFechas').on('click', function() {
          var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
          var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

          CallFilter( desde, hasta );        
      });
      


      $("#id_search_importaciones").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_topsku_clientes').DataTable().search(searchTerm).draw();
      });

      CallFilter( desde, hasta );  


  });


  function CallFilter( desde = null, hasta = null ) {

      $('#tl_periodo').html(`<b>${moment(desde).format('D MMM. YYYY')}</b> al <b>${moment(hasta).format('D MMM. YYYY')}</b>`);
      
      cargarGetProyecto(desde, hasta);
    
  }

  function OnWay() {
    swal.fire({
      title: 'En Construcción',
      text: 'Esta sección está en desarrollo y estará disponible pronto.',
      icon: 'info',
      confirmButtonText: 'Aceptar'
    });
  }

  function eneableButton(EnableButton, textButton) {

      textButton = textButton ?? '<i class="fas fa-filter"></i> Filtrar';

      $('#filtrarFechas').prop('disabled', EnableButton);

      $('#filtrarFechas').html(
          '<i class="fas fa-spinner fa-spin" style="display:' +
          (EnableButton ? 'inline-block' : 'none') +
          '"></i> ' + textButton
      );
  }


    function TBL_TOP_CLIENTES(selector, data) {

      var table = $(selector).DataTable({
        data: data,
        destroy: true,
        paging: true,
        pageLength: 7,
        info: false,
        searching: false,
        ordering: false,
        columns: [
          { data: 'NOMBRE', render: function(data, type, row) {
            return `<div class="item-left">${data}<br><span class="item-sub">${row.CODIGO}</span></div>`;
          }},
          { data: 'VENTA', render: function(data, type, row) {
            return `<div class="item-right">C$ ${numeral(data).format('0,0.00')}<br><span class="item-sub">${numeral(row.CANTIDAD).format('0,0.00')}</span></div>`;
          }},
        ],
        createdRow: function (row, rowData) {
          $(row).on('click', function() {
            var data = table.row(this).data();
            $('#mdl-topsku').modal('show');
            $('#id-name-articulo').text(data.NOMBRE);
            getDetallesCliente(data.CODIGO);
            //excelSku(data.SKU);
            
          });
        },
      });
      $(selector + '_length').hide();
    
    }

    function loadAndBuildTable(selector, data) {
      $(selector).DataTable({
        data: data,
        destroy: true,
        paging: true,
        pageLength: 7,
        info: false,
        searching: false,
        ordering: false,
        columns: [
          { data: 'NOMBRE', render: function(data, type, row) {
            return `<div class="item-left">${data}<br><span class="item-sub">${row.CODIGO}</span></div>`;
          }},
          { data: 'VENTA', render: function(data, type, row) {
            return `<div class="item-right">C$ ${numeral(data).format('0,0.00')}<br><span class="item-sub">${numeral(row.CANTIDAD).format('0,0.00')}</span></div>`;
          }},
        ],
      });
      $(selector + '_length').hide();
    }
    function TBL_TOP_SKU(selector, data) {
      var table = $(selector).DataTable({
        data: data,
        destroy: true,
        paging: true,
        pageLength: 7,
        info: false,
        searching: false,
        ordering: false,
        columns: [
          { data: 'DESCRIPCION', render: function(data, type, row) { return `<div class="item-left">${data}<br><span class="item-sub">${row.SKU}</span></div>`;}},
          { data: 'VENTA', render: function(data, type, row) {
            return `<div class="item-right">
                  C$ ${numeral(data).format('0,0.00')}<br>
                  <span class="item-sub">${numeral(row.CANTIDAD).format('0,0')} Und.</span>
                </div>`;}          
          }
        ],
        createdRow: function (row, rowData) {
          $(row).on('click', function() {
            var data = table.row(this).data();
            $('#mdl-topsku').modal('show');
            $('#id-name-articulo').text(data.DESCRIPCION );
            getDetallesSKUCliente(data.SKU);
            excelSku(data.SKU);
            
          });
        },
      });

    
      $(selector + '_length').hide();
    }

    function TBL_TOP_SKU_CLIENTES(Dt) {        
        // Populate the table with data
        
        $("#tbl_topsku_clientes").DataTable({
            data: Dt,
            destroy: true,
            columnDefs: [
                { targets: 0, visible: false }
            ],
            
            order: [],
            columns: [
                { data: "CLIENTE", title: "" },
                { data: "CLIENTE", title: "CLIENTE" },
                { data: "NOMBRE", title: "NOMBRE" },
                { data: "CANTIDAD", title: "CANT." ,  class: "text-right", render: $.fn.dataTable.render.number(',', '.', 0, '') },
                { data: "VENTA_SIN_IVA", title: "VENTA SIN IVA",   class: "text-right", render: $.fn.dataTable.render.number(',', '.', 0, '') },
                { data: "VENTA_CON_IVA", title: "VENTA CON IVA",  class: "text-right", render: $.fn.dataTable.render.number(',', '.', 0, '') }
            ],
            pageLength: 7,
            bLengthChange: false,
            searching: true,
        });
        
        $("#tbl_topsku_clientes_filter").hide();
    }

    function tbl_grupos(Dt) {        
        // Populate the table with data
        
        $("#table_grupos").DataTable({
            data: Dt,
            destroy: true,
            columnDefs: [
                
            ],
            
            order: [],
            columns: [
                { data: "ARTICULO", title: "ARTICULO" },
                { data: "DESCRIPCION", title: "NOMBRE" },
                { data: "GRUPOS", title: "GRUPOS" },
            ],
            pageLength: 7,
            bLengthChange: false,
            searching: true,
        });
        
        $("#table_grupos_filter").hide();
    }

    function TBL_DETALLES_FACTURAS_CLIENTES(Dt) {        
        // Populate the table with data
        $("#tbl_topsku_clientes").DataTable({
            data: Dt,
            destroy: true,
            order: [],
            columns: [
                { data: "FACTURA", title: "",  class: "text-center",render: function ( data, type, row ) {
                    return `<a id="exp_factura" href="#!"><i class="material-icons expan_more">expand_more</i></a>`;
                }},
                { data: "FACTURA", title: "FACT.",  class: "text-center" },
                { data: "FECHA_FACTURA", title: "FECHA FACT." , class: "text-center",
                    render: function ( data, type, row ) {
                        return moment(data).format('D MMM. YYYY');
                    }
                },
                { data: "CANTIDAD", title: "CANT." ,  class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { data: "VENTA_SIN_IVA", title: "SIN IVA C$.",   class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { data: "VENTA_CON_IVA", title: "CON IVA C$.",  class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') }
            ],
            pageLength: 7,
            bLengthChange: false,
            searching: true,
        });
        
        $("#tbl_topsku_clientes_filter").hide();
    }
    $(document).on('click', '#exp_factura', function(ef) {
      
        var table = $('#tbl_topsku_clientes').DataTable();
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

          console.log(row.child,data.FACTURA);

          format( row.child,  data.FACTURA );
          tr.addClass('shown');
          
          ef.target.innerHTML = "expand_less";
          ef.target.style.background = '#F39200';
          ef.target.style.color = '#e2e2e2';
      }
    });

    function format ( callback, Factura ) {

    
        var thead = tbody = '';            
            thead =`<table id="id_exp_detalles" width='100%'>
                      <thead>                        
                          <tr>
                              <th class="center">ARTICULO</th>
                              <th class="center">DESC.</th>
                              <th class="center">CANT.</th>
                              <th class="center">TOTAL</th>
                          </tr>
                      </thead>


                    <tbody>`;
        $.ajax({
            type: "POST",
            url: "getDetallesFacturasInnova",
            data:{
                FACTURA: Factura,      
            },        
            success: function ( data ) {
                if (data.length==0) {
                    tbody +=`<tr>
                                <td colspan='6'><center>Bodega sin existencia</center></td>
                            </tr>`;
                    callback(thead + tbody).show();
                }
                $.each(data, function (i, item) {
                    tbody +=`<tr >
                                <td class="text-center">` + item['ARTICULO'] + `</td>
                                <td>` + item['DESCRIPCION'] + `</td>
                                <td class="text-right">` + item['CANTIDAD'] + `</td>
                                <td class="text-right">` + item['TOTAL'] + `</td>
                            </tr>`;
                });
                tbody += `</tbody></table>`;
                callback(thead + tbody).show();
            }
        });
    }
    async function getDetallesSKUCliente(articulo) {
      try {

        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
        var Clientes = $('#cmbClientesExcluir').val();


        const response = await fetch('getDetallesSKUCliente', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ 
            desde     : desde, 
            hasta     : hasta,
            articulo  : articulo,
            Clientes  : Clientes
          })
        });
        const result = await response.json();

        TBL_TOP_SKU_CLIENTES(result);
      } catch (error) {
        console.error(error);
      }
    }
    async function getDetallesCliente(CLIENTE) {
      try {

        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
        var ExClu = $('#cmbClientesExcluir').val();


        const response = await fetch('getFacturasClientes', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ 
            desde     : desde, 
            hasta     : hasta,
            CLIENTE   : CLIENTE,
            ExClu     : ExClu
          })
        });

        const result = await response.json();

        TBL_DETALLES_FACTURAS_CLIENTES(result);
      } catch (error) {
        console.error(error);
      }
    }
    async function cargarGetProyecto(desde, hasta){
      
        try {
            eneableButton(true,'Calc...') ;

            var grupo = $('#cmbClientesExcluir').val();
            
            const response = await fetch('dtProyect', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                  desde: desde, 
                  hasta: hasta,
                  grupo: grupo

                })
            });

            const result = await response.json();
            loadAndBuildTable('#clientesTable', result.CLIENTES);            
            loadAndBuildTable('#vendedoresTable', result.VENDEDORESHOY);
            loadAndBuildTable('#vendedoresRangoTable', result.VENDEDORES);
            TBL_TOP_SKU('#tbl_top_sku', result.SKU_CHART);
            TBL_TOP_CLIENTES('#tbl_top_clientes', result.CLS_CHART);
            renderSKUPieChart(result.SKU_CHART);
            renderClienteBolsonChart(result.CLS_CHART);
            tbl_grupos(result.GRUPOS)
            console.log(result.GRUPOS)

            let datos = [];
            let totales = [];
            
            
            $('#fechaClienteFact').text(moment(result.HASTA).format('D MMM. YYYY'));
            $('#fechaVentaVendedor').text(moment(result.HASTA ).format('D MMM. YYYY'));
            $('#fechaRangoVentaVendedor').text(moment(result.DESDE).format('D MMM. YYYY') + ' al ' + moment(result.HASTA).format('D MMM. YYYY'),);
            $('#fechaSKU').text( moment(result.DESDE).format('D MMM. YYYY') + ' al ' + moment(result.HASTA).format('D MMM. YYYY'),); //result.ACTUAL.DESDE + ' al ' + result.ACTUAL.HASTA);
            $('#fechaVentaNeta').text( moment(result.DESDE).format('D MMM. YYYY') + ' al ' + moment(result.HASTA).format('D MMM. YYYY'),); // result.ACTUAL.DESDE + ' al ' + result.ACTUAL.HASTA);

            $("#anioAnterior").text(new Date().getFullYear() - 1);
            $("#anioActual").text(new Date().getFullYear());


            //Declarar la variable como global
            window.datos = datos;
            window.totales = totales;

            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }

    //Funcion para actualizar el grafico ytd segun el tipo que se escoja 
    function actualizarGraficoYTD() {
      const tipo = document.getElementById("tipoDato").value;
      
      //Las variable se actualizaran en funcion del tipo de dato que se necesite
      $('#ytd_anterior').text(tipo === 'valor' ? 'C$ '+ numeral(window.totales.YTD_VALOR_ANTERIOR).format('0,0.00') : numeral(window.totales.YTD_UND_ANTERIOR).format('0,0'));
      $('#ytd_actual').text(tipo === 'valor' ? 'C$ '+ numeral(window.totales.YTD_VALOR_ACTUAL).format('0,0.00') : numeral(window.totales.YTD_UND_ACTUAL).format('0,0'));
      $('#ytd_crecimiento').text(tipo === 'valor' ? numeral(window.totales.YTD_VALOR_CRECIMIENTO).format('0,0.00') : numeral(window.totales.YTD_UND_CRECIMIENTO).format('0,0.00'));
      
      //Llamada a la funcion que se encuentra en js_chart_YTD.blade
      renderComparativaYTD(window.datos, tipo);
    }

    function excelSku(articulo){

      $("#exp-to-excel").click(function() {    
        const desde = $('#desdeInnova').val();
        const hasta = $('#hastaInnova').val();
        location.href = "getExcelSku?desde=" + encodeURIComponent(desde) + "&hasta=" + encodeURIComponent(hasta) + "&articulo=" + encodeURIComponent(articulo);
      })
    }

    document.addEventListener('DOMContentLoaded', async () => {
      const hoyDesde = new Date().toISOString().split('T')[0]; 
      $('#desdeInnova').val(hoyDesde);
      const hoyHasta = new Date().toISOString().split('T')[0]; 
      $('#hastaInnova').val(hoyHasta);

      cargarGetProyecto(hoyDesde, hoyHasta);
        
    });


</script>