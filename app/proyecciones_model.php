<?php

namespace App;
use App\User;
use App\Company;

use Illuminate\Database\Eloquent\Model;

class proyecciones_model extends Model {
    
    public static function getDataProyecciones($ud) {

    	$ud_ = ($ud=='m_p')?'INST.PRIVADO':'FARMACIA';

    	$sql_server = new \sql_server();
    	$sql_exec = "SELECT * FROM DESARROLLO.dbo.ESTADISTICA_CA WHERE NIVEL_PRECIO='".$ud_."' ";

    	$query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i = 0;
        $json = array();

        foreach($query as $key) {
            
            $json[$i]['ARTICULO']					= $key['ARTICULO'];
            $json[$i]['DESCRIPCION']				= $key['DESCRIPCION'];
            $json[$i]['CLASE_ABC']					= $key['CLASE_ABC'];
            $json[$i]['ORDEN_MINIMA']				= $key['ORDEN_MINIMA'];
            $json[$i]['FACTOR_EMPAQUE']				= number_format($key['FACTOR_EMPAQUE'],4,'.','');
            $json[$i]['OPC'] 						= '<a href="#!" onclick="detailsProyeccion('."'".$key['ARTICULO']."'".','."'".$ud."'".')" class="active-page-details"><i class="material-icons">content_paste</i></a>';

            $i++;
        }

        return $json;
        $sql_server->close();

    }

    public static function getDataProyeccionArticulo($ud, $articulo) {
    	$ud_ = ($ud=='m_p')?'INST.PRIVADO':'FARMACIA';

    	$sql_server = new \sql_server();
    	$sql_exec = "SELECT * FROM DESARROLLO.dbo.ESTADISTICA_CA WHERE NIVEL_PRECIO='".$ud_."' AND ARTICULO='".$articulo."' ";

    	$query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i = 0;
        $json = array();

        foreach($query as $key) {            
            $json[$i]['LABORATORIO']                  = $key['LABORATORIO'];
            $json[$i]['COSTO_PROM_LOC']               = number_format($key['COSTO_PROM_LOC'],4,'.','');
            $json[$i]['ARTICULO']                     = $key['ARTICULO'];
            $json[$i]['DESCRIPCION']                  = $key['DESCRIPCION'];
            $json[$i]['CLASE_ABC']                    = $key['CLASE_ABC'];
            $json[$i]['FACTOR_EMPAQUE']               = number_format($key['FACTOR_EMPAQUE'],4,'.','');
            $json[$i]['ORDEN_MINIMA']                 = $key['ORDEN_MINIMA'];
            $json[$i]['BODEGA']                       = $key['BODEGA'];
            $json[$i]['CANT_DISPONIBLE']              = number_format($key['CANT_DISPONIBLE'],4,'.','');
            $json[$i]['CANT_PEDIDA']                  = number_format($key['CANT_PEDIDA'],4,'.','');
            $json[$i]['CANT_TRANSITO']                = number_format($key['CANT_TRANSITO'],4,'.','');
            $json[$i]['CANT_RESERVADA']               = number_format($key['CANT_RESERVADA'],4,'.','');
            $json[$i]['PRECIO']                       = $key['PRECIO'];
            $json[$i]['NIVEL_PRECIO']                 = $key['NIVEL_PRECIO'];
            $json[$i]['CODIGO_Privado']               = $key['CODIGO(Privado)'];
            $json[$i]['DESCRIPCION_Privado']          = $key['DESCRIPCION(Privado)'];
            $json[$i]['CLASE_ABC_Privado']            = $key['CLASE_ABC(Privado)'];
            $json[$i]['ORDEN_MINIMA_Privado']         = $key['ORDEN_MINIMA(Privado)'];
            $json[$i]['FACTOR_EMPAQUE_Privado']       = number_format($key['FACTOR_EMPAQUE(Privado)'],4,'.','');
            $json[$i]['BODEGA_privado']               = $key['BODEGA (privado)'];
            $json[$i]['CANT_DISPONIBLE_Privado']      = number_format($key['CANT_DISPONIBLE (Privado)'],4,'.','');
            $json[$i]['CANT_PEDIDA_Privado']          = number_format($key['CANT_PEDIDA (Privado)'],4,'.','');
            $json[$i]['CANT_TRANSITO_Privado']        = number_format($key['CANT_TRANSITO (Privado)'],4,'.','');
            $json[$i]['CANT_RESERVADA_Privado']       = number_format($key['CANT_RESERVADA(Privado)'],4,'.','');
            $json[$i]['COSTO_PROM_LOC_Privado']       = number_format($key['COSTO_PROM_LOC(Privado)'],4,'.','');
            $json[$i]['Precio_Privado']               = number_format($key['Precio(Privado)'],4,'.','');
            $json[$i]['NIVEL_PRECIO_Privado']         = $key['NIVEL_PRECIO(Privado)'];
            $i++;
        }

        return $json;
        $sql_server->close();
    }
}
