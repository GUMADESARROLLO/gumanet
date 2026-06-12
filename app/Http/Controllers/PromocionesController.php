<?php

namespace App\Http\Controllers;

use App\Facturas;
use App\Vendedor;
use Illuminate\Http\Request;
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

        $nombre = $info->NOMBRE ?? $cliente;
        $url = 'https://carro.unimarksa.com/api/Perfil/' . $cliente;
        $qr = \QrCode::size(220)->generate($url);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qr);

        return view('pages.Promociones.RifaCar.qr_card', compact('cliente', 'nombre') + ['qrUrl' => $qrBase64]);
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

    public function ImprimirAcciones(Request $request)
    {
        $Acciones = Facturas::ImprimirAcciones($request);
        $InfoFactura = Facturas::getInfoFactura($request->Factura);

        $UrlQR = QrCode::size(150)->generate('https://carro.unimarksa.com/api/Perfil/' . $InfoFactura->CLIENTE);
        
        //$Pdf = PDF::loadView('pages.Promociones.RifaCar.Imprimir', compact('Acciones', 'InfoFactura'));
        //return $Pdf->download('Acciones.pdf');
        return view('pages.Promociones.RifaCar.Voucher', compact('Acciones', 'InfoFactura', 'UrlQR'));
        
    }

}