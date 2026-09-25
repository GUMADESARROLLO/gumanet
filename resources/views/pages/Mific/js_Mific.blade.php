<script>
$(document).ready(function() {
    fullScreen();

    $('input[name="dt_range"]').daterangepicker({
        "autoApply": true,
            ranges: {
            'Hoy': [moment(), moment()],
            'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],              
            'Este Mes': [moment().startOf('month'), moment()],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            "3 Meses": [moment().subtract(3, 'month'), moment()],
            "6 Meses": [moment().subtract(6, 'month'), moment()],
            
            '1 Año': [moment().subtract(1, 'year'), moment()],
            // '2 Años': [moment().subtract(2, 'year'), moment()],
            // '3 Años': [moment().subtract(3, 'year'), moment()]
            },
        "showCustomRangeLabel": false,
        "alwaysShowCalendars": true,
        "startDate": moment().startOf('month').format('D MMM. YYYY'),
        "endDate": moment().format('D MMM. YYYY'),
        opens: 'left',
        locale: {
            //format: "DD/MM/YYYY",
            format: "D MMM. YYYY",   // Ejemplo: 1 ago. 2025
            separator: " - ",
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "Desde",
            toLabel: "Hasta",
            customRangeLabel: "Personalizado",
            weekLabel: "S",
            daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
            monthNames: [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ],
            firstDay: 1
        }
    }, function(start, end, label) {
        CallFilter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });


    var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');



    $('#filtrarFechas').on('click', function() {
        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

        CallFilter( desde, hasta );        
    });

    $('#txt_busqueda_orden_compra').on('keyup', function() {   
        var vTableArticulos = $('#tbl_ordenes_compras').DataTable();     
        vTableArticulos.search(this.value).draw();
    });

    $('#txt_search_upload').on('keyup', function() {   
        var vTableExcel = $('#tbl_excel').DataTable();     
        vTableExcel.search(this.value).draw();
    });

    CallFilter( desde, hasta );  

    $('#btnSaveMific').on('click', function() {
        //$('#mdl-edit-mific').modal('hide');

        var IdMific = $('#id_row').val();
        var UrlPath = (IdMific == 0) ? 'SaveMific' : 'UpdateMific';

        var data_mific = {};
        data_mific['id_row']                = IdMific;
        data_mific['sku_umk']               = $('#sku_umk').val();        
        data_mific['registro_sanitario']    = $('#registro_sanitario').val();
        data_mific['nombre_comercial']      = $('#nombre_comercial').val();
        data_mific['nombre_generico']       = $('#nombre_generico').val();
        data_mific['concentracion']         = $('#concentracion').val();
        data_mific['presentacion']          = $('#presentacion').val();
        data_mific['cantidad']              = $('#cantidad').val();
        data_mific['laboratorio']           = $('#laboratorio').val();
        data_mific['precio_farmacia']       = $('#precio_farmacia').val();
        data_mific['precio_publico']        = $('#precio_publico').val();
        data_mific['unidad_negocio']        = $('#unidad_negocio').val();

        $.ajax({
            type: 'POST',
            url: UrlPath,
            data: data_mific,
            success: function(response) {
                Swal.fire({
                    title: 'Guardado correctamente',
                    text: '',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });

        
    });

    $('#btnUploadMific').on('click', function() {
        $('#mdl-upload-mific').modal('show');
    });
    
    $('#NuevoMific').on('click', function() {
        CleanFormMific();
        $('#mdl-edit-mific').modal('show');
    });


    $('#frm-upload').on("change", function(evt){ 
        var files = evt.target.files;
        var xl2json = new ExcelToJSON();
        xl2json.parseExcel(files[0]);
    });

});

var ExcelToJSON = function () {

    this.parseExcel = function (file) {

        var reader = new FileReader();

        reader.onload = function (e) {

            var data = e.target.result;
            var workbook = XLSX.read(data, { type: 'binary' });

            dta_table_excel = [];
            isError = false;

            workbook.SheetNames.forEach(function (sheetName) {

                var worksheet = workbook.Sheets[sheetName];

                // Lee TODO el contenido sin limitar rango
                var rows = XLSX.utils.sheet_to_json(worksheet, { defval: '' });

                rows.forEach(function (row) {

                    // Convertimos a array de valores
                    var rowArray = Object.values(row);

                    // Filtrar valores realmente llenos
                    var columnasConDatos = rowArray.filter(function (value) {
                        return value !== null && value !== undefined && value.toString().trim() !== '';
                    });

                    dta_table_excel.push({
                        ARTICULO                : rowArray[0] ? rowArray[0].toString().trim() : 'N/D',
                        REGISTRO_SANITARIO      : rowArray[1] ? rowArray[1].toString().trim() : 'N/D',
                        NOMBRE_COMERCIA         : rowArray[2] ? rowArray[2].toString().trim() : 'N/D',
                        NOMBRE_GENERICO         : rowArray[3] ? rowArray[3].toString().trim() : 'N/D',
                        CONCENTRACION           : rowArray[4] ? rowArray[4].toString().trim() : 'N/D',
                        PRESENTACION            : rowArray[5] ? rowArray[5].toString().trim() : 'N/D',
                        CANTIDAD                : rowArray[6] ? rowArray[6].toString().trim() : 'N/D',
                        LABORATORIO             : rowArray[7] ? rowArray[7].toString().trim() : 'N/D',
                        PRECIO_FARMACIA         : rowArray[8] ? rowArray[8].toString().trim() : 'N/D',
                        PRECIO_PUBLICO          : rowArray[9] ? rowArray[9].toString().trim() : 'N/D',
                        UNIDAD_NEGOCIO          : sheetName || 'N/D',
                    });

                });

            });

            $("#id_registros_encontrados").text(dta_table_excel.length).addClass('text-success font-weight-bolder');
            

            dta_table_header = [
                {"title": "ARTICULO","data": "ARTICULO"},
                {"title": "REGISTRO_SANITARIO","data": "REGISTRO_SANITARIO"},
                {"title": "NOMBRE_COMERCIA","data": "NOMBRE_COMERCIA"},
                {"title": "NOMBRE_GENERICO","data": "NOMBRE_GENERICO"},
                {"title": "CONCENTRACION","data": "CONCENTRACION"},
                {"title": "PRESENTACION","data": "PRESENTACION"},
                {"title": "CANTIDAD","data": "CANTIDAD"},
                {"title": "LABORATORIO","data": "LABORATORIO"},
                {"title": "PRECIO_FARMACIA","data": "PRECIO_FARMACIA"},
                {"title": "PRECIO_PUBLICO","data": "PRECIO_PUBLICO"},
                {"title": "UNIDAD_NEGOCIO","data": "UNIDAD_NEGOCIO"}
            ]
            dta_columnDefs = [
                {"className": "dt-center", "targets": [0,3,4,5,6,7,8,10]},
                {"className": "dt-right", "targets": [2]},
                {"visible"  : false, "searchable": false,"targets": [] }
            ]
            table_render('#tbl_excel',dta_table_excel,dta_table_header,dta_columnDefs,false)

        };

        reader.onerror = function (ex) {
            console.log(ex);
        };

        reader.readAsBinaryString(file);
    };
};




function CallFilter( desde = null, hasta = null ) {

    $('#tl_periodo').html(`<b>${moment(desde).format('D MMM. YYYY')}</b> al <b>${moment(hasta).format('D MMM. YYYY')}</b>`);
    
    GetData(desde, hasta);
    
}

function editMific(id) {
    $('#mdl-edit-mific').modal('show');
    getDetallesMific(id);
    
}
function removeMific(id) {

    Swal.fire({
        title: 'Estas seguro?' + id,
        text: "Se eliminar  el registro de mific con id "+id,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'S , eliminar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'DeleteMific',
                type: 'post',
                data: {
                    id_row : id
                },
                async: true,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Registro eliminado',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                CallFilter( desde, hasta );  
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Error al eliminar registro',
                            text: response.message,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })
        }
    })
    
}
function CleanFormMific() {
    $('#sku_umk').val("");
    $('#id_row').val(0);
    $('#registro_sanitario').val("");
    $('#nombre_comercial').val("");
    $('#nombre_generico').val("");
    $('#concentracion').val("");
    $('#presentacion').val("");
    $('#cantidad').val("");
    $('#laboratorio').val("");
    $('#precio_farmacia').val("");
    $('#precio_publico').val("");
    $('#unidad_negocio').val("ND").trigger('change');    
}
function UIMific(data) {
    $('#sku_umk').val(data.ARTICULO);
    $('#id_row').val(data.ID_MIFIC);
    $('#registro_sanitario').val(data.REGISTRO_SANITARIO);
    $('#nombre_comercial').val(data.NOMBRE_COMERCIAL);
    $('#nombre_generico').val(data.NOMBRE_GENERICO);
    $('#concentracion').val(data.CONCENTRACION);
    $('#presentacion').val(data.PRESENTACION);
    $('#cantidad').val(data.CANTIDAD);
    $('#laboratorio').val(data.LABORATORIO);
    $('#precio_farmacia').val(data.MIFIC_FARMACIA);
    $('#precio_publico').val(data.MIFIC_PUBLICO);
    $('#unidad_negocio').val(data.UNIDAD_NEGOCIO).trigger('change');    
}

