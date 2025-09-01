<script>

    const staticColumns = [
        { data: "NUM_ORDER", title: "No", class: "text-left" },
        { data: "ARTICULO", title: "ARTICULO", class: "text-left" },
        { data: "DESCRIPCION", title: "MARCA UMK", class: "text-left" },
        { data: "UNIDAD_MEDIDA", title: "UNID x PACK UMK", class: "text-left" },
        { data: "COUNT_COMPETIDORES", title: "CANT. COMPETIDORES IMS", class: "text-center" },
        { data: "VAL_US_2024", title: "PRECIO PROM. UMK 2024 U$", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PRECIO_PROM_IMS_2024", title: "PRECIO PROM. IMS 2024 U$", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "DIF", title: "PRECIO PROM. IMS DIF. vs. UMK 2024 %", class: "text-center", render: data => data + ' %' },

        { data: "TOP1_MANU_DESC", title: "PRECIO MENOR IMS COMP.", class: "text-left" },
        { data: "CANT_PACKS_EQV1", title: "CANT. PACKS Equiv.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "TOP1_PRICE", title: "PRECIO MENOR IMS U$", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "DIF_TOP1", title: "PRECIO MENOR IMS DIF. vs. UMK %", class: "text-center", render: data => data + ' %' },

        { data: "TOP2_MANU_DESC", title: "PRECIO 2do. MENOR IMS COMP.", class: "text-left" },
        { data: "CANT_PACKS_EQV2", title: "CANT. PACKS Equiv.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "TOP2_PRICE", title: "PRECIO 2do. MENOR IMS U$", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "DIF_TOP2", title: "PRECIO 2do. MENOR IMS DIF. vs. UMK %", class: "text-center", render: data => data + ' %' },

        { data: "TOP3_MANU_DESC", title: "PRECIO 3er. MENOR IMS COMP.", class: "text-left" },
        { data: "CANT_PACKS_EQV3", title: "CANT. PACKS Equiv.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "TOP3_PRICE", title: "PRECIO 3er. MENOR IMS U$", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "DIF_TOP3", title: "PRECIO 3er. MENOR IMS DIF. vs. UMK %", class: "text-center", render: data => data + ' %' },
        
        { data: "PACKS_TOTAL_UMK23", title: "PACKS TOTAL UMK 2023", class: "text-right" },
        { data: "PACKS_TOTAL_EQV_IMS_23", title: "PACKS TOTAL EQUIV. IMS 2023", class: "text-right" },
        { data: "MARKET_SHARE_UMK_23", title: "PACKS MARKET SHARE UMK 2023 %", class: "text-center", render: data => data + ' %' },

        { data: "PACKS_TOTAL_UMK24", title: "PACKS TOTAL UMK 2024", class: "text-right" },
        { data: "PACKS_TOTAL_EQV_IMS_24", title: "PACKS TOTAL EQUIV. IMS 2024", class: "text-right" },
        { data: "MARKET_SHARE_UMK_24", title: "PACKS MARKET SHARE UMK 2024 %", class: "text-center", render: data => data + ' %' },
        { data: "CRECI_23_24", title: "CRECIMIENTO 2023 VS 2024", class: "text-center", render: data => data + ' %' },

        { data: "PACKS_MANU1", title: "PACKS COMP. 1er. LUGAR 2024", class: "text-left" },
        { data: "TOP1_AVG_PRICE", title: "PRECIO X PACK Equiv.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PACKS_CANT1", title: "PACKS CANT. 1er. LUGAR vs. UMK 2024 %", class: "text-right"},
        { data: "PACKS_CANT_DIF1_24", title: "PACKS CANT. 1er. LUGAR vs. UMK 2024 %", class: "text-center", render: data => data + ' %' },

        { data: "PACKS_MANU2", title: "PACKS COMP. 2do. LUGAR 2024", class: "text-left" },
        { data: "TOP2_AVG_PRICE", title: "PRECIO X PACK Equiv.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PACKS_CANT2", title: "PACKS CANT. 2do. LUGAR 2024", class: "text-right" },
        { data: "PACKS_CANT_DIF2_24", title: "PACKS CANT. 2do. LUGAR vs. UMK 2024 %", class: "text-center", render: data => data + ' %' },

        { data: "PACKS_MANU3", title: "PACKS COMP. 3er. LUGAR 2024", class: "text-left" },
        { data: "TOP3_AVG_PRICE", title: "PRECIO X PACK Equiv.", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2) },
        { data: "PACKS_CANT3", title: "PACKS CANT. 3er. LUGAR 2024", class: "text-right" },
        { data: "PACKS_CANT_DIF3_24", title: "PACKS CANT. 3er. LUGAR vs. UMK 2024 %", class: "text-center", render: data => data + ' %' },
    ];


    let topStart_custom = document.createElement('div');
    topStart_custom.setAttribute('class', 'col-12 ');
    topStart_custom.innerHTML = `
    <div class="row">
        <div class="col-sm-10">	
            <div class="input-group"> 
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></i></span>
                </div>
                <input type="text" id="id_search_AnalisisIMS" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
        <div class="col-sm-2 col-md-2">
            <select class="custom-select" id="select_rows">
                <option value="7" selected>7</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="100">100</option>
                <option value="-1">Todo</option>
            </select>
        </div>
    </div>`;
$(document).ready(function() {
    fullScreen();
    TableAnalisisIMS();
    TableAnalisisIMS_Origin();
    getRequest();
    


    $("#id_search_AnalisisIMS").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_analisis_ims').DataTable().search(searchTerm).draw();
    });

    $( "#select_rows").change(function() {
        var table = $('#tbl_analisis_ims').DataTable();
        table.page.len(this.value).draw();
    });

    $("#id_search_Origin_ims").on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('#tbl_origin_ims').DataTable().search(searchTerm).draw();
    });

    $('.button_export_excel').click(() => {
        $('#tbl_origin_ims').DataTable().buttons(0,0).trigger()
    })
    

});


