<script type="text/javascript">
$(document).ready(function() {

});

$('.menu_rol').on('change', function() {
	var data = {
		menu_id: $(this).data('menuid'),
		rol_id: $(this).val(),
		_token: $('input[name=_token]').val()
	}

	if ($(this).is(':checked')) {
		data.estado = 1
	} else {
		data.estado = 0
	}
	ajaxRequest('menu-rol', data)
})

function ajaxRequest(url, data) {
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		success: function( respuesta ) {
			//alert('El rol se asigno correctamente')
		}
	})
}
</script>