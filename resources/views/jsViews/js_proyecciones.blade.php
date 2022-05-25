<script>
$(document).ready(function() {
	dataProyecciones(false);
	/*$.ajax({
	    url: `dataProyeccion`,
	    type: 'POST',
	    data: { ud : 'm_p' },
	    async: true,
	    success: function(data) {
				dataProyecciones(data)
	    }
	});*/

	$("#item-nav-01").after(`<li class="breadcrumb-item active">Proyecciones de ventas</li>`);
});

function detailsProyeccion(articulo, unidad) {
    $.ajax({
        url: `artProyectado`,
        type: 'POST',
        data: { ud : unidad, art : articulo },
        async: true,
        success: function(data) {
   			//dataProyecciones(data)


   			$.each(data, function(i, item) {
   			if (unidad=='c_a') {
			body=`<div class="col-sm-12">
			    <div class="card">
			        <div class="card-body">
			            <form>
			                <div class="form-row">
			                    <div class="form-group col-md-3">
			                        <label for="lab">Laboratorio</label>
			                        <input type="text" class="form-control" id="lab" value="">
			                    </div>
			                    <div class="form-group col-md-6">
			                        <label for="desc">Descripcion</label>
			                        <input type="text" class="form-control" value="`+item['DESCRIPCION']+`" id="desc">
			                    </div>
			                    <div class="form-group col-md-3">
			                        <label for="ord-min">Orden Minima</label>
			                        <input type="text" class="form-control" id="ord-min" value="">
			                    </div>
			                </div>
			                <div class="form-row">
			                    <div class="form-group col-md-3">
			                        <label for="emp-ud">Empaque(unidades)</label>
			                        <input type="text" class="form-control" id="emp-ud" value="">
			                    </div>
			                    <div class="form-group col-md-3">
			                        <label for="contrat">Contratada</label>
			                        <input type="text" class="form-control" id="contrat" value="">
			                    </div>
			                    <div class="form-group col-md-3">
			                        <label for="pendi">Pendiente</label>
			                        <input type="text" class="form-control" id="pendi" value="">
			                    </div>
			                    <div class="form-group col-md-3">
			                        <label for="ordenad">Ordenada</label>
			                        <input type="text" class="form-control" id="ordenad" value="">
			                    </div>
			                </div>
			            </form>
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-sm-6">
			            <div class="card mt-3">
			                <div class="card-header">
			                    Bodega #6
			                </div>
			                <div class="card-body">
			                    <form>
			                        <div class="form-row">
			                            <div class="form-group col-md-6">
			                                <label for="cat-b6">Categoria</label>
			                                <input type="text" class="form-control" id="cat-b6">
			                            </div>
			                            <div class="form-group col-md-6">
			                                <label for="meses-disp-b6">Meses disponibles Bodega #6</label>
			                                <input type="text" class="form-control" id="meses-disp-b6">
			                            </div>
			                        </div>
			                        <div class="form-group">
			                            <label for="disp-1">Disponible</label>
			                            <input type="text" class="form-control" id="disp-1">
			                        </div>
			                        <div class="form-group">
			                            <label for="pedi-1">Pedida</label>
			                            <input type="text" class="form-control" id="pedi-1">
			                        </div>
			                        <div class="form-group">
			                            <label for="trans-1">En transito</label>
			                            <input type="text" class="form-control" id="trans-1">
			                        </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			        <div class="col-sm-6">
			            <div class="card mt-3">
			                <div class="card-header">
			                    Bodega #2
			                </div>
			                <div class="card-body">
			                    <form>
			                        <div class="form-row">
			                            <div class="form-group col-md-6">
			                                <label for="cat-priv">Categoria(privado)</label>
			                                <input type="text" class="form-control" id="cat-priv">
			                            </div>
			                            <div class="form-group col-md-6">
			                                <label for="mes-dis-b2">Meses disponibles Bodega 2</label>
			                                <input type="text" class="form-control" id="mes-dis-b2">
			                            </div>
			                        </div>
			                        <div class="form-row">
			                            <div class="form-group col-md-6">
			                                <label for="disp-priv">Disponible(privado)</label>
			                                <input type="text" class="form-control" id="disp-priv">
			                            </div>
			                            <div class="form-group col-md-6">
			                                <label for="">Min. exis. permitida Bod2</label>
			                                <input type="text" class="form-control" id="mes-dis-b2">
			                            </div>
			                        </div>
			                        <div class="form-row">
			                            <div class="form-group col-md-6">
			                                <label for="disp-priv">Pedida(privado)</label>
			                                <input type="text" class="form-control" id="disp-priv">
			                            </div>
			                            <div class="form-group col-md-6">
			                                <label for="">Meses disp. Bod2 Priv.</label>
			                                <input type="text" class="form-control" id="mes-dis-b2">
			                            </div>
			                        </div>
			                        <div class="form-group">
			                            <label for="disp-1">En transito(privado)</label>
			                            <input type="text" class="form-control" id="disp-1">
			                        </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			        <div class="col-sm-12">
			            <div class="card mt-3">
			                <div class="card-body">
			                    <form>
			                        <div class="form-row">
			                            <div class="form-group col-md-3">
			                                <label for="pro-cont-mens">Promedio contratado mensual</label>
			                                <input type="text" class="form-control" id="pro-cont-mens">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="prom-vend-mens">Promedio vendido mensual</label>
			                                <input type="text" class="form-control" id="prom-vend-mens">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="val-cri">Valor critico</label>
			                                <input type="text" class="form-control" id="val-cri">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="meses-disp-b6">Cumplimiento</label>
			                                <input type="text" class="form-control" id="meses-disp-b6">
			                            </div>
			                        </div>
			                        <div class="form-row">
			                            <div class="form-group col-md-3">
			                                <label for="dem-anual-ajus">Demanda anual ajustada</label>
			                                <input type="text" class="form-control" id="dem-anual-ajus">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="punt-reord">Punto de reorden</label>
			                                <input type="text" class="form-control" id="punt-reord">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="pedir">¿Pedir?</label>
			                                <input type="text" class="form-control" id="pedir">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="meses-disp-b6">Cantidad a pedir(packs)</label>
			                                <input type="text" class="form-control" id="meses-disp-b6">
			                            </div>
			                        </div>
			                        <div class="form-row">
			                            <div class="form-group col-md-3">
			                                <label for="vent-priv-mens">Venta privada mensual</label>
			                                <input type="text" class="form-control" id="vent-priv-mens">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="cant-mov-b2">Cantidad movible Bod2</label>
			                                <input type="text" class="form-control" id="cant-mov-b2">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="equi">¿Equivalente?</label>
			                                <input type="text" class="form-control" id="equi">
			                            </div>
			                            <div class="form-group col-md-3">
			                                <label for="exis-equi">Existencia equivalente</label>
			                                <input type="text" class="form-control" id="exis-equi">
			                            </div>
			                        </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>`
   			}else if(unidad=='m_p') {
			body=`<div class="col-sm-12">
			    <div class="card">
			        <div class="card-body">
			            <form>
			              <div class="form-row">
			                <div class="form-group col-md-2">
			                  <label for="cate">Categoria</label>
			                  <input type="text" class="form-control" id="cate">
			                </div>
			                <div class="form-group col-md-10">
			                  <label for="desc">Descripcion</label>
			                  <input type="text" class="form-control" value="`+item['DESCRIPCION']+`" id="desc">
			                </div>
			              </div>
			              <div class="form-row">
			                <div class="form-group col-md-6">
			                    <label for="labt">Laboratorio</label>
			                    <input type="text" class="form-control" id="lab">
			                </div>
			                <div class="form-group col-md-3">
			                  <label for="emp-ud">Empaque(Unidades)</label>
			                  <input type="text" class="form-control" id="emp-ud">
			                </div>
			                <div class="form-group col-md-3">
			                  <label for="ord-min">Orden minima</label>
			                  <input type="text" class="form-control" id="ord-min">
			                </div>
			              </div>
			            </form>
			        </div>
			    </div>
			    <div class="row">
			        <div class="col-sm-3">
			            <div class="card mt-3">
			                  <div class="card-header">
			                    Disponiblidad
			                  </div>
			                <div class="card-body">
			                    <form>
			                      <div class="form-group">
			                        <label for="disp">Disponible</label>
			                        <input type="text" class="form-control" id="disp">
			                      </div>
			                    <div class="form-group">
			                        <label for="pedi">Pedido</label>
			                        <input type="text" class="form-control" id="pedi">
			                      </div>
			                    <div class="form-group">
			                        <label for="trans">En transito</label>
			                        <input type="text" class="form-control" id="trans">
			                      </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			        <div class="col-sm-9">
			            <div class="card mt-3">
			                  <div class="card-header">
			                    Calculos
			                  </div>
			                <div class="card-body">
			            <form>
			              <div class="form-row">
			                <div class="form-group col-md-4">
			                  <label for="punt-reorden">Punto de reorden</label>
			                  <input type="text" class="form-control" id="punt-reorden">
			                </div>
			                <div class="form-group col-md-4">
			                  <label for="pres-anio-ant">Presupuesto 2019</label>
			                  <input type="text" class="form-control" id="pres-anio-ant">
			                </div>
			                <div class="form-group col-md-4">
			                  <label for="entr-pendi">Entrega(s) pendiente(s)</label>
			                  <input type="text" class="form-control" id="entr-pendi">
			                </div>
			              </div>
			              <div class="form-row">
			                <div class="form-group col-md-4">
			                  <label for="prom-mes-disp">Promedio meses disponibles</label>
			                  <input type="text" class="form-control" id="prom-mes-disp">
			                </div>
			                <div class="form-group col-md-4">
			                  <label for="prom-vent-mens">Promedio venta mensual</label>
			                  <input type="text" class="form-control" id="prom-vent-mens">
			                </div>
			                <div class="form-group col-md-4">
			                  <label for="disp-equiv">Dispibilidad equivalente</label>
			                  <input type="text" class="form-control" id="disp-equiv">
			                </div>
			              </div>
			              <div class="form-row">
			                <div class="form-group col-md-4">
			                  <label for="min-mes-disp">Minimos meses disponibles</label>
			                  <input type="text" class="form-control" id="min-mes-disp">
			                </div>
			                <div class="form-group col-md-4">
			                  <label for="max-vent-mens">Maxima venta mensual</label>
			                  <input type="text" class="form-control" id="max-vent-mens">
			                </div>
			                <div class="form-group col-md-4">
			                  <label for="cant-pedir">Cantidad a pedir</label>
			                  <input type="text" class="form-control" id="cant-pedir">
			                </div>
			              </div>
			            </form>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>`
   			}
   			})






   			$('#body-modal')
   			.empty()
   			.append(body)
        }
    });

	$("#page-details").toggleClass('active');
}

