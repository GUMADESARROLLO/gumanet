   <script>
    function OnWay() {
      swal.fire({
        title: 'En Construcción',
        text: 'Esta sección está en desarrollo y estará disponible pronto.',
        icon: 'info',
        confirmButtonText: 'Aceptar'
      });
    }
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

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch('getDataInnova');
            const result = await response.json();
            
            loadAndBuildTable('#clientesTable', result.ACTUAL.Clientes);            
            loadAndBuildTable('#vendedoresTable', result.ACTUAL.Vendedores);
            loadAndBuildTable('#tbl_top_sku', result.ACTUAL.Vendedores);
            loadAndBuildTable('#tbl_top_clientes', result.ACTUAL.Vendedores);

            $('#bultos_facturacion').text(result.ACTUAL.Metricas.BULTOS_TOTAL_NIO);
            $('#bultos_valor').text("C$. "+result.ACTUAL.Metricas.BULTOS_TOTAL_UND);
            $('#bultos_anterior').text(result.COMPARATIVA.UND_YTD.BULTOS_UND_ANIO_ACTUAL);
            $('#bultos_actual').text(result.COMPARATIVA.UND_YTD.BULTOS_UND_ANIO_ANTERIOR);

        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    });
  </script>