function eneableButton(EnableButton, textButton = '<i class="fas fa-filter"></i> Filtrar') {
    $('#filtrarFechas').prop('disabled', EnableButton);
    $('#filtrarFechas').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
}


function TablaOrdenesCompras(selector, data) {
    var table = $(selector).DataTable({
        data: data,
        destroy: true,
        paging: true,
        pageLength: 10,
        info: false,
        searching: true,
        ordering: true,
        columns: [
            { title : 'SKU UMK', data: 'SKU_UMK', className: 'text-center', render: function(data, type, row) {
                return `<div class="item-center"><strong>${data}</strong></div>`;
            }},

            { title: 'REGISTRO SANITARIO', data: 'REGISTRO_SANITARIO', className: 'text-center', render: function(data, type, row) {
                return `<div class="item-center"><strong>${data}</strong></div>`;
            }},
            { title: 'NOMBRE COMERCIAL', data: 'NOMBRE_COMERCIAL', className: 'text-center',render: function(data, type, row) {
                return `<div class="item-center"><strong>${data}</strong></div>`;
                }          
            },
            { title: 'NOMBRE GENERICO', data: 'NOMBRE_GENERICO',className: 'text-center', render: function(data, type, row) {
                return `<div class="item-center"><strong>${data}</strong></div>`;
                }          
            },
            { title: 'CONCENTRACION', data: 'CONCENTRACION', className: 'text-left', render: function(data, type, row) {
                return `<div class="item-left"> ${data} </div>`;
                }          
            },
            { title: 'PRESENTACION', data: 'PRESENTACION', className: 'text-left', render: function(data, type, row) {
                return `<div class="item-left"> ${data} </div>`;
                }          
            },
            { title : 'CANTIDAD', data: 'CANTIDAD', className: 'text-center'},
            { title : 'LABORATORIO', data: 'LABORATORIO', className: 'text-center'},
            { title : 'PRECIO FARMACIA', data: 'PRECIO_FARMACIA', className: 'text-center'},
            { title : 'PRECIO PUBLICO', data: 'PRECIO_PUBLICO', className: 'text-center'},
            { title : 'UNIDAD NEGOCIO', data: 'UNIDAD_NEGOCIO', className: 'text-center'},
            { title : 'ACCIONES', data: 'ACCIONES', className: 'text-center'},
        ],
    
    });    
    $(selector + '_length').hide();
    $(selector + '_filter').hide();
}

