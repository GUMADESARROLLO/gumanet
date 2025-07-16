<script>
    function renderSKUPieChart(data) {
        const totalPeso = data.reduce((sum, item) => sum + parseFloat(item.PESO), 0);

        const chartData = data.map(item => ({
            name: item.SKU,
            y: parseFloat(((item.PESO / totalPeso) * 100).toFixed(2))
        }));

        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Participación por SKU (%)'
            },
            tooltip: {
                pointFormat: '<b>{point.y:.2f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
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
                    },
                    showInLegend: true
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                itemMarginTop: 5,
                itemMarginBottom: 5
            },
            series: [{
                name: 'Participación',
                colorByPoint: true,
                data: chartData
            }]
        });
    }
</script>
