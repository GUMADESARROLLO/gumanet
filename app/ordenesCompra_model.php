<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ordenesCompra_model extends Model
{
    public static function getOrdenesCompra($f1, $f2) {        
        $sql_server = new \sql_server();        
        $request = Request();
        $sql_exec = '';
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        $i=0;
        $data = array();
        $f1 = $f1." 00 : 00 : 00 : 000";
        $f2 = $f2." 23 : 59 : 59 : 998";

        switch ($company_user) {
            case '1':
                $sql_exec = "EXEC gnet_ordenes_compra '".$f1."','".$f2."'";

                break;
            case '2':
                $sql_exec = "";

                break;
            case '3':
                return false;
                break;
            case '4':
                $sql_exec = "";
                break; 
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

    
        $query = $sql_server->fetchArray( $sql_exec , SQLSRV_FETCH_ASSOC);
        
        

        foreach ($query as $key) {
            $data[$i]["DETALLE"]        = '<a id="exp_more" class="exp_more" href="#!"><i class="material-icons expan_more">expand_more</i></a>';
            $data[$i]['ORD_COMPRA']     = $key['orden_compra'];
            $data[$i]['PROVEEDOR']      = $key['proveedor'];
            $data[$i]['NOMBRE']         = $key['nombre'];
            $data[$i]['FECHA']          = $key['fecha02'];
            $data[$i]['ESTADO']         = $key['estado'];
            $i++;
        }
        $sql_server->close();        

        return $data;
    }

    public static function getDetalleOrdenCompra($ordCompra){
        $sql_server = new \sql_server();
        $dta = array();
        $i=0;
        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

        switch ($company_user) {
            case '1':
                $sql_exec = 'SELECT * FROM GNET_ORDEN_COMPRA_LINEAS WHERE ORDEN_COMPRA = '."'".$ordCompra."'";
                break;
            case '2':
                return false;
                break;
            case '3':
                return false;
                break;
            case '4':
                return false;
                break;
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }
        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

        if( count($query)>0 ) {
            foreach ($query as $key) {
                $dta[$i]['ARTICULO']                = $key['ARTICULO'];
                $dta[$i]['DESCRIPCION']             = $key['DESCRIPCION'];
                $dta[$i]['LABORATORIO']             = $key['LABORATORIO'];
                $dta[$i]['UNIDAD_ALMACEN']          = $key['UNIDAD_ALMACEN'];
                $dta[$i]['PROVEEDOR']               = $key['PROVEEDOR'];
                $dta[$i]['NOMBRE']                  = $key['NOMBRE'];
                $dta[$i]['CANTIDAD_ORDENADA']       = number_format($key['CANTIDAD_ORDENADA'], 2);
                $dta[$i]['CANTIDAD_RECIBIDA']       = number_format($key['CANTIDAD_RECIBIDA'], 2);
                $dta[$i]['CANT_PEDIDA']             = number_format($key['CANT_PEDIDA'], 2);
                $dta[$i]['CANT_RESTANTE']           = number_format($key['CANT_RESTANTE'], 2);
                $dta[$i]['ESTADO']                  = trim($key['ESTADO']);
                $dta[$i]['Fecha']                   = trim($key['Fecha_']);
                $dta[$i]['Dias_acumulados']         = trim($key['Dias_acumulados']);
                $dta[$i]['FECHA_COTIZACION']        = trim($key['FECHA_COTIZACION_']);
                $dta[$i]['FECHA_OFRECIDA']          = trim($key['FECHA_OFRECIDA_']);
                $dta[$i]['FECHA_EMISION']           = trim($key['FECHA_EMISION_']);
                $dta[$i]['FECHA_REQ_EMBARQUE']      = trim($key['FECHA_REQ_EMBARQUE_']);
                $dta[$i]['FECHA_REQUERIDA']         = trim($key['FECHA_REQUERIDA_']);
                $dta[$i]['Dias_acumulado_despacho'] = trim($key['Dias_acumulado_despacho']);
                $dta[$i]['REF_ORDEN_COMPRA']        = trim($key['REF_ORDEN_COMPRA']);
                $dta[$i]['REF_FACTURA']             = trim($key['REF_FACTURA']);
                $dta[$i]['TIPO_MERCADO']            = trim($key['TIPO_MERCADO']);
                $dta[$i]['LEYENDA_MINSA']           = trim($key['LEYENDA_MINSA']);
                $dta[$i]['LOTE']                    = trim($key['LOTE']);
                $i++;
            }
            return $dta;
        }

        $sql_server->close();
        return false;
    }
}

