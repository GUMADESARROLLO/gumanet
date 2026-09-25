<script>
function renderClienteBolsonChart(data) {

    const ArrayDias = data.map(item => {
        const fecha = new Date(item.DIA);

        return fecha.toLocaleDateString('es-ES', {
            month: 'short',
            day: 'numeric'
        });
    });
    const PEDIDOS = data.map(item => parseInt(item.PEDIDOS));
    const FACTURADOS = data.map(item => parseInt(item.FACTURAS));

    const totalPedidos = PEDIDOS.reduce((sum, val) => sum + val, 0);
    const totalFacturados = FACTURADOS.reduce((sum, val) => sum + val, 0);  

    Highcharts.chart('chart_pedidos_dia', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'PEDIDOS VS FACTURADOS POR DÍA'
        },
        xAxis: {
            categories: ArrayDias,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad'
            }
        },
        exporting: {
            enabled: false
        },
        tooltip: {
            shared: true,
            formatter: function () {

                const pedidos = this.points[0].y;
                const facturados = this.points[1].y;

                return `
                    <b>${this.x}</b><br>
                    Pedidos: <b>${pedidos}</b><br>
                    Facturados: <b>${facturados}</b>
                `;
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.1,
                borderWidth: 0
            }
        },
        series: [
            {
                name: `Pedidos (${numeral(totalPedidos).format('0,0')})`,
                data: PEDIDOS,
                color: '#3498db',
                point: {
                    events: {
                        click: function() {
                            detalles_ventas_diarias(
                                this.series.name
                            );
                        }
                    }
                }
            },
            {
                name: `Facturados (${numeral(totalFacturados).format('0,0')})`,
                data: FACTURADOS,
                color: '#2ecc71',
                point: {
                    events: {
                        click: function() {
                            detalles_ventas_diarias(
                                this.series.name
                            );
                        }
                    }
                }
            }
        ]
    });
}
</script>
