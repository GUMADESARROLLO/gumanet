<?php

namespace App;
use App\User;
use App\Company;

Use App\Gn_couta_x_producto;
Use App\Umk_recuperacion;
Use App\Metacuota_gumanet;
use App\clientes_x_rutas;
use App\proyectos_model;
use App\rutas;
use App\proyectosDetalle_model;
use DB;
use DateTime;

use Illuminate\Database\Eloquent\Model;

class dashboard_model extends Model {
    public static function getDataGraficas($mes, $anio, $xbolsones) {
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

        $array_merge = array();
        $date = $anio.'-'.$mes.'-01';
        $dtaBodega[] = array(
            'tipo' => 'dtaBodega',
            'data' => dashboard_model::getValBodegas($date, $company_user)
        );
        $dtaTop10Cl[] = array(
            'tipo' => 'dtaCliente',
            'data' => dashboard_model::getTop10Clientes($mes, $anio, $company_user, $xbolsones,0)
        );
        $dtaTop10Pr[] = array(
            'tipo' => 'dtaProductos',
            'data' => dashboard_model::getTop10Productos($mes, $anio, $company_user, $xbolsones,0)
        );
        $dtaVtasMes[] = array(
            'tipo' => 'dtaVentasMes',
            'data' => dashboard_model::getVentasMes($mes, $anio, $company_user, $xbolsones)
        );
        $dtaVtnDiarias[] = array(
            'tipo' => 'dtaVentasDiarias',
            'data' => dashboard_model::get_Ventas_diarias($mes, $anio, $company_user, $xbolsones,0)
        );
        $dtaRecupera[] = array(
            'tipo' => 'dtaRecupera',
            'data' => dashboard_model::getRecuperaMes($mes, $anio, $company_user)
        );

        $dtaCompMesesVentas[] = array(
            'tipo' => 'dtaCompMesesVentas',
            'data' => dashboard_model::getComparacionMesVentas($mes, $anio, $company_user, $xbolsones)
        );

        $dtaCompMesesItems[] = array(
            'tipo' => 'dtaCompMesesItems',
            'data' => dashboard_model::getComparacionMesItems($mes, $anio, $company_user) 
        );

        $dtaVentasXCateg[] = array(
            'tipo' => 'dtaVentasXCateg',
            'data' => dashboard_model::getVentasXCategorias($mes, $anio, $company_user, $xbolsones)
        );

        $dtaClientes[] = array(
            'tipo' => 'dtaClientes',
            'data' => dashboard_model::clientesMeta($mes, $anio, $company_user)
        );

        $dtaProyectos[] = array(
            'tipo' => 'dtaProyectos',
            'data' => dashboard_model::dataProyectos($mes, $anio, $company_user)
        );

        $array_merge = array_merge($dtaBodega, $dtaTop10Cl, $dtaTop10Pr, $dtaVtasMes, $dtaRecupera, $dtaCompMesesVentas, $dtaCompMesesItems, $dtaVentasXCateg, $dtaClientes, $dtaProyectos,$dtaVtnDiarias);
        //$array_merge = array_merge($dtaTop10Pr);
        return $array_merge;
        $sql_server->close();
    }
    public static function get_Ventas_diarias($mes, $anio, $company_user, $xbolsones ,$Segmento) 
    {

        $sql_server = new \sql_server();
        $sql_exec = '';
        $tem_ = 0;
        $json = array();
        $i = 0;

        $crecimiento_diario = 0;

        $fecha = new DateTime($anio.'-'.$mes.'-01');
        $info_metas = Metacuota_gumanet::where(['Fecha' => $fecha,'IdCompany'=> $company_user])->get();

        foreach ($info_metas as $iMetas) {
            $dias_habiles[0] =  $iMetas->dias_facturados;
            $dias_habiles[1] =  $iMetas->IdPeriodo;
        }

        switch ($company_user) {
            case '1':
                $sql_exec = " EXEC gnet_vnts_diaria_generico ".$mes.", ".$anio.", 'VtasTotal_UMK', ".$Segmento." ";
                break;
            case '2':
                $sql_exec = " EXEC gnet_vnts_diaria_gp ".$mes.", ".$anio." ";
                break;
            case '3':
                $sql_exec = "";
                break;   
            case '4':
                $sql_exec = " EXEC gnet_vnts_diaria_inn ".$mes.", ".$anio." ";
                break;        
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        if( count($query)>0 ) {            
            if (count($info_metas) > 0) {
                //EXTRAER META DE VENTA DEL MES
                $Meta_Mes = Gn_couta_x_producto::where(['IdPeriodo'=> $dias_habiles[1]])->sum('val');
                //CALCULO DE CRECIMIENTO OPTIMO DIARIO CON FORME A METAS
                $crecimiento_diario = $Meta_Mes / $dias_habiles[0];
            } else {
                $crecimiento_diario = 0;
            }
            

            foreach ($query as $key) {

                $json[$i]['name']       = $key['dey'];
                $json[$i]['articulo']   = $key['dey'];

                if ( $company_user==4 ) {
                    $tem_ = ($xbolsones) ? intval($key['Cantidad']): (float) number_format(floatval($key['MontoVenta']),2,".","");
                    $UND_ = 0;
                }else {
                    $tem_ = (float) number_format(floatval($key['MontoVenta']),2,".","");
                    $UND_ = intval($key['Cantidad']);                    
                }

                $json[$i]['data']   = $tem_;
                $json[$i]['dtUnd']  = $UND_;
                $json[$i]['dtAVG']  = (float) number_format($crecimiento_diario,2,".","");

                $i++;
            }
        }

        return $json;
        $sql_server->close();
    }

    public static function dataProyectos($mes, $anio, $company_user) {
        $sql_server = new \sql_server();
        $sql_exec = '';
        $segmentos = array();
        $array = array();
        $line = '';
        $meta = 0;
        $fecha = new DateTime($anio.'-'.$mes.'-01');
        $idPeriodo = Metacuota_gumanet::where(['Fecha' => $fecha,'IdCompany'=> $company_user])->pluck('IdPeriodo');
        $i=0;

        $segmentos[0] = array(
            'name' => 'Instituciones',
            'line' => "'F02'",
            'ruta' => ['F02']
        );

        $segmentos[1] = array(
            'name' => 'Mayoristas',
            'line' => "'F04'",
            'ruta' => ['F04']
        );

        $segmentos[2] = array(
            'name' => 'Farmacias',
            'line' => "'F03','F05','F06','F07','F08','F09','F10','F11','F13','F14','F15','F20'",
            'ruta' => ['F03','F05','F06','F07','F08','F09','F10','F11','F13','F14','F15','F20']
        );

        switch ($company_user) {
            case '1':
                $proyectos = proyectos_model::orderBy('priori', 'asc')->get();

                foreach ($segmentos as $key) {
                    
                    /*$rutas = proyectosDetalle_model::select('rutas.vendedor as ruta')
                            ->where('proyectos_rutas.proyecto_id', $key['id'])
                            ->where('rutas.estado', 1)
                            ->join('rutas', 'proyectos_rutas.ruta_id', '=', 'rutas.id')
                            ->get()
                            ->toArray();

                    foreach ($rutas as $r) {
                        ($r === end($rutas))?$line .= ''."'".$r['ruta']."'".'':$line .= ''."'".$r['ruta']."'".',';
                    }*/

                    $sql_exec = "SELECT
                                SUM(venta) as total
                                FROM
                                    Softland.dbo.VtasTotal_UMK (nolock)
                                WHERE
                                    [Año] = ".$anio." AND nmes = ".$mes." AND Ruta IN(".$key['line'].")
                                AND [P. Unitario] > 0
                                GROUP BY
                                    [P. Unitario],
                                    Cantidad";

                    $rutas =     $key['ruta'];
                    
                    $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

                    if ( count($idPeriodo)>0 ) {
                        $meta =  Gn_couta_x_producto::where('IdPeriodo', $idPeriodo)
                                    ->where(function ($query) use ($rutas) {                                     
                                        $query->whereIn('CodVendedor', $rutas);
                                    })->sum('val');
                    }

                    $array[$i]['proyecto'] = $key['name'];
                    $array[$i]['real'] = array_sum(array_column($query, 'total'));
                    $array[$i]['meta'] = $meta;
                    $line = '';
                    $i++;
                }

                return $array;
                break;
            case '2':
                break;
            case '3':
                break;    
            case '4':
                break;      
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }
        return $array;
        $sql_server->close();
    }
    
    public static function getTotalRutaXVentas($mes, $anio) {
        $sql_server = new \sql_server();
        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

        switch ($company_user) {
            case '1':
                $sql_exec = "EXEC Ventas_Rutas ".$mes.", ".$anio;
                break;
            case '2':
                $sql_exec = "EXEC Ventas_Rutas_GF ".$mes.", ".$anio;
                break;
            case '3':
                $sql_exec = "";
                break;  
            case '4':
                $sql_exec = "EXEC Ventas_Rutas_INV ".$mes.", ".$anio;
                break;          
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i = 0;
        $json = array();

        
        foreach ($query as $fila) {
            $json[$i]["RUTA"]       = $fila["Ruta"];
            $json[$i]["MONTO"]    = number_format($fila["Monto"],2);
            $i++;
        }
        return $json;
        $sql_server->close();
    }    

    public static function getTotalUnidadesXRutaXVentas($mes, $anio){
        $sql_server = new \sql_server();
        $fecha = new DateTime($anio.'-'.$mes.'-01');
        $sql_exec = '';
        $request = Request();
        $idPeriodo = '';
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        $idPeriodo = Metacuota_gumanet::where(['Fecha' => $fecha,'IdCompany'=> $company_user])->pluck('IdPeriodo');

        switch ($company_user) {
            case '1':
               
                $sql_exec = "EXEC Ventas_Rutas ".$mes.", ".$anio;
                
                break;
            case '2':
                $sql_exec = "EXEC Ventas_Rutas_GF ".$mes.", ".$anio;
                break;
            case '3':
                $sql_exec = "";
                break;     
                 case '4':
                $sql_exec = "EXEC Ventas_Rutas_INV ".$mes.", ".$anio;
                break;       
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }


        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i = 0;
        $json = array();        

        //if(count($idPeriodo) != ""){
        foreach ($query as $fila) {

            $VENDEDOR = dashboard_model::buscarVendedorXRuta($fila["Ruta"], $company_user);
            $json[$i]["VENDE"] = $VENDEDOR;

            if ( count($idPeriodo)>0 ) {
                $meta =  Gn_couta_x_producto::where(['IdPeriodo'=> $idPeriodo, 'CodVendedor' => $fila["Ruta"]])->sum('Meta');
                $monto = Gn_couta_x_producto::where(['IdPeriodo'=> $idPeriodo, 'CodVendedor' => $fila["Ruta"]])->sum('val');
            }else {
                $meta = $monto = 0;
            }            

            $json[$i]["METAU"] = number_format($meta,2);
            $json[$i]["REALU"] = number_format($fila["Cantidad"],2);
            
            $json[$i]["DIFU"] = ($meta==0) ? "100.00%" : number_format(((floatval($fila["Cantidad"])/floatval($meta))*100),2)."%";            
            $json[$i]["METAE"] = "C$ ".number_format($monto,2);
            $json[$i]["REALE"] = "C$ ".number_format($fila["Monto"],2);
            $json[$i]["DIFE"] = ($meta==0) ? "100.00%" : number_format(((floatval($fila["Monto"])/floatval($monto))*100),2)."%";
            $json[$i]["RUTA"] = '<a href="#!" id="rutaDetVenta" onclick="getDetalleVenta('.$mes.','.$anio.','."'".$json[$i]["METAU"]."'".','."'".$json[$i]["REALU"]."'".','."'".$json[$i]["METAE"]."'".','."'".$json[$i]["REALE"]."'".','."'".$fila["Ruta"]."'".', '."'".$VENDEDOR."'".')" >'.$fila["Ruta"].'</a>';
            $i++;
            }

        //}
        return $json;

            $sql_server->close();
            $sql_exec = 'SELECT ';
    }

    public static function buscarVendedorXRuta($ruta, $compañia){
        $sql_server = new \sql_server();
        $vendedor = array(); 


        switch ($compañia) {
            case '1':
                $sql_exec =  "VENDEDOR_UMK ".$ruta;
                $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
                foreach ($query as $fila){
                $vendedor = $fila['NOMBRE'];
                }
                break;

            case '2':
                $sql_exec =  "VENDEDOR_GP ".$ruta;
                $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
                foreach ($query as $fila){
                $vendedor = $fila['NOMBRE'];
                }
                break;
            case '3':
                # code...
                break;

            case '4':
                $sql_exec =  "VENDEDOR_INV ".$ruta;
                $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
                foreach ($query as $fila){
                $vendedor = $fila['NOMBRE'];
                }
               
                break;
            
            default:
                # code...
                break;
        }

           
           return $vendedor;
    }

    public static function getDetalleVentasXRuta($mes, $anio, $ruta) {

        $sql_server = new \sql_server();
        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        $fecha = new DateTime($anio.'-'.$mes.'-01');
        $idPeriodo = Metacuota_gumanet::where(['Fecha' => $fecha,'IdCompany'=> $company_user])->pluck('IdPeriodo');

        switch ($company_user) {
            case '1':
                $sql_exec = "EXEC umk_VentaArticulo_Vendedor ".$mes.", ".$anio.", '".$ruta."'";
                
                break;
            case '2':
                $sql_exec = "EXEC Gp_VentaArticulo_Vendedor ".$mes.", ".$anio.", '".$ruta."'";
                break;
            case '3':
                $sql_exec = "";
                break;   
            case '4':
                $sql_exec = "EXEC Inv_VentaArticulo_Vendedor ".$mes.", ".$anio.", '".$ruta."'";
                break;         
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i      = 0;
        $j      = 0;
        $label = '';
        $codProdVendido = array();
        $json = array();

         foreach ($query as $fila) {
            $meta_u = $meta_v = 0;

            if( count($idPeriodo)>0 ) {
                if (Gn_couta_x_producto::where(['CodVendedor' => $ruta, 'IdPeriodo'=> $idPeriodo, 'CodProducto' => $fila["ARTICULO"]])->first()) {
                    $meta_u =  Gn_couta_x_producto::where(['CodVendedor' => $ruta, 'IdPeriodo'=> $idPeriodo, 'CodProducto' => $fila["ARTICULO"]])->sum('Meta');

                    $meta_v = Gn_couta_x_producto::where(['CodVendedor' => $ruta, 'IdPeriodo'=> $idPeriodo, 'CodProducto' => $fila["ARTICULO"]])->sum('val');
                    $label = '';
                }
                else {
                    $meta = number_format(0, 2);
                    $label = "<p class='text-danger'> (No definido en meta)</p>";
                }
            }

            $json[$i]["ARTICULO"]       = $fila["ARTICULO"];
            $json[$i]["DESCRIPCION"]    = $fila["DESCRIPCION"].$label;
            $json[$i]["METAU"]          = number_format($meta_u, 2);
            $json[$i]["REALU"]          = number_format($fila["CANTIDAD"], 2);
            $json[$i]["DIFU"]           = ($meta_u==0) ? "0.00%" : number_format(((floatval($fila["CANTIDAD"])/floatval($meta_u))*100),2)."%";
            
            $json[$i]["METAE"]          = number_format($meta_v, 2);
            $json[$i]["REALE"]          = number_format($fila["MONTO"], 2);
            $json[$i]["DIFE"]           = ($meta_v==0) ? "0.00%" : number_format(((floatval($fila["MONTO"])/floatval($meta_v))*100),2)."%";
            $i++;
        }

        $sql_server->close();
        return $json;
    }  

    
    public static function get_Vta_Ruta_dia($dia,$mes, $anio, $ruta) {

        $sql_server = new \sql_server();
        $sql_exec = '';
        $i =0;
        $request = Request();
        $json = array();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        
        switch ($company_user) {
            case '1':
                $sql_exec = "EXEC gnet_vnt_diaria_ruta_umk ".$dia.",".$mes.", ".$anio.", '".$ruta."'";
                
                break;
            case '2':                
                $sql_exec = "EXEC gnet_vnt_diaria_ruta_gp ".$dia.",".$mes.", ".$anio.", '".$ruta."'";
                break;
            case '3':
                $sql_exec = "";
                break;   
            case '4':
                $sql_exec = "EXEC gnet_vnt_diaria_ruta_inn ".$dia.",".$mes.", ".$anio.", '".$ruta."'";
                break;         
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
        
        foreach ($query as $fila) {
            $json[$i]["DETALLE"]    = '<a id="exp_more" class="exp_more" href="#!"><i class="material-icons expan_more">expand_more</i></a>';
            $json[$i]["Factura"]    = $fila["FACTURA"];
            $json[$i]["Dia"]        = $fila["Dia"]->format('F j, Y');;
            $json[$i]["CODE"]       = $fila["CodCliente"];
            $json[$i]["NOMBRE"]     = $fila["NombreCliente"];
            $json[$i]["Total"]      = "C$ " .number_format($fila["Total"], 2);
            $i++;
        }

        $sql_server->close();
        return $json;
    }    
    public static function get_Vta_all_items($dia,$mes, $anio,$Segmento) {

        $sql_server = new \sql_server();
        $sql_exec = '';
        $i =0;
        $request = Request();
        $json = array();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

        $RutaSegmento = "";

        $Sql_Dia = ($dia==0) ? '' : ' AND DAY(dia) = '.$dia.' '  ;
        switch ($company_user) {
            case '1':

                if ($Segmento==0) {
                    //TODAS LOS SEGMENTOS
                    $qSegmento =" Ruta NOT IN ('F01','F12') ";

                } else {
                    if ($Segmento==1) {
                        //TODAS LAS RUTAS DEL SEGMENTO FARMACIA
                        $qSegmento =" Ruta NOT IN ('F04','F02','F01','F12') ";
                    } else {
                        if ($Segmento==2) {
                           //TODAS LAS RUTAS DEL SEGMENTO MAYORISTA
                            $qSegmento =" Ruta IN ('F04') ";
                        } else {
                            if ($Segmento==3) {
                               //TODAS LAS RUTAS DEL SEGMENTO INSTITUCION
                                $qSegmento =" Ruta IN ('F02') ";
                            }
                            
                        }
                        
                    }
                }
                $sql_exec ="SELECT 
                                T1.Articulo,
                                T1.Descripcion,
                                count(T1.articulo) As NºVentaMes,
                                (SELECT T3.CANT_DISPONIBLE FROM iweb_bodegas T3  WHERE T3.ARTICULO = T1.ARTICULO AND T3.BODEGA='002') AS EXISTENCIA,
                                isnull(sum(T1.cantidad),0) Cantidad,
                                isnull(sum(T1.venta),0) MontoVenta,
                                AVG (T1.[P. Unitario]) as AVG_,         
                                T1.[Costo Unitario] AS COSTO_PROM,
                                isnull((SELECT SUM(T2.cantidad) FROM  Softland.dbo.VtasTotal_UMK T2 WHERE (T2.[P. Unitario] = 0) and (".$mes." = T2.nMes) AND (".$anio." = T2.[Año]) AND  ".$qSegmento."  $Sql_Dia  AND ARTICULO = T1.ARTICULO  ), 0) AS Cantida_boni
                    
                                from Softland.dbo.VtasTotal_UMK T1 Where ".$mes." = T1.nMes and $anio = T1.[Año] and T1.[P. Unitario] > 0
                                AND  T1.".$qSegmento." $Sql_Dia
                                group by T1.Articulo,T1.Descripcion,T1.[Costo Unitario]
                                order by MontoVenta desc;";

                    
                
                break;
            case '2':
                $sql_exec ="SELECT 
                            T1.Articulo,
                            T1.Descripcion,
                            count(T1.articulo) As NºVentaMes,
                            (SELECT T3.CANT_DISPONIBLE FROM GP_iweb_bodegas T3  WHERE T3.ARTICULO = T1.ARTICULO AND T3.BODEGA='001') AS EXISTENCIA,
                            isnull(sum(T1.cantidad),0) Cantidad,
                            isnull(sum(T1.venta),0) MontoVenta,
                            AVG (T1.[P. Unitario]) as AVG_,         
                            T1.[Costo Unitario] AS COSTO_PROM,
                            isnull((SELECT SUM(T2.cantidad) FROM  Softland.dbo.GP_VtasTotal_UMK T2 WHERE (T2.[P. Unitario] = 0) and (".$mes." = T2.nMes) AND (".$anio." = T2.[Año]) $Sql_Dia AND ARTICULO = T1.ARTICULO  ), 0) AS Cantida_boni
                
                            from Softland.dbo.GP_VtasTotal_UMK T1 WHERE ".$mes." = T1.nMes and $anio = T1.[Año] and T1.[P. Unitario] > 0
                            GROUP BY T1.Articulo,T1.Descripcion,T1.[Costo Unitario]
                            ORDER BY MontoVenta desc;"; 
                break;
            case '3':
                //$sql_exec = "";
                break;   
            case '4':
                $sql_exec ="SELECT 
                            T1.Articulo,
                            T1.Descripcion,
                            count(T1.articulo) As NºVentaMes,
                            (SELECT T3.CANT_DISPONIBLE FROM INN_iweb_bodegas T3  WHERE T3.ARTICULO = T1.ARTICULO AND T3.BODEGA='007') AS EXISTENCIA,
                            isnull(sum(T1.cantidad),0) Cantidad,
                            isnull(sum(T1.venta),0) MontoVenta,
                            AVG (T1.[P. Unitario]) as AVG_,         
                            T1.[Costo Unitario] AS COSTO_PROM,
                            isnull((SELECT SUM(T2.cantidad) FROM  Softland.dbo.INV_VtasTotal_UMK_Temporal T2 WHERE (T2.[P. Unitario] = 0) and (".$mes." = T2.nMes) AND (".$anio." = T2.[Año]) $Sql_Dia AND ARTICULO = T1.ARTICULO  ), 0) AS Cantida_boni
                
                            from Softland.dbo.INV_VtasTotal_UMK_Temporal T1 WHERE ".$mes." = T1.nMes and $anio = T1.[Año] and T1.[P. Unitario] > 0
                            GROUP BY T1.Articulo,T1.Descripcion,T1.[Costo Unitario]
                            ORDER BY MontoVenta desc;"; 
                break;         
            default:                
                //dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
        
        foreach ($query as $fila) {

            $Total_Facturado        = $fila['MontoVenta'];
            $Cantidad               = $fila['Cantidad'];
            $Cantidad_bonificada    = $fila['Cantida_boni'];                
            $COSTO_PROM             = $fila['COSTO_PROM'];

            $AVG = floatval($Total_Facturado)  / (  floatval($Cantidad) + floatval($Cantidad_bonificada) );

            $Costo_total_Promedio = (floatval($Cantidad) + floatval($Cantidad_bonificada)) * floatval($COSTO_PROM);
            $Monto_Contribucion = floatval($Total_Facturado)  - floatval($Costo_total_Promedio);

            $prom_contribucion = (( $AVG - floatval($COSTO_PROM) ) / $AVG) * 100;




            $json[$i]["Articulo"]           = $fila["Articulo"];
            $json[$i]["Descripcion"]        = $fila["Descripcion"];            
            $json[$i]["TotalFacturado"]     = number_format($Total_Facturado,2);
            $json[$i]["Existencia"]         = number_format($fila["EXISTENCIA"],2);
            $json[$i]["UndFacturado"]       = number_format($Cantidad, 0);
            $json[$i]["UndBoni"]            = number_format($Cantidad_bonificada, 0);
            $json[$i]["PrecProm"]           = number_format($AVG, 2);
            $json[$i]["CostProm"]           = number_format($COSTO_PROM, 2);
            $json[$i]["Contribu"]           = number_format($Monto_Contribucion, 2);
            $json[$i]["MargenBruto"]        = number_format($prom_contribucion, 2);

            
            $i++;
        }

        $sql_server->close();
        return $json;
    }  

    public static function getDetalleVentasDia($dia, $mes, $anio,$Segmento)
    {
        $sql_server = new \sql_server();
        $fecha = new DateTime($anio.'-'.$mes.'-01');
        $sql_exec = '';
        $request = Request();
        $idPeriodo = '';
        $qSegmento ="";
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        switch ($company_user) {
            case '1': 
                if ($Segmento==0) {
                    //TODAS LOS SEGMENTOS
                    $qSegmento =" Ruta NOT IN ('F01','F12') ";

                } else {
                    if ($Segmento==1) {
                        //TODAS LAS RUTAS DEL SEGMENTO FARMACIA
                        $qSegmento =" Ruta NOT IN ('F04','F02','F01','F12') ";
                    } else {
                        if ($Segmento==2) {
                           //TODAS LAS RUTAS DEL SEGMENTO MAYORISTA
                            $qSegmento =" Ruta IN ('F04') ";
                        } else {
                            if ($Segmento==3) {
                               //TODAS LAS RUTAS DEL SEGMENTO INSTITUCION
                                $qSegmento =" Ruta IN ('F02') ";
                            }
                            
                        }
                        
                    }
                }
                $sql_exec =" SELECT Ruta, SUM ( VENTA ) AS Monto, SUM ( Cantidad ) AS Cantidad 
                FROM Softland.DBO.VtasTotal_UMK ( nolock ) 
                WHERE DAY ( DIA ) =".$dia." AND MONTH ( DIA ) = ".$mes."  AND YEAR ( DIA ) = ".$anio."  AND [P. Unitario] > 0  AND Ruta NOT IN ( 'F01', 'F12' ) AND  ".$qSegmento."
                GROUP BY Ruta ORDER BY Ruta";
                break;
            case '2':
                $sql_exec =" SELECT Ruta, SUM ( VENTA ) AS Monto, SUM ( Cantidad ) AS Cantidad 
                FROM Softland.DBO.GP_VtasTotal_UMK ( nolock ) 
                WHERE DAY ( DIA ) =".$dia." AND MONTH ( DIA ) = ".$mes."  AND YEAR ( DIA ) = ".$anio."  AND [P. Unitario] > 0 
                GROUP BY Ruta ORDER BY Ruta";
                break;
            case '3':
                $sql_exec = "";
                break;     
            case '4':
                $sql_exec =" SELECT Ruta, SUM ( VENTA ) AS Monto, SUM ( Cantidad ) AS Cantidad 
                FROM Softland.DBO.INV_VtasTotal_UMK_Temporal ( nolock ) 
                WHERE DAY ( DIA ) =".$dia." AND MONTH ( DIA ) = ".$mes."  AND YEAR ( DIA ) = ".$anio."  AND [P. Unitario] > 0 
                GROUP BY Ruta ORDER BY Ruta";
                break;       
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }



        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i = 0;
        $json = array();        

        foreach ($query as $fila) {
            $VENDEDOR = dashboard_model::buscarVendedorXRuta($fila["Ruta"], $company_user);                        
            $json[$i]["RUTA"] = '<a href="#!" onclick="get_Detalle_Venta_dia('.$dia.','.$mes.','.$anio.','."'".$fila["Ruta"]."'".', '."'".$VENDEDOR."'".')" >'.$fila["Ruta"].'</a>';
            $json[$i]["VENDE"] = $VENDEDOR;
            $json[$i]["REALE"] = "C$ ".number_format($fila["Monto"],2);
            $i++;
        }
        $sql_server->close();
        return $json;
    }
    
    
    public static function getDetalleVentas($tipo, $mes, $anio, $cliente, $articulo, $ruta) {
        $sql_server     = new \sql_server();

        $sql_exec       = '';
        $request        = Request();
        $company_user   = Company::where('id',$request->session()->get('company_id'))->first()->id;

        $cliente        = ($cliente=='ND')?'':$cliente;
        $articulo       = ($articulo=='ND')?'':$articulo;
        $ruta           = ($ruta=='ND')?'':$ruta;

        switch ($company_user) {
            case '1':
                $sql_exec = "EXEC gnet_Ventas_detalle ".$mes.", ".$anio.", '', '".$cliente."', '".$articulo."', ''";
                break;
            case '2':
                $sql_exec = "EXEC gnet_Ventas_detalle_gp ".$mes.", ".$anio.", '', '".$cliente."', '".$articulo."', ''";
                break;
            case '3':
                $sql_exec = "";
                break;    
            case '4':
                $sql_exec = "EXEC gnet_Ventas_detalle_inn ".$mes.", ".$anio.", '', '".$cliente."', '".$articulo."', ''";
                break;        
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $i = 0;
        $json = array();

        switch ($tipo) {
            case 'vent':
                $real_ = array_sum(array_column($query, 'Monto'));
                $json[$i]['MONTO'] = $real_;
            break;
            case 'recu':
                    $mes = (strlen($mes)==1)?'0'.$mes:$mes;
                    $f1 = intval($anio.$mes.'01');

                    $fecha = new DateTime( $anio.'-'.$mes.'-01' );
                    $fecha->modify('last day of this month');
                    $ult_dia = $fecha->format('d');
                    $f2 = intval($anio.$mes.$ult_dia);

                    switch ($company_user) {
                        case '1':
                            $sql_exec = "EXEC Recuperacion_Cartera '".$f1."', '".$f2."', '' ";

                            break;
                        case '2':
                            $sql_exec = "SELECT
                                            T0.COBRADOR AS Vendedor,
                                            SUM(T0.MONTO) AS real_,
                                            T0.NombreVendedor AS Nombre
                                        FROM
                                            gn_recuperacion T0
                                        WHERE
                                            T0.Mes = ".$mes."
                                            AND T0.Anno = ".$anio."
                                        GROUP BY T0.COBRADOR, T0.NombreVendedor";
                            break;
                        case '3':
                            $sql_exec = "";
                            break;  
                        case '4':
                            $sql_exec = "EXEC Inv_Recuperacion_Cartera '".$f1."', '".$f2."', '' ";
                        break;          
                        default:                
                            dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                            break;
                    }

                    $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

                    foreach ($query as $fila_) {                        
                        $real = ($company_user==1)?(floatval($fila_['Recuperacion_Contado'])):$fila_['real_'];
                        $meta = dashboard_model::returnMetaRecuperacion($mes, $anio, $company_user, $fila_['Vendedor']);

                        $cump = ($meta==0)?100:( $real / $meta ) * 100;

                        $json[$i]["RUTA"]       = $fila_['Vendedor'];
                        $json[$i]["NOMBRE"]     = $fila_['Nombre'];
                        $json[$i]["MONTO"]      = number_format($real, 2);
                        $json[$i]["META"]       = number_format($meta, 2);
                        $json[$i]["EFEC"]       = number_format($cump, 0).'%';
                        $i++;
                    }
                break;
            case 'clien':
                foreach ($query as $fila) {
                    $json[$i]["ARTICULO"]       = $fila["articulo"];
                    $json[$i]["DESCRIPCION"]    = $fila["descripcion"];
                    $json[$i]["CANTIDAD"]       = number_format($fila["Cantidad"], 2);
                    $json[$i]["TOTAL"]          = number_format($fila["total"], 2);
                    $i++;
                }
                break;
            case 'artic':
                foreach ($query as $fila) {
                    $json[$i]["CLIENTE"]       = $fila["cliente"];
                    $json[$i]["NOMBRE"]        = $fila["nombre"];
                    $json[$i]["CANTIDAD"]      = number_format($fila["Cantidad"], 2);
                    $json[$i]["CANTIDAD_BONI"] = number_format($fila["Cantida_boni"], 2);
                    $json[$i]["TOTAL"]         = number_format($fila["Monto"], 2);
                    $i++;
                }
                break; 
            default:
                return false;
                break;
        }
        $sql_server->close();
        return $json;
    }

    public static function returnMetaRecuperacion($mes, $anio, $company_user, $rta) {
        $meta = 0;
        $query = DB::select("CALL sp_recuperacionMeta(".$mes.",".$anio.",".$company_user.", '".$rta."' )");
            
        foreach($query as $t) {
            $meta = $t->meta;
        }

        return $meta;
    }

    public static function getTop10Clientes($mes, $anio, $company_user, $xbolsones,$Segmento) {
        $sql_server = new \sql_server();        
        $sql_exec = '';
        $tem_ = 0;

        switch ($company_user) {
            case '1':

                if ($Segmento==0) {
                    //TODAS LOS SEGMENTOS
                    $RutaSegmento = "'F02','F03','F04','F05','F06','F07','F08','F09','F10','F11','F13','F14','F15','F20'";
                } else {
                    if ($Segmento==1) {
                        //TODAS LAS RUTAS DEL SEGMENTO FARMACIA
                        $RutaSegmento = "'F03','F05','F06','F07','F08','F09','F10','F11','F13','F14','F15','F20'";
                    } else {
                        if ($Segmento==2) {
                           //TODAS LAS RUTAS DEL SEGMENTO MAYORISTA
                            $RutaSegmento = "'F02'";
                        } else {
                            if ($Segmento==3) {
                               //TODAS LAS RUTAS DEL SEGMENTO INSTITUCION
                                $RutaSegmento = "'F04'";
                            }
                            
                        }
                        
                    }
                }

                //$sql_exec = " EXEC Umk_ReportVentas_Cliente ".$mes.", ".$anio." ";

                



                $sql_exec ="select top 10
                [Cod. Cliente] AS codigo,
                [Nombre del Cliente] AS cliente,
                isnull(SUM(VENTA),0) AS MontoVenta,
                isnull(sum(Cantidad),0) As CantidadVenta,Mes,Año
                
                from Softland.dbo.VtasTotal_UMK  (nolock)where MONTH(dia)=".$mes." AND YEAR(dia)=".$anio."
                AND  Ruta NOT IN('F01', 'F12')  AND  Ruta IN(".$RutaSegmento.") 
                GROUP BY [Cod. Cliente],[Nombre del Cliente],MES,AÑO
                ORDER BY isnull(SUM(VENTA),0) DESC";

                break;
            case '2':
                $sql_exec = " EXEC Gp_ReportVentas_Cliente ".$mes.", ".$anio." ";
                break;
            case '3':
                $sql_exec = "";
                break;    
            case '4':
                if ($xbolsones) {
                    $sql_exec = " EXEC Inv_ReportVentas_Cliente_Bolsones ".$mes.", ".$anio." ";
                }else {
                    $sql_exec = " EXEC Inv_ReportVentas_Cliente ".$mes.", ".$anio." ";
                }

                break;      
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }
        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $json = array();
        $i = 0;        
        
        if( count($query)>0 ){
            foreach ($query as $key) {

                $json[$i]['name']       = $key['codigo'];
                $json[$i]['cliente']    = $key['cliente'];
                
                if ( $company_user==4 ) {
                    $tem_ = ($xbolsones)?intval($key['CantidadVenta']):intval($key['MontoVenta']);

                }else {
                    $tem_ = intval($key['MontoVenta']);
                }

                $json[$i]['data'] = $tem_;
                $i++;
            }
        }
        return $json;
        $sql_server->close();
    }

    public static function getTop10Productos($mes, $anio, $company_user, $xbolsones,$Segmento) {
        $sql_server = new \sql_server();
        $sql_exec = '';
        $tem_=0;
        $RutaSegmento = "";
        
        switch ($company_user) {
            case '1':
                

                if ($Segmento==0) {
                    //TODAS LOS SEGMENTOS
                    $qSegmento =" Ruta NOT IN ('F01','F12') ";

                } else {
                    if ($Segmento==1) {
                        //TODAS LAS RUTAS DEL SEGMENTO FARMACIA
                        $qSegmento =" Ruta NOT IN ('F04','F02','F01','F12') ";
                    } else {
                        if ($Segmento==2) {
                           //TODAS LAS RUTAS DEL SEGMENTO MAYORISTA
                            $qSegmento =" Ruta IN ('F04') ";
                        } else {
                            if ($Segmento==3) {
                               //TODAS LAS RUTAS DEL SEGMENTO INSTITUCION
                                $qSegmento =" Ruta IN ('F02') ";
                            }
                            
                        }
                        
                    }
                }

                

                $sql_exec ="select top 10
                        T1.Articulo,T1.Descripcion,T1.Clasificacion6,
                        count(T1.articulo) As NºVentaMes,
                        isnull(sum(T1.cantidad),0) Cantidad,
                        isnull(sum(T1.venta),0) MontoVenta,
                        AVG (T1.[P. Unitario]) as AVG_,         
                        T1.[Costo Unitario] AS COSTO_PROM,
                        isnull((SELECT TOP 1 SUM(T2.cantidad) AS Cantidad FROM Softland.dbo.VtasTotal_UMK T2  WHERE ".$mes." = T2.nMes AND ".$anio." = T2.[Año] AND T2.[P. Unitario] <= 0 AND T2.Articulo = T1.Articulo and ".$qSegmento." GROUP BY  T2.Articulo),0) AS Cantida_boni,
                        ISNULL((SELECT SUM(T2.venta)  FROM Softland.dbo.VtasTotal_UMK T2 WHERE (".$mes." = T2.nMes) AND ( ".$anio." = T2.[Año]) AND (T2.[P. Unitario] > 0) AND (T2.Articulo = T1.Articulo) AND (T2.Ruta in ('F04'))    ), 0) AS Mayoristas,
                        ISNULL((SELECT SUM(T2.venta)  FROM Softland.dbo.VtasTotal_UMK T2 WHERE (".$mes." = T2.nMes) AND ( ".$anio." = T2.[Año]) AND (T2.[P. Unitario] > 0) AND (T2.Articulo = T1.Articulo) AND (T2.Ruta in ('F02'))  ), 0) AS Instituciones,
                        ISNULL((SELECT SUM(T2.venta)  FROM Softland.dbo.VtasTotal_UMK T2 WHERE (".$mes." = T2.nMes) AND (".$anio." = T2.[Año]) AND (T2.[P. Unitario] > 0) AND (T2.Articulo = T1.Articulo) AND (T2.Ruta NOT IN ('F04','F02','F01','F12'))  ), 0) AS Farmacias
            
            from Softland.dbo.VtasTotal_UMK T1 Where ".$mes." = T1.nMes and ".$anio." = T1.[Año] and T1.[P. Unitario] > 0
            AND  Ruta NOT IN('F01', 'F12') AND  ".$qSegmento." 
            group by T1.Articulo,T1.Descripcion,T1.Clasificacion6,T1.mes,T1.año,T1.[Costo Unitario]
            order by MontoVenta desc";




                break;
            case '2':
                $sql_exec = " EXEC Gp_DetalleVentas_Mes ".$mes.", ".$anio." ";
                break;
            case '3':
                $sql_exec = "";
                break;   
            case '4':
                $sql_exec = " EXEC Inv_DetalleVentas_Mes ".$mes.", ".$anio." ";
                break;        
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

        $json = array();
        $json = array();
        $i = 0;
   

        if( count($query)>0 ) {
            foreach ($query as $key) {

                $Total_Facturado        = $key['MontoVenta'];
                $Cantidad               = $key['Cantidad'];
                $Cantidad_bonificada    = $key['Cantida_boni'];                
                $COSTO_PROM             = $key['COSTO_PROM'];
                $json[$i]['name']       = $key['Articulo'];
                $json[$i]['articulo']   = $key['Descripcion'];


                $AVG = floatval($Total_Facturado)  / (  floatval($Cantidad) + floatval($Cantidad_bonificada) );

                $Costo_total_Promedio = (floatval($Cantidad) + floatval($Cantidad_bonificada)) * floatval($COSTO_PROM);
                
                $Monto_Contribucion = floatval($Total_Facturado)  - floatval($Costo_total_Promedio);

               //$prom_contribucion = ($Monto_Contribucion / $Costo_total_Promedio) * 100;          

               $prom_contribucion = (( $AVG - floatval($COSTO_PROM) ) / $AVG) * 100;


                if ( $company_user==4 ) {

                    $tem_   = ($xbolsones) ? floatval($Cantidad) : floatval($Total_Facturado);
                    $UND_ = floatval($Cantidad);
                    $UND_BO = floatval($Cantidad_bonificada);
                    $AVG_   = number_format(floatval($AVG),2);                    
                    $COSTO_PROM_ = number_format(floatval($COSTO_PROM),2);
                    $MARG_CONTRI = number_format(floatval($Monto_Contribucion),2);
                    $PORC_CONTRI = number_format(floatval($prom_contribucion),2);

                }else {

                    $tem_ = floatval($Total_Facturado);
                    $UND_ = floatval($Cantidad);
                    $UND_BO = floatval($Cantidad_bonificada);
                    $AVG_ = number_format(floatval($AVG),2);
                    $COSTO_PROM_ = number_format(floatval($COSTO_PROM),2);
                    $MARG_CONTRI = number_format(floatval($Monto_Contribucion),2);
                    $PORC_CONTRI = number_format(floatval($prom_contribucion),2);
                }



                $json[$i]['data']       = $tem_;
                $json[$i]['dtUnd']      = $UND_;
                $json[$i]['dtUndBo']    = $UND_BO;
                $json[$i]['dtAVG']      = $AVG_;
                $json[$i]['dtCPM']      = $COSTO_PROM_;
                $json[$i]['dtMCO']      = $MARG_CONTRI;
                $json[$i]['dtPCO']      = $PORC_CONTRI;   
                
                if ($company_user==1) {
                    $json[$i]['M1']         = $key['Farmacias'];
                    $json[$i]['M2']         = $key['Mayoristas'];
                    $json[$i]['M3']         = $key['Instituciones'];
                } else {
                    $json[$i]['M1']         = $tem_;
                    $json[$i]['M2']         = 0;
                    $json[$i]['M3']         = 0;
                }


                
                $i++;
            }
        }

        
        return $json;
        $sql_server->close();
    }

    public static function getVentasMes($mes, $anio, $company_user, $xbolsones) {
        $total = 0;
        $items = 0;
        $sql_server = new \sql_server();
        $sql_exec = '';
        
        switch ($company_user) {
            case '1':
                $sql_exec = "EXEC Umk_VentaLinea_Articulo ".$mes.", ".$anio.", '', '', '', ''";
                $sql_meta = "EXEC UMK_meta_articulos ".$mes.", ".$anio.", '', '', ''";
                break;
            case '2':
                $sql_exec = "EXEC Gp_VentaLinea_Articulo ".$mes.", ".$anio.", '', '', '', ''";
                $sql_meta = "EXEC Gp_meta_articulos ".$mes.", ".$anio.", '', '', ''";
                break;
            case '3':
                $sql_exec = "";
                break; 
            case '4':
                if ($xbolsones) {
                    $sql_meta = "EXEC Inv_meta_articulos ".$mes.", ".$anio.", '', '', ''";
                }else {
                    $sql_meta = "EXEC Inv_meta_articulos_xbolsones ".$mes.", ".$anio.", '', '', ''";                    
                }

                $sql_exec = "EXEC Inv_VentaLinea_Articulo ".$mes.", ".$anio.", '', '', '', ''";
                break;           
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
            break;
        }

        $query  = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);        
        $query2 = $sql_server->fetchArray($sql_meta, SQLSRV_FETCH_ASSOC);
        $json = array();

        $json[0]['name'] = 'Real';

        if ( $company_user==4 ) {
            $tem_ = ($xbolsones)?array_sum(array_column($query, 'Cantidad')):array_sum(array_column($query, 'Monto'));
        }else {
            $tem_ = array_sum(array_column($query, 'Monto'));
        }

        $json[0]['data'] = $tem_;

        $json[1]['name'] = 'Meta';
        $json[1]['data'] = floatval($query2[0]['meta']);

        $json[2]['name'] = 'items';
        $json[2]['data'] =  dashboard_model::cantItems($mes, $anio, $company_user);

        return $json;
        $sql_server->close();
    }

    public static function clientesMeta($mes, $anio, $company_user) {
        $sql_server = new \sql_server();
        $sql_exec = '';

        switch ($company_user) {
            case '1':
                $sql_exec ="SELECT count(distinct t.[Cod. Cliente]) as totalClientes
                            FROM
                                Softland.dbo.VtasTotal_UMK (nolock) t
                            WHERE
                                t.[Año] = ".$anio." AND t.nMes = ".$mes."
                            AND [P. Unitario] > 0
                            AND Ruta NOT IN ('F01', 'F12')";
                break;
            case '2':                
                /*$sql_exec ="SELECT count(distinct t.[Cod. Cliente]) as totalClientes
                            FROM
                                Softland.dbo.GP_VtasTotal_UMK (nolock) t
                            WHERE
                                t.[Año] = ".$anio." AND t.nMes = ".$mes."
                            AND [P. Unitario] > 0";*/
                return false;
                break;
            case '3':
                return false;
                break; 
            case '4':
                /*$sql_exec ="SELECT count(distinct t.[Cod. Cliente]) as totalClientes
                            FROM
                                Softland.dbo.INN_VtasTotal_UMK (nolock) t
                            WHERE
                                t.[Año] = ".$anio." AND t.nMes = ".$mes."
                            AND [P. Unitario] > 0";*/
                return false;
                break;          
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);
        $array = array();

        if (count($query)>0) {

            $metas = dashboard_model::getVentasMes($mes, $anio, $company_user, 0);
            $clientesMeta = clientes_x_rutas::sum('cantidad');

            if (count($metas)>0) {
                $array[0]['title'] = 'real';
                $array[0]['data'] = $metas[0]['data'];

                $array[1]['title'] = 'meta';
                $array[1]['data'] = $metas[1]['data'];

                $array[2]['title'] = 'clientesMeta';
                $array[2]['data'] = $clientesMeta;

                $array[3]['title'] = 'clientesReal';
                $array[3]['data'] = ( $query[0]['totalClientes']=='' )?0:$query[0]['totalClientes'];
            }
        }

        return $array;
        $sql_server->close();

    }

    public static function cantItems($mes, $anio, $company_user) {
        $sql_server = new \sql_server();
        $sql_exec = '';        
        switch ($company_user) {
            case '1':
                $sql_exec =
                "SELECT dbo.UMK_RETURN_ITEMS_MES(".$mes.", ".$anio.") cantItems";
                break;
            case '2':                
                $sql_exec =
                "SELECT dbo.GP_RETURN_ITEMS_MES(".$mes.", ".$anio.") cantItems";
                break;
            case '3':
                $sql_exec = "";
                break;  
            case '4':
               $sql_exec =
                "SELECT dbo.INV_RETURN_ITEMS_MES(".$mes.", ".$anio.") cantItems";
                break;          
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);
        
        if (count($query)>0) {
           return $query[0]['cantItems']; 
        }

        return false;
    }

    public static function getComparacionMesVentas($mes, $anio, $company_user, $xbolsones) {
        $total_1 = $total_2 = $total_3 = 0;
        $sql_server = new \sql_server();
        $sql_exec = '';

        $meses = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
        
        switch ($company_user) {
            case '1':
                $sql_exec =
                "EXEC UMK_GN_VENTAS_COMPARACION ".$mes.", ".$anio." ";
                break;
            case '2':                
                $sql_exec =
                "EXEC GP_GN_VENTAS_COMPARACION ".$mes.", ".$anio." ";
                break;
            case '3':
                $sql_exec = "";
                break; 
            case '4':
                if ($xbolsones) {
                    $sql_exec =
                    "EXEC INV_GN_CANTIDAD_COMPARACION ".$mes.", ".$anio." ";
                }else {
                    $sql_exec =
                    "EXEC INV_GN_VENTAS_COMPARACION ".$mes.", ".$anio." ";
                }
                break;          
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);
        $json = array();

        if (count($query)>0) {
            $x = $query[0];

            $json[0]['name'] = ($meses[($x['mesActual'])-1]).' '.$x['anioActual'];
            $json[0]['data'] =  floatval($query[0]['montoActual']);

            $json[1]['name'] = ($meses[($x['mesPasado'])-1]).' '.$x['anioPasado'];
            $json[1]['data'] =  floatval($x['montoAnioPasado']);

            $json[2]['name'] = ($meses[($x['mesAnterior'])-1]).' '.$x['AnioAnterior'];
            $json[2]['data'] = floatval($x['montoMesPasado']);
        }


        return $json;
        $sql_server->close();
    }

    public static function getComparacionMesItems($mes, $anio, $company_user) {
        $total_1 = $total_2 = $total_3 = 0;
        $sql_server = new \sql_server();
        $sql_exec = '';

        $meses = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
        
        switch ($company_user) {
            case '1':
                $sql_exec =
                "EXEC UMK_GN_ITEMS_COMPARACION ".$mes.", ".$anio." ";
                break;
            case '2':
                $sql_exec =
                "EXEC GP_GN_ITEMS_COMPARACION ".$mes.", ".$anio." ";
                break;
            case '3':
                $sql_exec = "";
                break;  
            case '4':
                $sql_exec =
                "EXEC INV_GN_ITEMS_COMPARACION ".$mes.", ".$anio." ";
                break;        
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);
        $json = array();

        if (count($query)>0) {
            $x = $query[0];

            $json[0]['name'] = ($meses[($x['mesActual'])-1]).' '.$x['anioActual'];
            $json[0]['data'] =  floatval($query[0]['cantActual']);

            $json[1]['name'] = ($meses[($x['mesPasado'])-1]).' '.$x['anioPasado'];
            $json[1]['data'] =  floatval($x['cantAnioPasado']);

            $json[2]['name'] = ($meses[($x['mesAnterior'])-1]).' '.$x['AnioAnterior'];
            $json[2]['data'] = floatval($x['cantMesPasado']);            
        }


        return $json;
        $sql_server->close();
    }

    public static function getVentasXCategorias($mes, $anio, $company_user, $xbolsones) {
        $sql_server = new \sql_server();
        $Dta = array();
        $sql_exec = '';
        $tem_ = 0;

        switch ($company_user) {
            case '1':
                $sql_exec = "SELECT
                            SUM(VENTA) AS Monto,
                            ( CASE 
                                WHEN LEN(Clasificacion3)=0 THEN 'SIN CATEGORIA'
                                ELSE Clasificacion3
                            END ) AS ClaseTerapeutica
                            FROM Softland.DBO.VtasTotal_UMK (nolock)
                            WHERE month(DIA)=".$mes." AND year(DIA)=".$anio."
                            AND  Ruta NOT IN('F01', 'F12')
                            GROUP BY Clasificacion3";
                break;
            case '2':
                $sql_exec = "SELECT
                            SUM(VENTA) AS Monto,
                            ( CASE 
                                WHEN LEN(Clasificacion3)=0 THEN 'SIN CATEGORIA'
                                ELSE Clasificacion3
                            END ) AS ClaseTerapeutica
                            FROM Softland.DBO.GP_VtasTotal_UMK (nolock)
                            WHERE month(DIA)=".$mes." AND year(DIA)=".$anio."
                            AND  Ruta NOT IN('F01', 'F12')
                            GROUP BY Clasificacion3";
                break;
            case '3':
                $sql_exec = "";
                break;  

            case '4':
                $sql_exec = "SELECT
                            SUM(CANTIDAD) AS Cantidad,
                            SUM(VENTA) AS Monto,
                            ( CASE 
                                    WHEN LEN(Clasificacion5)='' THEN 'SIN CATEGORIA'
                                    ELSE Clasificacion5
                            END ) AS ClaseTerapeutica
                            FROM Softland.DBO.INV_VtasTotal_UMK_Temporal (nolock)
                            WHERE month(DIA)=".$mes." AND year(DIA)=".$anio."
                            GROUP BY Clasificacion5";
                break;         
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }
        
        $json = array();
        $i=0;
        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

        if( count($query)>0 ) {
            foreach ($query as $key) {
                $tem_ = 0;
                if ( $company_user==4 ) {
                    $tem_ = ($xbolsones)?floatval($key['Cantidad']):floatval($key['Monto']);
                }else {
                    $tem_ = floatval($key['Monto']);
                }

                $json[$i]['name']       = $key['ClaseTerapeutica'];
                $json[$i]['data']       = floatval($tem_);

                $i++;
            }
        }

        return $json;
        //$sql_server->close();
    }

    public static function getValBodegas($date, $company_user) {
        $sql_server = new \sql_server();
        $sql_exec = '';
        
        switch ($company_user) {
            case '1':
                $sql_exec = " EXEC UMK_ReportValorizacion_TotalINV '".$date."' ";
                break;
            case '2':
                $sql_exec = " EXEC GP_ReportValorizacion_TotalINV '".$date."' ";
                break;
            case '3':
                $sql_exec = "";
                break;           
            case '4':
                $sql_exec = " EXEC INV_ReportValorizacion_TotalINV '".$date."' ";
                break;      
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

        $query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
        
        $json = array();
        $i = 0;

        if( count($query)>0 ){
            foreach ($query as $key) {
                $json[$i]['name']       = 'B'.($i+1);
                $json[$i]['bodega']     = $key['Bodega'];
                $json[$i]['data']       = intval($key['TotalBodega']);
                $i++;
            }
        }

        return $json;
        $sql_server->close();
    }

    public static function ventaXCategorias($mes, $anio, $cate) {
        $sql_server = new \sql_server();
        $Dta = array();

        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;        

        if ($cate=='TODAS LAS CATEGORIAS') {
            return dashboard_model::getVentasXCategorias($mes, $anio, $company_user, $xbolsones);
        }else{
            switch ($company_user) {
                case '1':
                    $sql_exec = "EXEC Umk_VentaLinea_Articulo ".$mes.", ".$anio.", '', '', '','' ";
                break;
                case '2':
                    $sql_exec = "EXEC Gp_VentaLinea_Articulo ".$mes.", ".$anio.", '', '', '','' ";
                break;
                case '3':
                    $sql_exec = "";
                break;   
                case '4':
                    $sql_exec = "EXEC Inv_VentaLinea_Articulo ".$mes.", ".$anio.", '', '', '','' ";
                break;       
                default:                
                    dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
            }

            $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

            $json = array();
            $real_ = $meta_ = 0;

            if( count($query)>0 ) {
                if ( $company_user==4 ) {

                    if ($xbolsones) {
                        $real_ = array_sum(array_column(array_filter($query, function($item) use($cate) { return $item['ClaseTerapeutica'] == $cate; } ), 'Cantidad'));
                    }else {
                        $real_ = array_sum(array_column(array_filter($query, function($item) use($cate) { return $item['ClaseTerapeutica'] == $cate; } ), 'Monto'));
                    }
                    
                }else {
                    $real_ = array_sum(array_column(array_filter($query, function($item) use($cate) { return $item['ClaseTerapeutica'] == $cate; } ), 'Monto'));
                }

                /*$real_ = array_sum(array_column(array_filter($query, function($item) use($cate) { return $item['ClaseTerapeutica'] == $cate; } ), 'Monto'));*/
                
                $meta_ = array_sum(array_column(array_filter($query, function($item) use($cate) { return $item['ClaseTerapeutica'] != $cate; } ), 'Monto'));
            }

            $json[0]['name'] = 'CATEGORIA: '.$cate;
            $json[0]['data'] = floatval($real_);

            $json[1]['name'] = 'Venta total';
            $json[1]['data'] = $meta_;

            $sql_server->close();           
            return $json;
        }
    }
    
    public static function getRecuperaMes($mes, $anio, $company_user) {
        $otroTipoVende = array('F01','F12','F16','F18','F19');//quitar f2 y f4
        $otroTipoVende_sql_server = "'F01','F12','F16','F18','F19'";//quitar f2 y f4
        $total = 0;
        $sql_server = new \sql_server();

        $mes = (strlen($mes)==1)?'0'.$mes:$mes;
        $f1 = intval($anio.$mes.'01');
        
        $fecha = new DateTime( $anio.'-'.$mes.'-01' );
        $fecha->modify('last day of this month');
        $ult_dia = $fecha->format('d');
        $f2 = intval($anio.$mes.$ult_dia);

        $sql_exec = '';
        
        switch ($company_user) {
            case '1':
                $query = Umk_recuperacion::where(['fecha_recup' => $anio.'-'.$mes.'-01', 'IdCompanny' => $company_user])->whereNotIn('ruta',$otroTipoVende)->pluck('recuperado_credito')->toArray();//"EXEC Recuperacion_Cartera '".$f1."', '".$f2."', ''; ";

              
                $sql_meta = "CALL sp_recuperacionMeta(".$mes.",".$anio.",".$company_user.", '' )";
                break;
            case '2':
                $sql_exec = "SELECT SUM(MONTO) AS M_REC FROM gn_recuperacion T0 WHERE Mes = ".$mes." AND Anno=".$anio." AND COBRADOR NOT IN (".$otroTipoVende_sql_server.")";

                $sql_meta = "CALL sp_recuperacionMeta(".$mes.",".$anio.",".$company_user.", '' )";
                break;
            case '3':
                $sql_exec = "";
                break;
              case '4':
                $query = Umk_recuperacion::where(['fecha_recup' => $anio.'-'.$mes.'-01', 'IdCompanny' => $company_user])->pluck('recuperado_credito')->toArray();


                $sql_meta = "CALL sp_recuperacionMeta(".$mes.",".$anio.",".$company_user.", '' )";
                break;           
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }
        
        if ($company_user!= 1 && $company_user!= 4) {
          
            $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC); 
        }     
        $query2 = DB::select($sql_meta);
        $meta = 0;
        $json = array();
       
            
        if (count($query)>0) {
            if ($company_user== 1 || $company_user== 4) {
                 
                for ($i=0; $i < count($query) ; $i++) { 
                     $total = $total +  (floatval($query[$i]));
                }
                   
                   
            }else {
                $total = floatval($query[0]['M_REC']);
            }


            $json[0]['name'] = 'Real';
            $json[0]['data'] =  $total;
        }
        
        foreach($query2 as $t){
            $meta = $t->meta;
        }

        if (count($query)>0 || $meta!=null) {
            $json[1]['name'] = 'Meta';
            $json[1]['data'] = floatval($meta);
        }        


        return $json;
        $sql_server->close();
    }

    public static function getRecuRowsByRoutes($mes, $anio, $pageName){

        $otroTipoVende_sql_server = "'F01','F12','F16','F18','F19'";//quitar f2 y f4
        $otroTipoVende = array('F01','F12','F16','F18','F19');//quitar f2 y f4


        $request = Request();
        $fecha =  date('Y-m-d', strtotime($anio.'-'.$mes.'-01'));

         $recuperacion = array();
                $json = array();
                $i = 0;
                $meta=0;
        

        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        
        switch ($company_user) {
            case '1':
               
                $recuperacion = Umk_recuperacion::where(['fecha_recup'=>$fecha, 'idCompanny' => $request->session()->get('company_id')])->whereNotIn('ruta',$otroTipoVende)->get();

                foreach ($recuperacion as $key) {
                    $meta = meta_recuperacion_exl::where(['fechaMeta'=>$fecha, 'idCompanny'=> $request->session()->get('company_id'), 'ruta' => $key['ruta']])->pluck('meta');

                    $meta =  str_replace(['[',']'],'',$meta);


                        if ($meta == '' || is_null($meta)) {
                            $meta = '0.00';
                        }else{
                            $meta = $meta;

                        } 

                    $json[$i]['RECU_RUTA'] =  $key['ruta'];
                    $json[$i]['RECU_VENDE'] =   '<span style="text-align: left; float: left" >'.$key['vendedor'].'</span>';

                    if($pageName == 'Recuperacion'){
                    $json[$i]['RECU_META'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="'.number_format($meta,2).'" id ="recu_meta_'.$key['ruta'].'">';
                    }else{
                        $json[$i]['RECU_META'] =  '<span style="text-align: right; float: right" >C$'.number_format($meta,2).'</span>';
                    }

                    if ($key['recuperado_credito']>0) {

                        if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CREDITO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="'.number_format($key['recuperado_credito'],2).'" id ="recu_credito_'.$key['ruta'].'">';
                        }else{
                            $json[$i]['RECU_CREDITO'] = '<span style="text-align: right; float: right" >C$'. number_format($key['recuperado_credito'],2).'</span>';
                        }
                     
                    }else{
                        if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CREDITO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="0.00" id ="recu_credito_'.$key['ruta'].'">';
                         }else{
                            $json[$i]['RECU_CREDITO'] =  '<span style="text-align: right; float: right">C$0.00</span>' ;
                         }
                         
                    }
                    if ($key['recuperado_contado']>0) {
                        if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CONTADO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="'.number_format($key['recuperado_contado'],2).'" id ="recu_contado_'.$key['ruta'].'">';
                          }else{
                            $json[$i]['RECU_CONTADO'] =  '<span style="text-align: right; float: right" >C$'. number_format($key['recuperado_contado'],2).'</span>';
                          }
                        
                    }else{
                         if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CONTADO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="0.00" id ="recu_contado_'.$key['ruta'].'">';
                         }else{
                            $json[$i]['RECU_CONTADO'] =  '<span style="text-align: right; float: right" >C$0.00</span>';

                         }
                        
                    }

                    $json[$i]['RECU_TOTAL'] =  ($key['recuperado_credito'] == 0 && $key['recuperado_contado'] == 0) ? '<span id="recu_total_'.$key['ruta'].'" style="text-align: right; float: right">C$0.00</span>' : '<span id="recu_total_'.$key['ruta'].'" style="text-align: right; float: right">C$'.number_format($key['recuperado_credito'] + $key['recuperado_contado']).'</span>';
                    $json[$i]['RECU_CUMPLIMIENTO'] =  ($meta=='0.00') ? '<span id="recu_cumplimiento_'.$key['ruta'].'" style="text-align: right; float: right">0.00%</span>' : '<span id="recu_cumplimiento_'.$key['ruta'].'" style="text-align: right; float: right">'.number_format(((floatval($key['recuperado_credito']) /*+ floatval($key['recuperado_contado'])*/)/floatval($meta)*100),2).'%</span>';
                    //$json[$i]['RECU_OPCIONES'] =  '<a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true"><span class="fa fa-pencil">Eliminar</span></a>';

                    $i++;
                }

                break;
            case '2':

                $sql_server = new \sql_server();
                $sql_exec = '';

                $sql_exec = "SELECT COBRADOR AS ruta, SUM(MONTO) as recuperado FROM gn_recuperacion T0 WHERE Mes = ".$mes." AND Anno=".$anio." AND COBRADOR NOT IN (".$otroTipoVende_sql_server.") GROUP BY COBRADOR" ;
                //$sql_meta = "CALL sp_recuperacionMeta(".$mes.",".$anio.",".$company_user.", '' )";

                 $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);
                
                foreach ($query as $key) {
                    $meta = meta_recuperacion_exl::where(['fechaMeta'=>$fecha, 'idCompanny'=> $request->session()->get('company_id'), 'ruta' => $key['ruta']])->pluck('meta');
                    //$meta = "CALL sp_recuperacionMeta(".$mes.",".$anio.",".$company_user.", ".$key['ruta']." )";

                     $meta =  str_replace(['[',']'],'',$meta);


                        if ($meta == '' || is_null($meta)) {
                            $meta = '0.00';
                        }else{
                            $meta = $meta;

                        } 
                        

                    $json[$i]['RECU_RUTA']   =  $key['ruta'];
                    $json[$i]['RECU_VENDE']  =  '<span style="text-align: left; float: left" >'.dashboard_model::buscarVendedorXRuta($key['ruta'], $company_user).'</span>';
                    $json[$i]['RECU_META'] =  '<span style="text-align: right; float: right" >C$'.number_format($meta,2).'</span>';

                    $json[$i]['RECU_TOTAL'] =  ($key['recuperado'] == 0) ? '<span id="recu_total_'.$key['ruta'].'" style="text-align: right; float: right">C$0.00</span>' : 'C$'.number_format($key['recuperado'],2);
                    $json[$i]['RECU_CUMPLIMIENTO'] =  ($meta=='0.00') ? '<span id="recu_cumplimiento_'.$key['ruta'].'" style="text-align: right; float: right">0.00%</span>' : '<span id="recu_cumplimiento_'.$key['ruta'].'" style="text-align: right; float: right">'.number_format(((floatval($key['recuperado']) /*+ floatval($key['recuperado_contado'])*/)/floatval($meta)*100),2).'%</span>';
                    $i++;
                }

               
                break;
            case '3':
                dd("Por el momento no hay nada que presentar para la empresa: ". $company->id);
                break; 
            case '4':
              $recuperacion = Umk_recuperacion::where(['fecha_recup'=>$fecha, 'idCompanny' => $request->session()->get('company_id')])->whereNotIn('ruta',$otroTipoVende)->get();

                foreach ($recuperacion as $key) {
                    $meta = meta_recuperacion_exl::where(['fechaMeta'=>$fecha, 'idCompanny'=> $request->session()->get('company_id'), 'ruta' => $key['ruta']])->pluck('meta');

                    $meta =  str_replace(['[',']'],'',$meta);


                        if ($meta == '' || is_null($meta)) {
                            $meta = '0.00';
                        }else{
                            $meta = $meta;

                        } 

                    $json[$i]['RECU_RUTA'] =  $key['ruta'];
                    $json[$i]['RECU_VENDE'] =   '<span style="text-align: left; float: left" >'.$key['vendedor'].'</span>';

                    if($pageName == 'Recuperacion'){
                    $json[$i]['RECU_META'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="'.number_format($meta,2).'" id ="recu_meta_'.$key['ruta'].'">';
                    }else{
                        $json[$i]['RECU_META'] =  '<span style="text-align: right; float: right" >C$'.number_format($meta,2).'</span>';
                    }

                    if ($key['recuperado_credito']>0) {

                        if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CREDITO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="'.number_format($key['recuperado_credito'],2).'" id ="recu_credito_'.$key['ruta'].'">';
                        }else{
                            $json[$i]['RECU_CREDITO'] = '<span style="text-align: right; float: right" >C$'. number_format($key['recuperado_credito'],2).'</span>';
                        }
                     
                    }else{
                        if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CREDITO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="0.00" id ="recu_credito_'.$key['ruta'].'">';
                         }else{
                            $json[$i]['RECU_CREDITO'] =  '<span style="text-align: right; float: right">C$0.00</span>' ;
                         }
                         
                    }
                    if ($key['recuperado_contado']>0) {
                        if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CONTADO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="'.number_format($key['recuperado_contado'],2).'" id ="recu_contado_'.$key['ruta'].'">';
                          }else{
                            $json[$i]['RECU_CONTADO'] =  '<span style="text-align: right; float: right" >C$'. number_format($key['recuperado_contado'],2).'</span>';
                          }
                        
                    }else{
                         if($pageName == 'Recuperacion'){
                            $json[$i]['RECU_CONTADO'] =  '<input type="text" onkeyup="getAttr(this)" style="text-align: right" class="form-control" value="0.00" id ="recu_contado_'.$key['ruta'].'">';
                         }else{
                            $json[$i]['RECU_CONTADO'] =  '<span style="text-align: right; float: right" >C$0.00</span>';

                         }
                        
                    }

                    $json[$i]['RECU_TOTAL'] =  ($key['recuperado_credito'] == 0 && $key['recuperado_contado'] == 0) ? '<span id="recu_total_'.$key['ruta'].'" style="text-align: right; float: right">C$0.00</span>' : '<span id="recu_total_'.$key['ruta'].'" style="text-align: right; float: right">C$'.number_format($key['recuperado_credito'] + $key['recuperado_contado']).'</span>';
                    $json[$i]['RECU_CUMPLIMIENTO'] =  ($meta=='0.00') ? '<span id="recu_cumplimiento_'.$key['ruta'].'" style="text-align: right; float: right">0.00%</span>' : '<span id="recu_cumplimiento_'.$key['ruta'].'" style="text-align: right; float: right">'.number_format(((floatval($key['recuperado_credito']) /*+ floatval($key['recuperado_contado'])*/)/floatval($meta)*100),2).'%</span>';
                    //$json[$i]['RECU_OPCIONES'] =  '<a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true"><span class="fa fa-pencil">Eliminar</span></a>';

                    $i++;
                }

                break;   
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;

        }
        return  $json;        
    }