function table_render(Table,datos,Header,columnDefs,Filter)
{

	TableExcel = $(Table).DataTable({
		"data": datos,
		"destroy": true,
		"info": false,
		"bPaginate": true,
		"order": [
			[0, "DESC"]
		],
		"lengthMenu": [
			[5, -1],
			[5, "Todo"]
		],
		"language": {
			"zeroRecords": "NO HAY COINCIDENCIAS",
			"paginate": {
				"first": "Primera",
				"last": "Última ",
				"next": "Siguiente",
				"previous": "Anterior"
			},
			"lengthMenu": "MOSTRAR _MENU_",
			"emptyTable": "-",
			"search": "BUSCAR"
		},
		'columns': Header,
		"columnDefs": columnDefs,
		rowCallback: function( row, data, index ) {
			
		}
	});
	if(!Filter){
		$(Table+"_length").hide();
		$(Table+"_filter").hide();
	}

}


async function GetData(desde, hasta){
    try {
        
        eneableButton(true,'Calc...') ;

        const response = await fetch('getDataMific', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify()
        });

        const result = await response.json();

        TablaOrdenesCompras('#tbl_ordenes_compras', result);


        eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

    } catch (error) {
        console.error('Error al obtener los datos:', error);
        eneableButton(false,null)
    }
}

async function getDetallesMific(Mific) {
    try {
        const response = await fetch('getDetallesMific', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                Mific     : Mific
            })
        });

        const result = await response.json();
        UIMific(result);
    } catch (error) {
        console.error(error);
    }
}
</script>