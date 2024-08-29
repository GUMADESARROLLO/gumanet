<script type="text/javascript">
    fullScreen();
$(document).ready(function () {
    inicializaControlFecha();
    $('#id_txt_buscar').on('keyup', function() {   
        var vTable = $('#table_contribucion').DataTable();     
        vTable.search(this.value).draw();        
    });

    $( "#InputCanales").change(function() {
        var table = $('#table_contribucion').DataTable();
        table.page.len(this.value).draw();
    });

    
    

    $('#table_contribucion').DataTable({ 
        "destroy": true,
        "info": true,
        "ajax":{
            "url": "canalData",
            'dataSrc': function(json) {
                var periodo = json.Periodo;
                
                var fechaIni = moment(periodo.primera_fecha).format('DD/MM/YYYY');
                var fechaEnd = moment(periodo.ultima_fecha).format('DD/MM/YYYY');
                $('#tl_periodo').html(fechaIni + " hasta el "+ fechaEnd);
                $("#f1").val( moment(periodo.fechaIni).format('YYYY-MM-DD'));
                $("#f2").val( moment(periodo.fechaEnd).format('YYYY-MM-DD'));

                return json.Registros;
            }
        },
        "lengthMenu": [[5,-1], [5,"Todo"]],
        "scrollY":        "900px",
        "scrollX":        true,
        "scrollCollapse": true,
        "paging":         true,
        "fixedColumns":   {
            "leftColumns": 3,
        },
        "language": {
            "zeroRecords": "No hay coincidencias",
            "loadingRecords": "Cargando datos...",
            "paginate": {
                "first": "Primera",
                "last": "Última ",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "REALICE UNA BUSQUEDA UTILIZANDO LOS FILTROS DE FECHA",
            "search": "BUSCAR"
        },
        'columns': [
            {"data": "ARTICULO"},
            {"data": "DESCRIPCION"},
            {"data": "FABRICANTE"},
            {"data": "FARMACIA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "FARMACIA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "FARMACIA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "FARMACIA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "FARMACIA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "FARMACIA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CADENA_FARMACIA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "CADENA_FARMACIA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CADENA_FARMACIA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CADENA_FARMACIA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CADENA_FARMACIA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CADENA_FARMACIA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "MAYORISTA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "MAYORISTA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "MAYORISTA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "MAYORISTA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "MAYORISTA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "MAYORISTA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PRIVADA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "INSTITUCION_PRIVADA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PRIVADA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PRIVADA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PRIVADA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PRIVADA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CRUZ_AZUL_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "CRUZ_AZUL_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CRUZ_AZUL_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CRUZ_AZUL_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CRUZ_AZUL_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "CRUZ_AZUL_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PUBLICA_CANTIDAD",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "INSTITUCION_PUBLICA_PROMEDIO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PUBLICA_VENTA",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PUBLICA_COSTO",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PUBLICA_CONTRIBUCION",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "INSTITUCION_PUBLICA_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "TOTAL_VENTAS_PACK",render: $.fn.dataTable.render.number( ',', '.', 0  , '' )},
            {"data": "TOTAL_PRECIO_PROM",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "TOTAL_VENTAS_C$",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "TOTAL_COSTOS_C$",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "TOTAL_CONTRIBUCION_C$",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},
            {"data": "TOTAL_MARGEN",render: $.fn.dataTable.render.number( ',', '.', 2  , '' )},         
        ],
        "columnDefs": [                       
            {"className": "dt-right", "targets": [ 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44 ]},
            { "width": "150px", "targets": [ 1 ] }
        ],           
    });
    $("#table_contribucion_length").hide();
    $("#table_contribucion_filter").hide();

    $("#exp-to-excel-canales").click(function(){
        location.href = "ExportToExcelCanales";
    })

    $("#BtnClick").click(function() {
        fechaIni = $("#f1").val();
        fechaEnd = $("#f2").val();
        Swal.fire({
            title: "Recalcular contribución de canales",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Calcular",
            showLoaderOnConfirm: true,
            preConfirm: async (login) => {
                try {
                const githubUrl = `calcularCanales/`+fechaIni+`/`+fechaEnd;
                const response = await fetch(githubUrl);
                if (!response.ok) {
                    return Swal.showValidationMessage(`${JSON.stringify(await response.json())}`);
                }
                return response.json();
                } catch (error) {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Calculos completados",
                    confirmButtonText: "Ok",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        } 
                    });
            }
        });

    })

    function calcularTotales() {
        var table = $('#table_contribucion').DataTable();
        var Farmacia_Cantidad = Farmacia_Costo = Farmacia_Venta = Farmacia_Contribucion = 0;
        var Cadena_Farmacia_Cantidad = Cadena_Farmacia_Costo = Cadena_Farmacia_Venta = Cadena_Farmacia_Contribucion = 0;
        var Mayorista_Cantidad = Mayorista_Costo = Mayorista_Venta = Mayorista_Contribucion = 0;
        var Cruz_Azul_Cantidad = Cruz_Azul_Costo = Cruz_Azul_Venta = Cruz_Azul_Contribucion = 0;
        var Institucion_Privada_Cantidad = Institucion_Privada_Costo = Institucion_Privada_Venta = Institucion_Privada_Contribucion = 0;
        var Institucion_Publica_Cantidad = Institucion_Publica_Costo = Institucion_Publica_Venta = Institucion_Publica_Contribucion = 0;
        var Total_Cantidad = Total_Costo = Total_Venta = Total_Contribucion = 0;

        table.rows({ search: 'applied' }).every(function() {
            var data = this.data();
            
            // TOTAL DE FARMACIAS
            Farmacia_Cantidad       += parseFloat(data.FARMACIA_CANTIDAD) || 0;
            Farmacia_Venta          += parseFloat(data.FARMACIA_VENTA) || 0;
            Farmacia_Costo          += parseFloat(data.FARMACIA_COSTO) || 0;
            Farmacia_Contribucion   += parseFloat(data.FARMACIA_CONTRIBUCION) || 0;

            // TOTAL DE CADENA DE FARMACIAS
            Cadena_Farmacia_Cantidad    += parseFloat(data.CADENA_FARMACIA_CANTIDAD) || 0;
            Cadena_Farmacia_Venta       += parseFloat(data.CADENA_FARMACIA_VENTA) || 0;
            Cadena_Farmacia_Costo       += parseFloat(data.CADENA_FARMACIA_COSTO) || 0;
            Cadena_Farmacia_Contribucion+= parseFloat(data.CADENA_FARMACIA_CONTRIBUCION) || 0;

            // TOTAL DE MAYORISTAS
            Mayorista_Cantidad      += parseFloat(data.MAYORISTA_CANTIDAD) || 0;
            Mayorista_Venta         += parseFloat(data.MAYORISTA_VENTA) || 0;
            Mayorista_Costo         += parseFloat(data.MAYORISTA_COSTO) || 0;
            Mayorista_Contribucion  += parseFloat(data.MAYORISTA_CONTRIBUCION) || 0;

            // TOTAL DE CRUZ AZUL
            Cruz_Azul_Cantidad      += parseFloat(data.CRUZ_AZUL_CANTIDAD) || 0;
            Cruz_Azul_Venta         += parseFloat(data.CRUZ_AZUL_VENTA) || 0;
            Cruz_Azul_Costo         += parseFloat(data.CRUZ_AZUL_COSTO) || 0;
            Cruz_Azul_Contribucion  += parseFloat(data.CRUZ_AZUL_CONTRIBUCION) || 0;

            // TOTAL INTITUCION PRIVADA
            Institucion_Privada_Cantidad    += parseFloat(data.INSTITUCION_PRIVADA_CANTIDAD) || 0;
            Institucion_Privada_Venta       += parseFloat(data.INSTITUCION_PRIVADA_VENTA) || 0;
            Institucion_Privada_Costo       += parseFloat(data.INSTITUCION_PRIVADA_COSTO) || 0;
            Institucion_Privada_Contribucion+= parseFloat(data.INSTITUCION_PRIVADA_CONTRIBUCION) || 0;

            // TOTAL INTITUCION PUBLICA
            Institucion_Publica_Cantidad    += parseFloat(data.INSTITUCION_PUBLICA_CANTIDAD) || 0;
            Institucion_Publica_Venta       += parseFloat(data.INSTITUCION_PUBLICA_VENTA) || 0;
            Institucion_Publica_Costo       += parseFloat(data.INSTITUCION_PUBLICA_COSTO) || 0;
            Institucion_Publica_Contribucion+= parseFloat(data.INSTITUCION_PUBLICA_CONTRIBUCION) || 0;

            // TOTAL DE TOTALES
            Total_Cantidad      += parseFloat(data.TOTAL_VENTAS_PACK) || 0;
            Total_Venta         += parseFloat(data.TOTAL_VENTAS_C$) || 0;
            Total_Costo         += parseFloat(data.TOTAL_COSTOS_C$) || 0;
            Total_Contribucion  += parseFloat(data.TOTAL_CONTRIBUCION_C$) || 0;

            console.log(data.TOTAL_VENTAS_C$);
        });

        // TOTAL DE FARMACIAS
        $('#Farmacia_Cantidad').html(numeral(Farmacia_Cantidad).format('0,0'));
        $('#Farmacia_Promedio').html('C$ '+numeral(Farmacia_Venta/Farmacia_Cantidad).format('0,0.00'));
        $('#Farmacia_Venta').html('C$ '+numeral(Farmacia_Venta).format('0,0.00'));
        $('#Farmacia_Costo').html('C$ '+numeral(Farmacia_Costo).format('0,0.00'));
        $('#Farmacia_Contribucion').html('C$ '+numeral(Farmacia_Contribucion).format('0,0.00'));
        $('#Farmacia_Margen').html(numeral((Farmacia_Contribucion/Farmacia_Venta)*100).format('0,0.00'));

        // TOTAL DE CADENA DE FARMACIAS
        $('#Cadena_Farmacia_Cantidad').html(numeral(Cadena_Farmacia_Cantidad).format('0,0'));
        $('#Cadena_Farmacia_Promedio').html('C$ '+numeral(Cadena_Farmacia_Venta/Cadena_Farmacia_Cantidad).format('0,0.00'));
        $('#Cadena_Farmacia_Venta').html('C$ '+numeral(Cadena_Farmacia_Venta).format('0,0.00'));
        $('#Cadena_Farmacia_Costo').html('C$ '+numeral(Cadena_Farmacia_Costo).format('0,0.00'));
        $('#Cadena_Farmacia_Contribucion').html('C$ '+numeral(Cadena_Farmacia_Contribucion).format('0,0.00'));
        $('#Cadena_Farmacia_Margen').html(numeral((Cadena_Farmacia_Contribucion/Cadena_Farmacia_Venta)*100).format('0,0.00'));

        // TOTAL DE MAYORISTAS
        $('#Mayorista_Cantidad').html(numeral(Mayorista_Cantidad).format('0,0'));
        $('#Mayorista_Promedio').html('C$ '+numeral(Mayorista_Venta/Mayorista_Cantidad).format('0,0.00'));
        $('#Mayorista_Venta').html('C$ '+numeral(Mayorista_Venta).format('0,0.00'));
        $('#Mayorista_Costo').html('C$ '+numeral(Mayorista_Costo).format('0,0.00'));
        $('#Mayorista_Contribucion').html('C$ '+numeral(Mayorista_Contribucion).format('0,0.00'));
        $('#Mayorista_Margen').html(numeral((Mayorista_Contribucion/Mayorista_Venta)*100).format('0,0.00'));

        // TOTAL INTITUCION PRIVADA
        $('#Institucion_Privada_Cantidad').html(numeral(Institucion_Privada_Cantidad).format('0,0'));
        $('#Institucion_Privada_Promedio').html('C$ '+numeral(Institucion_Privada_Venta/Institucion_Privada_Cantidad).format('0,0.00'));
        $('#Institucion_Privada_Venta').html('C$ '+numeral(Institucion_Privada_Venta).format('0,0.00'));
        $('#Institucion_Privada_Costo').html('C$ '+numeral(Institucion_Privada_Costo).format('0,0.00'));
        $('#Institucion_Privada_Contribucion').html('C$ '+numeral(Institucion_Privada_Contribucion).format('0,0.00'));
        $('#Institucion_Privada_Margen').html(numeral((Institucion_Privada_Contribucion/Institucion_Privada_Venta)*100).format('0,0.00'));
        
        // TOTAL DE CRUZ AZUL
        $('#Cruz_Azul_Cantidad').html(numeral(Cruz_Azul_Cantidad).format('0,0'));
        $('#Cruz_Azul_Promedio').html('C$ '+numeral(Cruz_Azul_Venta/Cruz_Azul_Cantidad).format('0,0.00'));
        $('#Cruz_Azul_Venta').html('C$ '+numeral(Cruz_Azul_Venta).format('0,0.00'));
        $('#Cruz_Azul_Costo').html('C$ '+numeral(Cruz_Azul_Costo).format('0,0.00'));
        $('#Cruz_Azul_Contribucion').html('C$ '+numeral(Cruz_Azul_Contribucion).format('0,0.00'));
        $('#Cruz_Azul_Margen').html(numeral((Cruz_Azul_Contribucion/Cruz_Azul_Venta)*100).format('0,0.00'));

        // TOTAL INTITUCION PUBLICA
        $('#Institucion_Publica_Cantidad').html(numeral(Institucion_Publica_Cantidad).format('0,0'));
        $('#Institucion_Publica_Promedio').html('C$ '+numeral(Institucion_Publica_Venta/Institucion_Publica_Cantidad).format('0,0.00'));
        $('#Institucion_Publica_Venta').html('C$ '+numeral(Institucion_Publica_Venta).format('0,0.00'));
        $('#Institucion_Publica_Costo').html('C$ '+numeral(Institucion_Publica_Costo).format('0,0.00'));
        $('#Institucion_Publica_Contribucion').html('C$ '+numeral(Institucion_Publica_Contribucion).format('0,0.00'));
        $('#Institucion_Publica_Margen').html(numeral((Institucion_Publica_Contribucion/Institucion_Publica_Venta)*100).format('0,0.00'));

        // TOTAL DE TOTALES
        $('#Total_Cantidad').html(numeral(Total_Cantidad).format('0,0'));
        $('#Total_Promedio').html('C$ '+numeral(Total_Venta/Total_Cantidad).format('0,0.00'));
        $('#Total_Venta').html('C$ '+numeral(Total_Venta).format('0,0.00'));
        $('#Total_Costo').html('C$ '+numeral(Total_Costo).format('0,0.00'));
        $('#Total_Contribucion').html('C$ '+numeral(Total_Contribucion).format('0,0.00'));
        $('#Total_Margen').html(numeral((Total_Contribucion/Total_Venta)*100).format('0,0.00'));
    }

    $('#table_contribucion').DataTable().on('draw', function() {
        calcularTotales();
    });

});
</script>