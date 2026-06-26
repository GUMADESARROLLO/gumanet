<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesModel extends Model
{
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "PRODUCCION.dbo.GMV_Clientes";
    public static function getClientes(Request $request) {        
         
        $Clientes = ClientesModel::get()->toArray();        
        
        foreach ($Clientes as $key) {
            $data[] = [
                'CLIENTE'    => $key['CLIENTE'],
                'NOMBRE'     => $key['NOMBRE'],
                'DIRECCION'  => $key['DIRECCION'] ?? '',
                'RUC'        => $key['RUC'] ?? '',
                'VENDEDOR'   => $key['VENDEDOR'] ?? '',
                'FECHA'      => $key['FECHA'] ?? date('d/m/Y'),
                'ACTIVO'     => $key['ACTIVO'] ?? 'SI',
                'MOROSO'     => $key['MOROSO'] ?? 'NO',
                'LIMITE'     => $key['LIMITE'] ?? '0.00',
                'SALDO'      => $key['SALDO'] ?? '0.00',
                'DISPONIBLE' => $key['DISPONIBLE'] ?? '0.00',
            ];
        }
        return $data;
    }

    public static function getFactura($cliente, $f1, $f2) {
        $data = array();

        $D1 = date('Y-m-d 00:00:00', strtotime($f1));
        $D2 = date('Y-m-d 23:59:59', strtotime($f2));

        $Facturas = [];

        $rows = DB::connection('sqlsrv')->select("
            SELECT
                v.Fecha_de_factura AS FECHA,
                v.FACTURA,
                SUM(v.VENTA) AS MONTO,
                v.VENDEDOR,
                v.Nombre_Vendedor AS NOMBRE_VENDEDOR
            FROM
                Softland.dbo.ANA_VentasTotales_MOD_Contabilidad_UMK v
            WHERE
                v.CLIENTE_CODIGO = ?
                AND v.Fecha_de_factura BETWEEN ? AND ?
            GROUP BY
                v.FACTURA, v.Fecha_de_factura, v.VENDEDOR, v.Nombre_Vendedor
            ORDER BY
                v.Fecha_de_factura DESC
        ", [$cliente, $D1, $D2]);

        $Facturas = collect($rows)->pluck('FACTURA')->toArray();   
        
        // TODO: CASO DE ESTUDIO FACTURA NUMERO 00293080
        $InfoFactura = DB::connection('sqlsrv')
            ->table('PRODUCCION.dbo.gnet_cxc')
            //->select('MONTO_CORD_CRED', 'FACTURA', 'MONTO', 'VENDEDOR', 'NOMBRE_VENDEDOR')
            ->whereIn('FACTURA', $Facturas)
            ->orderBy('FECHA_DEBITO', 'DESC')
            ->get()
            ->toArray();



        foreach ($rows as $key) {            
            $Posicion = array_search($key->FACTURA, array_column($InfoFactura, 'FACTURA'));
            $MONTO_CORD_CRED = $Posicion !== false ? $InfoFactura[$Posicion]->MONTO_CORD_CRED : 0;
            $SALDO = $Posicion !== false ? $InfoFactura[$Posicion]->SALDO : 0;

            $data[] = [
                'FECHA'          => date('d/m/Y', strtotime($key->FECHA)) ?? '',
                'FACTURA'        => $key->FACTURA,
                'MONTO'          => number_format($key->MONTO, 2),
                'VENDEDOR'       => $key->VENDEDOR ?? '',
                'NOMBRE_VENDEDOR'=> $key->NOMBRE_VENDEDOR ?? '',
                'SALDO'          => number_format($SALDO, 2),
                'MONTO_CORD_CRED' => number_format($MONTO_CORD_CRED, 2),
            ];
        }

        return $data;
    }

    public static function getFacturaDetalle($FACTURA) {
        $data = array();

        $rows = DB::connection('sqlsrv')->select(" SELECT * FROM Softland.dbo.ANA_VentasTotales_MOD_Contabilidad_UMK WHERE FACTURA = ? ", [$FACTURA]);

        foreach ($rows as $key) {
            $data[] = [
                'ARTICULO'          => $key->ARTICULO ?? '',
                'DESCRIPCION'       => mb_strtoupper($key->DESCRIPCION ?? ''),
                'CANTIDAD_FACT'     => number_format($key->CANTIDAD_FACT, 2),
                'PRECIO_UNITARIO'   => $key->PRECIO_UNITARIO ?? '',
                'VENTA'             => $key->VENTA ?? '',
            ];
        }

        return $data;
    }

    public static function getFacturaPagos($FACTURA) {
        $data = array();

        $rows = DB::connection('sqlsrv')->select(" SELECT * FROM PRODUCCION.dbo.gnet_cxc WHERE FACTURA = ? ORDER BY FECHA_DEBITO DESC", [$FACTURA]);

        foreach ($rows as $key) {
            $data[] = [
                'FECHA'      => date('d/m/Y', strtotime($key->FECHA_DEBITO)) ?? '',
                'RECIBO'     => $key->COD_RECIBO ?? '',
                'MONTO'      => $key->MONTO_CORD_CRED ?? 0,
                'FORMA_PAGO' => $key->TIPO_CREDITO ?? '-',
            ];
        }

        return $data;
    }
}
