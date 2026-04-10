<script>
function renderClienteBolsonChart(data) {
    //const dataLimit = data.slice(0, 10);    
    
    const ArrayDias = data.map(item => item.DIA);
   
    const FACTURAS = data.map(item => item.FACTURAS);
    const TOTAL = data.map(item => item.TOTAL_LINEA);
    //const UNITS = data.map(item => item.CANTIDAD);

    const UNITS = data.map(item => parseFloat(item.CANTIDAD.replace(/,/g, '')));
    //const UNITS = data.map(item => parseFloat(item.CANTIDAD.replace(/,/g, '')));

    Highcharts.chart('chart_pedidos_dia', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'COMPORTAMIENTO DE PEDIDOS'
        },
        xAxis: [{
            categories: ArrayDias,
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
                    <b>${ArrayDias[index]}</b><br/>
                    VALOR.: <b>C$ ${numeral(TOTAL[index]).format('0,0.00')}</b><br/>
                    UNITS: <b>${numeral(UNITS[index]).format('0,0')}</b><br/>
                    CANT. PEDIDOS.: <b>${numeral(FACTURAS[index]).format('0,0')}</b>
                `;
            }
        },
        series: [{
            name: 'Valor (C$)',
            type: 'column',
            yAxis: 0,
            data: UNITS,
            color: '#8e44ad',
            point: {
                events: {
                    click: function(e) {

                        $('#mdl-topsku').modal('show');

                    }
                }
            },
        }]
    });
}
</script>
