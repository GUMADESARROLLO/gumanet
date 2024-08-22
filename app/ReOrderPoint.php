<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Cell;

class ReOrderPoint extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_gnet_reorder_lvl3";

    /**
     * Executes three stored procedures to calculate reorder points for articles.
     *
     * @return void
     */
    public static function CalcReorder()
    {
        $currentDate = date('Y-m-d');
        $startOfMonth = date('Y-m-01', strtotime($currentDate));

        $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-12 months', strtotime($startOfMonth)));
        $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate . ' -1 days'));
        $DiaActual  = (int) date('d', strtotime($FechaEnd));


        
        
        // Ejecutar el tercer procedimiento almacenado
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.sp_Calc_12_month_reorder_point ?, ?, ?", [$FechaIni, $FechaEnd, $DiaActual]);

        // Ejecutar el primer procedimiento almacenado
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.pr_calc_reorder_factura_linea ?, ?", [$FechaIni, $FechaEnd]);

        // Ejecutar el segundo procedimiento almacenado
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.pr_calc_reorder_factura_linea_ca ?", [$FechaEnd]);

        

    }

    public static function getArticulo() 
    {
        $array = [];
        $Articulos = ReOrderPoint::WHERE('VALUACION','!=',"0")->get();
        foreach ($Articulos as $key => $a) {
            $array[$key] = [
                "ARTICULO"                  => '<a href="#!" onclick="getDetalleArticulo('."'".$a->ARTICULO."'".', '."'".strtoupper($a->DESCRIPCION)."'".')" >'.$a->ARTICULO.'</a>',
                "DESCRIPCION"               => strtoupper($a->DESCRIPCION),
                "VENCE_MENOS_IGUAL_12"      => number_format($a->VENCE_MENOS_IGUAL_12,2),
                "VENCE_MAS_IGUAL_7"         => number_format($a->VENCE_MAS_IGUAL_7,2),
                "LOTE_MAS_PROX_VENCER"      => date("d-m-Y", strtotime($a->LOTE_MAS_PROX_VENCER)),
                "EXIT_LOTE_PROX_VENCER"     => number_format($a->EXIT_LOTE_PROX_VENCER,2),
                "FECHA_ENTRADA_LOTE"        => date("d-m-Y", strtotime($a->FECHA_ENTRADA_LOTE)),
                "CANTIDAD_INGRESADA"        => number_format($a->CANTIDAD_INGRESADA,2),
                "LEADTIME"                  => $a->LEADTIME,
                "EJECUTADO_UND_YTD"         => number_format($a->EJECUTADO_UND_YTD,2),
                "VENTAS_YTD"                => number_format($a->VENTAS_YTD,2),
                "CONTRIBUCION_YTD"          => number_format($a->CONTRIBUCION_YTD,2),
                "DEMANDA_ANUAL_CA_NETA"     => number_format($a->DEMANDA_ANUAL_CA_NETA,2),
                "DEMANDA_ANUAL_CA_AJUSTADA" => number_format($a->DEMANDA_ANUAL_CA_AJUSTADA,2),
                "FACTOR"                    => number_format($a->FACTOR,2),
                "LIMITE_LOGISTICO_MEDIO"    => number_format($a->LIMITE_LOGISTICO_MEDIO,2),
                "CLASE"                     => $a->CLASE,
                "VALUACION"                 => $a->VALUACION,
                "CONTRIBUCION"              => number_format($a->CONTRIBUCION,2),
                "PEDIDO"                    => number_format($a->PEDIDO,2),
                "TRANSITO"                  => number_format($a->TRANSITO,2),
                "MOQ"                       => number_format($a->MOQ,2),
                "ESTIMACION_SOBRANTES_UND"  => number_format($a->ESTIMACION_SOBRANTES_UND,2),
                "REORDER1"                  => number_format($a->REORDER1,2),
                "REORDER"                   => number_format($a->REORDER,2),
                "CANTIDAD_ORDENAR"          => number_format($a->CANTIDAD_ORDENAR,2),
                "IS_CA"                     => $a->IS_CA,
                "ROTACION_CORTA"            => number_format($a->ROTACION_CORTA, 2),
                "ROTACION_MEDIA"            => number_format($a->ROTACION_MEDIA, 2),
                "ROTACION_LARGA"            => number_format($a->ROTACION_LARGA, 2),
                "ULTIMO_COSTO_USD"          => number_format($a->ULTIMO_COSTO_USD, 2),
                "COSTO_PROMEDIO_USD"        => number_format($a->COSTO_PROMEDIO_USD, 2),
                "COSTO_PROMEDIO_LOC"        => number_format($a->COSTO_PROMEDIO_LOC, 2),
                "COSTO_PROMEDIO_LOC"        => number_format($a->COSTO_PROMEDIO_LOC, 2),
                "UPDATED_AT"                => substr($a->FechaFinal, 0, 10),
                "FACTOR_STOCK_SEGURIDAD"    => number_format($a->FACTOR_STOCK_SEGURIDAD, 2),
                
                "ROTACION_PREVISTA_EXISTENCIAS_VENCER" => number_format($a->ROTACION_PREVISTA_EXISTENCIAS_VENCER, 2),
            ];
        }

        return $array;
    }
    public static function getDataGrafica($Articulos) {

        $array = array();

        $Sales = ReOrderPoint::WHERE('ARTICULO',$Articulos)->first();
        
        $array["LEADTIME"] = number_format(bcdiv($Sales->LEADTIME, '1', 0), 0);
        $array["DEMANDA_ANUAL_CA_NETA"] = number_format(bcdiv($Sales->DEMANDA_ANUAL_CA_NETA, '1', 0), 0);
        $array["DEMANDA_ANUAL_CA_AJUSTADA"] = number_format(bcdiv($Sales->DEMANDA_ANUAL_CA_AJUSTADA, '1', 0), 0);
        $array["LIMITE_LOGISTICO_MEDIO"] = number_format(bcdiv($Sales->LIMITE_LOGISTICO_MEDIO, '1', 0), 0);
        $array["CONTRIBUCION"] = number_format(bcdiv($Sales->CONTRIBUCION, '1', 0), 0);

        $array["REORDER1"] = number_format(bcdiv($Sales->REORDER1, '1', 0), 0);
        $array["REORDER"] = number_format(bcdiv($Sales->REORDER, '1', 0), 0);
        $array["CANTIDAD_ORDENAR"] = number_format(bcdiv($Sales->CANTIDAD_ORDENAR, '1', 0), 0);
        $array["MOQ"] = number_format(bcdiv($Sales->MOQ, '1', 0), 0);
        $array["PEDIDO"] = number_format(bcdiv($Sales->PEDIDO, '1', 0), 0);
        $array["TRANSITO"] = number_format(bcdiv($Sales->TRANSITO, '1', 0), 0);
        $array["CLASE"] = $Sales->CLASE;

        $array["ROTACION_CORTA"] = bcadd(number_format($Sales->ROTACION_CORTA, 0), 5, 0);
        $array["ROTACION_MEDIA"] = bcadd(number_format($Sales->ROTACION_MEDIA, 0), 5, 0); 
        $array["ROTACION_LARGA"] = bcadd(number_format($Sales->ROTACION_LARGA, 0), 5, 0);
        

        $array["COSTO_PROMEDIO_USD"] = number_format(bcdiv($Sales->COSTO_PROMEDIO_USD, '1', 0), 0);
        $array["ULTIMO_COSTO_USD"] = number_format(bcdiv($Sales->ULTIMO_COSTO_USD, '1', 0), 0);
        $array["VENTAS_YTD"] = number_format(bcdiv($Sales->VENTAS_YTD, '1', 0), 0);
        $array["CONTRIBUCION_YTD"] = number_format(bcdiv($Sales->CONTRIBUCION_YTD, '1', 0),0);
        $array["EJECUTADO_UND_YTD"] = number_format(bcdiv($Sales->EJECUTADO_UND_YTD, '1', 0),0);
        
        for ($i=1; $i <= 12; $i++) { 
            $array["VENTAS"][$i] = [
                "Mes"                 => "Mes".$i,
                "data" =>  (isset($Sales) && !empty($Sales->$i)) ? (float) number_format($Sales->$i,2,".","") : 0 
            ];
        }

        return $array;
    }

    public static function ExportToExcel() {
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();
        $columnIndex = 0;
        $rowIndex = 3;

        $estiloTituloReporte = array(
            'font' => array(
            'name'      => 'Tahoma',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size'      => 14,
            'color'     => array(
                            'rgb' => '212121')
            ),
            'alignment' =>  array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'rotation'   => 0,
                            'wrap'       => TRUE,
                            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                        'name'  => 'Arial',
                        'bold'  => true
            ),
            'alignment' =>  array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                'wrap'          => TRUE
                            ),
            'borders' => array(
                            'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                            )
            )
        );
                
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(
            array(
                'borders' => array(
                'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                ),
                )
            )
        );    

        $RowReOrderPoint = ReOrderPoint::all();
        $titulosColumnas = array_keys(ReOrderPoint::first()->toArray());

    
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Actualizado al : ". $RowReOrderPoint[0][ 'FechaFinal']);
        

        
        foreach ($titulosColumnas as $titulo) {
            //VALIDACION SI EL CAMPO ES ENTERO Y INCREMENTA A 1 PARA EL NUMERO DE MESES
            $titulo = (!is_string($titulo)) ? strval( $titulo + 1) : $titulo ;

            if (!in_array($titulo, array('FechaFinal', 'IS_CA','CALC_AVG','CONTRIBUCION'))) {
                $i = 4;

                $NameColumna = (strlen($titulo) <= 2) ? "Mes".$titulo : $titulo ;

                //ASIGNA LA LETRA A LA COLUMNA
                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($columnIndex);
                
                $objPHPExcel->setActiveSheetIndex()->setCellValue($columnLetter . $rowIndex, $NameColumna);
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnLetter)->setWidth(15);

                //ASIGNA LOS VALORES A CADA UNA DE LAS CELDAS
                foreach ($RowReOrderPoint as $key) {
                    $objPHPExcel->setActiveSheetIndex()->setCellValue($columnLetter.$i,  $key[$titulo]);
                    $i++;
                }
                
                $columnIndex++;
            }
            
        }
        $ultimaColumnaLetra = PHPExcel_Cell::stringFromColumnIndex($columnIndex - 1);
    
        $i++;   

        //ANCHO DE CADA COLUMNAS
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(110);
        $objPHPExcel->getActiveSheet()->getStyle('A3:' . $ultimaColumnaLetra . '3')->applyFromArray($estiloTituloColumnas);

        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:". $ultimaColumnaLetra .($i-1));

        //FORMATOS NUMERICOS
        $formatCode = '_-" "* #,##0.00_-;_-" "* #,##0.00_-;_-" "* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle("D4:". $ultimaColumnaLetra .($i-1))->getNumberFormat()->setFormatCode($formatCode);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReOrderPoint.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');


    }
}
