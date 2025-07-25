   <script>
    function OnWay() {
      swal.fire({
        title: 'En Construcción',
        text: 'Esta sección está en desarrollo y estará disponible pronto.',
        icon: 'info',
        confirmButtonText: 'Aceptar'
      });
    }

    $(document).ready(function() {
      fullScreen();
      
      inicializaControlFecha();

  });

    function formatRow(rowData) {
      return `
        <div class="item-left">
          ${rowData.NOMBRE}<br>
          <span class="item-sub">${rowData.CODIGO}</span>
        </div>
        <div class="item-right">
          ${rowData.BULTOS_TOTAL_NIO}<br>
          <span class="item-sub">${rowData.BULTOS_TOTAL_UND}</span>
        </div>
      `;
    }

    function loadAndBuildTable(selector, data) {
      $(selector).DataTable({
        data: data,
        destroy: true,
        paging: true,
        pageLength: 5,
        info: false,
        searching: false,
        ordering: false,
        columns: [
          { data: 'NOMBRE', render: function(data, type, row) {
              return `<div class="item-left">${data}<br><span class="item-sub">${row.CODIGO}</span></div>`;
            }
          },
          { data: 'BULTOS_TOTAL_NIO', render: function(data, type, row) {
            return `<div class="item-right">${data}<br><span class="item-sub">${row.BULTOS_TOTAL_UND}</span></div>`;
          }
          }
          
        
        ],
      });
      $(selector + '_length').hide();
    }
    function Tbl_TopSKU(selector, data) {
      var table = $(selector).DataTable({
        data: data,
        destroy: true,
        paging: true,
        pageLength: 5,
        info: false,
        searching: false,
        ordering: false,
        columns: [
          { 
            data: 'DESCRIPCION', render: function(data, type, row) { return `<div class="item-left">${data}<br><span class="item-sub">${row.SKU}</span></div>`;}
          },
          { data: 'BULTOS_TOTAL_NIO', render: function(data, type, row) {
              return `<div class="item-right">
                    C$ ${numeral(data).format('0,0.00')}<br>
                    <span class="item-sub">${numeral(row.BULTOS_TOTAL_UND).format('0,0')} Bls.</span>
                  </div>`;
            }          
          },
          { data: 'PESO', render: function(data, type, row){
              return `<div class="item-right">${numeral(data).format('0,0.00')} %</div>`;
            }          
          }
        ],
        createdRow: function (row, rowData) {
          
        }
      });

    
      $(selector + '_length').hide();
    }

    async function cargarGetDataInnova(desde, hasta){
        try {
            const response = await fetch('getDataInnova', {
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

            loadAndBuildTable('#clientesTable', result.ACTUAL.Clientes);            
            loadAndBuildTable('#vendedoresTable', result.ACTUAL.Vendedores);
            Tbl_TopSKU('#tbl_top_sku', result.ACTUAL.SKU_CHART.data);
            loadAndBuildTable('#tbl_top_clientes', result.ACTUAL.CLS_CHART);
            renderSKUPieChart(result.ACTUAL.SKU_CHART.data);
            renderClienteBolsonChart(result.ACTUAL.CLS_CHART);

            let datos = [];
            let totales = [];
            
            datos = result.COMPARATIVAYTD.COMPARATIVA_YTD;
            totales = result.COMPARATIVAYTD;

            renderComparativaYTD(datos, 'valor');

            $('#bultos_facturacion').text(result.ACTUAL.Metricas.BULTOS_TOTAL_NIO);
            $('#bultos_valor').text("C$. " + result.ACTUAL.Metricas.BULTOS_TOTAL_UND);
            $('#bultos_anterior').text(result.COMPARATIVA.UND_YTD.BULTOS_UND_ANIO_ACTUAL);
            $('#bultos_actual').text(result.COMPARATIVA.UND_YTD.BULTOS_UND_ANIO_ANTERIOR);
            $('#fechaClienteFact').text(result.ACTUAL.DESDE + ' al ' + result.ACTUAL.HASTA);
            $('#fechaVentaVendedor').text(result.ACTUAL.DESDE + ' al ' + result.ACTUAL.HASTA);
            $('#fechaSKU').text(result.ACTUAL.DESDE + ' al ' + result.ACTUAL.HASTA);
            $('#fechaVentaNeta').text(result.ACTUAL.DESDE + ' al ' + result.ACTUAL.HASTA);

            $('#ytd_anterior').text(numeral(result.COMPARATIVAYTD.YTD_VALOR_ANTERIOR).format('0,0.00'));
            $('#ytd_actual').text(numeral(result.COMPARATIVAYTD.YTD_VALOR_ACTUAL).format('0,0.00'));
            $('#ytd_crecimiento').text(numeral(result.COMPARATIVAYTD.YTD_VALOR_CRECIMIENTO).format('0,0.00'));

            $("#anioAnterior").text(new Date().getFullYear() - 1);
            $("#anioActual").text(new Date().getFullYear());
            $("#total_sku_bultos").text(result.ACTUAL.SKU_CHART.Totals.Bultos + " Bls.");
            $("#total_sku_valor").text("C$. " + result.ACTUAL.SKU_CHART.Totals.Valor);
            $("#total_Cliente_bultos").text(result.ACTUAL.SKU_CHART.Totals.Bultos + " Bls.");
            $("#total_Cliente_valor").text("C$. " + result.ACTUAL.SKU_CHART.Totals.Valor);


            //Declarar la variable como global
            window.datos = datos;
            window.totales = totales;

        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    }

    //Funcion para actualizar el grafico ytd segun el tipo que se escoja 
    function actualizarGraficoYTD() {
      const tipo = document.getElementById("tipoDato").value;
      
      //Las variable se actualizaran en funcion del tipo de dato que se necesite
      $('#ytd_anterior').text(tipo === 'valor' ? numeral(window.totales.YTD_VALOR_ANTERIOR).format('0,0.00') : numeral(window.totales.YTD_UND_ANTERIOR).format('0,0'));
      $('#ytd_actual').text(tipo === 'valor' ? numeral(window.totales.YTD_VALOR_ACTUAL).format('0,0.00') : numeral(window.totales.YTD_UND_ACTUAL).format('0,0'));
      $('#ytd_crecimiento').text(tipo === 'valor' ? numeral(window.totales.YTD_VALOR_CRECIMIENTO).format('0,0.00') : numeral(window.totales.YTD_UND_CRECIMIENTO).format('0,0.00'));
      
      //Llamada a la funcion que se encuentra en js_chart_YTD.blade
      renderComparativaYTD(window.datos, tipo);
    }


    document.addEventListener('DOMContentLoaded', async () => {
      const hoyDesde = new Date().toISOString().split('T')[0]; 
      $('#desdeInnova').val(hoyDesde);
      const hoyHasta = new Date().toISOString().split('T')[0]; 
      $('#hastaInnova').val(hoyHasta);

      cargarGetDataInnova(hoyDesde, hoyHasta);
        
    });
  
    document.getElementById('filtrarFechas').addEventListener('click', async () => {
      const desde = $('#desdeInnova').val();
      const hasta = $('#hastaInnova').val();

      cargarGetDataInnova(desde, hasta);
    });

</script>