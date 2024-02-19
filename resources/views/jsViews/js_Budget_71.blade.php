<script type="text/javascript">


$("#btnTable71").click( function() {
    DrawTable71();
   
});

    function DrawTable71(){



        f1 = $("#f1_p71").val();
        f2 = $("#f2_p71").val();

        $("#spn_dtIni_71").html(moment(f1).format('MMM/YY'))
        $("#spn_dtEnd_71").html(moment(f2).format('MMM/YY'))

        $("#Id_Progress_Bar_71").empty().append(`<div>
                        <div class="d-flex align-items-center">
                            <strong class="text-info">Calculando...</strong>
                            <div class="spinner-border ml-auto text-primary" role="status" aria-hidden="true"></div>
                        </div>
                    </div>`);



        $.getJSON("dtProyect?f1="+f1+"&f2="+f2+"&pr=2", function(dataset) {

            var c = 2 ; 
            var Header_Align = [];
            var tbl_header = [];


            

            tbl_header = [
                { "title": "DETALLES",      "data": "DETALLE" },   
                { "title": "ARTICULO",      "data": "ARTICULO" },
                {"title": "DESCRIPCION",    "data": "DESCRIPCION", "render": function(data, type, row, meta) { 
                    return`<a href="#!" onclick="OpenModal(`+ "'" +row.ARTICULO + "'" +` )" >`+ row.DESCRIPCION +`</a>`
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


            

            TblInit(dataset, tbl_header,'#dtProyect71',[3,4,5,6]);


        })
    }

    function TblInit(datos, Header,Table,Align) {


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
                $("#Id_Progress_Bar_71").empty();
            
            },
        });

            
        ObjTable.columns().header().each(function (columnHeader) {
            $(columnHeader).addClass('bg-blue text-light');
        });

        $(Table + "_length").hide();
        $(Table + "_filter").hide();


}


</script>