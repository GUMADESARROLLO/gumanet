<script>
function renderSKUPieChart(data) {

    // 1️⃣ Ordenar por VENTA (descendente) y tomar TOP 10
    const top10 = [...data]
        .sort((a, b) => parseFloat(b.VENTA) - parseFloat(a.VENTA))
        .slice(0, 10);

    // 2️⃣ Total de VENTA solo del TOP 10
    const totalVenta = top10.reduce(
        (sum, item) => sum + parseFloat(item.VENTA),
        0
    );

    const colores = [
        '#4E79A7', '#F28E2B', '#E15759', '#76B7B2',
        '#59A14F', '#EDC949', '#AF7AA1', '#FF9DA7',
        '#9C755F', '#BAB0AC'
    ];

    // 3️⃣ Datos del gráfico (porcentaje sobre VENTA)
    const chartData = top10.map((item, index) => ({
        name: item.DESCRIPCION,
        y: parseFloat(((item.VENTA / totalVenta) * 100).toFixed(2)),
        color: colores[index % colores.length]
    }));

    Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'PARTICIPACIÓN POR SKU (VENTA)'
        },
        tooltip: {
            pointFormat: '<b>{point.y:.2f}%</b>'
        },
        exporting: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.2f}%',
                    distance: -30,
                    color: 'white',
                    style: {
                        fontWeight: 'bold',
                        textOutline: '1px contrast'
                    }
                }
            }
        },
        series: [{
            name: 'Participación',
            data: chartData
        }]
    });
}
</script>

