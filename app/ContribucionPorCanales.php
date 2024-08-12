<?php

namespace App;

use App\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Cell;

class ContribucionPorCanales extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_canal_contribuciones";

    public static function calcularCanales($fechaIni, $fechaEnd)
    {
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.pr_calcular_canal_contribucion ?, ?", [$fechaIni, $fechaEnd]);
    }

    public static function periodoFechas(){
        $currentDate = date('Y-m-d');
        $startOfMonth = date('Y-m-01', strtotime($currentDate));

        $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-12 months', strtotime($startOfMonth)));
        $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate . ' -1 days'));

        $result = DB::connection('sqlsrv')->select("SELECT MIN(fecha) AS primera_fecha, MAX(fecha) AS ultima_fecha FROM PRODUCCION.dbo.tbl_canales_contribucion");
        return [
            'primera_fecha' => $result[0]->primera_fecha,
            'ultima_fecha' => $result[0]->ultima_fecha,
            'fechaIni' => $FechaIni,
            'fechaEnd' => $FechaEnd,
        ];
    }
    public static function ExportToExcel() {
        $objPHPExcel = new PHPExcel();
        $tituloReporte = "";
        $titulosColumnas = array();
        $columnIndex = 0;
        $rowIndex = 3;

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

        $RowReOrderPoint = ContribucionPorCanales::all();
        $titulosColumnas = array_keys(ContribucionPorCanales::first()->toArray());

    
        //TODO: AGREGAR CORRECTAMENTE DESDE EL CAMPO DE RANGO QUE SE ESTA EVALUANDO
        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Actualizado al : ". $RowReOrderPoint[0][ 'FechaFinal']);       

        
        foreach ($titulosColumnas as $titulo) {
            //VALIDACION SI EL CAMPO ES ENTERO Y INCREMENTA A 1 PARA EL NUMERO DE MESES
            $titulo = (!is_string($titulo)) ? strval( $titulo + 1) : $titulo ;

            //TODO: AQUI EXCLUIR LAS COLUMNAS QUE NO QUIERES QUE SE MUESTRE EN EL REPORTE
            if (!in_array($titulo, array('FechaFinal', 'IS_CA','CALC_AVG'))) {
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
        header('Content-Disposition: attachment;filename="Reporte_por_canales.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');


    }
}