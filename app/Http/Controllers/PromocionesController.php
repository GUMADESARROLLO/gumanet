<?php

namespace App\Http\Controllers;

use App\Facturas;
use App\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PromocionesController extends Controller
{
    public function __construct() 
    {
		$this->middleware(['auth','roles'])->except(['SendAcciones', 'ExportRifa', 'qrCliente']);
    }

    public function RifaCar()
    {
        
        $Facturas = Facturas::take(100)->get();

        return view('pages.Promociones.RifaCar.home', compact('Facturas'));
    }

    public function getFactPromocion(Request $request)
    {
        $Facturacion = Facturas::Filtrar($request);

        $Array_Facturacion = [
            'FACTURACION'        => $Facturacion,
        ];
        return response()->json($Array_Facturacion);
    }

    public function getFactAcciones(Request $request)
    {
        $Acciones = Facturas::Acciones($request);

        $Array_Acciones = [
            'ACCIONES'        => $Acciones,
        ];
        return response()->json($Array_Acciones);
    }

    public function AsignarAcciones(Request $request)
    {
        $InfoFactura = Facturas::AsignarAcciones($request);
        return response()->json($InfoFactura);
    }

    public function RevertirAcciones(Request $request)
    {
        $InfoFactura = Facturas::RevertirAcciones($request);
        return response()->json($InfoFactura);
    }

    public function VerificarFacturaAnulada(Request $request)
    {
        $Factura = $request->Factura;
        $resultado = Facturas::VerificarFacturaAnulada($Factura);
        return response()->json($resultado);
    }

    public function getFacturasAnuladas()
    {
        $Facturas = Facturas::getFacturasAnuladasPendientes();

        return response()->json([
            'FACTURAS' => $Facturas
        ]);
    }

    public function MetricasRifa()
    {
        $raw = DB::connection('sqlsrv')->select("SELECT * FROM PRODUCCION.dbo.view_gnet_rifa_stat ORDER BY 1");
        $Num = DB::connection('sqlsrv')->select("
            SELECT T0.NUMERO, T0.USADO, T0.CLIENTE, T0.FACTURA, T0.FECHA_ASIGNACION, T1.NOMBRE
            FROM PRODUCCION.dbo.NUMEROS_RIFA T0
            LEFT JOIN Softland.umk.CLIENTE T1 ON T0.CLIENTE = T1.CLIENTE
        ");

        $stats = [];
        $totalAcciones = 0;
        $totalLibres   = 0;
        $totalUsados   = 0;

        foreach ($raw as $row) {
            $vars = get_object_vars($row);
            $keys = array_keys($vars);

            $serieKey = $keys[0] ?? null;
            $accKey   = null;
            $libKey   = null;
            $usaKey   = null;

            foreach ($keys as $k) {
                $uk = strtoupper($k);
                if ($uk === 'TTACCIONES' || $uk === 'TOTAL_ACCIONES') $accKey = $k;
                elseif ($uk === 'LIBRES') $libKey = $k;
                elseif ($uk === 'USADOS') $usaKey = $k;
            }

            if (!$serieKey || !$accKey || !$libKey || !$usaKey) continue;

            $obj = new \stdClass();
            $obj->Serie      = $vars[$serieKey];
            $obj->ttACCIONES = (int) $vars[$accKey];
            $obj->LIBRES     = (int) $vars[$libKey];
            $obj->USADOS     = (int) $vars[$usaKey];

            $totalAcciones += $obj->ttACCIONES;
            $totalLibres   += $obj->LIBRES;
            $totalUsados   += $obj->USADOS;

            $stats[] = $obj;
        }

        $porcentajeGlobal = $totalAcciones > 0 ? round(($totalLibres / $totalAcciones) * 100, 2) : 0;
        $porcentajeUsadoGlobal = $totalAcciones > 0 ? round(($totalUsados / $totalAcciones) * 100, 2) : 0;

        $asignados = [];
        $disponibles = [];
        foreach ($Num as $n) {
            if ((int) $n->USADO === 1) {
                $asignados[] = $n;
            } else {
                $disponibles[] = $n->NUMERO;
            }
        }

        $chartAsignados = DB::connection('sqlsrv')->select("
            SELECT CAST ( FECHA AS DATE ) AS dia, sum ( ACCIONES ) AS total  FROM PRODUCCION.dbo.LOG_ACCIONES_RIFA 
            WHERE FECHA IS NOT NULL  GROUP BY CAST ( FECHA AS DATE )  ORDER BY dia
        ");
        
        $totalFacturado = DB::connection('sqlsrv')->selectOne("SELECT SUM(TOTAL_FACTURA) AS total FROM PRODUCCION.dbo.LOG_ACCIONES_RIFA");
        $montoFacturado = $totalFacturado ? (float) $totalFacturado->total : 0;

        $dias = [];
        $totales = [];
        foreach ($chartAsignados as $c) {
            $dias[] = 'Dia ' . date('d', strtotime($c->dia));
            $totales[] = (int) $c->total;
        }

        return view('pages.Promociones.RifaCar.metricas', compact(
            'stats', 'totalAcciones', 'totalLibres', 'totalUsados', 'porcentajeGlobal',
            'porcentajeUsadoGlobal', 'asignados', 'disponibles', 'dias', 'totales',
            'montoFacturado'
        ));
    }

    public function getChartRifa(Request $request)
    {
        $desde = $request->desde ?? date('Y-m-01');
        $hasta = $request->hasta ?? date('Y-m-t');

        $rows = DB::connection('sqlsrv')->select("
            SELECT CAST(FECHA_ASIGNACION AS DATE) AS dia, COUNT(*) AS total
            FROM PRODUCCION.dbo.NUMEROS_RIFA
            WHERE USADO = 1 AND FECHA_ASIGNACION IS NOT NULL
                AND CAST(FECHA_ASIGNACION AS DATE) BETWEEN ? AND ?
            GROUP BY CAST(FECHA_ASIGNACION AS DATE)
            ORDER BY dia
        ", [$desde, $hasta]);

        $dias = [];
        $totales = [];
        foreach ($rows as $r) {
            $dias[] = 'Dia ' . date('d', strtotime($r->dia));
            $totales[] = (int) $r->total;
        }

        return response()->json(['dias' => $dias, 'totales' => $totales]);
    }

    public function ExportRifa($tipo)
    {
        $usado = $tipo == 1 ? 1 : 0;

        $data = DB::connection('sqlsrv')->select("
            SELECT T0.NUMERO, T0.CLIENTE, T1.NOMBRE, T0.FACTURA, T0.FECHA_ASIGNACION
            FROM PRODUCCION.dbo.NUMEROS_RIFA T0
            LEFT JOIN Softland.umk.CLIENTE T1 ON T0.CLIENTE = T1.CLIENTE
            WHERE T0.USADO = ?
            ORDER BY T0.NUMERO
        ", [$usado]);

        $headers = ['Número', 'Cliente', 'Nombre', 'Factura', 'Fecha'];

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle($tipo == 1 ? 'Asignados' : 'Disponibles');

        $col = 0;
        foreach ($headers as $h) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, 1, $h);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2;
        foreach ($data as $item) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, str_pad($item->NUMERO, 5, '0', STR_PAD_LEFT));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item->CLIENTE ?? '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $item->NOMBRE ?? '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $item->FACTURA ?? '');
            $fecha = isset($item->FECHA_ASIGNACION) ? date('d-m-Y', strtotime($item->FECHA_ASIGNACION)) : '';
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $fecha);
            $row++;
        }

        foreach (range(0, 4) as $i) {
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . ($tipo == 1 ? 'asignados' : 'disponibles') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function qrCliente($cliente)
    {
        $info = DB::connection('sqlsrv')->selectOne("
            SELECT CLIENTE, NOMBRE FROM Softland.umk.CLIENTE WHERE CLIENTE = ?
        ", [$cliente]);

        $nombreCompleto = $info->NOMBRE ?? $cliente;
        $nombre = preg_replace('/\s*[-]?\s*(?:RUC[- ]?\s*\S+|C(?:E|É)DULA\s*\S+|\d{3}[-]\d{6}[-]\d{4}[A-Z]?).*$/i', '', $nombreCompleto);
        $nombre = trim($nombre, ' -');
        $nombre = trim($nombre);
        $url = 'https://carro.unimarksa.com/api/Perfil/' . $cliente;
        $qrSvg = \QrCode::size(220)->generate($url);
        $qrSvgBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return view('pages.Promociones.RifaCar.qr_card', compact('cliente', 'nombre', 'nombreCompleto') + ['qrUrl' => $qrSvgBase64]);
    }

    public function SendAcciones(Request $request, $cliente)
    {
        $acciones = DB::connection('sqlsrv')->select("
            SELECT T0.NUMERO, T0.FACTURA, T0.FECHA_ASIGNACION, T1.NOMBRE
            FROM PRODUCCION.dbo.NUMEROS_RIFA T0
            LEFT JOIN Softland.umk.CLIENTE T1 ON T0.CLIENTE = T1.CLIENTE
            WHERE T0.CLIENTE = ? AND T0.USADO = 1
            ORDER BY T0.FECHA_ASIGNACION DESC
        ", [$cliente]);

        $nombre = $acciones[0]->NOMBRE ?? 'N/D';

        $email = 'analista.guma@gmail.com';
        // if ($email) {
        //     Mail::send('pages.Promociones.RifaCar.emails.acciones_cliente', compact(
        //         'cliente', 'nombre', 'acciones'
        //     ), function($message) use ($email, $cliente) {
        //         $message->to($email)
        //                 ->subject('Acciones Rifa — Cliente ' . $cliente);
        //     });

        //     return response()->json(['status' => true, 'message' => 'Correo enviado a ' . $email]);
        // }

        return view('pages.Promociones.RifaCar.emails.acciones_cliente', compact(
            'cliente', 'nombre', 'acciones'
        ));
    }

    public function ReporteClientesRifa($cliente)
    {
        $data = $this->getReporteData($cliente);
        return view('pages.Promociones.RifaCar.reporte_clientes', $data);
    }

    public function ReporteClientesRifaPDF($cliente)
    {
        $data = $this->getReporteData($cliente);
        $pdf = PDF::loadView('pages.Promociones.RifaCar.reporte_clientes_pdf', $data);
        return $pdf->download('Reporte_' . $cliente . '.pdf');
    }

    private function getReporteData($cliente)
    {
        $rows = DB::connection('sqlsrv')->select("
            SELECT 
                T0.NUMERO, 
                T0.FACTURA, 
                T0.FECHA_ASIGNACION,
                V.FECHA AS FECHA_FACTURA, 
                V.TOTAL_FACTURA, 
                V.NOMBRE, 
                V.CLIENTE
            FROM PRODUCCION.dbo.NUMEROS_RIFA T0
            LEFT JOIN PRODUCCION.dbo.view_gnet_rifa_masterFactura V ON T0.FACTURA = V.FACTURA
            WHERE T0.USADO = 1 AND T0.CLIENTE = ?
            ORDER BY V.FECHA DESC, T0.NUMERO
        ", [$cliente]);

        $nombre = $rows[0]->NOMBRE ?? $cliente;

        $facturasMap = [];
        $totalComprado = 0;
        $totalAcciones = 0;

        foreach ($rows as $r) {
            $fac = $r->FACTURA;
            if (!isset($facturasMap[$fac])) {
                $facturasMap[$fac] = [
                    'FACTURA'       => $fac,
                    'FECHA'         => $r->FECHA_FACTURA,
                    'TOTAL_FACTURA' => (float) ($r->TOTAL_FACTURA ?? 0),
                    'ACCIONES'      => [],
                ];
                $totalComprado += (float) ($r->TOTAL_FACTURA ?? 0);
            }
            $facturasMap[$fac]['ACCIONES'][] = str_pad($r->NUMERO, 5, '0', STR_PAD_LEFT);
            $totalAcciones++;
        }

        $facturas = array_values($facturasMap);
        $totalFacturas = count($facturas);

        $fechaGeneracion = date('d/m/Y, h:i:s A');
        $admin = Auth::user()->name ?? 'Sistema';

        $qrSvg = \QrCode::size(130)->generate('https://carro.unimarksa.com/api/Perfil/' . $cliente);
        $qrSvgBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return compact(
            'cliente', 'nombre', 'facturas',
            'totalFacturas', 'totalAcciones', 'totalComprado',
            'fechaGeneracion', 'admin', 'qrSvg', 'qrSvgBase64'
        );
    }

    public function ImprimirAcciones(Request $request)
    {
        $Acciones = Facturas::ImprimirAcciones($request);
        $InfoFactura = Facturas::getInfoFactura($request->Factura);

        $UrlQR = QrCode::size(80)->generate('https://carro.unimarksa.com/api/Perfil/' . $InfoFactura->CLIENTE);
        
        //$Pdf = PDF::loadView('pages.Promociones.RifaCar.Imprimir', compact('Acciones', 'InfoFactura'));
        //return $Pdf->download('Acciones.pdf');
        return view('pages.Promociones.RifaCar.Voucher', compact('Acciones', 'InfoFactura', 'UrlQR'));
        
    }

    public function ImprimirAccionesV2(Request $request)
    {
        $Acciones = Facturas::ImprimirAcciones($request);
        $InfoFactura = Facturas::getInfoFactura($request->Factura);

        $UrlQR = QrCode::size(80)->generate('https://carro.unimarksa.com/api/Perfil/' . $InfoFactura->CLIENTE);
        
        return view('pages.Promociones.RifaCar.voucher-v2', compact('Acciones', 'InfoFactura', 'UrlQR'));
    }

}