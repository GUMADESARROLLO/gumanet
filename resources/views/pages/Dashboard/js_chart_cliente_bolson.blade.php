<script>
function renderClienteBolsonChart(data) {

    const dataLimit = data.slice(0, 10);
    const categorias = dataLimit.map(item => item.CODIGO);
    const nombresClientes = dataLimit.map(item => item.NOMBRE);
    const valoresNIO = dataLimit.map(item => parseFloat(item.BULTOS_TOTAL_NIO.replace(/,/g, '')));
    const valoresUND = dataLimit.map(item => parseFloat(item.BULTOS_TOTAL_UND.replace(/,/g, '')));

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
                    <b>${nombresClientes[index]}</b><br/>
                    CLIENTE: ${categorias[index]}<br/>
                    VALOR: C$ ${valoresNIO[index].toLocaleString()}<br/>
                    BULTOS: ${ valoresUND[index] }
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
            color: '#8e44ad',
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
