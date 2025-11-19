<script>
$(document).ready(function() {
	fullScreen();
	fechas = {};

    $('input[name="dt_range"]').daterangepicker({
        "autoApply": true,
            ranges: {
            'Hoy': [moment(), moment()],
            'Últm. 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últm. 30 Días': [moment().subtract(29, 'days'), moment()],
            
            'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
            'Semana Anterior': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
            
            'Este Mes': [moment().startOf('month'), moment()],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            
            //'1 Año': [moment().subtract(1, 'year'), moment()],
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
        //console.log('Nuevo rango seleccionado: ' + start.format('YYYY-MM-DD') + ' a ' + end.format('YYYY-MM-DD') + ' (rango: ' + label + ')');
        //CallFilter(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        setFechas(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    });


	// $('#dom-id').dateRangePicker({
	// 	language: 'es',
	// 	singleMonth: true,
	// 	showShortcuts: false,
	// 	startOfWeek: 'monday',
	// 	separator : ' al ',
	// 	showTopbar: false,
	// 	autoClose: true,
	// 	setValue: function(s,s1,s2) {
	// 		setFechas(s1, s2)
	// 	}
	// });

	$("#item-nav-01").after(`<li class="breadcrumb-item active">Inteligencia de Mercado</li>`);
});

var fechas = {};

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();

    if ($(this).parent().hasClass('active')) return;

    const page = $(this).attr('href').split('page=')[1];

    fetch_data(page);
});


$(document).on('change', '#orderByDate', function (e) {
	fetch_data(1)
})

$(document).on('keyup','#search', function (event) {
	fetch_data(1);
 });

function setFechas(f1, f2) {
	fechas = { fecha1:f1, fecha2:f2 };
	fetch_data(1);
}

function fetch_data(page) {
    let value = $('#search').val();
    let valueDate = $('#orderByDate').val();
    let fechas_ = fechas;

    $('.comentarios').html(`
        <div class="text-center mt-5 mb-5">
            <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;"></div>
            <p class="mt-3 font-weight-bold text-secondary">Cargando comentarios...</p>
        </div>
    `);

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
        success: function(data) {console.log(data)
            $('.comentarios').hide().html(data).fadeIn(300);
        },
        error: function() {
            $('.comentarios').html(
                '<p class="text-center text-danger mt-3">Error al cargar los comentarios</p>'
            );
        }
    });

}


$(document).on('click', '.cardComentario', function() {
	let autor = $(this).data('autor');
    let id = $(this).data('id');
    let titulo = $(this).data('titulo');
    let contenido = $(this).data('contenido');
    let nombre = $(this).data('nombre');
    let fecha = $(this).data('fecha');

    $('#comentario_id').val(id);

    // Construir mensaje principal
    $('#comentario_principal').html(`
        <div class="direct-chat-infos clearfix">
            <span class="direct-chat-name float-left">${nombre}</span>
            <span class="direct-chat-timestamp float-right">${fecha}</span>
        </div>
        <div class="direct-chat-text">
            <strong>${titulo}</strong><br>${contenido}
        </div>
    `);

    // Limpiar respuestas antes de cargar
    $('#contenedor_respuestas').html("Cargando respuestas...");

    // Obtener respuestas vía AJAX
    $.get("comentarios/respuestas/" + id, function(res){
        let html = "";
        if(res.length === 0){
            html = `<p class="text-muted text-center">Sin respuestas aún...</p>`;
        } else {
           res.forEach(r => {

    const esAutor = (r.created_by === autor);

    html += `
    <div class="direct-chat-msg mb-3 clearfix">

        <div class="direct-chat-text p-3"
            style="
                background:${esAutor ? '#64bc61' : '#e9ecef'};
                color:${esAutor ? '#fff' : '#333'};
                border-radius:18px;
                line-height:1.4;
                max-width:82%;
                float:${esAutor ? 'right' : 'left'};
                clear:both;
                box-shadow:0px 2px 6px rgba(0,0,0,0.15);
            ">

            <!-- Usuario -->
            <div class="w-100 mb-1" style="font-size:13px; font-weight:bold;
                text-align:${esAutor ? 'right' : 'left'};">
                ${r.created_by}
            </div>

            <!-- Mensaje -->
            <div class="w-100 mb-1"
                style="text-align:left">
                ${r.comments}
            </div>

            <!-- Fecha -->
            <div class="w-100 text-muted"
                style="font-size:11px;
                text-align:${esAutor ? 'right' : 'left'};">
                ${moment(r.created_at).format('DD/MM/YYYY h:mm a')}
            </div>

        </div>

        <div style="clear:both;"></div>
    </div>`;
});


        }
        $('#contenedor_respuestas').html(html);
    });

    $('#modalChat').modal('show');
});


function descargarArchivo() {
	valueFiltro	= $('#search').val();
	valueDate 	= $('#orderByDate').val();
	
	valueFecha1	= ( Object.entries(fechas).length===0 )?'ND':fechas['fecha1'];
	valueFecha2	= ( Object.entries(fechas).length===0 )?'ND':fechas['fecha2'];

	$('<input />')
	.attr('type', 'hidden')
	.attr('name', "valueFiltro_")
	.attr('value', valueFiltro)
	.appendTo('#fmrDescargarComent');

	$('<input />')
	.attr('type', 'hidden')
	.attr('name', "valueDate_")
	.attr('value', valueDate)
	.appendTo('#fmrDescargarComent');

	$('<input />')
	.attr('type', 'hidden')
	.attr('name', "valueFecha1")
	.attr('value', valueFecha1)
	.appendTo('#fmrDescargarComent');

	$('<input />')
	.attr('type', 'hidden')
	.attr('name', "valueFecha2")
	.attr('value', valueFecha2)
	.appendTo('#fmrDescargarComent');

	$('#fmrDescargarComent').submit();
}

$(document).on('click', '.img-fluid', function (e) {
	url_image = $(this).attr('src');
	swal({
		showCloseButton: true,
		showConfirmButton: false,
		imageUrl: url_image,
		imageAlt: 'Custom image'
	})

	$(".swal2-popup").css('width', '50%');
})

$(document).on('mouseenter','.card', function (event) {
    $( this ).removeClass('border-light').addClass('border-primary')
}).on('mouseleave','.card',  function(){
	$( this ).removeClass('border-primary').addClass('border-light')
});

</script>