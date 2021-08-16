@extends('layouts.main')
@section('title' , $data['name'])
@section('name_user' , 'Administrador')
@section('metodosjs')
  @include('jsViews.js_meta')
@endsection
@section('content')


<div style="position: relative;">
  <!-- Then put toasts within -->
  <div class="toast mx-auto"  data-delay="5000" style="  right: 0; z-index: 1000; position: absolute; top: 0" id="toast1" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="mr-auto"><h5 id="tituloToastMeta"></h5></strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body" id="toastProcesoMetaText" style="font-size: 1.3em">
      
    </div>
  </div>


 

  <div class="row" style="margin: 0 auto">
    <div class="card mt-3" style="width: 100%">
      <div class="card-body">                
        <h5 class="card-title">{{ $data['page'] }}</h5>
        <div class="row">
        	<div class="col-12">
        		<div class="alert alert-primary" role="alert"  id="alertMetas">
  			  
  			    </div>
        	</div>
        </div>
        
        <div class="row justify-content-center mb-2" id="optionMeta">
          <div class="col-md-3">
  	        <div class="form-check form-check-inline">
  					  <input class="form-check-input" type="radio" name="radioMeta" id="radioMeta1" value="option1" checked>
  					  <label class="form-check-label" for="radioMeta1" id="radioMeta1Label">
  					    Agergar meta
  					  </label>
  			    </div>
  		     </div>

    			<div class="col-md-3">
    				<div class="form-check form-check-inline">
    					  <input class="form-check-input" type="radio" name="radioMeta" id="radioMeta2" value="option2">
    					  <label class="form-check-label" for="radioMeta2" id="radioMeta2Label">
    					    Ver Historial
    					  </label>
    				</div>
    			</div>
  	    </div>
        <div class="row justify-content-center">
          <div class="col-md-5 mb-2">
            <div class="input-group">
              <select class="custom-select" id="selectTipoMeta" name="selectTipoMeta">
                <option value="00">Tipo meta</option>
                <option value="vent">Venta</option>
                <option value="recu">Recuperación</option>
              </select>
            </div>
            
          </div>
          <div class="col-md-1 mt-1">
            <a href="#!" id="donwloadExcelPlantilla"><i class="material-icons" style="font-size: 30px">get_app</i></a>
          </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-3 mb-2">
               <div class="input-group">
                  <select class="custom-select" id="selectMesMeta" name="selectMesMeta">
                  	<option value="00">Mes</option>
                      <option value="01">Enero</option>
                      <option value="02">Febrero</option>
                      <option value="03">Marzo</option>
                      <option value="04">Abril</option>
                      <option value="05">Mayo</option>
                      <option value="06">Junio</option>
                      <option value="07">Julio</option>
                      <option value="08">Agosto</option>
                      <option value="09">Septiembre</option>
                      <option value="10">Octubre</option>
                      <option value="11">Noviembre</option>
                      <option value="12">Diciembre</option>
                  </select>
              </div>
            </div>            
            <div class="col-md-3 mb-2">
               <div class="input-group">
                  <select class="custom-select" id="selectAnnoMeta" name="selectAnnoMeta">
                  <?php
                        $year = date("Y");
                        for ($i= 2018; $i <= $year ; $i++) {
                          if ($i==$year) {
                            echo'<option selected value="'.$i.'">'.$i.'</option>';
                          }else {
                            echo'<option value="'.$i.'">'.$i.'</option>';
                          }
                         
                        }
                    ?>
                  </select>
                </div>
            </div>            
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6 mb-2">
              <div class="input-group">
                <select class="custom-select" id="sltDiasHabiles" name="slt_dias_habiles">
                  <option value="0">Dias Habiles</option>
                  <?php
                  for ($i= 1; $i <= 31 ; $i++) 
                  {
                    echo'<option value="'.$i.'">'.$i.'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6 mb-2">
          	<form method="POST" id="export_excel" name="export_excel" enctype="multipart/form-data">
              <div class="input-group">
    					  <div class="custom-file" id="contInputExlFileMetas">
    					    <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" value="upload" class="custom-file-input" name="addExlFileMetas" id="addExlFileMetas"/>
    					    <label class="custom-file-label" id="fileLabelMeta" for="addExlFileMetas" data-label="Buscar">Seleccione un archivo Excel
    					    </label>
    					  </div>
    					</div>
    					{{-- @csrf --}} 
    				</form>
    			</div>
  	    </div>
        
        

  	
  	    <div class="row justify-content-center">
          <div class="col-md-6">
          	<div class="input-group">
              <a href="#" style="width: 100%" class="btn btn-primary"  id="btnShowModalExl"></a> 
              <button style="width: 100%" class="btn btn-primary" type="button" id="disabledLoaderBtn"  disabled>
  				      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  				  Cargando Espere un momento...
  			      </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  

  <div class="row" id="verMetasAgregadasXMes" style="margin: 0 auto">
    <div class="card mt-3" style="width: 100%">
	    <div class="card-body">         
	    	<h5 class="card-title">{{ "Datos de Metas / " }}<span id="mesHistorialMeta"></span> {{ " " }} <span id="annoHistorialMeta"></span></h5>
	        <hr>     
	       
	        <div class="row" id="bloqueTblVerMetasAgregadas">
	        	<div class="col-12">
	            	<div class="table-responsive mt-3 mb-5">
		        		<table class="table table-bordered table-sm" width="100%" id="tblVerMetasAgregadas">
				        	<thead class="text-center">
			               <tr>
                        <th>RUTA</th>
		                    <th>CODIGO</th>
		                    <th>PRODUCTO</th>
		                    <th>META</th>
		                    <th>VALOR</th>
			                </tr>
				        	</thead>
				        </table>
				      </div>
			      </div>
	        </div>
          <div class="row" id="bloqueTblExcelVerMetaRecu">
            <div class="col-12">
              <div class="table-responsive mt-3 mb-5">
                <table class="table table-bordered table-sm" width="100%" id="tblExcelVerMetaRecu">
                  <thead class="text-center">
                    <tr>
                        <th>RUTA</th>
                        <th>VENDEDOR</th>
                        <th>META</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
  	  </div>
	  </div>
  </div>
</div>



{{--///////////////////////////////////////// MODAL SHOW EXCEL /////////////////////////////--}}

<div class="modal fade" id="modalShowModalExl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Datos de Metas / <span id="mesModalExl"></span> del <span id="annoModalExl"></span> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bodyModalMetasExldata">
        <div class="table-responsive mt-3 mb-5" id="bloqueTblExcVenta">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i data-feather="search"></i></span>
            </div>
            <input type="text" id="btnSearchMetas" class="form-control" placeholder="Buscar">
          </div>          
	        <table class="table table-bordered table-sm mt-3" width="100%" id="tblExcelImportMeta">
	        	<thead class="text-center">
	                <tr>
	                    <th>RUTA</th>
	                    <th>CODIGO</th>
	                    <th>CLIENTE</th>
	                    <th>ARTICULO</th>
	                    <th>DESCRIPCION</th>
	                    <th>VALOR</th>
	                    <th>UNIDAD</th>
	                </tr>
	            </thead>
	        </table>
	      </div>
        <div class="table-responsive mt-3 mb-5" id="bloqueTblExcRecup">
          <table class="table table-bordered table-sm" width="100%" id="tblExcelImportMetaRecu">
            <thead class="text-center">
                  <tr>
                      <th>RUTA</th>
                      <th>VENDEDOR</th>
                      <th>META</th>
                  </tr>
              </thead>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="cancelModalMetaBtn" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger procesarActionBtn" id="procesarModalMetaExl">Procesar</button>
        <button style="width: 100%" class="btn btn-danger" type="button" id="disabledLoaderBtnProcess"  disabled>
			  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
			  Procesando datos...
		</button>
      </div>
    </div>
  </div>
</div>


@endsection