<script>
$(document).ready(function() {
    fullScreen();
    //AGREGO LA RUTA AL NAVEGADOR
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Usuario</li>`);

    $('#dtUsuarios').DataTable({
    	
    	"info":    false,
    	"lengthMenu": [[10,30,50,100,-1], [20,30,50,100,"Todo"]],
    	"language": {
    	    "zeroRecords": "Cargando...",
    	    "paginate": {
    	        "first":      "Primera",
    	        "last":       "Última ",
    	        "next":       "Siguiente",
    	        "previous":   "Anterior"
    	    },
    	    "lengthMenu": "MOSTRAR _MENU_",
    	    "emptyTable": "NO HAY DATOS DISPONIBLES",
    	    "search":     "BUSCAR"
    	},
    	
        "columnDefs": [
            { "width": "17%", "targets": [ 0 ] },
            { "width": "10%", "targets": [ 1 ] },
            { "width": "10%", "targets": [ 2 ] },
            { "width": "25%", "targets": [ 3 ] },
            { "width": "15%", "targets": [ 4 ] },
            { "width": "10%", "targets": [ 5 ] },
            { "width": "13%", "targets": [ 6 ] }
        ],
    });

    $("#dtUsuarios_length").hide();
    $("#dtUsuarios_filter").hide();
    //inicializaControlFecha();
});

$('#InputDtShowSearchFilterUser').on( 'keyup', function () {
	var table = $('#dtUsuarios').DataTable();
	table.search(this.value).draw();
});

$( "#InputDtShowColumnsUser").change(function() {
	var table = $('#dtUsuarios').DataTable();
	table.page.len(this.value).draw();
});

$('#company').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {//capturar los valores seleccionados del multiselect en un span para luego obtenerlo en un array
    $('#edit_company_values').val( $('#company').val());
});


$(document).on('click','#editUserModal', function(){
    
    var companiesRes = getCompaniesByUser($(this).data('id'));
    var companiesId = new Array();

    $.each( companiesRes, function( key, value ) {//agregar id de companies a un arreglo para despues pasarlo al DOM para ser leido por el multiselect
      companiesId[key] = value.id;
    });

    $('#edit_company_values').val(companiesId.join(','));
    
    $('select[name=editRole]').val($(this).data('role')).selectpicker('refresh');
        
    $('select[name=company]').val(companiesId).selectpicker('refresh');//refresca el selectpicker de bootstrap

    $("#idUser").val($(this).data('id'));
    $("#name").val($(this).data('name'));
    $("#surname").val($(this).data('surname'));
    $("#email").val($(this).data('email'));
    $("#description").val($(this).data('description'));  

  
});

$('.editActionBtn').on('click', function(){
    $.ajax({
        url:"editUser",
        type:"POST",
        data:{
            "_token": $("input[name=_token]").val(),
            "id": $("#idUser").val(),
            "name": $("#name").val(),
            "surname": $("#surname").val(),
            "email": $("#email").val(),
            "role": $('select[name=editRole]').val(),
            "description": $("#description").val(),
            "company_id": $('#edit_company_values').val()

        },
        success: function(){
            location.reload();
        }

    });
});
$(document).on('click','#deleteUserModal',function(){
    var companiesRes = getCompaniesByUser($(this).data('id'));
    var companiesId = new Array();

    $.each( companiesRes, function( key, value ) {//agregar id de companies a un arreglo para despues pasarlo al DOM para ser leido por el multiselect
      companiesId[key] = value.id;
    });

    
    $("#idCompanyToDelete").text(companiesId.join(','));
    $("#idUserToDelete").text($(this).data('id'));

});



$(".deleteActionBtn").on('click', function(){

    
    $.ajax({
        url:"deleteUser",
        type:"POST",
        data:{
            "_token": $("input[name=_token]").val(),
            "id": $("#idUserToDelete").text(),
            "company_id": $('#idCompanyToDelete').text()
        },
        success: function(){
            location.reload();
        }

    });

})

$(".estadoBtn").on('click', function(){

    $.ajax({
        url:"changeUserStatus",
        type:"POST",
        data:{
            "_token": $("input[name=_token]").val(),
            "id": $(this).data("id"),
            "estado": $(this).data("status")
        },
        success: function(){
            location.reload();
        }
    });
})


function getCompaniesByUser(idUser){
    var companiesId = new Array();
    $.ajax({
        url:"usuario/"+idUser+"/companies",
        method:"GET",
        async:false,
        success: function(res){
            
              companiesId = res;
        
        }

    });
    return companiesId;
}


</script>