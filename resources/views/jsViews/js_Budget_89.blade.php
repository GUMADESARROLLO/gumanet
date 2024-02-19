<script type="text/javascript">

$(document).ready(function() {
    $("#item-nav-01").after(`<li class="breadcrumb-item active">Presupuesto</li>`);
    inicializaControlFecha();    
    CalcIndicadores_89();
    DrawTable71();

});


$(document).on('click', '#exp_more', function(ef) {
    var table = $('#dtProyect89').DataTable();
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var data = table.row($(this).parents('tr')).data();

    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        ef.target.innerHTML = "expand_more";
        ef.target.style.background = '#e2e2e2';
        ef.target.style.color = '#007bff';
    } else {
        //VALIDA SI EN LA TABLA HAY TABLAS SECUNDARIAS ABIERTAS
        table.rows().eq(0).each( function ( idx ) {
            var row = table.row( idx );

            if ( row.child.isShown() ) {
                row.child.hide();
                ef.target.innerHTML = "expand_more";

                var c_1 = $(".expan_more");
                c_1.text('expand_more');
                c_1.css({
                    background: '#e2e2e2',
                    color: '#007bff',
                });
            }
        } );

        Draw_Table(row.child,data);
        tr.addClass('shown');
        
        ef.target.innerHTML = "expand_less";
        ef.target.style.background = '#ff5252';
        ef.target.style.color = '#e2e2e2';
    }
});


function Draw_Table ( callback, dta ) {    
    var table = thead = tBody  = '';
    $.each(dta.FECHA, function (i, item) {
        
        var month_UND = item.mes + '_UND'
        var month_VAL = item.mes + '_VAL'
        var Cumpli = (( dta[month_VAL] / dta.VAL_MES ) * 100) 

        thead += `<th class="center">`+item.mes+`</th>`;

        tBody +=  `<td><table class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="bg-blue text-light">META</th>
                                <th class="bg-blue text-light">META VAL. C$</th>
                                <th class="bg-blue text-light">FACT.</th>                                    
                                <th class="bg-blue text-light">FACT. C$</th>
                                <th class="bg-blue text-light">%</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <p class="text-right">`+numeral(dta.UND_MES).format('0,0.00')+`</p></td>
                                <td> <p class="text-right">`+numeral(dta.VAL_MES).format('0,0.00')+`</p></td>
                                <td> <p class="text-right">`+numeral(dta[month_UND]).format('0,0.00')+`</p></td>
                                <td> <p class="text-right">`+numeral(dta[month_VAL]).format('0,0.00')+`</p></td>
                                <td> <p class="text-right">`+numeral(Cumpli).format('0,0.00')+`</p></td>
                            </tr>
                        </tbody>
                    </table></td>`;



    });

    table = `<table class="table table-striped table-bordered table-sm">
                    <thead class="text-center bg-secondary text-black">
                        <tr>`+thead+`</tr>
                    </thead>
                    <tbody>
                        <tr>`+tBody+`</tr>
                    </tbody>
                    </table>`;



    

callback(table).show();
    
}
$("#btnCalcular").click( function() {
    CalcIndicadores_89();
   
});

$('#txt_Search89').on( 'keyup', function () {
    var table = $('#dtProyect89').DataTable();
    table.search(this.value).draw();
});

