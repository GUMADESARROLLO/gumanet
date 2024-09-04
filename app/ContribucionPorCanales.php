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
use PhpParser\Node\Stmt\Foreach_;

class ContribucionPorCanales extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.view_canal_contribuciones";

    public static function getData(){
        $json = array(); $i = 0;
        $sql = ContribucionPorCanales::all();
        $result = DB::connection('sqlsrv')->select("SELECT * FROM PRODUCCION.dbo.tbl_articulos_transito");

        foreach($sql as $row){
            $TotalCantidad = $row['FARMACIA_CANTIDAD']+$row['CADENA_FARMACIA_CANTIDAD']+$row['MAYORISTA_CANTIDAD']+$row['INSTITUCION_PRIVADA_CANTIDAD']+$row['CRUZ_AZUL_CANTIDAD']+$row['INSTITUCION_PUBLICA_CANTIDAD'];
            $TotalCosto = $row['FARMACIA_COSTO']+$row['CADENA_FARMACIA_COSTO']+$row['MAYORISTA_COSTO']+$row['INSTITUCION_PRIVADA_COSTO']+$row['CRUZ_AZUL_COSTO']+$row['INSTITUCION_PUBLICA_COSTO'];
            $TotalVenta = $row['FARMACIA_VENTA']+$row['CADENA_FARMACIA_VENTA']+$row['MAYORISTA_VENTA']+$row['INSTITUCION_PRIVADA_VENTA']+$row['CRUZ_AZUL_VENTA']+$row['INSTITUCION_PUBLICA_VENTA'];

            $articulo = $row['ARTICULO']; 
            $CantOnHand = array_filter($result, function($item) use ($articulo) {
                return ($item->estado_compra === 'ON-HAND') && $item->Articulo == $articulo; 
            });

            $CantOnHandTransito = array_filter($result, function($item) use ($articulo) {
                return $item->cantidad_transito > 0 && $item->Articulo == $articulo; 
            });

            $json[$i]['COSTO_PROM_PRIV_PACK']                       = (($TotalCantidad-$row['INSTITUCION_PUBLICA_CANTIDAD']) > 0) ? ($TotalCosto-$row['INSTITUCION_PUBLICA_COSTO'])/($TotalCantidad-$row['INSTITUCION_PUBLICA_CANTIDAD']):0;
            $json[$i]['COSTO_PROM_MINSA_PACK']                      = ($row['INSTITUCION_PUBLICA_CANTIDAD'] > 0) ? $row['INSTITUCION_PUBLICA_COSTO']/$row['INSTITUCION_PUBLICA_CANTIDAD']:0;
            $json[$i]['Valor_USD_Inventario_ONHAND_PRIVADO']        = ($CantOnHand != null) ? $CantOnHand[0]->cantidad_transito:0;
            $json[$i]['Valor_USD_Total_OnHand+Tránsito_PRIVADO']    = ($CantOnHandTransito != null) ? $CantOnHandTransito[0]->cantidad_transito:0;
            $json[$i]['ARTICULO']                                   = $row['ARTICULO'];
            $json[$i]['DESCRIPCION']                                = $row['DESCRIPCION'];
            $json[$i]['FABRICANTE']                                 = $row['FABRICANTE'];
            $json[$i]['FARMACIA_CANTIDAD']                          = $row['FARMACIA_CANTIDAD'];
            $json[$i]['FARMACIA_PROMEDIO']                          = $row['FARMACIA_PROMEDIO'];
            $json[$i]['FARMACIA_VENTA']                             = $row['FARMACIA_VENTA'];
            $json[$i]['FARMACIA_COSTO']                             = $row['FARMACIA_COSTO'];
            $json[$i]['FARMACIA_CONTRIBUCION']                      = $row['FARMACIA_CONTRIBUCION'];
            $json[$i]['FARMACIA_MARGEN']                            = $row['FARMACIA_MARGEN'];
            $json[$i]['CADENA_FARMACIA_CANTIDAD']                   = $row['CADENA_FARMACIA_CANTIDAD'];
            $json[$i]['CADENA_FARMACIA_PROMEDIO']                   = $row['CADENA_FARMACIA_PROMEDIO'];
            $json[$i]['CADENA_FARMACIA_VENTA']                      = $row['CADENA_FARMACIA_VENTA'];
            $json[$i]['CADENA_FARMACIA_COSTO']                      = $row['CADENA_FARMACIA_COSTO'];
            $json[$i]['CADENA_FARMACIA_CONTRIBUCION']               = $row['CADENA_FARMACIA_CONTRIBUCION'];
            $json[$i]['CADENA_FARMACIA_MARGEN']                     = $row['CADENA_FARMACIA_MARGEN'];
            $json[$i]['MAYORISTA_CANTIDAD']                         = $row['MAYORISTA_CANTIDAD'];
            $json[$i]['MAYORISTA_PROMEDIO']                         = $row['MAYORISTA_PROMEDIO'];
            $json[$i]['MAYORISTA_VENTA']                            = $row['MAYORISTA_VENTA'];
            $json[$i]['MAYORISTA_COSTO']                            = $row['MAYORISTA_COSTO'];
            $json[$i]['MAYORISTA_CONTRIBUCION']                     = $row['MAYORISTA_CONTRIBUCION'];
            $json[$i]['MAYORISTA_MARGEN']                           = $row['MAYORISTA_MARGEN'];
            $json[$i]['INSTITUCION_PRIVADA_CANTIDAD']               = $row['INSTITUCION_PRIVADA_CANTIDAD'];
            $json[$i]['INSTITUCION_PRIVADA_PROMEDIO']               = $row['INSTITUCION_PRIVADA_PROMEDIO'];
            $json[$i]['INSTITUCION_PRIVADA_VENTA']                  = $row['INSTITUCION_PRIVADA_VENTA'];
            $json[$i]['INSTITUCION_PRIVADA_COSTO']                  = $row['INSTITUCION_PRIVADA_COSTO'];
            $json[$i]['INSTITUCION_PRIVADA_CONTRIBUCION']           = $row['INSTITUCION_PRIVADA_CONTRIBUCION'];
            $json[$i]['INSTITUCION_PRIVADA_MARGEN']                 = $row['INSTITUCION_PRIVADA_MARGEN'];
            $json[$i]['CRUZ_AZUL_CANTIDAD']                         = $row['CRUZ_AZUL_CANTIDAD'];
            $json[$i]['CRUZ_AZUL_PROMEDIO']                         = $row['CRUZ_AZUL_PROMEDIO'];
            $json[$i]['CRUZ_AZUL_VENTA']                            = $row['CRUZ_AZUL_VENTA'];
            $json[$i]['CRUZ_AZUL_COSTO']                            = $row['CRUZ_AZUL_COSTO'];
            $json[$i]['CRUZ_AZUL_CONTRIBUCION']                     = $row['CRUZ_AZUL_CONTRIBUCION'];
            $json[$i]['CRUZ_AZUL_MARGEN']                           = $row['CRUZ_AZUL_MARGEN'];
            $json[$i]['INSTITUCION_PUBLICA_CANTIDAD']               = $row['INSTITUCION_PUBLICA_CANTIDAD'];
            $json[$i]['INSTITUCION_PUBLICA_PROMEDIO']               = $row['INSTITUCION_PUBLICA_PROMEDIO'];
            $json[$i]['INSTITUCION_PUBLICA_VENTA']                  = $row['INSTITUCION_PUBLICA_VENTA'];
            $json[$i]['INSTITUCION_PUBLICA_COSTO']                  = $row['INSTITUCION_PUBLICA_COSTO'];
            $json[$i]['INSTITUCION_PUBLICA_CONTRIBUCION']           = $row['INSTITUCION_PUBLICA_CONTRIBUCION'];
            $json[$i]['INSTITUCION_PUBLICA_MARGEN']                 = $row['INSTITUCION_PUBLICA_MARGEN'];
            $json[$i]['TOTAL_VENTAS_PACK']                          = $TotalCantidad;
            $json[$i]['TOTAL_PRECIO_PROM']                          = ($TotalCantidad > 0) ? $TotalVenta/$TotalCantidad:0;
            $json[$i]['TOTAL_VENTAS_C$']                            = $TotalVenta;
            $json[$i]['TOTAL_COSTOS_C$']                            = $TotalCosto;
            $json[$i]['TOTAL_CONTRIBUCION_C$']                      = $TotalVenta-$TotalCosto;
            $json[$i]['TOTAL_MARGEN']                               = (($TotalCantidad > 0) ? ($TotalVenta-$TotalCosto)/$TotalVenta:0)*100;
            $i++;
        }

        return $json;
    }

    public static function calcularCanales($fechaIni, $fechaEnd)
    {
        DB::connection('sqlsrv')->statement("EXEC PRODUCCION.dbo.pr_calcular_canal_contribucion_dev ?, ?", [$fechaIni, $fechaEnd]);
    }

    public static function periodoFechas(){
        $currentDate = date('Y-m-d');
        $startOfMonth = date('Y-m-01', strtotime($currentDate));

        $FechaIni   = date('Y-m-d 00:00:00.000', strtotime('-11 months', strtotime($startOfMonth)));
        $FechaEnd   = date('Y-m-d 00:00:00.000', strtotime($currentDate . ' -1 days'));

        $result = DB::connection('sqlsrv')->select("SELECT MIN(fecha) AS primera_fecha, MAX(fecha) AS ultima_fecha FROM PRODUCCION.dbo.tbl_canales_contribuciones");
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
        $rowIndex = 1;

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
            $CantidadFarmacia = 0; $VentasFarmacia = 0; $CostoFarmacia = 0;
            $CantidadCadenaFarmacia = 0; $VentasCadenaFarmacia = 0; $CostoCadenaFarmacia = 0;
            $CantidadMayorista = 0; $VentasMayorista = 0; $CostoMayorista = 0;
            $CantidadIntitucionPrivida = 0; $VentasIntitucionPrivida = 0; $CostoIntitucionPrivida = 0;
            $CantidadCruzAzul = 0; $VentasCruzAzul = 0; $CostoCruzAzul = 0;
            $CantidaIntitucionPublica = 0; $VentasIntitucionPublica = 0; $CostoIntitucionPublica = 0;
            $CantidadTotal = 0; $VentasTotal = 0; $CostoTotal = 0;
            //TODO: AQUI EXCLUIR LAS COLUMNAS QUE NO QUIERES QUE SE MUESTRE EN EL REPORTE
            if (!in_array($titulo, array('FechaFinal', 'IS_CA','CALC_AVG'))) {
                $i = 3;

                $NameColumna = (strlen($titulo) <= 2) ? "Mes".$titulo : $titulo ;

                //ASIGNA LA LETRA A LA COLUMNA
                $columnLetter = PHPExcel_Cell::stringFromColumnIndex($columnIndex);
               
                $objPHPExcel->setActiveSheetIndex()->setCellValue($columnLetter . $rowIndex, $NameColumna);
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnLetter)->setWidth(15);
                
                //ASIGNA LOS VALORES A CADA UNA DE LAS CELDAS
                foreach ($RowReOrderPoint as $key) {
                    $objPHPExcel->setActiveSheetIndex()->setCellValue($columnLetter.$i, $key[$titulo]);
                    $CantidadFarmacia += $key['FARMACIA_CANTIDAD'];
                    $VentasFarmacia += $key['FARMACIA_VENTA'];
                    $CostoFarmacia += $key['FARMACIA_COSTO'];
                    $CantidadCadenaFarmacia += $key['CADENA_FARMACIA_CANTIDAD'];
                    $VentasCadenaFarmacia += $key['CADENA_FARMACIA_VENTA'];
                    $CostoCadenaFarmacia += $key['CADENA_FARMACIA_COSTO'];
                    $CantidadMayorista += $key['MAYORISTA_CANTIDAD'];
                    $VentasMayorista += $key['MAYORISTA_VENTA'];
                    $CostoMayorista += $key['MAYORISTA_COSTO'];
                    $CantidadIntitucionPrivida += $key['INSTITUCION_PRIVADA_CANTIDAD'];
                    $VentasIntitucionPrivida += $key['INSTITUCION_PRIVADA_VENTA'];
                    $CostoIntitucionPrivida += $key['INSTITUCION_PRIVADA_COSTO'];
                    $CantidadCruzAzul += $key['CRUZ_AZUL_CANTIDAD'];
                    $VentasCruzAzul += $key['CRUZ_AZUL_VENTA'];
                    $CostoCruzAzul += $key['CRUZ_AZUL_COSTO'];
                    $CantidaIntitucionPublica += $key['INSTITUCION_PUBLICA_CANTIDAD'];
                    $VentasIntitucionPublica += $key['INSTITUCION_PUBLICA_VENTA'];
                    $CostoIntitucionPublica += $key['INSTITUCION_PUBLICA_COSTO'];
                    $CantidadTotal += $key['TOTAL_VENTAS_PACK'];
                    $VentasTotal += $key['TOTAL_VENTAS_C$'];
                    $CostoTotal += $key['TOTAL_COSTOS_C$'];
                    $i++;
                }
                // TOTAL FARMACIA
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D2', ''.$CantidadFarmacia);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E2', ''.($VentasFarmacia/$CantidadFarmacia));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F2', ''.$VentasFarmacia);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G2', ''.$CostoFarmacia);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H2', ''.($VentasFarmacia-$CostoFarmacia));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I2', ''.(($VentasFarmacia-$CostoFarmacia)/$VentasFarmacia)*100);

                //TOTAL CADENA DE FARMACIA
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J2', ''.$CantidadCadenaFarmacia);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K2', ''.($VentasCadenaFarmacia/$CantidadCadenaFarmacia));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('L2', ''.$VentasCadenaFarmacia);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('M2', ''.$CostoCadenaFarmacia);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('N2', ''.($VentasCadenaFarmacia-$CostoCadenaFarmacia));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('O2', ''.(($VentasCadenaFarmacia-$CostoCadenaFarmacia)/$VentasCadenaFarmacia)*100);
                // TOTAL MAYORISTA
                $objPHPExcel->setActiveSheetIndex()->setCellValue('P2', ''.$CantidadMayorista);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('Q2', ''.($VentasMayorista/$CantidadMayorista));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('R2', ''.$VentasMayorista);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('S2', ''.$CostoMayorista);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('T2', ''.($VentasMayorista-$CostoMayorista));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('U2', ''.(($VentasMayorista-$CostoMayorista)/$VentasMayorista)*100);
                
                // TOTAL INSTITUCION PRIVADA
                $objPHPExcel->setActiveSheetIndex()->setCellValue('V2', ''.$CantidadIntitucionPrivida);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('W2', ''.($VentasIntitucionPrivida/$CantidadIntitucionPrivida));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('X2', ''.$VentasIntitucionPrivida);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('Y2', ''.$CostoIntitucionPrivida);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('Z2', ''.($VentasIntitucionPrivida-$CostoIntitucionPrivida));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AA2', ''.(($VentasIntitucionPrivida-$CostoIntitucionPrivida)/$VentasIntitucionPrivida)*100);
                
                // TOTAL CRUZ AZUL
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AB2', ''.$CantidadCruzAzul);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AC2', ''.($VentasCruzAzul/$CantidadCruzAzul));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AD2', ''.$VentasCruzAzul);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AE2', ''.$CostoCruzAzul);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AF2', ''.($VentasCruzAzul-$CostoCruzAzul));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AG2', ''.(($VentasCruzAzul-$CostoCruzAzul)/$VentasCruzAzul)*100);
        
                // TOTAL INSTITUCION PUBLICA
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AH2', ''.$CantidaIntitucionPublica);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AI2', ''.($VentasIntitucionPublica/$CantidaIntitucionPublica));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AJ2', ''.$VentasIntitucionPublica);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AK2', ''.$CostoIntitucionPublica);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AL2', ''.($VentasIntitucionPublica-$CostoIntitucionPublica));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AM2', ''.(($VentasIntitucionPublica-$CostoIntitucionPublica)/$VentasIntitucionPublica)*100);
                
                // TOTALES
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AN2', ''.$CantidadTotal);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AO2', ''.($VentasTotal/$CantidadTotal));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AP2', ''.$VentasTotal);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AQ2', ''.$CostoTotal);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AR2', ''.($VentasTotal-$CostoTotal));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('AS2', ''.(($VentasTotal-$CostoTotal)/$VentasTotal)*100);
                $columnIndex++;
            }
            
        }
        $ultimaColumnaLetra = PHPExcel_Cell::stringFromColumnIndex($columnIndex - 1);
        $i++;   
        
        //ANCHO DE CADA COLUMNAS
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(110);
        $startColumn = 'D';
        $endColumn = 'AS';
        
        for ($column = $startColumn; $column !== $endColumn; $column++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(30);
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension($endColumn)->setWidth(30);

        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $ultimaColumnaLetra . '1')->applyFromArray($estiloTituloColumnas);

        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:". $ultimaColumnaLetra .($i-1));

        //FORMATOS NUMERICOS
        $formatCode = '_-" "* #,##0.00_-;_-" "* #,##0.00_-;_-" "* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle("D2:". $ultimaColumnaLetra .($i-1))->getNumberFormat()->setFormatCode($formatCode);

        /*$objPHPExcel->getActiveSheet()->getStyle("A1:C1")->applyFromArray([
            'fillType' => PHPExcel_Style_Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0000FF' 
            ]
        ]);*/

        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '1d5dec')
        ));

        $objPHPExcel->getActiveSheet()->getStyle("D1:I1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'FFFF00')
        ));

        $objPHPExcel->getActiveSheet()->getStyle("J1:O1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'FF6F1D')
        ));
        
        $objPHPExcel->getActiveSheet()->getStyle("P1:U1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'EE9C4')
        ));

        $objPHPExcel->getActiveSheet()->getStyle("V1:AA1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '1dec43')
        ));

        $objPHPExcel->getActiveSheet()->getStyle("AB1:AG1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '1d91ec')
        ));

        $objPHPExcel->getActiveSheet()->getStyle("AH1:AM1")->getFill('')
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '1dec43')
        ));

        $objPHPExcel->getActiveSheet()->getStyle("AN1:AS1")->getFill('')
            ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => 'FFFF00')
        ));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_por_canales.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }
}