    public static function getVentasMensuales($xbolsones) {
        $sql_server = new \sql_server();
        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        $i = 0;

        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        
        switch ($company_user) {
            case '1':
            $sql = 'EXECUTE UMK_GN_VENTAS_MENSUALES';
            break;

            case '2':
            $sql = 'EXECUTE GP_GN_VENTAS_MENSUALES';
            break;

            case '3':
            $sql = "";
            break;

            case '4':
            if ($xbolsones) {
                $sql = "EXECUTE INV_GN_VENTAS_MENSUALES_X_BOLSONES";
            }else {
                $sql = "EXECUTE INV_GN_VENTAS_MENSUALES";
            }
            break;

            default:                
            dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
            break;
        }

        $json = array();
        $array = array();
        $query = $sql_server->fetchArray($sql, SQLSRV_FETCH_ASSOC);

        $anioActual = intval(date('Y'));
        $anioLimit = ( $company_user=='1' )?( $anioActual - 2 ):( $anioActual - 1 );

        for ($anio=$anioLimit; $anio<=$anioActual; $anio++) {
            
            foreach ($meses as $key => $mes) {
                $temp = array_column(array_filter($query, function($item) use($mes,$anio) { return $item['anio'] == $anio and $item['mes']==$mes; } ), 'montoVenta');

                ( count($temp)>0 )?( array_push($array, $temp[0])):false;
            }

            $json[$i]['name'] = $anio;
            $json[$i]['venta'] = $array;
            $i++;
            
            $array = array();
        }

        return $json;
        $sql_server->close();
    }

