<script>
// ============================================================================
// VARIABLES GLOBALES
// ============================================================================
var fechas = {};

// ============================================================================
// INICIALIZACIÓN DEL DOCUMENTO
// ============================================================================
$(document).ready(function() {
    inicializarPantalla();
    inicializarCKEditor();
    inicializarDateRangePicker();
    inicializarBreadcrumb();
    cargarDatosIniciales();
});

// ============================================================================
// FUNCIONES DE INICIALIZACIÓN
// ============================================================================

/**
 * Configura la pantalla en modo fullscreen
 */
function inicializarPantalla() {
    fullScreen();
}

/**
 * Inicializa el editor de texto CKEditor para respuestas
 */
function inicializarCKEditor() {
    CKEDITOR.replace('respuesta', {
        language: 'es',
        toolbar: [{ name: 'basicstyles', items: ['Bold', 'Italic'] }],
        removeButtons: 'Underline,Strike,Subscript,Superscript,RemoveFormat,Copy,Paste,Undo,Redo,Link,Unlink,Image,Table,Source',
        allowedContent: true,
        height: '100px'
    });
}

/**
 * Configura el selector de rango de fechas
 */
function inicializarDateRangePicker() {
    $('input[name="dt_range"]').daterangepicker({
        autoApply: true,
        ranges: {
            'Hoy': [moment(), moment()],
            'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
            'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
            'Semana Anterior': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
            'Este Mes': [moment().startOf('month'), moment()],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        showCustomRangeLabel: false,
        alwaysShowCalendars: true,
        startDate: moment().startOf('month').format('D MMM. YYYY'),
        endDate: moment().format('D MMM. YYYY'),
        opens: 'left',
        locale: {
            format: "D MMM. YYYY",
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
        setFechas(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });
}

/**
 * Agrega el breadcrumb de navegación
 */
function inicializarBreadcrumb() {
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Inteligencia de Mercado</li>`);
}

/**
 * Carga los datos iniciales de la página
 */
function cargarDatosIniciales() {
    fetch_data(1);
}

// ============================================================================
// EVENT LISTENERS
// ============================================================================

/**
 * Filtrar datos por fechas
 */
$('#filtrarFechas').on('click', function() {
    fetch_data(1);
});

/**
 * Búsqueda en tiempo real
 */
$('#search').on('keyup', function(event) {
    fetch_data(1);
});

/**
 * Búsqueda en DataTable de importaciones
 */
$("#id_search_importaciones").on('keyup', function() {
    var searchTerm = $(this).val().toLowerCase();
    $('#tbl_topsku_clientes').DataTable().search(searchTerm).draw();
});

/**
 * Cambio en ordenamiento por fecha
 */
$('#orderByDate').on('change', function(e) {
    fetch_data(1);
});

/**
 * Paginación
 */
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    if ($(this).parent().hasClass('active')) return;
    
    const page = $(this).attr('href').split('page=')[1];
    fetch_data(page);
});

/**
 * Mostrar imagen en modal al hacer clic
 */
$(document).on('click', '.img-fluid', function(e) {
    Swal.fire({
        showCloseButton: true,
        showConfirmButton: false,
        imageUrl: $(this).attr('src')
    });
    $(".swal2-popup").css('width', '50%');
});

/**
 * Efecto hover en tarjetas
 */
$(document).on('mouseenter', '.card', function(event) {
    $(this).removeClass('border-light').addClass('border-primary');
}).on('mouseleave', '.card', function() {
    $(this).removeClass('border-primary').addClass('border-light');
});

/**
 * Abrir modal de comentario con detalles
 */
$(document).on('click', '.cardComentario', function() {
    mostrarDetalleComentario($(this));
});

// ============================================================================
// FUNCIONES DE DATOS
// ============================================================================

/**
 * Establece el rango de fechas y recarga los datos
 * @param {string} f1 - Fecha inicial (YYYY-MM-DD)
 * @param {string} f2 - Fecha final (YYYY-MM-DD)
 */
function setFechas(f1, f2) {
    fechas = { fecha1: f1, fecha2: f2 };
    fetch_data(1);
}

/**
 * Obtiene los datos filtrados del servidor
 * @param {number} page - Número de página a cargar
 */
function fetch_data(page) {
    let value = $('#search').val();
    let valueDate = $('#orderByDate').val();
    let fechas_ = fechas;
    
    // Deshabilitar botón y mostrar loading
    eneableButton(true, 'Calc...');
    
    // Mostrar spinner de carga
    $('.comentarios').html(`
        <div class="text-center mt-5 mb-5">
            <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;"></div>
            <p class="mt-3 font-weight-bold text-secondary">Cargando comentarios...</p>
        </div>
    `);

    // Petición AJAX
    $.ajax({
        type: 'POST',
        url: 'paginateDataSearch',
        data: {
            search: value,
            date: 'desc',
            page: page,
            fechas: fechas_,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $('.comentarios').hide().html(data).fadeIn(300);
        },
        error: function() {
            $('.comentarios').html(
                '<p class="text-center text-danger mt-3">Error al cargar los comentarios</p>'
            );
        },
        complete: function() {
            // Habilitar botón nuevamente
            eneableButton(false, '<i class="fas fa-filter"></i> Filtrar');
        }
    });
}

// ============================================================================
// FUNCIONES DE UI
// ============================================================================

/**
 * Habilita/deshabilita el botón de filtrar
 * @param {boolean} EnableButton - true para deshabilitar, false para habilitar
 * @param {string} textButton - Texto a mostrar en el botón
 */
function eneableButton(EnableButton, textButton = '<i class="fas fa-filter"></i> Filtrar') {
    $('#filtrarFechas').prop('disabled', EnableButton);
    $('#filtrarFechas').html(
        '<i class="fas fa-spinner fa-spin" style="display:' + 
        (EnableButton ? 'inline-block' : 'none') + '"></i> ' + textButton
    );
}

/**
 * Muestra el detalle de un comentario en el modal
 * @param {jQuery} elemento - Elemento clickeado con los datos
 */
function mostrarDetalleComentario(elemento) {
    // Extraer datos del elemento
    let autor = elemento.data('autor');
    let id = elemento.data('id');
    let titulo = elemento.data('titulo');
    let contenido = elemento.data('contenido');
    let nombre = elemento.data('nombre');
    let fecha = elemento.data('fecha');
    let imagen = elemento.data('imagen');
    let oneSignal = elemento.data('onesignal');

    // Establecer valores en el modal
    $('#comentario_id').val(id);
    $('#oneSignal').val(oneSignal);
    $('#txtTitle').html(titulo.toUpperCase());
    $('#txtAutor').html(autor);
    $('#txtAutorNombre').html(nombre);
    $('#txtCreado').html(fecha);

    // Construir mensaje principal
    $('#comentario_principal').html(`
        <div class="row">
            <div class="col-md-3 col-lg-3 col-xl-3 mb-lg-0">
                <div class="bg-image hover-zoom ripple rounded ripple-surface">
                    ${imagen ? `<img src="${imagen}" id="id_product_img" class="img-fluid img-thumbnail w-100" />` : ``}
                    <a href="#!">
                        <div class="hover-overlay">
                            <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-9 col-lg-9 col-xl-9">
                <h5 id="tArticulo">${titulo.toUpperCase()}</h5>
                <div class="d-flex flex-row" style="font-size: 0.9rem; line-height:1.4;">
                    ${contenido}
                </div>
            </div>
        </div>
    `);

    // Cargar respuestas
    cargarRespuestas(id, autor);

    // Mostrar modal
    $('#modalChat').modal('show');
}

/**
 * Carga las respuestas de un comentario
 * @param {number} id - ID del comentario
 * @param {string} autor - Nombre del autor del comentario principal
 */
function cargarRespuestas(id, autor) {
    $('#contenedor_respuestas').html("Cargando respuestas...");

    $.get("comentarios/respuestas/" + id, function(res) {
        let html = "";

        if (res.length === 0) {
            html = `<p class="text-muted text-center">Sin respuestas aún...</p>`;
        } else {
            res.forEach(r => {
                const esAutor = (r.created_by === autor);

                html += `
                <div class="mb-3 clearfix">
                    <div class="p-3 w-100"
                        style="
                            background:${esAutor ? '#64bc61' : '#e9ecef'};
                            color:${esAutor ? '#fff' : '#333'};
                            border-radius:18px;
                            line-height:1.4;
                            clear:both;
                            box-shadow:0px 2px 6px rgba(0,0,0,0.15);
                        ">
                        <!-- Usuario -->
                        <div class="w-100 mb-1 d-flex align-items-center" 
                             style="font-size:13px; font-weight:bold; text-align:${esAutor ? 'right' : 'left'};">
                            <img src="{{ asset('images/avatar-4.jpg') }}" 
                                 class="rounded-circle me-2 mr-1" width="25" height="25">
                            ${r.created_by} • ${moment(r.created_at).fromNow()}
                        </div>
                        <!-- Mensaje -->
                        <div class="w-100 mb-1" style="text-align:left; font-size:1.2em">
                            ${r.comments}
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </div>`;
            });
        }

        $("#total_comentarios").html("( " + res.length + " )");
        $('#contenedor_respuestas').html(html);
    });
}

// ============================================================================
// FUNCIONES DE DESCARGA
// ============================================================================

/**
 * Descarga el archivo de comentarios con los filtros aplicados
 */
function descargarArchivo() {
    let valueFiltro = $('#search').val();
    let valueDate = $('#orderByDate').val();
    let valueFecha1 = $('input[name="dt_range"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
    let valueFecha2 = $('input[name="dt_range"]').data('daterangepicker').endDate.format('YYYY-MM-DD');

    // Agregar campos ocultos al formulario
    agregarCampoOculto('valueFiltro_', valueFiltro);
    agregarCampoOculto('valueDate_', valueDate);
    agregarCampoOculto('valueFecha1', valueFecha1);
    agregarCampoOculto('valueFecha2', valueFecha2);

    // Enviar formulario
    $('#fmrDescargarComent').submit();
}

/**
 * Agrega un campo oculto al formulario de descarga
 * @param {string} name - Nombre del campo
 * @param {string} value - Valor del campo
 */
function agregarCampoOculto(name, value) {
    $('<input />')
        .attr('type', 'hidden')
        .attr('name', name)
        .attr('value', value)
        .appendTo('#fmrDescargarComent');
}

</script>