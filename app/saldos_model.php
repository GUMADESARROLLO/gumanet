<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class saldos_model extends Model {
    
    public static function rutas(){
         $sql_server = new \sql_server();

        $sql_exec = '';
        $request = Request();
        $company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

        switch ($company_user) {
            case '1':
                $sql_exec = " SELECT * FROM UMK_VENDEDORES_ACTIVO ";
                break;
            case '2':
                $sql_exec = " SELECT * FROM GP_VENDEDORES_ACTIVOS ";
                break;
            case '3':
                return false;
                break;
            case '4':
                $sql_exec = " SELECT * FROM INV_VENDEDORES_ACTIVOS ";
                break;
            default:                
                dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
                break;
        }

         $query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

        if( count($query)>0 ){
            return $query;
        }

        $sql_server->close();
        return false;
    }

    public static function saldosAll($ruta) {
		$sql_server = new \sql_server();
		$temp = array();
		$i=0;

		$sql_exec = '';
		$request = Request();
		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

		switch ($company_user) {
			case '1':
			    $sql_exec = 
			    "SELECT
					m.VENDEDOR AS RUTA,
					ISNULL(( SELECT T.NOMBRE FROM UMK_VENDEDORES_ACTIVO T WHERE T.VENDEDOR=m.VENDEDOR ), 'ND') AS NOMBRE,
					SUM (m.NoVencidos) AS N_VENCIDOS,
					(SUM(m.Dias30) + SUM(m.Dias60) + SUM(m.Dias90) + SUM(m.Dias120) + SUM(m.Mas120)) AS VENCIDO
				FROM
					GMV_ClientesPerMora m
				GROUP BY
					VENDEDOR";
			break;
			case '2':
			    $sql_exec = 
			    "SELECT
			    m.VENDEDOR AS RUTA,
			    ISNULL(( SELECT T.NOMBRE FROM GP_VENDEDORES_ACTIVOS T WHERE T.VENDEDOR=m.VENDEDOR ), 'ND') AS NOMBRE, SUM (m.NoVencidos) AS N_VENCIDOS,
					(SUM(m.Dias30) + SUM(m.Dias45)+ SUM(m.Dias60) + SUM(m.Dias90) + SUM(m.Dias120) + SUM(m.Dias150) + SUM(m.Mas150)) AS VENCIDO
				FROM
					GP_View_ClientesPerMora m
				GROUP BY
					VENDEDOR";
			    break;
			case '3':
			    return false;
			    break;
			case '4':
			    $sql_exec = 
			    "SELECT
					m.VENDEDOR AS RUTA,
					ISNULL(( SELECT T.NOMBRE FROM INV_VENDEDORES_ACTIVOS T WHERE T.VENDEDOR=m.VENDEDOR ), 'ND') AS NOMBRE,
					SUM (m.NoVencidos) AS N_VENCIDOS,
					(SUM(m.Dias30) + SUM(m.Dias60) + SUM(m.Dias90) + SUM(m.Dias120) + SUM(m.Mas120)) AS VENCIDO
				FROM
					INN_ClientesPerMora m
				GROUP BY
					VENDEDOR";
			    break;
			default:                
			    dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
			    break;
		}

		$query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC); 

		if( count($query)>0 ) {
			foreach ($query as $key) {
	    		$temp[$i]['OPC'] 		= '<a id="exp_more" class="exp_more" href="#!"><i class="material-icons expan_more">expand_more</i></a>';
				$temp[$i]['RUTA'] 		= $key['RUTA'];
				$temp[$i]['RUTA01'] 	= '<p class="font-weight-bold text-info">'.$key['RUTA'].'</p>';
				$temp[$i]['NOMBRE'] 	= '<p class="font-weight-bold">'.$key['NOMBRE'].'</p>';
				$temp[$i]['N_VENCIDO'] 	= 'C$ '.number_format($key['N_VENCIDOS'], 2);
				$temp[$i]['VENCIDO'] 	= 'C$ '.number_format($key['VENCIDO'], 2);
				$i++;
			}
		}

		$sql_server->close();
		return $temp;
    }

    public static function saldosXRuta($ruta) {
		$sql_server = new \sql_server();
		$tmp = array();

		$sql_exec = '';
		$request = Request();
		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;

		switch ($company_user) {
			case '1':
			    $sql_exec = 
			    "SELECT
					SUM (m.NoVencidos) AS N_VENCIDOS,
					SUM (m.Dias30) AS Dias30,
					SUM (m.Dias60) AS Dias60,
					SUM (m.Dias90) AS Dias90,
					SUM (m.Dias120) AS Dias120,
					SUM (m.Mas120) AS Mas120,
					m.VENDEDOR
				FROM
					GMV_ClientesPerMora m
				WHERE
					VENDEDOR = '".$ruta."'
				GROUP BY
					VENDEDOR";
			break;
			case '2':
			    $sql_exec = 
			    "SELECT
					SUM (m.NoVencidos) AS N_VENCIDOS,
					SUM (m.Dias30) AS Dias30,
					SUM (m.Dias45) AS Dias45,
					SUM (m.Dias60) AS Dias60,
					SUM (m.Dias90) AS Dias90,
					SUM (m.Dias120) AS Dias120,
					SUM (m.Dias150) AS Dias150,
					SUM (m.Mas150) AS Mas150,
					m.VENDEDOR
				FROM
					GP_View_ClientesPerMora m
				WHERE
					VENDEDOR = '".$ruta."'
				GROUP BY
					VENDEDOR";
			    break;
			case '3':
			    return false;
			    break;
			case '4':
			    $sql_exec = 
			    "SELECT
					SUM (m.NoVencidos) AS N_VENCIDOS,
					SUM (m.Dias30) AS Dias30,
					SUM (m.Dias60) AS Dias60,
					SUM (m.Dias90) AS Dias90,
					SUM (m.Dias120) AS Dias120,
					SUM (m.Mas120) AS Mas120,
					m.VENDEDOR
				FROM
					INN_ClientesPerMora m
				WHERE
					VENDEDOR = '".$ruta."'
				GROUP BY
					VENDEDOR";
				
			    break;  
			default:                
			    dd("Ups... al parecer sucedio un error al tratar de encontrar articulos para esta empresa. ". $company->id);
			    break;
		}

		$query = $sql_server->fetchArray($sql_exec, SQLSRV_FETCH_ASSOC);

		if( count($query)>0 ) {

			if ($company_user==1 || $company_user==4) {
				$tmp[0]['desc'] 	= 'N_VENCIDOS';
 				$tmp[0]['value'] 	= $query[0]['N_VENCIDOS'];

				$tmp[1]['desc'] 	= '30 Días';
 				$tmp[1]['value'] 	= $query[0]['Dias30'];

				$tmp[2]['desc'] 	= '60 Días';
 				$tmp[2]['value'] 	= $query[0]['Dias60'];

				$tmp[3]['desc'] 	= '90 Días';
 				$tmp[3]['value'] 	= $query[0]['Dias90'];

				$tmp[4]['desc'] 	= '120 Días';
 				$tmp[4]['value'] 	= $query[0]['Dias120'];

				$tmp[5]['desc'] 	= 'más 120 Días';
 				$tmp[5]['value'] 	= $query[0]['Mas120'];

			}elseif ($company_user==2) {
				$tmp[0]['desc'] 	= 'N_VENCIDOS';
 				$tmp[0]['value'] 	= $query[0]['N_VENCIDOS'];

				$tmp[1]['desc'] 	= '30 Días';
 				$tmp[1]['value'] 	= $query[0]['Dias30'];

				$tmp[2]['desc'] 	= '45 Días';
 				$tmp[2]['value'] 	= $query[0]['Dias45'];

				$tmp[3]['desc'] 	= '60 Días';
 				$tmp[3]['value'] 	= $query[0]['Dias60'];

				$tmp[4]['desc'] 	= '90 Días';
 				$tmp[4]['value'] 	= $query[0]['Dias90'];

				$tmp[5]['desc'] 	= '120 Días';
 				$tmp[5]['value'] 	= $query[0]['Dias120'];

				$tmp[6]['desc'] 	= '150 Días';
 				$tmp[6]['value'] 	= $query[0]['Dias150'];

				$tmp[7]['desc'] 	= 'más 150 Días';
 				$tmp[7]['value'] 	= $query[0]['Mas150'];
			}

			return $tmp;
		}

		$sql_server->close();
		return false;
		
    }
}
