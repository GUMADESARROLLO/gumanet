<script>
	$(document).ready(function() {
		fullScreen();
	    //AGREGO LA RUTA AL NAVEGADOR
	    $("#item-nav-01").after(`<li class="breadcrumb-item active">Metas</li>`);
	    $('#disabledLoaderBtn').hide();
	    $("#disabledLoaderBtnProcess").hide();
	    $('#bloqueTblExcVenta').hide(); 
	    $('#bloqueTblExcRecup').hide();
	    $("#verMetasAgregadasXMes").hide();
	    $("#alertMetas").hide();
	    if($("#radioMeta1").is(':checked')){//cambiar texto del boton
	    	$("#btnShowModalExl").text("Agregar");
	    }else{
	    	$("#btnShowModalExl").text("Visualizar");
	    }
	    $('#tblExcelImportMetaRecu').DataTable({
			
	    	'pageLength' : 10,
	    	'bFilter':false,
	    	'lengthChange': false,
	    	'info':    false,
	    	'lengthMenu': [[10,30,50,100,-1], [20,30,50,100,'Todo']],
	    	'language': {
    			'sProcessing': 'Procesando...',
    	    	'zeroRecords': 'Cargando...',
	    	    'paginate': {
	    	        'first':      'Primera',
	    	        'last':       'Última ',
	    	        'next':       'Siguiente',
	    	        'previous':   'Anterior'
		    	    },
	    	    'lengthMenu': 'MOSTRAR _MENU_',
	    	    'emptyTable': 'NO HAY DATOS DISPONIBLES',
	    	    'infoEmpty': 'NO HAY DATOS DISPONIBLES',
	    	    'search':     'BUSCAR'
	    	}, 
	    	columnDefs:[
	    		{"targets": [ 0 ],"className": "dt-center"},
	    		{"targets": [ 2 ],"className": "dt-center"},
	    	]
    		
		    
		});
		$('tblExcelImportMetaRecu_length').hide();//Ocultar select que muestra cantidad de registros por pagina
    	$('tblExcelImportMetaRecu_filter').hide();//Esconde input de filtro de tabla por texto escrito
    
	});
	$('.custom-file-input').on('change',function(){//Mostrar nombrede archivo al seleccionarlo
    	
    	if ($(this).val()=='') {
			$('#fileLabelMeta').text('Seleccione un archivo Ecxel');
    	}else{
    		$('#fileLabelMeta').text($(this).val());
    	}
	});
	/*$('#selectTipoMeta').on('change', function(){
		
	});*/
	//Boton muestra modal de datos de metas a agregar o muestra datos de metas segun la fecha en de pendencia de que ratio Button este selecionado
	$("#btnShowModalExl").on('click', function(){
		if (validarCamposMeta()) {//Validar mes y año que no esten vacios
			$("#alertMetas").hide();
			if($("#radioMeta1").is(':checked')){
				if ($("#selectTipoMeta option:selected").val() == 'recu'){
					if(!existeFechaMetaRecu()){
						$('#btnShowModalExl').hide();
						$('#disabledLoaderBtn').show();
						$('#bloqueTblExcRecup').show();
						$('#bloqueTblExcVenta').hide();
						exportarDatosExlAModalMetasRecu();
					}else{
						$("#alertMetas").show();
						$("#alertMetas").css({"color":"red","font-weight":"bold"});
						$("#alertMetas").text("Ya existe meta con la fecha seleccionada");
					}
				}else{
					if(!existeFechaMeta()){// si no existe mes y año seleciionados en datos de metas muestra el modal
						$('#btnShowModalExl').hide();
						$('#disabledLoaderBtn').show();
						$('#bloqueTblExcRecup').hide();
						$('#bloqueTblExcVenta').show();
						truncate_tmp_exl_tbl();//Borrar registro de tabla temporal en phpMyAdmin
						exportarDatosExlAModalMetas();//Funcion para exportar datos de excel a la tabla temporal
					}else{// existe mes y a{ño uestra un alerta de bootstrap

						swal({
						  html: `<p class="font-weight-bold">¡Ya existe un registo con esta fecha!</p>
						  		<p class="font-weight-normal">¿Quiere sobreescribir estos registros?</p>`,
						  text: "¿Deseas sobreescribir estos registros?",
						  icon: 'warning',
						  showCancelButton: true,
						  confirmButtonColor: '#3085d6',
						  cancelButtonColor: '#d33',
						  confirmButtonText: 'Sí, sobreescribir'
						}).then((result) => {
						  if (result.value) {
							$('#btnShowModalExl').hide();
							$('#disabledLoaderBtn').show();
							$('#bloqueTblExcRecup').hide();
							$('#bloqueTblExcVenta').show();
						  	truncate_tmp_exl_tbl();
						  	exportarDatosExlAModalMetas();
						  }
						})

						//$("#alertMetas").show();
						//$("#alertMetas").css({"color":"red","font-weight":"bold"});
						//$("#alertMetas").text("Ya existe meta con la fecha seleccionada");
					}
			    }
			}else{
			    	
			    	if ($("#selectTipoMeta option:selected").val() == 'recu'){
			    		
			    		$('#bloqueTblVerMetasAgregadas').hide();
			    		$('#bloqueTblExcelVerMetaRecu').show();
			    		getHistoriaMetaRecu();
			    	}else{
			    		
			    		$('#bloqueTblVerMetasAgregadas').show();
			    		$('#bloqueTblExcelVerMetaRecu').hide();
			    		getHistorialMeta();//funcion para visualizar datos de metas por fecha agregados anteriormente 
			    		
			    }
			    
			    	$('#verMetasAgregadasXMes').show();
		    	}
		}else{
			$("#alertMetas").css({"color":"red","font-weight":"bold"});
			if($("#radioMeta1").is(':checked')){
				$("#alertMetas").text("*Seleccione un valor valido para cada campo*");
			}else{
				$("#alertMetas").text("*Seleccione un valor valido para cada campo*");
			}
			
			$("#alertMetas").show();
			
		}
		
	});
	$('#procesarModalMetaExl').on('click', function(){
		
		$("#procesarModalMetaExl").hide();
		$("#cancelModalMetaBtn").hide();
		$("#disabledLoaderBtnProcess").show();
		if($('#selectTipoMeta option:selected').val() =='recu'){
			addDatatableDatosMetaRecu();
		}else{
			addDatatableDatosMeta();
		}
		
	});
	$("#radioMeta1").on('click', function(){
		$("#btnShowModalExl").text("Agregar");
	    $("#contInputExlFileMetas").show();
	    $("#verMetasAgregadasXMes").hide();
	    $("#alertMetas").hide();
	});
	$("#radioMeta2").on('click', function(){
		$("#btnShowModalExl").text("Visualizar");
	    $("#contInputExlFileMetas").hide();
	    $("#verMetasAgregadasXMes").hide();
	    $("#alertMetas").hide();
	});
	$('#selectTipoMeta').on('change',function(){
		$("#verMetasAgregadasXMes").hide();
	    $("#alertMetas").hide();
	})
	function getHistorialMeta(){
		var columnDefs = new Array();
		var columns= new Array();
		columnDefs=[//tamaños de cplumans del datatable
				{ "width": "3%", "targets": [ 0 ],"className": "dt-center" },
	            { "width": "3%", "targets": [ 1 ],"className": "dt-center" },
	            { "width": "24%", "targets": [ 2 ] },
	            { "width": "10%", "targets": [ 3 ],"className": "dt-center" },
	            { "width": "10%", "targets": [ 4 ],"className": "dt-center" }
	            ];
		   
	    columns=[//variables para recibir datos de columnas del datatable
	    		{data:'CodVendedor', name:'CodVendedor'},
	    		{data:'CodProducto', name:'CodProducto'},
		        {data:'NombreProducto', name:'NombreProducto'},
		        {data:'Meta', name:'Meta'},
		        {data:'val', name:'val'}
		        ];
		inicialDataTablesMetas('#tblVerMetasAgregadas','get_historial_meta',columns,columnDefs);	// funcion que recibe el id del datatable a mostrar el url de la ruta y las vasriables para los datos de las columnas
	}
	function getHistoriaMetaRecu(){
		var columnDefs = new Array();
		var columns= new Array();
		columnDefs=[//tamaños de cplumans del datatable
				{ "width": "10%", "targets": [ 0 ],"className": "dt-center" },
	            { "width": "70%", "targets": [ 1 ] },
	            { "width": "20%", "targets": [ 2 ],"className": "dt-center" }
	            ];
		   
	    columns=[//variables para recibir datos de columnas del datatable
	    		{data:'ruta', name:'ruta'},
	    		{data:'vendedor', name:'vendedor'},
		        {data:'meta', name:'meta'}
		        ];
		inicialDataTablesMetas('#tblExcelVerMetaRecu','getHistoriaMetaRecu',columns,columnDefs);	// funcion que recibe el id del datatable a mostrar el url de la ruta y las vasriables para los datos de las columnas
	}
	function get_tmp_exl_data(){
		var columnDefs = new Array();
		var columns= new Array();
		
		columnDefs=[//tamaños de cplumans del datatable
			{ "width": "3%", "targets": [ 0 ], "className": "dt-center" },
            { "width": "3%", "targets": [ 1 ], "className": "dt-center" },
            { "width": "24%", "targets": [ 2 ] },
            { "width": "10%", "targets": [ 3 ] },
            { "width": "40%", "targets": [ 4 ] },
            { "width": "10%", "targets": [ 5 ],"className": "dt-center" },
            { "width": "10%", "targets": [ 6 ],"className": "dt-center" }
            ];
	   
    	columns=[//variables para recibir datos de columnas del datatable
    		{data:'ruta', name:'ruta'},
	        {data:'codigo', name:'codigo'},
	        {data:'cliente', name:'cliente'},
	        {data:'articulo', name:'articulo'},
	        {data:'descripcion', name:'descripcion'},
	        {data:'valor', name: 'valor'},
		    {data:'unidad', name:'unidad'}
	        ];
		inicialDataTablesMetas('#tblExcelImportMeta','get_tmp_exl_data',columns,columnDefs);// funcion que recibe el id del datatable a mostrar el url de la ruta y las vasriables para los datos de las columnas
    	
		
	}
	
	function truncate_tmp_exl_tbl(){// funcion que trunca la tabla temporal que esta en phpMyadmin mysql antes de agregarle datos
		$.ajax({
			url:"truncate_tmp_exl_tbl"
		});
	}
	function exportarDatosExlAModalMetasRecu(){
	
		$('#tblExcelImportMetaRecu').DataTable().clear().draw();
		var objTable =$('#tblExcelImportMetaRecu').DataTable();
		var formData = new FormData($("#export_excel")[0]);
		var mes = $("#selectMesMeta option:selected").val();
		var anno = $("#selectAnnoMeta option:selected").val();
		 $('tblExcelImportMetaRecu_length').hide();//esconder select que muestra cantidad de registros por pagina
    			$('tblExcelImportMetaRecu_filter').hide();//Esconde input de filtro de tabla por texto escrito
		
		
		formData.append("mes", mes);
		formData.append("anno", anno);
		$.ajax({
			url:"export_meta_from_exl_venta",
			method:"POST",
			data:formData,
			contentType:false,
            processData: false,
            success: function(res){
            	for (f=0;f<res.length;f++){
            
		            objTable.row.add
		            ( [
		              res[f].ruta,
		              res[f].vendedor,
		              res[f].meta
		            ] ).draw( false )
		        }
		       
            	//esconder, cambiar texto, y mostrar modal
            	$('#mesModalExl').text($("#selectMesMeta option:selected").text());
	            $('#annoModalExl').text(anno);
            	$('#disabledLoaderBtn').hide();
		        $('#modalShowModalExl').modal({backdrop: 'static', keyboard: false}); //modal no desaparecera si se le hace clik fuera de el
		    	$('#modalShowModalExl').modal('show');
		    	$('#btnShowModalExl').show();
            }
		});
	}

	function exportarDatosExlAModalMetas() {
        var fileUpload = $("#addExlFileMetas")[0];
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;


        if (regex.test(fileUpload.value.toLowerCase())) { 
			if (typeof (FileReader) != "undefined") {
				var reader = new FileReader();

                //For Browsers other than IE.
                if (reader.readAsBinaryString) {
                    reader.onload = function (e) {

						var mes = $("#selectMesMeta option:selected").val(); 
						var anno = $("#selectAnnoMeta option:selected").val();						
                        
                        objExcel = ProcessExcel(e.target.result);

                        var formData = new FormData();

                        var data = objExcel;
						formData.append("data", JSON.stringify(data));

						$.ajax({
							url:"export_meta_from_exl",
							method:"POST",
							data:formData,
							contentType:false,
				            processData: false,
				            async: true,
				            success: function(res) {
				            	//ocultar, cambiar texto, y mostrar modal
				            	$('#mesModalExl').text($("#selectMesMeta option:selected").text());
					            $('#annoModalExl').text(anno);
				            	get_tmp_exl_data();
				            	$('#disabledLoaderBtn').hide();
						        $('#modalShowModalExl').modal({backdrop: 'static', keyboard: false}); //modal no desaparecera si se le hace clik fuera de el
						    	$('#modalShowModalExl').modal('show');
						    	$('#btnShowModalExl').show();				            	
				            }
						});
                    };
                    reader.readAsBinaryString(fileUpload.files[0]);
                }
			}else {
                alert("Este navegador no es compatible con HTML5.");
            }
        }else {
            mensaje("Seleccione un archivo Excel válido", "error");
        }
	}

	$("#donwloadExcelPlantilla").click( function() {
		if ($("#selectTipoMeta option:selected").val() == 'recu'){ 
			document.location = "/gumanet/public/tmp_excel/META_RECUPERACION.xlsx";
		}else if($("#selectTipoMeta option:selected").val() == 'vent') {
			document.location = "/gumanet/public/tmp_excel/META_VENTAS.xlsx";
		}else if($("#selectTipoMeta option:selected").val() == '00') {
			mensaje("Seleccione una categoria para descargar el archivo", "error")
		}
	  
	})

    function ProcessExcel(data) {
    	var Dta = [];
        var workbook = XLSX.read(data, {
            type: 'binary'
        });

        var firstSheet = workbook.SheetNames[0];
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        
        //FECHA META
		var mes = $("#selectMesMeta option:selected").val(); 
		var anno = $("#selectAnnoMeta option:selected").val();
		var dHab = $("#sltDiasHabiles option:selected").val();
		//FECHA ACTUAL
		var date    = new Date();
		var anioA   = parseInt(date.getFullYear())
		var mesA    = parseInt(date.getMonth()+1);


        fechaActual 	= anioA+`-`+mesA+`-01`;
        fechaMeta 		= anno+`-`+mes+`-01`;

        for (var i = 0; i < excelRows.length; i++) {

			obj =   {
				fechaMeta 		: fechaMeta,
				ruta 			: excelRows[i].RUTA,
				codigo 			: (excelRows[i].COD==undefined)?'99999':excelRows[i].COD,
				cliente 		: (excelRows[i].CLIENTE==undefined)?'ND':excelRows[i].CLIENTE,
				articulo 		: excelRows[i].ARTICULO,
				descripcion 	: excelRows[i].DESCRIPCION,
				valor 			: excelRows[i].VAL,
				unidad 			: excelRows[i].UNID,
				dHab 			: dHab,
				created_at 		: fechaActual,
				updated_at 		: fechaActual
			}
			Dta.push(obj);
        }        
        return Dta;
    }
	
	function addDatatableDatosMetaRecu(){
		var datas = getDataFromTableMetaRecu();
		
		$.ajax({
			url:"addDataRecuToDB",
			method:"POST",
			data:{datas},
			success: function(res){
				if(res != 0){
					$('#tituloToastMeta').text("Datos Procesados");
					$('#tituloToastMeta').css('color','black');
					$('#toastProcesoMetaText').text("Los datos han sido procesado exitosamente!");
					$('#modalShowModalExl').modal('hide');
					$("#procesarModalMetaExl").show();
					$("#cancelModalMetaBtn").show();
					$("#disabledLoaderBtnProcess").hide();
					$('#toast1').toast('show');//mostrar toast despues de procesar datos calculados (suma) hacia el servidor
				}else{
					$('#tituloToastMeta').text("No hay datos para procesar");
					$('#toastProcesoMetaText').text("Documento excel no coinside con la plantilla configurada");
					$('#tituloToastMeta').css('color','red');
					$('#modalShowModalExl').modal('hide');
					$("#procesarModalMetaExl").show();
					$("#cancelModalMetaBtn").show();
					$("#disabledLoaderBtnProcess").hide();
					$('#toast1').toast('show');//mostrar toast despues de procesar datos calculados (suma) hacia el servidor
				}
			}
		});
        
	}
	function getDataFromTableMetaRecu(){
		var mes = $("#selectMesMeta option:selected").val();
		var anno = $("#selectAnnoMeta option:selected").val();
		var cells = [];
		var rows = $("#tblExcelImportMetaRecu").dataTable().fnGetNodes();
	
        for(var i=0;i<rows.length;i++)
        {
            
        	cells.push({'mes':mes,'anno':anno,'ruta':$(rows[i]).find("td:eq(0)").html(),'vendedor':$(rows[i]).find("td:eq(1)").html(),'meta':$(rows[i]).find("td:eq(2)").html()});
        }
        return cells;
	}
	function addDatatableDatosMeta(){
    	
		$.ajax({
		url:"add_data_meta",
		success: function(data){
			if(data!=0){
				$('#tituloToastMeta').text("Datos Procesados");
				$('#tituloToastMeta').css('color','black');
				$('#toastProcesoMetaText').text("Los datos han sido procesado exitosamente!");
				$('#modalShowModalExl').modal('hide');
				$("#procesarModalMetaExl").show();
				$("#cancelModalMetaBtn").show();
				$("#disabledLoaderBtnProcess").hide();
				$('#toast1').toast('show');//mostrar toast despues de procesar datos calculados (suma) hacia el servidor
			}else{
				$('#tituloToastMeta').text("Erro de plantilla");
				$('#toastProcesoMetaText').text("Documento excel no coinside con la plantilla configurada");
				$('#tituloToastMeta').css('color','red');
				$('#modalShowModalExl').modal('hide');
				$("#procesarModalMetaExl").show();
				$("#cancelModalMetaBtn").show();
				$("#disabledLoaderBtnProcess").hide();
				$('#toast1').toast('show');//mostrar toast despues de procesar datos calculados (suma) hacia el servidor
			}
			
		}
       });
    }
	function calcMetaAddMetaUnidad(){
		var mes = $("#selectMesMeta option:selected").val();
		var anno = $("#selectAnnoMeta option:selected").val();
		
		/*$.ajax({
			url:"calc_and_add_unidad_meta",
			success: function(data){
				
			}
		});*/
	}
	
	function inicialDataTablesMetas(dtName,dataUrl,columns,columnDefs){
		//$(dtName).DataTable().clear().draw();
		
		$(dtName).dataTable().fnDestroy();//destruir dataatble e inicializarlo nuevamente para eliminar datos solicitados anteriormente
		mes = $("#selectMesMeta option:selected").val();
		anno = $("#selectAnnoMeta option:selected").val();
		fileName = $('#addExlFileMetas').val();
		$(dtName).DataTable({
			'processing': true,
	        'serverSide': true,//proceso desde el servidor
	    	'ajax':{
	    		'url':dataUrl,
	    		'type': 'POST',
                'data': {
               	'mes': mes,
               	'anno': anno,
               	'fileName':fileName
               	},
            },
	    	'pageLength' : 10,
	    	'info':    false,
    	'lengthMenu': [[10,30,50,100,-1], [20,30,50,100,'Todo']],
    	'language': {
    		'sProcessing': 'Procesando...',
    	    'zeroRecords': 'Cargando...',
    	    'paginate': {
    	        'first':      'Primera',
    	        'last':       'Última ',
    	        'next':       'Siguiente',
    	        'previous':   'Anterior'
    	    },
    	    'lengthMenu': 'MOSTRAR _MENU_',
    	    'emptyTable': 'NO HAY DATOS DISPONIBLES',
    	    'infoEmpty': 'NO HAY DATOS DISPONIBLES',
    	    'search':     'BUSCAR'
    	}, 
    		'columnDefs': columnDefs,
	    	'columns': columns
		    
		});
		$(dtName+'_length').hide();//Ocultar select que muestra cantidad de registros por pagina
    	$(dtName+'_filter').hide();//Esconde input de filtro de tabla por texto escrito
		$('#mesHistorialMeta').text($('#selectMesMeta option:selected').text());
        $('#annoHistorialMeta').text($('#selectAnnoMeta option:selected').text());
	}

	
	$('#btnSearchMetas').on('keyup', function() {
	    var table = $('#tblExcelImportMeta').DataTable();
	    table.search(this.value).draw();
	});
	
		
		
		
		
	function validarCamposMeta(){
		if($("#radioMeta1").is(':checked')){
			if($('#selectTipoMeta').val() !='00' && $("#selectMesMeta").val() != "00" && $("#selectAnnoMeta").val() != "00" && $("#addExlFileMetas").val().length != 0){
				return true;
			}else{
				return false;
			}
	    }else{
	    	if($('#selectTipoMeta').val() !='00' && $("#selectMesMeta").val() != "00" && $("#selectAnnoMeta").val() != "00"){
				return true;
			}else{
				return false;
			}
	    	
    	}
		
	}
	function existeFechaMetaRecu(){
		var mes = $("#selectMesMeta option:selected").val();
		var anno = $("#selectAnnoMeta option:selected").val();
		var resultado;
		$.ajax({
			url:'existe_Fecha_Meta_venta',
    		type: 'POST',
    		async:false,
            data: {
           		mes : mes,
           		anno: anno
            },
            success: function(res){
            	
            	if (res == true){
            		resultado = true;
            	}else{
            		resultado = false;
            	}
            }
		});
		return resultado;
	}
	function existeFechaMeta(){
		var mes = $("#selectMesMeta option:selected").val();
		var anno = $("#selectAnnoMeta option:selected").val();
		var resultado;
		$.ajax({
			url:'existe_Fecha_Meta',
    		type: 'POST',
    		async:false,
            data: {
           		mes : mes,
           		anno: anno
            },
            success: function(res){
            	
            	if (res == true){
            		resultado = true;
            	}else{
            		resultado = false;
            	}
            }
		});
		return resultado;
	}
	
</script>