<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;

class ArticulosTransito extends Model
{
    
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.tbl_articulos_transito_v2";
    protected $primaryKey = 'Id_transito';
    protected $keyType    = 'string';


    public function getArticulo()
    {
        return $this->belongsTo(Articulo::class, 'Articulo', 'ARTICULO');
    }

    protected $fillable = [
        'Articulo',
        'Descripcion',
        'fecha_estimada',
        'fecha_pedido',
        'documento',
        'cantidad',
        'mercado',
        'mific',
        'observaciones',
        'estado_compra',
        'Nuevo',
        'Precio_mific_farmacia',
        'Precio_mific_public',
        'cantidad_pedido',
        'cantidad_transito',
        'via_transporte'
    ];


    public static function SaveTransitoExcel(Request $request) 
    {
        if ($request->ajax()) {
            try {
                $datos_a_insertar = array();    
            
                ArticulosTransito::truncate();

                foreach ($request->input('datos') as $k => $v) 
                {
                    $Cantidad = number_format(str_replace(',', '', $v['CANTIDAD']), 4,'.','');
                    $Articulo = ($v['ARTICULO'] == 'N/D' || $v['ARTICULO'] == 'N/A' || is_numeric(intval($v['ARTICULO']) == false)) ? mt_rand(10000000, 99999999).'-N' : $v['ARTICULO'] ;
                    $Estado = strtoupper((isset($v['estado_pedido'])) ? $v['estado_pedido'] : 'N/D');
                    $Mercado = (isset($v['Mercado'])) ? $v['Mercado'] : 'N/D';
                    $Mific = (isset($v['Mific'])) ? $v['Mific'] : 'N/D';
                    $Documento = (isset($v['Documento'])) ? $v['Documento'] : 'N/D';
                    $Comment = (isset($v['Comment'])) ? $v['Comment'] : 'N/D';
                    $Via_transi = (isset($v['Via_transi'])) ? $v['Via_transi'] : 'N/D';

                    $datos_a_insertar[$k] = [
                        'Articulo'		        => $Articulo,
                        'Descripcion'		    => strtoupper($v['DESCRIPC']),
                        'cantidad'		        => $Cantidad,
                        'cantidad_pedido'	    => ($Estado === 'PEDIDO') ? $Cantidad : '0' ,
                        'cantidad_transito'	    => ($Estado === 'TRANSITO' || $Estado ==='ON-HAND') ? $Cantidad : '0',
                        'estado_compra'		    => $Estado,
                        'fecha_pedido'		    => $v['dtPedido'],
                        'fecha_estimada'	    => (strpos($v['dtEstimada'], 'N/') === false) ? $v['dtEstimada'] : null ,
                        'mercado'		        => strtoupper($Mercado),
                        'mific'			        => strtoupper($Mific),
                        'documento'		        => $Documento,
                        'observaciones'		    => $Comment,
                        'Nuevo'		            => 'N',                        
                        'via_transporte'        => $Via_transi,
                        'Precio_mific_farmacia' => 0,
                        'Precio_mific_public'   => 0,
                    ];
                }


                
                $response = ArticulosTransito::insert($datos_a_insertar);
                
                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    
    public static function getTransitoConCodigo() 
    {

        $Array    = array();
        $result = ArticulosTransito::where('ARTICULO', 'NOT LIKE', '%-N%')->get();
        
        foreach ($result as $k => $v) {
            $Array[$k] = [
                'ID'                => $v['Id_transito'],
                'ARTICULO'          => $v['Articulo'],
                //'DESCRIPCION'       => strtoupper($v['Descripcion']),
                'DESCRIPCION'       => strtoupper($v->getArticulo->DESCRIPCION) ?? 'N/D',
                'FECHA_ESTIMADA'    => ($v['fecha_estimada']== null) ? 'N/D' : \Date::parse($v['fecha_estimada'])->format('D, M d, Y') ,
                'FECHA_PEDIDO'      => ($v['fecha_pedido']== null) ? 'N/D' : \Date::parse($v['fecha_pedido'])->format('D, M d, Y') ,
                'PEDIDO'            => number_format($v['cantidad_pedido'], 0),
                'TRANSITO'          => number_format($v['cantidad_transito'], 0),
                'CANTIDAD'          => number_format($v['cantidad'], 0),
                'MERCADO'           => strtoupper($v['mercado']),
            ];        
        }        

        return $Array;
    }
    public static function getTransitoSinCodigo() 
    {
        $Array    = array();
        $result = ArticulosTransito::where('ARTICULO', 'LIKE', '%-N%')->get();

        foreach ($result as $k => $v) {
            $Array[] = [
                'ID'                => $v['Id_transito'],
                'ARTICULO'          => $v->Articulo,
                'DESCRIPCION'       => strtoupper($v['Descripcion']),
                'FECHA_ESTIMADA'    => ($v['fecha_estimada']== null) ? 'N/D' : \Date::parse($v['fecha_estimada'])->format('D, M d, Y') ,
                'FECHA_PEDIDO'      => ($v['fecha_pedido']== null) ? 'N/D' : \Date::parse($v['fecha_pedido'])->format('D, M d, Y') ,
                'CANTIDAD'          => number_format($v['cantidad'], 0),
                'MERCADO'           => strtoupper($v['mercado']),
            ];        
        }

        
        return $Array;
    }

    public static function DeleteArticuloTransito(Request $request)
    {
        if ($request->ajax()) {
            try {                
                $NumRow     = $request->NumRow;

                $response = ArticulosTransito::WHERE('Id_transito', $NumRow)->delete();
                return response()->json($response);

            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }

    public static function ExportToExcel() {
        $objPHPExcel = new PHPExcel();
        $titulosColumnas = array();
        $columnIndex = 0;
        $rowIndex = 1;

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

        $transito = ArticulosTransito::get();
        $titulosColumnas = array_keys($transito->first()->toArray());
        
        foreach (array_slice($titulosColumnas, 1) as $titulo) {
            $i = 2;

            // Convierte a mayúsculas y reemplaza guiones bajos por espacios
            $NameColumna = strtoupper(str_replace('_', ' ', $titulo));

            // Asigna la letra a la columna (A, B, C, ...)
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($columnIndex);

            // Escribe el nombre de la columna en la primera fila
            $objPHPExcel->setActiveSheetIndex()->setCellValue($columnLetter . $rowIndex, $NameColumna);
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnLetter)->setWidth(15);

            // Asigna los valores a cada una de las celdas
            foreach ($transito as $key) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue($columnLetter.$i, $key[$titulo]);
                $i++;
            }

            $columnIndex++;
        }


        $ultimaColumnaLetra = PHPExcel_Cell::stringFromColumnIndex($columnIndex - 1);
    
        $i++;   

        //ANCHO DE CADA COLUMNAS
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(110);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $ultimaColumnaLetra . '1')->applyFromArray($estiloTituloColumnas);

        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:". $ultimaColumnaLetra .($i-1));

        //FORMATOS NUMERICOS
        $formatCode = '_-" "* #,##0.00_-;_-" "* #,##0.00_-;_-" "* "-"??_-;_-@_-';
        $objPHPExcel->getActiveSheet()->getStyle("C2:". $ultimaColumnaLetra .($i-1))->getNumberFormat()->setFormatCode($formatCode);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Transito.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');


    }

}
