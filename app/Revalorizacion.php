<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Revalorizacion extends Model
{
    /**
     * Catálogo de bodegas de la empresa en sesión (Unimark 1, Innova 4).
     */
    public static function getBodegas()
    {
        $tabla = (Session::get('company_id') == 4) ? 'PRODUCCION.dbo.INN_iweb_bodegas' : 'PRODUCCION.dbo.iweb_bodegas';

        $rows = DB::connection('sqlsrv')->select("SELECT DISTINCT T0.BODEGA, T0.NOMBRE FROM " . $tabla . " T0 ORDER BY T0.BODEGA");

        $Arry = [];
        foreach ($rows as $item) {
            $Arry[] = [
                "BODEGA" => trim($item->BODEGA),
                "NOMBRE" => trim($item->NOMBRE),
            ];
        }
        return $Arry;
    }

    public static function Filtrar($request)
    {
        $bodegas = is_array($request->bodegas) ? $request->bodegas : [];

        return [
            'REVALORIZACION' => self::Generar($bodegas, $request->fecha),
        ];
    }

    /**
     * 1) Calcula la valoración al corte (sp_ActualizaValoracionInventario guarda el corte por lote)
     * 2) Genera el reporte desde ValoracionLote_Corte, solo con las bodegas seleccionadas.
     */
    public static function Generar(array $bodegas, $fecha)
    {
        $bodegas = array_values(array_filter(array_map('trim', $bodegas), 'strlen'));
        if (empty($bodegas) || !self::fechaValida($fecha)) {
            return [];
        }

        $corte = $fecha . ' 23:59:59';

        DB::connection('sqlsrv')->statement("SET NOCOUNT ON; EXEC PRODUCCION.dbo.sp_ActualizaValoracionInventario @FechaCorte = ?", [$corte]);

        $in = implode(',', array_fill(0, count($bodegas), '?'));

        // La tabla guarda varios cortes: filtrar por el que se acaba de calcular
        $rows = DB::connection('sqlsrv')->select("
            SELECT
                FECHA_CORTE,
                ARTICULO,
                DESCRIPCION,
                BODEGA,
                LOTE,
                CANTIDAD,
                UNIDAD_ALMACEN,
                COSTO_UNITARIO,
                COSTO_TOTAL,
                COMPRA_TOTAL,
                NUM_CRM,
                ORDEN_COMPRA,
                PROVEEDOR,
                NOMBRE_PROVEEDOR
            FROM PRODUCCION.dbo.ValoracionLote_Corte
            WHERE FECHA_CORTE = ?
              AND BODEGA IN (" . $in . ")
            ORDER BY ARTICULO, LOTE
        ", array_merge([$corte], $bodegas));

        $Arry = [];
        foreach ($rows as $item) {
            $Arry[] = [
                "ARTICULO"         => trim($item->ARTICULO),
                "DESCRIPCION"      => trim($item->DESCRIPCION),
                "BODEGA"           => trim($item->BODEGA),
                "LOTE"             => trim($item->LOTE),
                "CANTIDAD"         => (float) $item->CANTIDAD,
                "UNIDAD_ALMACEN"   => trim($item->UNIDAD_ALMACEN),
                "COSTO_UNITARIO"   => (float) $item->COSTO_UNITARIO,
                "COSTO_TOTAL"      => (float) $item->COSTO_TOTAL,
                "COMPRA_TOTAL"     => (float) $item->COMPRA_TOTAL,
                "NUM_CRM"          => trim($item->NUM_CRM),
                "ORDEN_COMPRA"     => trim($item->ORDEN_COMPRA),
                "PROVEEDOR"        => trim($item->PROVEEDOR),
                "NOMBRE_PROVEEDOR" => trim($item->NOMBRE_PROVEEDOR),
            ];
        }
        return $Arry;
    }

    private static function fechaValida($fecha)
    {
        $d = \DateTime::createFromFormat('Y-m-d', (string) $fecha);
        return $d && $d->format('Y-m-d') === $fecha;
    }

    /**
     * Libro de Excel presentable: logo, título, filtros, encabezado corporativo, totales y formato de impresión.
     */
    public static function Excel(array $data, $fecha, array $nombres, $logoPath)
    {
        $headers = [
            'Artículo', 'Descripción', 'Bodega', 'Lote', 'Cantidad', 'Unidad', 'Costo Unitario',
            'Costo Total', 'Compra Total', 'Num. CRM', 'Orden Compra', 'Proveedor', 'Nombre Proveedor',
        ];
        $ultCol   = 'M';
        $filaHead = 6;
        $azul     = '002D72';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator('GUMA@NET')->setTitle('Revalorización de Inventario');
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Revalorizacion');
        $sheet->setShowGridlines(false);

        // Encabezado: logo + título + filtros
        $logo = new \PHPExcel_Worksheet_Drawing();
        $logo->setName('Unimark');
        $logo->setPath($logoPath);
        $logo->setHeight(55);
        $logo->setCoordinates('A1');
        $logo->setOffsetX(5);
        $logo->setOffsetY(5);
        $logo->setWorksheet($sheet);

        $sheet->setCellValue('C1', 'REVALORIZACIÓN DE INVENTARIO');
        $sheet->setCellValue('C2', 'Fecha de corte: ' . ($fecha ? date('d/m/Y', strtotime($fecha)) : ''));
        $sheet->setCellValue('C3', 'Bodegas: ' . implode(', ', $nombres));
        $sheet->setCellValue('C4', 'Generado: ' . date('d/m/Y h:i A'));
        foreach (['1', '2', '3', '4'] as $f) {
            $sheet->mergeCells('C' . $f . ':' . $ultCol . $f);
        }
        $sheet->getStyle('C1')->applyFromArray(['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => $azul]]]);
        $sheet->getStyle('C2:C4')->applyFromArray(['font' => ['size' => 10, 'color' => ['rgb' => '555555']]]);
        $sheet->getStyle('C3')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Encabezado de la tabla
        $col = 0;
        foreach ($headers as $h) {
            $sheet->setCellValueByColumnAndRow($col++, $filaHead, $h);
        }
        $sheet->getStyle('A' . $filaHead . ':' . $ultCol . $filaHead)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $azul]],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => true,
            ],
        ]);
        $sheet->getRowDimension($filaHead)->setRowHeight(30);

        // Detalle (códigos como texto para no perder ceros a la izquierda)
        $texto = \PHPExcel_Cell_DataType::TYPE_STRING;
        $row = $filaHead + 1;
        foreach ($data as $item) {
            $sheet->setCellValueExplicitByColumnAndRow(0, $row, $item['ARTICULO'], $texto);
            $sheet->setCellValueByColumnAndRow(1, $row, $item['DESCRIPCION']);
            $sheet->setCellValueExplicitByColumnAndRow(2, $row, $item['BODEGA'], $texto);
            $sheet->setCellValueExplicitByColumnAndRow(3, $row, $item['LOTE'], $texto);
            $sheet->setCellValueByColumnAndRow(4, $row, $item['CANTIDAD']);
            $sheet->setCellValueByColumnAndRow(5, $row, $item['UNIDAD_ALMACEN']);
            $sheet->setCellValueByColumnAndRow(6, $row, $item['COSTO_UNITARIO']);
            $sheet->setCellValueByColumnAndRow(7, $row, $item['COSTO_TOTAL']);
            $sheet->setCellValueByColumnAndRow(8, $row, $item['COMPRA_TOTAL']);
            $sheet->setCellValueExplicitByColumnAndRow(9, $row, $item['NUM_CRM'], $texto);
            $sheet->setCellValueExplicitByColumnAndRow(10, $row, $item['ORDEN_COMPRA'], $texto);
            $sheet->setCellValueExplicitByColumnAndRow(11, $row, $item['PROVEEDOR'], $texto);
            $sheet->setCellValueByColumnAndRow(12, $row, $item['NOMBRE_PROVEEDOR']);
            $row++;
        }
        $ultFila = max($row - 1, $filaHead + 1);

        $sheet->getStyle('E' . ($filaHead + 1) . ':E' . $ultFila)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('G' . ($filaHead + 1) . ':G' . $ultFila)->getNumberFormat()->setFormatCode('#,##0.0000');
        $sheet->getStyle('H' . ($filaHead + 1) . ':I' . $ultFila)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('C' . ($filaHead + 1) . ':D' . $ultFila)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F' . ($filaHead + 1) . ':F' . $ultFila)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Totales como valor: PHPExcel (abandonado) falla al escribir fórmulas en PHP 7.4+
        $filaTot = $ultFila + 1;
        $sheet->setCellValue('A' . $filaTot, 'TOTAL');
        $sheet->mergeCells('A' . $filaTot . ':G' . $filaTot);
        $sheet->setCellValue('H' . $filaTot, array_sum(array_column($data, 'COSTO_TOTAL')));
        $sheet->setCellValue('I' . $filaTot, array_sum(array_column($data, 'COMPRA_TOTAL')));
        $sheet->getStyle('A' . $filaTot . ':' . $ultCol . $filaTot)->applyFromArray([
            'font'    => ['bold' => true],
            'fill'    => ['type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'E9ECEF']],
            'borders' => ['top' => ['style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => ['rgb' => $azul]]],
        ]);
        $sheet->getStyle('A' . $filaTot)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H' . $filaTot . ':I' . $filaTot)->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle('A' . $filaHead . ':' . $ultCol . $filaTot)->applyFromArray([
            'borders' => ['allborders' => ['style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => ['rgb' => 'D0D5DD']]],
        ]);

        // Anchos, paneles fijos e impresión (sin autofiltro: PHPExcel falla al escribirlo en PHP 7.4+)
        $anchos = ['A' => 14, 'B' => 45, 'C' => 9, 'D' => 16, 'E' => 13, 'F' => 9, 'G' => 15,
                   'H' => 16, 'I' => 16, 'J' => 13, 'K' => 15, 'L' => 12, 'M' => 40];
        foreach ($anchos as $c => $w) {
            $sheet->getColumnDimension($c)->setWidth($w);
        }
        $sheet->freezePane('A' . ($filaHead + 1));

        $sheet->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER)
            ->setFitToWidth(1)
            ->setFitToHeight(0)
            ->setRowsToRepeatAtTopByStartAndEnd($filaHead, $filaHead);
        $sheet->getPageMargins()->setTop(0.4)->setBottom(0.4)->setLeft(0.3)->setRight(0.3);
        $sheet->getHeaderFooter()->setOddFooter('&LRevalorización de Inventario&RPágina &P de &N');

        return $objPHPExcel;
    }
}
