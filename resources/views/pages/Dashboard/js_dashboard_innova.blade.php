   <script>
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
        paging: false,
        info: false,
        searching: false,
        ordering: false,
        columns: [
          { data: 'NOMBRE' },
          { data: 'BULTOS_TOTAL_UND' },
          { data: 'BULTOS_TOTAL_NIO' },
          { data: 'CODIGO' }
        ],
        createdRow: function (row, rowData) {
          row.innerHTML = `<td colspan="4">${formatRow(rowData)}</td>`;
        }
      });
    }

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch('getDataInnova');
            const result = await response.json();
            
            loadAndBuildTable('#clientesTable', result.ACTUAL.Clientes);
            
            loadAndBuildTable('#vendedoresTable', result.ACTUAL.Vendedores);

            $('#bultos_facturacion').text(result.ACTUAL.Metricas.BULTOS_TOTAL_NIO);
            $('#bultos_valor').text(result.ACTUAL.Metricas.BULTOS_TOTAL_UND);
            $('#bultos_anterior').text(result.COMPARATIVA.UND_YTD.BULTOS_UND_ANIO_ACTUAL);
            $('#bultos_actual').text(result.COMPARATIVA.UND_YTD.BULTOS_UND_ANIO_ANTERIOR);

        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    });
  </script>