// Function to fetch data based on the selected molecule
function eneableButton(EnableButton, textButton = '<i class="fas fa-sync"></i> Actualizar.') {


    const table = $('#tbl_analisis_ims').DataTable();
    const nuevoTexto = EnableButton
        ? '<i class="fas fa-spinner fa-spin"></i> Cargando...'
        : textButton;

    table.button(0, 0).text(nuevoTexto);

    // Cambiar estado del botón
    table.button(0, 0).enable(!EnableButton);
}

function cleanTextos() {
    TableAnalisisIMS(Dt = []);
    TableAnalisisIMS_Origin(Dt = []);
}

// Initialize the DataTable
function TableAnalisisIMS(Dt = []) {

    // Clear the table before initializing
    $('#tbl_analisis_ims').DataTable().clear().destroy();
        // Copiar columnas base (para que no modifiques el original)
    

    // Populate the table with data
    $('#tbl_analisis_ims thead tr').addClass('bg-umk text-white text-center');
    new DataTable('#tbl_analisis_ims', {
        data: Dt,
        //order: [14 , 'desc'],
        buttons: [{extend: 'excelHtml5'}],
        columns: staticColumns,
        pageLength: 7,
        bLengthChange: false,
        searching: true,   
        layout: {
            topStart: null,
            bottom: 'paging',
            bottomStart: null,
            bottomEnd: null,     
            topStart : topStart_custom,  
            topEnd: {
                buttons: [
                {
                    text: `<i class="fas fa-sync"></i> Actualizar.`,                    
                    className: 'btn-primary-umk',
                    action: function ( e, dt, node, config ) {                        
                        getRequest()
                    }
                },               
                {
                    text:   `<i class="fas fa-file-excel"></i> Exportar`,
                    extend: 'excelHtml5',
                    className: 'btn-primary-umk-success',
                    title:  'Reporder Point: ' + moment().format('MMMM D, YYYY H:mm'),
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    text: `<i class="fas fa-database"></i> Datos IMS`,
                    className: 'btn-primary-umk-success',
                    action: function ( e, dt, node, config ) {

                        if ($('#tbl_analisis_ims').DataTable().data().any()) {
                            $('#mdlIMS').modal('show');
                        } else {
                            Swal.fire({
                                title: 'No hay datos para mostrar',
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            })
                        }
                        
                    }
                },
            
                    
                ]
            }
        },
        rowCallback: function(row, data, index) {
            
        },
    });
    
}
function TableAnalisisIMS_Origin(Dt = []) {
    $('#tbl_origin_ims').DataTable().clear().destroy();
    // Populate the table with data
    new DataTable('#tbl_origin_ims', {
        data: Dt,
        order: [],
        buttons: [{extend: 'excelHtml5'}],
        columns: [
            { data: "ID", title: "ID" },
            { data: "ARTICULO", title: "ARTICULO" },
            { data: "PACK_MARK", title: "PACK_MARK" },
            { data: "PACK_GENE", title: "PACK_GENE" },
            { data: "MANU_DESC", title: "MANU_DESC" },
            { data: "APP_DESC", title: "APP_DESC" },
            { data: "PACK_DESC", title: "PACK_DESC" },
            { data: "MOLECULE", title: "MOLECULE" },
            { data: "SALES_VAL_23", title: "SALES_VAL_23", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "SALES_VAL_24", title: "SALES_VAL_24", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "SALES_QTY_23", title: "SALES_QTY_23", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "SALES_QTY_24", title: "SALES_QTY_24", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "AVG_PRICE_23", title: "AVG_PRICE_23", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "AVG_PRICE_24", title: "AVG_PRICE_24", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "FACTOR", title: "FACTOR", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "UND_EQV23", title: "UND_EQV23", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "UND_EQV24", title: "UND_EQV24", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "PONDE23", title: "PONDE23", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "PONDE24", title: "PONDE24", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "SALES23_EQV", title: "SALES23_EQV", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "SALES24_EQV", title: "SALES24_EQV", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "AVG_23", title: "AVG_23", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "AVG_24", title: "AVG_24", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { data: "PRECIO_PACK_EQV", title: "PRECIO_PACK_EQV", class: "text-right", render: $.fn.dataTable.render.number(',', '.', 2, '') },
        ],
        pageLength: 5,
        bLengthChange: false,
        searching: true,
    });
    
    $(".dt-search").hide();
}




async function getRequest() {
    try {
        eneableButton(true,'Cargando...');

        const response = await fetch('getDataAnalisisIMS', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });        

        const result = await response.json();

        TableAnalisisIMS(result.AnalisisIMS);
        TableAnalisisIMS_Origin(result.Origin);
        
        
        eneableButton(false);
        

    } catch (error) {
        console.error('Error al obtener los datos:', error);
        eneableButton(false)
    }
}




</script>