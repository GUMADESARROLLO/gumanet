<script>
$(document).ready(function() {
    fullScreen();
    const now = new Date();
    const nyear_actual = now.getFullYear();
    const nyear_pasado = nyear_actual - 1;

    //getRequest(nyear_actual, nyear_pasado);

    $("#tl_titulo").text(`CALCULO DE REORDER POINT ${nyear_pasado} vs ${nyear_actual}`);
    
    TableReorderPoint();
    TableBase();
    getRequest()
    
    // Event listener for the filter button
    $('#IdFilterMolecula').on('click', function() {
        eneableButton(true,'Calc...')          
        getCalcular(nyear_actual, nyear_pasado)
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
        $('#tbl_base_reorder').DataTable().search(searchTerm).draw();
    });

    $("#id_search_reorder").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_competidores').DataTable().search(searchTerm).draw();
    });

    $('.button_export_excel').click(() => {
        $('#tbl_base_reorder').DataTable().buttons(0,0).trigger()
    })
    $('.btn_export_excel').click(() => {
        $('#tbl_competidores').DataTable().buttons(0,0).trigger()
    })

});


// Function to fetch data based on the selected molecule
function eneableButton(EnableButton, textButton = 'Calcular') {
    $('#IdFilterMolecula').prop('disabled', EnableButton);
    $('#IdFilterMolecula').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
}

function cleanTextos() {

    TableReorderPoint(Dt = []);
    TableBase(Dt = []);
}

// Initialize the DataTable
function TableReorderPoint(Dt = []) {
    // Clear the table before initializing
    $('#tbl_competidores').DataTable().clear().destroy();

    // Populate the table with data
    new DataTable('#tbl_competidores', {
        data: Dt,
        order: [12 , 'desc'],
        buttons: [{extend: 'excelHtml5'}],
        columns: [
            { data: "ARTICULO", class: "text-left" },
            { data: "DESCRIPCION", class: "text-left" },
            { data: "LABORATORIO", class: "text-center" },
            { data: "PROM_NORMAL", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "PROM_3M", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "PROM_ANUAL", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "INVENTARIO", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) }, 
            { data: "ONHAND", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "PROCENT_ANUAL", class: "text-center", render: function(data, type, row, meta) {
                return data + ' %';
            }},
            { data: "NECESITDAD_COMPRA_ANUAL", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "FACT_CA_YEAR_ACTUAL", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "POTENCIAL_CA", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "PEDIDO_TOTAL", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "MOQ", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: "ULTM_COST_USD", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        ],
        pageLength: 7,
        bLengthChange: false,
        searching: true,
        rowCallback: function(row, data, index) {
            

            // $(row).find('td:eq(3), td:eq(4), td:eq(5)').css({
            //     'background-color': '#72d083',
            //     'text-align': 'right'
            // });

            // $(row).find('td:eq(10), td:eq(11)').css({
            //     'background-color': '#a4edb2',
            //     'text-align': 'right'
            // });

            $(row).find('td:eq(12)').css({
                'font-weight': 'bold',
                'background-color': '#a4edb2',
                'text-align': 'right'
            });
        },
    });
    
}

function TableBase(Dt = []) {
    $('#tbl_base_reorder').DataTable().clear().destroy();
    // Populate the table with data
    new DataTable('#tbl_base_reorder', {
        data: Dt,
        order: [],
        buttons: [{extend: 'excelHtml5'}],
        columns: [
            { data: "ARTICULO", title: "ARTICULO" },
            { data: "DESCRIPCION", title: "DESCRIPCION" },    
            { data: "LABORATORIO", title: "LABORATORIO", class: "text-center" },        
            { data: "CANTIDAD", title: "CANTIDAD", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "NMONTH", title: "MONTH", class: "text-right" },
            { data: "NYEAR", title: "YEAR", class: "text-right" },           
            { data: "SEGMENTO", title: "SEGMENTO", class: "text-center" },
        ],
        pageLength: 5,
        bLengthChange: false,
        searching: true,
    });
    
    $(".dt-search").hide();
}



async function getRequest() {
    try {
        cleanTextos();
        eneableButton(true,'Cargando...') 
        // Fetch data from the server
        const response = await fetch('getReorderPoint', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });        

        const result = await response.json();

        TableReorderPoint(result.ReOrder);

        TableBase(result.Records);

        eneableButton(false);
        

    } catch (error) {
        console.error('Error al obtener los datos:', error);
        eneableButton(false)
    }
}

async function getCalcular( nyear_actual, nyear_pasado) {
    try {
        // Fetch data from the server
        const response = await fetch('getCalcular', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                nyear_actual    : nyear_actual,
                nyear_pasado    : nyear_pasado,
            })
        });        
        getRequest();
        

    } catch (error) {
        console.error('Error al obtener los datos:', error);
        eneableButton(false)
    }
}


</script>