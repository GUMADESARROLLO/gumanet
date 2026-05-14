@extends('layouts.main')

@section('title' , "COMPRAS")
    @section('name_user' , 'Administrador')
@section('metodosjs')
    @include('pages.Mific.js_Mific')
    @include('pages.Mific.css_Mific')
@endsection

@section('content')

<div class="row border">
    <div class="col-md-5">            
        <h4 class="h4 text-innova">PRECIOS MIFIC</h4>
        <p class="text-muted mb-4">Ordenes de compra, tomando en cuenta el periodo de <span id="tl_periodo"></span>.</p>
    </div>
    <div class="col-md-3">        
        <div class="form-group">                
            <label for="f1">BUSQUEDA</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"> <i class="fas fa-search"></i> </span>
                </div>
                <input type="text" id="txt_busqueda_orden_compra" class="form-control" placeholder="Buscar..." aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="col-md-2 ">        
        <div class="form-group">                
            <label for="f1">FECHA DE VENCIMIENTO</label>
            <input type="text" class="input-fecha" name="dt_range" />
        </div>
    </div>
    <div class="col-md-2 mt-4">
        <div class="d-flex">
            <button type="button" 
                    class="btn btn-primary-umk flex-fill mr-2" 
                    id="filtrarFechas">
                Filtrar
            </button>

            <!-- <button type="button" 
                    class="btn btn-primary-success flex-fill"
                    id="btnUploadMific">
                <i class="fa fa-upload"></i> Cargar
            </button> -->
            <button type="button" 
                    class="btn btn-primary-success flex-fill"
                    id="NuevoMific">
                <i class="fa fa-upload"></i> Nuevo
            </button>

        </div>
    </div>
</div>

<div class="row g-4 mb-4">      
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-innova text-white">            
                <div class="d-flex justify-content-between">
                    <h6 class="mb-0">PRECIOS MIFIC</h6>
                </div>
                <p class="text-white mb-0" >Lista encontrado de ordenes de compras</p>
            </div>
            <div class="card-body">
                <table id="tbl_ordenes_compras" class="display" style="width:100%"></table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="mdl-upload-mific" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header bg-innova text-white">                    
                <h4 class="modal-title">CARGAR INFORMACION MIFIC</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> <i class="fas fa-search"></i> </span>
                            </div>
                            <input type="text" id="txt_search_upload" class="form-control" placeholder="Buscar..." >
                        </div>                  
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <div class="custom-file" id="contInputExlFileTransito">
                                <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" value="upload" class="custom-file-input" name="addExlFileTransito" id="frm-upload"/>
                                <label class="custom-file-label" id="fileLabelTransito" for="addExlFileTransito" data-label="Cargar">Seleccione un archivo Excel</label>
                            </div>
                        </div>                    
                    </div>
                    <div class="col-md-6">
                        <p class="font-italic text-muted">Registros: ( <span id="id_registros_encontrados">0</span> )</p>	
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="font-italic text-muted">Campos Vacios: ( <span id="id_registros_errados"> 0 </span> )</p>	
                    </div>
                </div>
                <div class="table-responsive" >  
                    <table class="table" id="tbl_excel" >
                        <thead>
                            <tr>
                                <th>ARTICULO</th>
                                <th>REGISTRO_SANITARIO</th>
                                <th>NOMBRE_COMERCIA</th>
                                <th>NOMBRE_GENERICO</th>
                                <th>CONCENTRACION</th>
                                <th>PRESENTACION</th>
                                <th>CANTIDAD</th>
                                <th>LABORATORIO</th>
                                <th>PRECIO_FARMACIA</th>
                                <th>PRECIO_PUBLICO</th>
                                <th>UNIDAD_NEGOCIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr colspan="12">
                                <td class="text-center" colspan="12">-</td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnUploadMific">Cargar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="mdl-edit-mific" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header bg-innova text-white">                    
                <h4 class="modal-title">INFORMACION MIFIC</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <input type="hidden" id="id_row" name="num_row">
                    <div class="form-group col-md-6">
                        <label for="nombre_comercial"><strong>NOMBRE COMERCIAL</strong></label>
                        <input type="text" class="form-control" id="nombre_comercial" name="NOMBRE_COMERCIAL" required>
                    </div>                    
                    <div class="form-group col-md-6">
                        <label for="nombre_generico"><strong>NOMBRE GENERICO</strong></label>
                        <input type="text" class="form-control" id="nombre_generico" name="NOMBRE_GENERICO" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="sku_umk"><strong>SKU UMK</strong></label>
                        <input type="text" class="form-control" id="sku_umk" name="SKU_UMK" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="registro_sanitario"><strong>REGISTRO SANITARIO</strong></label>
                        <input type="text" class="form-control" id="registro_sanitario" name="REGISTRO_SANITARIO" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="concentracion"><strong>CONCENTRACION</strong></label>
                        <input type="text" class="form-control" id="concentracion" name="CONCENTRACION" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="presentacion"><strong>PRESENTACION</strong></label>
                        <input type="text" class="form-control" id="presentacion" name="PRESENTACION" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cantidad"><strong>CANTIDAD</strong></label>
                        <input type="text" class="form-control" id="cantidad" name="CANTIDAD" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="laboratorio"><strong>LABORATORIO</strong></label>
                        <input type="text" class="form-control" id="laboratorio" name="LABORATORIO" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="precio_farmacia"><strong>PRECIO FARMACIA</strong></label>
                        <input type="text" step="0.01" class="form-control" id="precio_farmacia" name="PRECIO_FARMACIA" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="precio_publico"><strong>PRECIO PUBLICO</strong></label>
                        <input type="text" step="0.01" class="form-control" id="precio_publico" name="PRECIO_PUBLICO" required>
                    </div>
                
                    <div class="form-group col-md-4">
                        <label for="unidad_negocio"><strong>UNIDAD NEGOCIO</strong></label>
                        <select class="form-control" id="unidad_negocio" name="UNIDAD_NEGOCIO" required>
                            <option value="ND">N/D</option>
                            <option value="UMK">UNIMARK S.A.</option>
                            <option value="GP">GUMAPHARMA</option>
                            <option value="INN">INNOVA S.A.</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnSaveMific">Guardar</button>
            </div>
        </div>
    </div>
</div>





@endsection
