<script>
function renderClienteBolsonChart(data) {
    const categorias = data.map(item => item.CODIGO);
    const nombresClientes = data.map(item => item.NOMBRE);
    const valoresNIO = data.map(item => parseFloat(item.BULTOS_TOTAL_NIO.replace(/,/g, '')));
    const valoresUND = data.map(item => parseFloat(item.BULTOS_TOTAL_UND.replace(/,/g, '')));

    Highcharts.chart('chart_cliente_bolson', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Comparativo de Bultos por Cliente'
        },
        xAxis: [{
            categories: categorias,
            crosshair: true,
            labels: {
                rotation: -45
            }
        }],
        yAxis: [{ // Primary yAxis
            title: {
                text: 'Valor (C$)',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Bultos UND',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        exporting: {
            enabled: false  
        },
        tooltip: {
            shared: true,
            formatter: function () {
                const index = this.points[0].point.index;
                return `
                    <b>${categorias[index]}</b><br/>
                    Cliente: ${nombresClientes[index]}<br/>
                    Valor: C$ ${valoresNIO[index].toLocaleString()}<br/>
                    Bultos UND: ${valoresUND[index]}
                `;
            }
        },
        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            layout: 'horizontal'
        },
        series: [{
            name: 'Valor (C$)',
            type: 'column',
            yAxis: 0,
            data: valoresNIO,
            tooltip: {
                valuePrefix: 'C$ '
            }
        }, {
            name: 'Bultos UND',
            type: 'spline',
            yAxis: 1,
            data: valoresUND,
            tooltip: {
                valueSuffix: ' UND'
            }
        }]
    });
}
</script>