    /******* Add Rodolfo *******/
    public static function getAllClientsByCategory($mes, $anio, $categoria,$xbolsones)
    {
        $sql_server = new \sql_server();
        $sql_exec = '';
        $tem_ = 0;
        $top10 = '';
        $request = Request();
        $json = array();
        $company_user = Company::where('id', $request->session()->get('company_id'))->first()->id;

        switch ($company_user) {
            case '1':
                //Todos
                if ($categoria == 0){
                    $segmentos = "'F02','F03','F04','F05','F06','F07','F08','F09','F10','F11','F13','F14','F15','F20'";
                }
                //Farmacias
                elseif ($categoria == 1){
                    $segmentos = "'F03','F05','F06','F07','F08','F09','F10','F11','F13','F14','F15','F20'";
                }
                //Instituciones
                elseif ($categoria == 2){
                    $segmentos = "'F02'";
                }
                //Mayoristas
                else{
                    $segmentos = "'F04'";
                }

                $sql_exec = "SELECT 
                                [Cod. Cliente] AS codigo,
                                [Nombre del Cliente] AS cliente,
                                isnull(SUM(VENTA),0) AS MontoVenta,
                                isnull(sum(Cantidad),0) As CantidadVenta,Mes,Año
                                FROM
                                    Softland.dbo.VtasTotal_UMK (nolock)
                                WHERE
                                    [Año] = " . $anio . " AND nmes = " . $mes . " AND Ruta IN(" . $segmentos . ") and Ruta NOT IN ('F01', 'F03')
                                AND [P. Unitario] > 0
                                GROUP BY
                                    [Cod. Cliente],[Nombre del Cliente],Mes, Año
                                ORDER BY
                                isnull(SUM(VENTA),0) DESC";

                break;
            case '2':
                //$segmentos = "'F04','F06','F08','F09','F10','F11'";        

                $sql_exec = "SELECT
                [Cod. Cliente] AS codigo,
                [Nombre del Cliente] AS cliente,
                isnull(SUM(VENTA),0) AS MontoVenta,
                isnull(sum(Cantidad),0) As CantidadVenta,Mes,Año
                
                from Softland.dbo.GP_VtasTotal_UMK  (nolock) where " . $mes . "=MONTH(dia) AND " . $anio . "=YEAR(dia) 
                GROUP BY [Cod. Cliente],[Nombre del Cliente],MES,AÑO
                ORDER BY isnull(SUM(VENTA),0) DESC";
                break;
            case '3':
                break;
            case '4':
                $sql_exec ="SELECT
                [Cod. Cliente] AS codigo,
                [Nombre del Cliente] AS cliente,
                isnull(SUM(VENTA),0) AS MontoVenta,
                isnull(sum(Cantidad),0) As CantidadVenta,Mes,Año
                
                from Softland.dbo.INV_VtasTotal_UMK_Temporal  (nolock)where  ".$mes."=MONTH(dia) AND ".$anio."=YEAR(dia) 
                GROUP BY [Cod. Cliente],[Nombre del Cliente],MES,AÑO
                ORDER BY isnull(SUM(VENTA),0) DESC";


                break;
            default:
                dd("Ha sucedido un error al buscar los clientes para esta empresa. " . $company->id);
                break;
        }
        $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

        $json = array();
        $i = 0;

        if (count($query) > 0) {
            foreach ($query as $key) {

                $json[$i]['codigo'] = $key['codigo'];
                $json[$i]['cliente'] = $key['cliente'];

                if ($company_user == 4) {
                    $tem_ = ($xbolsones) ? intval($key['CantidadVenta']) : intval($key['MontoVenta']);

                } else {
                    $tem_ = intval($key['MontoVenta']);
                }

                $json[$i]['data_innova'] = $tem_;
                $i++;
            }
        }
        return $json;
        $sql_server->close();
}

