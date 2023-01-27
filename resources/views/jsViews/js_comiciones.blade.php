<script type="text/javascript">
$(document).ready(function() {
    const fecha = new Date();
    
    dataComisiones(fecha.getMonth()+1, fecha.getFullYear());
});
    // INICIALIZA Y ASIGNA LA FECHA EN EL DATEPICKER
    const startOfMonth  = moment().subtract(1,'days').format('YYYY-MM-DD');
    const endOfMonth    = moment().subtract(0, "days").format("YYYY-MM-DD");
    var labelRange      = startOfMonth + " to " + endOfMonth;      
    $('#id_range_select').val(labelRange);

    $("#id_btn_new").click( function() {

        var mes = $('#id_select_mes').val();
        var anno = $('#id_select_year').val();

        dataComisiones(mes, anno);
    });

    $('#id_txt_buscar').on( 'keyup', function () {
        var table = $('#table_comisiones').DataTable();
        table.search(this.value).draw();
    });

    $( "#frm_lab_row").change(function() {
        var table = $('#table_comisiones').DataTable();
        table.page.len(this.value).draw();
    });
       
    $('#id_txt_History').on( 'keyup', function () {
        var table = $('#tb_history').DataTable();
        
        $("#tb_history_length").hide();
        $("#tb_history_filter").hide();
        table.search(this.value).draw();
    });

    function dataComisiones(mes, anno){
        //var mes = $('#id_select_mes').val();
        //var anno = $('#id_select_year').val();

        $.ajax({
                url: "getDataComiciones",
                type: 'get',
                data: {
                    mes      : mes,
                    anno     : anno
                },
                async: false,
                success: function(response) {
                    $('#table_comisiones').DataTable({
                        "data":response,
                        "destroy" : true,
                        "info":    false,
                        "lengthMenu": [[5,10,-1], [5,10,"Todo"]],
                        "language": {
                            "zeroRecords": "NO HAY COINCIDENCIAS",
                            "paginate": {
                                "first":      "Primera",
                                "last":       "Última ",
                                "next":       "Siguiente",
                                "previous":   "Anterior"
                            },
                            "lengthMenu": "MOSTRAR _MENU_",
                            "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
                            "search":     "BUSCAR"
                        },
                        'columns': [
                            {    "data": "NOMBRE", "render": function(data, type, row, meta) {

                                return  `   <div class="d-flex align-items-center position-relative mt-2">
                                                <div class="flex-1 ms-3" style="text-align: left;">
                                                    <h7 class="mb-0 fw-semi-bold"><a class="stretched-link text-900 fw-semi-bold" href="#!" id="itemHistory" idRuta="`+row.VENDEDOR+`"  data-toggle="modal" data-target="#modalHistoryItem"><div class="stretched-link text-dark"><b>`+row.NOMBRE+`</b></div></a></h7>
                                                    <p class="text-secondary fs--2 mb-0">`+row.VENDEDOR+` | `+row.ZONA+` </p>
                                                </div>
                                            </div>
                                        `

                            }},        
                            {   "data": "VENDEDOR", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Basico</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-1">C$ `+row.BASICO+` </h6>
                                            </div>
                                        </div>`

                            } },    
                            {   "data": "BASICO", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Ventas Val.</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-2">C$ `+row.DATARESULT.Comision_de_venta.Total[1]+` </h6>
                                            </div>
                                        </div>`

                            } },
                            {    "data": "VENDEDOR", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Comisión</b></h7>
                                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                            <button class="btn btn-link btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-total-sales" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                                <div class="d-flex align-items-center">
                                                <h6 class="fs-0 text-dark mb-0 me-2">C$ `+row.DATARESULT.Comision_de_venta.Total[3]+`</h6>
                                                <span class="badge rounded-pill badge-primary">
                                                `+row.DATARESULT.Comision_de_venta.Total[2]+`%
                                                    </span>
                                                </div>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-total-sales">
                                                
                                            <table class="table" style="border: 2px solid black;">                                  
                                                <thead class="bg-200 text-900">
                                                <tr class="bg-primary text-light">
                                                    <th class="">CLASIF</th>
                                                    <th class="">SKU</th>
                                                    <th class="">Val. C$.</th>
                                                    <th class="">Fct.%</th>
                                                    <th class="">Comision</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="border-200">
                                                    <td class="align-middle">
                                                    <h6 class="mb-0 text-nowrap">80% </h6>
                                                    </td>
                                                    <td class="align-middle text-center">`+row.DATARESULT.Comision_de_venta.Lista80[0]+`</td>
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Lista80[1]+` </td>
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Lista80[2]+` %</td>                                          
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Lista80[3]+` </td>
                                                </tr>
                                                <tr class="border-200">
                                                    <td class="align-middle">
                                                    <h6 class="mb-0 text-nowrap">20% </h6>
                                                    </td>
                                                    <td class="align-middle text-center">`+row.DATARESULT.Comision_de_venta.Lista20[0]+`</td>
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Lista20[1]+` </td>
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Lista20[2]+` %</td>                                          
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Lista20[3]+` </td>
                                                </tr>
                                                <tr class="border-200">
                                                    <td class="align-middle">
                                                    <h6 class="mb-0 text-nowrap">Total </h6>
                                                    </td>
                                                    <td class="align-middle text-center">`+row.DATARESULT.Comision_de_venta.Total[0]+`</td>
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Total[1]+` </td>
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Total[2]+` %</td>                                          
                                                    <td class="align-middle text-end ">`+row.DATARESULT.Comision_de_venta.Total[3]+` </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                            </div>
                                            
                                        </div>`

                            }},        
                            {   "data": "VENDEDOR", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Prom.</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-2">`+row.DATARESULT.Totales_finales[4]+`</h6>
                                            </div>
                                        </div>`

                            } },    
                            {  "data": "BASICO", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Meta.</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-2">`+row.DATARESULT.Totales_finales[5]+`</h6>
                                            </div>
                                        </div>`

                            } },
                            {    "data": "NOMBRE", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Fact.</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-2">`+row.DATARESULT.Totales_finales[6]+`</h6>
                                            </div>
                                        </div>`

                            }},        
                            {   "data": "VENDEDOR", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Bono.Cobertura</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-dark mb-0 me-2">C$ `+row.DATARESULT.Totales_finales[0]+`</h6>
                                            <span class="badge rounded-pill badge-primary">`+row.DATARESULT.Totales_finales[3]+`%</span>
                                            </div>
                                        </div> `

                            } },    
                            {   "data": "BASICO", "render": function(data, type, row, meta) {

                                return  `<div class="pe-4 border-sm-end border-200" >
                                            <h7 class="fs--2 text-secondary mb-1"><b>Comisión + Bono</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-2">C$ `+row.DATARESULT.Totales_finales[1]+`</h6>
                                            </div>
                                        </div>`

                            } },
                            {   "data": "BASICO", "render": function(data, type, row, meta) {

                                return  `<div class="">
                                            <h7 class="fs--2 text-secondary mb-1"><b>Total Comp.</b></h7>
                                            <div class="d-flex align-items-center">
                                            <h6 class="fs-0 text-900 mb-0 me-2">C$ `+row.DATARESULT.Total_Compensacion+`</h6>                                  
                                            </div>
                                        </div>`

                            } },
                        ],
                    })
                    //OCULTA DE LA PANTALLA EL FILTRO DE PAGINADO Y FORM DE BUSQUEDA
                    $("#table_comisiones_length").hide();
                    $("#table_comisiones_filter").hide();
                }
        });
    }

    

    $(document).on('click', '#itemHistory', function(ef) {
        const fecha = new Date();
        var i = j = venta = meta = valor =  0;

       

        $('#id_txt_History').val("");
        var mes = fecha.getMonth()+1;
        var anno = fecha.getFullYear();
        var ruta = $(this).attr('idRuta');
        var thead = tbody = '';

            $.ajax({
            url: "getHistoryItem",
            type: 'GET',
            data:{
                mes :   mes,
                anno:   anno,
                ruta:   ruta
            },
            async: true,
            success: function(response) {
                 thead =`<table <table class="table table-striped table-bordered table-sm" id="tb_history" width="100%">
                        <thead c class="bg-blue text-light">
                            <tr>
                                <th class="center" width="10%">ARTICULO</th>
                                <th class="center" width="50%">DESCRIPCION</th>
                                <th class="center" width="10%">P.UNIT</th>
                                <th class="center" width="10%">APORTE</th>
                                <th class="center" width="5%">SKU</th>
                                <th class="center" width="10%">META UND</th>
                                <th class="center" width="10%">VENTA UND</th>
                                <th class="center" width="10%">VENTA VAL.</th>
                                <th class="center" width="10%">CUM.%</th>
                                <th class="center" width="10%">CUM. META</th>
                                
                            </tr>
                        </thead>
                        </tbody>`;
                $.each( response, function( key, item ) {
                    if(item['Lista'] === '80'){
                        i += 1;
                    }else{
                        j +=1;
                    }
                    valor += Number(item['VentaVAL']);
                    meta += Number(item['MetaUND']);
                    venta += Number(item['VentaUND']);
                    tbody +='<tr>'+
                            '<td class="text-center" >' + item['ARTICULO'] + '</td>'+
                            '<td>' + item['DESCRIPCION'] + '</td>'+
                            '<td class="text-right">' + numeral(item['Venta']).format('0,0.00') + '</td>'+
                            '<td class="text-right">' + numeral(item['Aporte']).format('0,0.00') + '</td>'+
                            '<td class="text-right">' + item['Lista'] + '</td>'+
                            '<td class="text-right">' + numeral(item['MetaUND']).format('0,0') + '</td>'+
                            '<td class="text-right">' + numeral(item['VentaUND']).format('0,0') + '</td>'+
                            '<td class="text-right">' + numeral(item['VentaVAL']).format('0,0.00') + '</td>'+
                            '<td class="text-right">' + numeral(item['Cumple']).format('0,0.00') + '</td>'+
                            '<td class="text-right">' + item['isCumpl'] + '</td>'+
                        '</tr>';
                })
                
                $('#lbl_80').html(i);
                $('#lbl_20').html(j);
                $('#lbl_venta').html(numeral(venta).format('0,0'));
                $('#lbl_meta').html(numeral(meta).format('0,0'));
                $('#lbl_val').html(numeral(valor).format('0,0.00'));
                tbody += `</tbody> </table>`;
                temp = thead + tbody;
                $("#dtViewHistory").empty().append(temp);
                $('#tb_history').DataTable({
                    "destroy": true,
                    "info": false,
                    "lengthMenu": [
                        [10, -1],
                        [10, "Todo"]
                    ],
                    "language": {
                        "zeroRecords": "NO HAY COINCIDENCIAS",
                        "paginate": {
                            "first": "Primera",
                            "last": "Última ",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        },
                        "lengthMenu": "MOSTRAR _MENU_",
                        "emptyTable": "-",
                        "search": "BUSCAR"
                    },
                });
                $("#tb_history_length").hide();
                $("#tb_history_filter").hide();
                
            },
            
        });

        
        
    });
    

</script>
