<script type="text/javascript">
	$(document).ready(function() {
		fullScreen();
		
		//AGREGO LA RUTA AL NAVEGADOR
		$("#item-nav-01").after(`<li class="breadcrumb-item"><a href="Usuario">Usuario</a></li><li class="breadcrumb-item active">Registro</li>`);
	});

	$('#company').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		$('#company_values').val( $('#company').val());
	});

	$('#rutas').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		$('#rutas_values').val( $('#rutas').val());
	});

	$("#role").change( function(event) {
		
		if ( $(this).val() == 4 ) {   			
   			$('#rutas').attr('disabled', false)
		}else {
			$('#rutas_values').val('');			
			$('#rutas').val([]).attr('disabled', true)
		}

		$('.selectpicker').selectpicker('refresh');

	})

</script>