<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
}
