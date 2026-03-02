<script>
function renderClienteBolsonChart(data) {
    const dataLimit = data.slice(0, 10);
    const categorias = dataLimit.map(item => item.CODIGO);
    const nombresClientes = dataLimit.map(item => item.NOMBRE);
    const valoresNIO = dataLimit.map(item => parseFloat(item.VENTA.replace(/,/g, '')));

    Highcharts.chart('chart_cliente_bolson', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'COMPARATIVO DE VENTAS POR CLIENTE'
        },
        xAxis: [{
            categories: nombresClientes,
            crosshair: true,
            labels: {
                rotation: 0,
                useHTML: true,
                formatter: function () {
                    const texto = String(this.value || '');
                    // Dividir por espacios
                    const partes = texto.split(' ');
                    if (partes.length > 2) {
                        // Insertar <br/> después del segundo elemento
                        partes.splice(2, 0, '<br/>');
                        return partes.join(' ');
                    }
                    return texto; // Si no hay suficiente para un salto, lo deja igual
                }
            }
        }],
        yAxis: [{ // Primary yAxis
            title: {
                text: 'FACTURADO (C$)',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }
        }],
        exporting: {
            enabled: false  
        },
        tooltip: {
            shared: true,
            formatter: function () {
                const index = this.points[0].point.index;
                return `
                    <b>${nombresClientes[index]}</b><br/>
                    CLIENTE: ${categorias[index]}<br/>
                    FACTURADO: C$ ${valoresNIO[index].toLocaleString()}
                `;
            }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            layout: 'horizontal'
        },
        series: [{
            name: 'FACTURADO (C$)',
            type: 'column',
            yAxis: 0,
            data: valoresNIO,
            color: '#8e44ad',
            tooltip: {
                valuePrefix: 'C$ '
            }
        }]
    });
}
</script>