$(".active-page-details").click( function() {
	$("#page-details").toggleClass('active');    
});

$("#btnVerPro").click(function(e) {

	var unidad = $("#cmbUnidad option:selected").val();

	if (unidad=='not') {
		mensaje("Seleccione una unidad", "error")
	}else {
	    $.ajax({
	        url: `dataProyeccion`,
	        type: 'POST',
	        data: { ud : unidad },
	        async: true,
	        success: function(data) {
	   			dataProyecciones(data)
	        }
	    });
	}


});
function dataProyecciones(json) {
	$('#dtProyecciones').DataTable ( {
		"data":json,
		"destroy": true,
		"info":    false,
		"lengthMenu": [[5,10,-1], [5,10,"Todo"]],
		"language": {
			"zeroRecords": "Cargando...",
			"paginate": {
				"first":      "Primera",
				"last":       "Última ",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"lengthMenu": "MOSTRAR _MENU_",
			"emptyTable": "AÚN NO HA REALIZADO NINGUNA BUSQUEDA",
			"search":     "BUSCAR"
		},
		'columns': [
			{ "data": "ARTICULO" },
			{ "data": "DESCRIPCION" },
			{ "data": "CLASE_ABC" },
			{ "data": "ORDEN_MINIMA" },
			{ "data": "FACTOR_EMPAQUE" },
			{ "data": "OPC" }
		],

		"columnDefs": [
			{"className": "text-right", "targets": [ 3, 4 ]},
			{"className": "text-center", "targets": [ 0, 2, 5 ]},
			{ "width": "40%", "targets": [ 1 ] },
			{ "width": "10%", "targets": [ 5 ] }
			
		],

		"fnInitComplete": function () {
			$("#dtProyecciones_length").hide();
			$("#dtProyecciones_filter").hide();
		}
	});
}

function printHtml(ud) {

}





</script>