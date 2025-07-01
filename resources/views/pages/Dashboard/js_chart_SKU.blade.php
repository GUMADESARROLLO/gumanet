<script>
Highcharts.chart('container', {
    chart: {
        type: 'pie',
       
        panning: {
            enabled: true,
            type: 'xy'
        },
    },
    title: {
        text: ''
    },
    tooltip: {
        valueSuffix: '%'
    },
   
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 1
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: [

                {
                    name: 'Fat',
                    sliced: true,
                    selected: true,
                    y: 10
                },
                {
                    name: 'Water',
                    color: '#F7931E',
                    y: 30
                },
                {
                    name: 'Carbohydrates',
                    color: '#802980',
                    y: 20
                },
                {
                    name: 'Protein',
                    color: '#008000',
                    y: 30
                },
                {
                    name: 'Ash',
                    color: '#B65FB5',
                    y: 10
                }
            ]
        }
    ]
});
</script>