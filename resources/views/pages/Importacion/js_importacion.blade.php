<script>
$(document).ready(function() {
    const nyear_actual = new Date().getFullYear();
    const nyear_pasado = nyear_actual - 1;
    
    $("#text-anio-pasado-valor").text(nyear_pasado);
    $("#text-anio-actual-valor").text(nyear_actual)

    $("#text-anio-pasado-unidades").text(nyear_pasado);
    $("#text-anio-actual-unidades").text(nyear_actual);

    $("#lbl_val_year_pasado").text(nyear_pasado);
    $("#lbl_val_year_actual").text(nyear_actual);
    $("#lbl_cant_year_pasado").text(nyear_pasado);
    $("#lbl_cant_year_actual").text(nyear_actual);

    TableTopCompetencia();
    TableDataImportacion();
    
    // Event listener for the filter button
    $('#IdFilterMolecula').on('click', function() {
        // Logic to filter by molecule
        const COD_MOLECULA = $("#Id_Molecula").val();   
        eneableButton(true,'Calc...')        
        //const COD_MOLECULA = "18805013"
        getRequest(COD_MOLECULA, nyear_actual, nyear_pasado);
    });

    $('#modal_importacion').on('click', function() {

        if ($('#tbl_competidores').DataTable().data().any()) {
            $('#mdlImportacion').modal('show');
        } else {
            Swal.fire({
                title: 'No hay datos para mostrar',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            })
        }

    });

    $("#id_search_importaciones").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_importaciones').DataTable().search(searchTerm).draw();
    });

    $('.button_export_excel').click(() => {
        $('#tbl_importaciones').DataTable().buttons(0,0).trigger()
    })

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
    TableTopCompetencia(Dt = []);
    TableDataImportacion(Dt = []);
}

// Initialize the DataTable
function TableTopCompetencia(Dt = []) {
    // Clear the table before initializing
    $('#tbl_competidores').DataTable().clear().destroy();

    // Populate the table with data
    new DataTable('#tbl_competidores', {
        data: Dt,
        order: [],
        columns: [
            { data: "COMPETIDOR" },
            { data: "MARCA", class: "text-center" },
            { data: "PASADO_FOB", class: "text-right" },
            { data: "ACTUAL_FOB", class: "text-right" },
            { data: "FOB_CREC", class: "text-center" },
            { data: "ORIGEN", class: "text-center" },
            { data: "PASADO_CANT", class: "text-right" },
            { data: "ACTUAL_CANT", class: "text-right" },
            { data: "CNT_CREC", class: "text-center" },
        ],
        pageLength: 100,
        bLengthChange: false,
        searching: false,
        rowCallback: function(row, data, index) {
            var Colors = ['#55b76c', '#55b76c', '#72d083', '#a4edb2', '#d8fae1'];

            if (index < 5) {
                $(row).css('background-color', Colors[index]);
            }
        },
    });
    
}

function TableDataImportacion(Dt = []) {
    $('#tbl_importaciones').DataTable().clear().destroy();
    // Populate the table with data
    new DataTable('#tbl_importaciones', {
        data: Dt,
        order: [],
        buttons: [{extend: 'excelHtml5'}],
        columns: [
            { data: "ARTICULO", title: "ARTICULO" },
            { data: "CANTIDAD", title: "CANTIDAD", class: "text-right" },
            { data: "FOB_TOTAL", title: "FOB_TOTAL", class: "text-right" },
            { data: "FOB_UNITARIO", title: "FOB_UNITARIO", class: "text-right" },
            { data: "NOMBRE_COMERCIAL", title: "NOMBRE_COMERCIAL" },
            { data: "NOMBRE_IMPORTADOR", title: "NOMBRE_IMPORTADOR" },
            { data: "NRO_RUC", title: "NRO_RUC", class: "text-center" },
            { data: "NYEAR", title: "NYEAR", class: "text-center" },
            { data: "PAIS_ORIGEN", title: "PAIS_ORIGEN", class: "text-center" },
            { data: "PRESENTACION", title: "PRESENTACION", class: "text-center" },
            { data: "UNIDADES", title: "UNIDADES", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { data: "UNIDADES_HOMOLOGADAS", title: "UNIDADES_HOMOLOGADAS", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { data: "UNIDAD_MED", title: "UNIDAD_MED", class: "text-center" },
        ],
        pageLength: 5,
        bLengthChange: false,
        searching: true,
    });
    
    $(".dt-search").hide();
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

        TableTopCompetencia(vCompetidores);
        TableDataImportacion(result.original.DATA_IMPORTACION);

        eneableButton(false)
        

    } catch (error) {
        console.error('Error al obtener los datos:', error);
        eneableButton(false)
    }
}


</script>