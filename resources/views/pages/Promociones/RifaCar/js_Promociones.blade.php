<script>
$(document).ready(function() {
    //inicializaControlFecha();
    fullScreen();


    // const yearActual = moment().year();

    // $('input[name="dt_range"]').daterangepicker({
    //     autoApply: true,

    //     minDate: moment(`${yearActual}-05-01`, 'YYYY-MM-DD'),
    //     maxDate: moment(`${yearActual}-09-20`, 'YYYY-MM-DD'),

    //     ranges: {
    //         'Hoy': [moment(), moment()],
    //         'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
    //         'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],              
    //         'Este Mes': [moment().startOf('month'), moment()],
    //         'Mes Anterior': [
    //             moment().subtract(1, 'month').startOf('month'), 
    //             moment().subtract(1, 'month').endOf('month')
    //         ],
    //         "3 Meses": [moment().subtract(3, 'month'), moment()],
    //         "6 Meses": [moment().subtract(6, 'month'), moment()],
    //         '1 Año': [moment().subtract(1, 'year'), moment()],
    //     },

    //     showCustomRangeLabel: false,
    //     alwaysShowCalendars: true,

    //     startDate: moment(`${yearActual}-05-01`, 'YYYY-MM-DD'),
    //     endDate: moment().isAfter(moment(`${yearActual}-09-20`))
    //         ? moment(`${yearActual}-09-20`)
    //         : moment(),

    //     opens: 'left',

    //     locale: {
    //         format: "D MMM. YYYY",
    //         separator: " - ",
    //         applyLabel: "Aplicar",
    //         cancelLabel: "Cancelar",
    //         fromLabel: "Desde",
    //         toLabel: "Hasta",
    //         customRangeLabel: "Personalizado",
    //         weekLabel: "S",
    //         daysOfWeek: ["Dom.", "Lun.", "Mar.", "Mie.", "Jue.", "Vie", "Sab."],
    //         monthNames: [
    //             "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    //             "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    //         ],
    //         firstDay: 1
    //     }

    // }, function(start, end, label) {
    //     CallFilter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    // });

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
    
    CallFilter( desde, hasta );  


    $('#txt_busqueda_orden_compra').on('keyup', function() {   
        var vTableArticulos = $('#tbl_ordenes_compras').DataTable();     
        vTableArticulos.search(this.value).draw();
    });

    $(document).on('change', '#select-all', function() {
        var isChecked = $(this).prop('checked');
        $('.row-checkbox', $('#tbl_ordenes_compras')).prop('checked', isChecked);
        updateSelectionToolbar();
    });

    $(document).on('change', '.row-checkbox', function() {
        updateSelectionToolbar();
    });

    $('#btn-aplicar-masivo').on('click', function() {
        AplicarAccionesSeleccionadas();
    });

});

    function OpenModal(RowData) {
        $('#ModalAcciones').modal('show');
        row = JSON.parse(decodeURIComponent(RowData));

        $('#lbl_nombre_cliente').html(row.NOMBRE);
        $('#lbl_codigo_cliente').html(row.CLIENTE);
        $('#lbl_factura').html(row.FACTURA);

        //$("#btn_imprimir_acciones").attr("href", `/ImprimirAcciones?Factura=${row.FACTURA}`);

        GetAcciones(row.FACTURA);
    }

    $("#btn_imprimir_acciones").on('click', function() {
        var factura = $('#lbl_factura').html();
        window.open(`/ImprimirAcciones?Factura=${factura}`, '_blank');
    });

    $("#btn_revertir_acciones").on('click', function() {
        var factura = $('#lbl_factura').html();
        $('#ModalAcciones').modal('hide');

        setTimeout(function() {
            Swal.fire({
                title: 'Revertir acciones',
                text: `Factura: ${factura}`,
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Motivo de la reversión',
                inputPlaceholder: 'Describa el motivo...',
                inputAttributes: {
                    'aria-label': 'Motivo de la reversión'
                },
                showCancelButton: true,
                confirmButtonText: 'Sí, revertir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                preConfirm: (motivo) => {
                    if (!motivo) {
                        Swal.showValidationMessage('Debe ingresar un motivo');
                        return false;
                    }
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: '/RevertirAcciones',
                            method: 'POST',
                            data: { 
                                Factura: factura, 
                                Motivo: motivo 
                            },
                            success: function (resp) {
                                resolve(resp);
                            },
                            error: function () {
                                reject('Error al revertir acciones');
                            }
                        });
                    }).catch((error) => {
                        Swal.showValidationMessage(error);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Revertido', 'Acciones revertidas correctamente', 'success').then(function() {
                        $('#ModalAcciones').modal('show');
                        GetAcciones(factura);
                    });
                } else {
                    $('#ModalAcciones').modal('show');
                }
            });
        }, 400);
    });


    function CallFilter( desde = null, hasta = null ) {

        $('#tl_periodo').html(`<b>${moment(desde).format('D MMM. YYYY')}</b> al <b>${moment(hasta).format('D MMM. YYYY')}</b>`);
        
        GetData(desde, hasta);
        
    }

    function eneableButton(EnableButton, textButton = '<i class="fas fa-filter"></i> Filtrar') {
        $('#filtrarFechas').prop('disabled', EnableButton);
        $('#filtrarFechas').html('<i class="fas fa-spinner fa-spin" style="display:' + (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton);
    }

    function AsignarAcciones(factura) {
        Swal.fire({
            title: '¿Asignar acciones?',
            text: `Factura: ${factura}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                Swal.showLoading();

                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '/AsignarAcciones',
                        method: 'POST',
                        data: { Factura: factura },
                        success: function (resp) {
                            resolve(resp);
                            getAcciones(factura);
                        },
                        error: function () {
                            reject();
                        }
                    });
                }).catch(() => {
                    Swal.showValidationMessage(
                        'Error al asignar acciones'
                    );
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Éxito', 'Acciones asignadas correctamente', 'success');
                GetAcciones(factura);
            }
        });
    }

    function updateSelectionToolbar() {
        var $checkboxes = $('.row-checkbox', $('#tbl_ordenes_compras'));
        var $checked = $checkboxes.filter(':checked');
        var count = $checked.length;
        var total = $checkboxes.length;
        var toolbar = $('#selection-toolbar');
        var btn = $('#btn-aplicar-masivo');

        $('#selected-count').text(count);
        $('#select-all').prop('checked', count > 0 && count === total);

        if (count > 0) {
            toolbar.removeClass('d-none').addClass('d-flex');
            btn.prop('disabled', false);
        } else {
            toolbar.removeClass('d-flex').addClass('d-none');
            btn.prop('disabled', true);
        }
    }

    async function AplicarAccionesSeleccionadas() {
        var facturas = [];
        var omitidas = 0;
        $('.row-checkbox:checked').each(function() {
            if ($(this).data('isaccion') === 'N') {
                facturas.push($(this).val());
            } else {
                omitidas++;
            }
        });

        if (facturas.length === 0 && omitidas === 0) return;

        var msgConfirm = 'Se asignarán acciones a ' + facturas.length + ' factura(s)';
        if (omitidas > 0) {
            msgConfirm += ' (' + omitidas + ' omitida(s) ya tienen acciones asignadas)';
        }

        if (facturas.length === 0) {
            Swal.fire('Sin facturas pendientes', 'Todas las facturas seleccionadas ya tienen acciones asignadas.', 'info');
            return;
        }

        var result = await Swal.fire({
            title: '¿Asignar acciones?',
            text: msgConfirm,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Procesando factura 1 de ' + facturas.length,
            text: 'Factura: ' + facturas[0],
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        for (var i = 0; i < facturas.length; i++) {
            var factura = facturas[i];

            Swal.update({
                title: 'Procesando factura ' + (i + 1) + ' / ' + facturas.length,
                text: 'Factura: ' + factura
            });

            try {
                await $.ajax({
                    url: '/AsignarAcciones',
                    method: 'POST',
                    data: { Factura: factura },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            } catch (error) {
                console.error('Error al asignar acciones a factura ' + factura, error);
            }
        }

        Swal.fire({
            title: 'Completado',
            text: 'Acciones asignadas a ' + facturas.length + ' factura(s) correctamente',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });

        var desde = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var hasta = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
        GetData(desde, hasta);
    }


    function TableAcciones(selector, data, Factura) {
        var Acciones = data.ACCIONES || [];

            $(selector).html(Acciones.length === 0 ?
                `<div class="d-flex flex-wrap gap-2 justify-content-center text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="text-center">
                                No hay acciones registradas <br>
                                <a href="#" onclick="AsignarAcciones('${Factura}')">
                                    Asignar acciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>` :
                `<div class="d-flex flex-wrap gap-2 justify-content-center">
                    ${Acciones.map(accion => `<span class="badge rounded-pill bg-primary px-3 py-2 fs-6">${accion.ACCION}</span>`).join('')}
                </div>`
            );

            $('#btn_imprimir_acciones').prop('disabled', (Acciones.length === 0 ? true : false));
            $('#btn_revertir_acciones').prop('disabled', (Acciones.length === 0 ? true : false));

    }
    
    function TablaOrdenesCompras(selector, data) {
        var table = $(selector).DataTable({
            data: data.FACTURACION.DATA,
            destroy: true,
            paging: true,
            pageLength: 25,
            info: false,
            searching: true,
            ordering: true,
            columns: [
                { 
                    title: '<div class="text-center"><input type="checkbox" id="select-all" class="form-check-input m-0"></div>', 
                    data: null, 
                    className: 'text-center align-middle', 
                    orderable: false,
                    render: function(data) {
                        return '<div class="d-flex justify-content-center align-items-center h-100"><input type="checkbox" class="row-checkbox form-check-input m-0" value="' + data.FACTURA + '" data-isaccion="' + data.IsAccion + '"></div>';
                    }
                },
                { title: 'FACTURA', data: 'FACTURA', className: 'text-center', render: function(data, type, row) {
                    const rowData = encodeURIComponent(JSON.stringify(row));
                    return `<strong><a href='#!' onclick="OpenModal('${rowData}')">${data}</a>
                    </strong>`;
                }},
                
                { title: 'CLIENTE', data: 'CLIENTE', className: 'text-center',render: function(data, type, row) {
                    //return `<div class="item-center"><strong>${data}</strong></div>`;

                    return `<strong><a href='https://carro.unimarksa.com/api/Perfil/${row.CLIENTE}' target="_blank'">${data}</a> </strong>`
                }          
                },
                
                { title: 'NOMBRE', data: 'NOMBRE', className: 'text-left',render: function(data, type, row) {
                    return `<div class="item-center"><strong>${data}</strong></div>`;
                    }          
                },  
                
                { title: 'VENDEDOR', data: 'VENDEDOR', className: 'text-left'},
                { title : 'FECHA FACTURA', data: 'FECHA', className: 'text-center'},
                { title: 'ACCIONES', data: 'ACCIONES', className: 'text-center',render: function(data, type, row) {
                    return `<div class="item-center"><strong>  ${row.ACCIONES} </strong></div>`;
                    }          
                },  
                { title: 'ESTADO', data: 'IsAccion', className: 'text-center',render: function(data, type, row) {
                    if (row.IsAccion === 'S') {
                        return '<span class="badge bg-success">Con Acciones</span>';
                    }
                    return '<span class="badge bg-warning text-dark">Sin Acciones</span>';
                    }          
                }, 
                { title: 'TOTAL C$', data: 'TOTAL_FACTURA', render: function(data, type, row) {
                    return `<div class="item-right">${numeral(data).format('0,0.00')} </div>`;
                    }          
                },
            ],
            rowCallback: function(row, data, index) {
                var $row = $(row);
                $row.removeClass('table-success');
                if(data.IsAccion === 'S') {
                    $row.addClass('table-success');
                }
            },
        });


        $("#total_ordenes").html(`C$. ${numeral(data.FACTURACION.TOTAL_FACTURADO).format('0,0.00')}`);

        $("#TOTAL_CLIENTES").html(`${numeral(data.FACTURACION.TOTAL_CLIENTES).format('0,0')}`);
        $("#TOTAL_FACTURAS").html(`${numeral(data.FACTURACION.TOTAL_FACTURAS).format('0,0')}`);
        $("#TOTAL_ACCIONES").html(`${numeral(data.FACTURACION.TOTAL_ACCIONES).format('0,0.00')}`);
        $("#ULTIMA_ACCION").html(`${data.FACTURACION.ULTIMA_ACCION}`);

        $("#TOTAL_SIN_ACCIONES").text("Sin Acciones: " + data.FACTURACION.TOTAL_SIN_ACCIONES);
        $("#porcentaje-disponible").html('Disponible: <strong>' + data.FACTURACION.PORCENTAJE_DISPONIBLE + '%</strong>');

        $("#acciones-asignadas").html("Asignadas: " + numeral(data.FACTURACION.TOTAL_ACCIONES_ASIG).format('0,0'))

        $(selector + '_length').hide();
        $(selector + '_filter').hide();
    }


    async function GetData(desde, hasta){
        try {
            eneableButton(true,'Calc...') ;

            
            const response = await fetch('getFactPromocion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    desde: desde, 
                    hasta: hasta
                })
            });

            const result = await response.json();

            TablaOrdenesCompras('#tbl_ordenes_compras', result);


            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }
    async function GetAcciones(Factura){
        try {
            eneableButton(true,'Calc...') ;

            
            const response = await fetch('getFactAcciones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    Factura: Factura
                })
            });

            const result = await response.json();

            TableAcciones('#tbl_factura_acciones', result, Factura);


            eneableButton(false,'<i class="fas fa-filter"></i> Filtrar')

        } catch (error) {
            console.error('Error al obtener los datos:', error);
            eneableButton(false,null)
        }
    }
</script>