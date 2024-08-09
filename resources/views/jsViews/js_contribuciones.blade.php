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
        "lengthMenu": [[15,-1], [15,"Todo"]],
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
        Swal.fire("Pendiente!", "En desarrollo", "info");
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
                Swal.showValidationMessage(`
                    Request failed: ${error}
                `);
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
});
</script>