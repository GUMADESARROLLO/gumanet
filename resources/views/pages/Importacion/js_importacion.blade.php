<script>
$(document).ready(function() {
    const nyear_actual = new Date().getFullYear();
    const nyear_pasado = nyear_actual - 1;
    
    $("#text-anio-pasado-valor").text(nyear_pasado);
    $("#text-anio-actual-valor").text(nyear_actual)

    $("#text-anio-pasado-unidades").text(nyear_pasado);
    $("#text-anio-actual-unidades").text(nyear_actual);

    InitializeTable();
    
    // Event listener for the filter button
    $('#IdFilterMolecula').on('click', function() {
        // Logic to filter by molecule
        const COD_MOLECULA = $("#Id_Molecula").val();   
        eneableButton(true,'Calc...')        
        //const COD_MOLECULA = "18805013"
        getRequest(COD_MOLECULA, nyear_actual, nyear_pasado);
    });

});

// Function to fetch data based on the selected molecule
function eneableButton(EnableButton, textButton = 'Filtrar') {
    $('#IdFilterMolecula').prop('disabled', EnableButton);
    $('#IdFilterMolecula').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
}

function cleanTextos() {
    $("#ranking_valor").text('0')
    $("#ranking_cant").text('0')

    $("#val-anio-pasado-unidades").text('0.00')
    $("#val-anio-actual-unidades").text('0.00')
    $("#dif-porcen-unidades").text('-')

    $("#val-anio-pasado-valor").text( '$ 0.00' ) 
    $("#val-anio-actual-valor").text( '$ 0.00' )
    $("#dif-porcen-valor").text('-')

    $("#val-umk-anio-pasado-valor").text( '$ 0.00' )
    $("#val-umk-anio-actual-valor").text( '$ 0.00' )
    $("#dif-porcen-umk-valor").html('-')

    $("#val-umk-anio-pasado-unidades").text('0.00')
    $("#val-umk-anio-actual-unidades").text('0.00')
    $("#dif-porcen-umk-unidades").html('-')
    $("#id_participacion").text(' 0' );
    InitializeTable(Dt = []);
}

// Initialize the DataTable
function InitializeTable(Dt = []) {
    // Clear the table before initializing
    $('#tbl_competidores').DataTable().clear().destroy();

    // Populate the table with data
    $('#tbl_competidores').DataTable({
        "data": Dt,
        "order" : [[ 3, "desc" ]],
        "columns": [
            { "data": "COMPETIDOR" },
            { "data": "MARCA", class: "text-center" },
            { "data": "PASADO_FOB", class: "text-right" },
            { "data": "ACTUAL_FOB", class: "text-right" },
            { "data": "FOB_CREC", class: "text-center" },
            { "data": "ORIGEN", class: "text-center" },
            { "data": "PASADO_CANT", class: "text-right" },
            { "data": "ACTUAL_CANT", class: "text-right" },
            { "data": "CNT_CREC", class: "text-center" },
        ],
        "pageLength": 5,
        "bLengthChange": false,
        "searching": false
    });
}


async function getRequest(COD_MOLECULA, nyear_actual, nyear_pasado) {
    try {
        cleanTextos();
        // Fetch data from the server
        const response = await fetch('getImportacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                COD_MOLECULA: COD_MOLECULA,
            })
        });        

        const result = await response.json();

        var vMercado = result.original.MERCADO;
        var vCompetidores = result.original.COMPETIDORES;

        $("#ranking_valor").text(result.original.RANKING.VALOR)
        $("#ranking_cant").text(result.original.RANKING.CANTIDAD)

        $("#val-anio-pasado-unidades").text(vMercado.CANT_HOMOLOGADAS[nyear_pasado])
        $("#val-anio-actual-unidades").text(vMercado.CANT_HOMOLOGADAS[nyear_actual])
        $("#dif-porcen-unidades").text(vMercado.CANT_HOMOLOGADAS.Crec)

        $("#val-anio-pasado-valor").text( '$ ' + vMercado.VALOR_MERCADO[nyear_pasado]) 
        $("#val-anio-actual-valor").text( '$ ' + vMercado.VALOR_MERCADO[nyear_actual])
        $("#dif-porcen-valor").text(vMercado.VALOR_MERCADO.Crec)

        $("#val-umk-anio-pasado-valor").text( '$ ' + result.original.UNIMARKSA.VALOR_MERCADO[nyear_pasado])
        $("#val-umk-anio-actual-valor").text( '$ ' + result.original.UNIMARKSA.VALOR_MERCADO[nyear_actual])
        $("#dif-porcen-umk-valor").html(result.original.UNIMARKSA.VALOR_MERCADO.Crec)

        $("#val-umk-anio-pasado-unidades").text(result.original.UNIMARKSA.CANTIDAD[nyear_pasado])
        $("#val-umk-anio-actual-unidades").text(result.original.UNIMARKSA.CANTIDAD[nyear_actual])
        $("#dif-porcen-umk-unidades").html(result.original.UNIMARKSA.CANTIDAD.Crec)
        $("#id_participacion").text(' ' + result.original.PARTICION)

        InitializeTable(vCompetidores);

        eneableButton(false)
        

    } catch (error) {
        console.error('Error al obtener los datos:', error);
        eneableButton(false)
    }
}


</script>