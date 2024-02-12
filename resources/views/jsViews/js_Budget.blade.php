<script>
  $("#item-nav-01").after(`<li class="breadcrumb-item active">Presupuesto</li>`);
    inicializaControlFecha();
    
    CalcIndicadores();


$("#btnCalcular").click( function() {
    CalcIndicadores()
   
});

$('#txtSearch').on( 'keyup', function () {
    var table = $('#dtProyect89').DataTable();
    table.search(this.value).draw();
});

function CalcIndicadores(){

    f1 = $("#f1").val();
    f2 = $("#f2").val();
    
    $("#IdCardTitle").text("Calculando . . . ") 

    

    $.getJSON("dtProyect?f1="+f1+"&f2="+f2+"&pr=1", function(dataset) {

        var c = 2 ; 
        var Header_Align = [];
        var tbl_header = [];
        

        tbl_header = [
            { "title": "ARTI.", "data": "ARTICULO" },
            { "title": "DESC.", "data": "DESCRIPCION" },
            { "title": "PRESUPUESTO", "data": "PRESUPUESTO" },
            { "title": "C$ VALOR.", "data": "CS_VALOR", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { "title": "PREC. PROM.", "data": "TOTAL", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { "title": "CONTRIBUCION", "data": "CANT_LIQUIDADA", render: $.fn.dataTable.render.number(',', '.', 0, '') },
        ];

        $.each(dataset[0]['FECHA'], function(key, val) {
            tbl_header.push({ "title": val.mes, "data": val.mes, render: $.fn.dataTable.render.number(',', '.', 0, '')  });
            Header_Align.push(c)
            c++;
        });

        dataProyect(dataset, tbl_header,'#dtProyect89',Header_Align);
        dataProyect(dataset, tbl_header,'#dtProyect71',Header_Align);
    
    
    })
}




function dataProyect(datos, Header,Table,Align) {
   
    if ( $.fn.DataTable.isDataTable(Table) ) {

        var dataTable = $(Table).DataTable();

        dataTable.clear().destroy();
        


    $(Table).empty().css("width", "100%");
        
    }
    $(Table).DataTable({
        "data": datos,
        "destroy" : true,
        "info":    false,
        "lengthMenu": [[10,-1], [10,"Todo"]],
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
        'columns': Header,
        "fixedColumns": {
                    leftColumns: 1,
                    rightColumns: 3
                },
        "columnDefs": [
            {"className": "dt-right","targets": Align},
            
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            $("#IdCardTitle").text("Proyecto 89") 
        
        },
    });

    $(Table + "_length").hide();
    $(Table + "_filter").hide();


}





</script>