function CalcIndicadores_89(){

    f1 = $("#f1").val();
    f2 = $("#f2").val();

    $("#spn_dtIni").html(moment(f1).format('MMM/YY'))
    $("#spn_dtEnd").html(moment(f2).format('MMM/YY'))
    
    $("#Id_Progress_Bar").empty().append(`<div>
                <div class="d-flex align-items-center">
                    <strong class="text-info">Calculando...</strong>
                    <div class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
                </div>
            </div>`);

    


    

    $.getJSON("dtProyect?f1="+f1+"&f2="+f2+"&pr=1", function(dataset) {

        var c = 2 ; 
        var Header_Align = [];
        var tbl_header = [];


        

        tbl_header = [
            { "title": "DETALLES",      "data": "DETALLE" },   
            { "title": "ARTICULO",      "data": "ARTICULO" },
            {"title": "DESCRIPCION",    "data": "DESCRIPCION", "render": function(data, type, row, meta) { 
                return`<a href="#!" onclick="OpenModal_Pro89(`+ "'" +row.ARTICULO + "'" +` )" >`+ row.DESCRIPCION +`</a>`
            }},
            { "title": "PRESUPUESTO",   "data": "PRESUPUESTO" , render: $.fn.dataTable.render.number(',', '.', 0, '')},
            { "title": "C$ VALOR.",     "data": "CS_VALOR", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { "title": "PREC. PROM.",   "data": "PREC_PROM", render: $.fn.dataTable.render.number(',', '.', 4, '') },
            { "title": "CONTRIBUCION",  "data": "CONTRIBUCION", render: $.fn.dataTable.render.number(',', '.', 0, '') },
        ];

        // $.each(dataset[0]['FECHA'], function(key, val) {
        //     tbl_header.push({ "title": val.mes, "data": val,"render": function(data, type, row, meta) {

        //         var month_UND = val.mes + '_UND'
        //         var month_VAL = val.mes + '_VAL'

        //         var Cumpli = (( row[month_VAL] / row.VAL_MES ) * 100) 

        //         table =  `<table>
        //                     <thead>
        //                         <tr>
        //                             <th class="bg-blue text-light">META</th>
        //                             <th class="bg-blue text-light">META VAL. C$</th>
        //                             <th class="bg-blue text-light">FACT.</th>                                    
        //                             <th class="bg-blue text-light">FACT. C$</th>
        //                             <th class="bg-blue text-light">%</th>
        //                     </thead>
        //                     <tbody>
        //                         <tr>
        //                             <td> <p class="text-right">`+numeral(row.UND_MES).format('0,0.00')+`</p></td>
        //                             <td> <p class="text-right">`+numeral(row.VAL_MES).format('0,0.00')+`</p></td>
        //                             <td> <p class="text-right">`+numeral(row[month_UND]).format('0,0.00')+`</p></td>
        //                             <td> <p class="text-right">`+numeral(row[month_VAL]).format('0,0.00')+`</p></td>
        //                             <td> <p class="text-right">`+numeral(Cumpli).format('0,0.00')+`</p></td>
        //                         </tr>
        //                     </tbody>
        //                 </table>`;
  
        //         return  table;
        //     } });
        //     Header_Align.push(c)
        //     c++;
        // });


        

        dataProyect(dataset, tbl_header,'#dtProyect89',[3,4,5,6]);
    
    
    })
}


function OpenModal_Pro89(ARTICULO) {
    $("#mdl_char_product").modal();
    bluid_char(ARTICULO,1);
}


function dataProyect(datos, Header,Table,Align) {


    if ( $.fn.DataTable.isDataTable(Table) ) {

        var dataTable = $(Table).DataTable();

        dataTable.clear().destroy();

        $(Table).empty();
        

        
    }
    var ObjTable = $(Table).DataTable({
        "data": datos,
        "destroy" : true,
        "info":    true,
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
        fixedColumns: {
            left: 6,
            right: 0
        },
        paging: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 500,
        "columnDefs": [
            {"className": "dt-center","targets": [0]},
            {"className": "dt-right","targets": Align},
            
        ],
        
        "footerCallback": function ( row, data, start, end, display ) {
            //$("#IdCardTitle").text("Proyecto 89") 
            $("#Id_Progress_Bar").empty();
        
        },
    });

        
    ObjTable.columns().header().each(function (columnHeader) {
        $(columnHeader).addClass('bg-blue text-light');
    });

    $(Table + "_length").hide();
    $(Table + "_filter").hide();


}





</script>