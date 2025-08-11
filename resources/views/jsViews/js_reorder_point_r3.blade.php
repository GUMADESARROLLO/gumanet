<script>
    // Columna base definida fuera de la función para evitar duplicación si se llama varias veces
    const staticColumns = [
        { data: "ARTICULO", title: "ARTICULO", class: "text-left" },
        { data: "DESCRIPCION", title: "DESCRIPCION", class: "text-left" },
        { data: "LABORATORIO", title: "LABORATORIO", class: "text-center" },
        { data: "PROM_NORMAL", title: "PROMEDIO NORMAL PRIV.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PROM_3M", title: "PROMEDIO 3 MESES + ALTOS PRIV.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PROM_ANUAL", title: "PROMEDIO CANTIDAD ANNUAL PRIV.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "INVENTARIO", title: "INVENTARIO", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) }, 
        { data: "PEDIDO", title: "PEDIDO", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "TRANSITO", title: "TRANSITO", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "ONHAND", title: "ONHAND", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PROCENT_ANUAL", title: "BONIFICACION", class: "text-center", render: data => data + ' %' },
        { data: "NECESITDAD_COMPRA_ANUAL", title: "NECESIDAD DE COMPRA AL AÑO", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "FACT_CA_YEAR_ACTUAL", title: "TOTAL VENDIDO AL AÑO DISCASA", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "POTENCIAL_CA", title: "POTENCIAL DISCASA", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },

        { data: "PEDIDO_TOTAL", title: "PEDIDO TOTAL", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },

        { data: "MOQ", title: "MOQ", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "ULTM_COST_USD", title: "ULTM. COST. USD.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "COSTO_PROM_DOL", title: "COSTO PROM. USD.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "LEADTIME", title: "LEADTIME", class: "text-Left" },
        { data: "CATEGORIA", title: "CATEGORIA", class: "text-right" },     
        { data: "LOTE", title: "LOTE", class: "text-left" },   
        { data: "FECHA_VENCE_LOTE", title: "VENC. LOTE", class: "text-left" },   
        { data: "CANTIDAD_INGRESADA", title: "ULT. CANT. INGRESADA", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "CANT_VENCE_LOTE", title: "CANT. VENCE LOTE", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },   
        
        
        
        
        
        
       
        
    ];
$(document).ready(function() {
    fullScreen();
    const now = new Date();
    const nyear_actual = now.getFullYear();
    const nyear_pasado = nyear_actual - 1;

    //getRequest(nyear_actual, nyear_pasado);

    
    TableReorderPoint();
    TableBase();
    getRequest()
    
    // Event listener for the filter button
    $('#IdFilterMolecula').on('click', function() {
        eneableButton(true,'Calc...')
        //cleanTextos();
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
        // Copiar columnas base (para que no modifiques el original)
        let DtColumns = [...staticColumns];
        // Crear un Set con las claves de columnas ya incluidas
        const existingKeys = new Set(DtColumns.map(col => col.data));
        // Agregar columnas dinámicas si no existen
        if (Dt.Columns && Array.isArray(Dt.Columns)) {
            Dt.Columns.forEach(colName => {
                if (!existingKeys.has(colName) && !['ARTICULO_pv', 'ARTICULO_ds'].includes(colName)) {
                    DtColumns.push({
                        data: colName,
                        title: colName.toUpperCase(),
                        class: "text-right",
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    });
                    // Añadir al set para evitar futuros duplicados
                    existingKeys.add(colName); 
                }
            });
        }

    // Populate the table with data
    $('#tbl_competidores thead tr').addClass('bg-umk text-white text-center');
    new DataTable('#tbl_competidores', {
        data: Dt.Rows,
        order: [14 , 'desc'],
        buttons: [{extend: 'excelHtml5'}],
        columns: DtColumns,
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

            $(row).find('td:eq(14)').css({
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

        
        console.log(result.Update_at);
        
        $("#tl_titulo").text(` ${result.Update_at} `);

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