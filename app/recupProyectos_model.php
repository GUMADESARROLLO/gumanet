<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\proyectos_model;
use App\proyectosDetalle_model;
use DB;

class recupProyectos_model extends Model {
	
    public static function returnDataVentas($anio1, $mes1, $anio2, $mes2) {
		$sql_server = new \sql_server();
		$sql_exec;
		$request = Request();
		$json = array();
		$json2 = array();
		$json3 = array();
		
		$segmentos = array("Institucional","Mayoristas","Rutas","Interno");
		$rutasInstitucionales = array("F02");
		$rutasMayoristas = array("F04");
		$rutasDetalle = array('F03','F05','F06','F07','F08','F09','F10','F11','F13','F14','F17','F20');
		$gerencia = array('F15');
		$company_user = Company::where('id',$request->session()->get('company_id'))->first()->id;
		$total1 = $total2 = 0;

		$a=0;
		$e=0;
		$i=0;

		switch ($company_user) {
			case '1':
			$sql_exec = DB::select('call RECUP_VENDOR_MES_AÑO(?, ?, ?, ?)',array($anio1, $anio2, $mes1, $mes2));
				//$sql_exec = "EXEC  ".$anio1.",".$anio2.",".$mes1.",".$mes2;
				
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
				dd("Ups... Al parecer sucedio un error. ". $company->id);
			break;
		}



		//$query = $sql_server->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);
		$query = json_decode(json_encode($sql_exec), true);
		//dd($query);

		foreach($query as $q){
			$ruta  = $q['RUTA'];
		
			

			$tempo = array_filter( $query, function($item) use($ruta) { return $item['RUTA']==$ruta; } );

			//los datos de cada segmento se almacena en un array independiente para luego agragarlo a un solo array de manera ordenada por segmento para que el datatable en el archivo javascript los mueste de manera ordenada, de lo contrario habra multiples segmentos del mismo tipo en la tabla
			if(in_array($ruta,$rutasInstitucionales)){#Agregando el segmento segun la ruta
				if (array_search( $q['RUTA'], array_column( $json, 'ruta' ) ) === false){#si no encuentra la ruta en la columna ruta del array json agrega nueva ruta ejecutando el siguiente codigo, si encuentra no almacena
					$json[$a]['ruta'] 			= $q["RUTA"];
					$json[$a]['nombre'] 		= $q["NOMBRE"];
					$json[$a]['zona'] 			= $segmentos[0];
					$json[$a]['groupColumn'] 	= '<center><b>' . strtoupper($segmentos[0]) . '</b></center>';
					$json[$a]['data']	= (new static)->recuperado($mes1, $anio1, $mes2, $anio2, $tempo);
					$a++;
				}

			}else if(in_array($ruta,$rutasMayoristas)){
				if (array_search( $q['RUTA'], array_column( $json2, 'ruta' ) ) === false){#si no encuentra la ruta en la columna ruta del array json2 agrega nueva ruta ejecutando el siguiente codigo 
					$json2[$e]['ruta'] 			= $q["RUTA"];
					$json2[$e]['nombre'] 		= $q["NOMBRE"];
					$json2[$e]['zona'] 			= $segmentos[1];
					$json2[$e]['groupColumn'] 	= '<center><b>' . strtoupper($segmentos[1]) . '</b></center>';
					$json2[$e]['data']	=  (new static)->recuperado($mes1, $anio1, $mes2, $anio2, $tempo);
					$e++;
				}

			}else if(in_array($ruta,$rutasDetalle)){
				if (array_search( $q['RUTA'], array_column( $json3, 'ruta' ) ) === false){#si no encuentra la ruta en la columna ruta del array json3 agrega nueva ruta ejecutando el siguiente codigo 
					$json3[$i]['ruta'] 			= $q["RUTA"];
					$json3[$i]['nombre'] 		= $q["NOMBRE"];
					$json3[$i]['zona'] 			= $segmentos[2];
					$json3[$i]['groupColumn'] 	= '<center><b>' . strtoupper($segmentos[2]) . '</b></center>';
					$json3[$i]['data']	=  (new static)->recuperado($mes1, $anio1, $mes2, $anio2, $tempo);
					$i++;
				}
			}
			
			
		}
		
		
		$jsonOrdenado = array();
		$jsonOrdenado = array_merge($json, $json2, $json3);# agregar datos de un array a un array existente con datos para que los datos queden ordenados por segmentos (institucional, mayoriatas, ruta y gerencia)

		
		
		return $jsonOrdenado;

			
	}

	private function recuperado($mes1, $anio1, $mes2, $anio2, $tempo){

		$datos = array(
			'mes1' => array(
				'anioActual' => array_sum(array_column(array_filter( $tempo, function($item) use($mes1, $anio1) { return $item['nMes']==$mes1 and $item['ANIO']==$anio1; } ),'RECUPERADO')),
				'anioAnterior' => array_sum(array_column(array_filter( $tempo, function($item) use($mes1, $anio2) { return $item['nMes']==$mes1 and $item['ANIO']==$anio2; } ),'RECUPERADO'))
			),
			'mes2' => array(
				'anioActual' => array_sum(array_column(array_filter( $tempo, function($item) use($mes2, $anio1) { return $item['nMes']==$mes2 and $item['ANIO']==$anio1; } ),'RECUPERADO')),
				'anioAnterior' => array_sum(array_column(array_filter( $tempo, function($item) use($mes2, $anio2) { return $item['nMes']==$mes2 and $item['ANIO']==$anio2; } ),'RECUPERADO'))
			),
		);
		return $datos;
	}

	public static function listarProyectos() {
		return $this->hasMany('App\Models\proyectos_model');
	}
}