    public static function getRealVentasMensuales($xbolsones,$Segmento) {
        $sql_server = new \sql_server();
        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
        $meta = array();
        $real = array();
        $fechaCorte = array();
        $anio = intval( date('Y') );
        $qSegmento ="";
        $View  = "VtasTotal_UMK";

        $Filtros ="AND Ruta NOT IN('F01', 'F12')";



        if ($Segmento==0) {
            //TODAS LOS SEGMENTOS
            $qSegmento =" AND Ruta NOT IN ('F01','F12') ";

        } else {
            if ($Segmento==1) {
                //TODAS LAS RUTAS DEL SEGMENTO FARMACIA
                $qSegmento =" AND Ruta NOT IN ('F04','F02','F01','F12') ";
            } else {
                if ($Segmento==2) {
                   //TODAS LAS RUTAS DEL SEGMENTO MAYORISTA
                    $qSegmento =" AND Ruta IN ('F04') ";
                } else {
                    if ($Segmento==3) {
                       //TODAS LAS RUTAS DEL SEGMENTO INSTITUCION
                        $qSegmento =" AND Ruta IN ('F02') ";
                    }
                    
                }
                
            }
        }

        $campo = ($xbolsones==0) ? "CANTIDAD" : "venta" ;

        if ($company_user != 1 ) {
            $Filtros   ="";
            $qSegmento ="";
            $View = ($company_user==2) ? "GP_VtasTotal_UMK" : "INV_VtasTotal_UMK_Temporal" ;
        }

        $sql_exec = "SELECT ISNULL( CAST( SUM(".$campo.") AS FLOAT), 0 ) AS montoVenta, nMes AS mes 
                    FROM Softland.dbo.".$View." (nolock)
                    WHERE [Año] IN (YEAR(GETDATE())) AND [P. Unitario] > 0 ".$Filtros.$qSegmento."
                    GROUP BY Mes,Año,nMes
                    ORDER BY nMes";
        $qReal = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

        $sql_meta = "SELECT ISNULL( CAST( SUM(T0.val) AS FLOAT ), 0 ) AS meta, MONTH(T1.Fecha) AS mes
                    FROM DESARROLLO.dbo.gn_cuota_x_productos T0 INNER JOIN DESARROLLO.dbo.metacuota_GumaNet T1 ON T0.IdPeriodo = T1.IdPeriodo
                    WHERE YEAR(T1.Fecha) = YEAR(GETDATE()) AND T1.IdCompany = $company_user ".str_replace('Ruta','CodVendedor',$qSegmento)."
                    GROUP BY MONTH(T1.Fecha)
                    ORDER BY MONTH(T1.Fecha)";

        $qMeta = $sql_server->fetchArray($sql_meta, SQLSRV_FETCH_ASSOC);
        
        
        $sql_tendencia ="SELECT CAST( ( AVG ( T0.SubTotal ) * 21 ) AS FLOAT ) montoVenta,T0.mes 
                            FROM( SELECT nMes AS mes, SUM ( ".$campo." ) SubTotal FROM Softland.dbo.".$View." ( nolock ) WHERE YEAR([Dia]) = YEAR(GETDATE())
                                    AND [P. Unitario] > 0 ".$Filtros.$qSegmento."
                                    GROUP BY nMes,DAY ( Dia ) 
                                ) T0 GROUP BY T0.mes;";
        
        
        $qTend = $sql_server->fetchArray($sql_tendencia, SQLSRV_FETCH_ASSOC);

        foreach ($qReal as $key) {

            $Numero_mes = $key['mes'];
            $temporal_Meta = array_column(array_filter($qMeta, function($item) use($Numero_mes) { return $item['mes']==$Numero_mes; } ), 'meta');
            ( count($temporal_Meta)>0 )?( array_push($meta, $temporal_Meta[0])):array_push($meta, 0); 
            
            $temporal_Tend = array_column(array_filter($qTend, function($row) use($Numero_mes) { return $row['mes']==$Numero_mes; } ), 'montoVenta');
            ( count($temporal_Tend)>0 )?( array_push($fechaCorte, $temporal_Tend[0])):array_push($fechaCorte, 0);

            array_push($real, $key['montoVenta']);
        }

        $array[0]['title'] = 'Meta';
        $array[0]['data'] = $meta;

        $array[1]['title'] = 'Tendencia';
        $array[1]['data'] = $fechaCorte;

        $array[2]['title'] = 'Real';
        $array[2]['data'] = $real;

        return $array;
    }
}
