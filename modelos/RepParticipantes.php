<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

/****************************** Participantes ****************************************/
 
function getReporte_Participantes($fechaini,$fechafin,$corredor,$tipoparticipante){
	$query=" select DISTINCT participantes.IDMUNICIPIO,participantes.IDCOMUNIDAD,
							(select municipio.NOMBREMUN from municipio where municipio.IDMUNICIPIO=participantes.IDMUNICIPIO)nombremun,
							(select comunidad.NOMBRECOMUNIDAD from comunidad where comunidad.IDCOMUNIDAD=participantes.IDCOMUNIDAD)nombrecom	,
						    sum(case when PRIMERAVEZASI=1  then 1 else 0 end) as nuevos
					  from participantes
					  INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
					  inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
					  inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
					  inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
			 ";

	if($fechaini  and  $fechafin){
		$query= $query.' and asistencias.FECHAASI between "'.$fechaini.'" and "'.$fechafin.'" ';
	}else{
		if($fechaini ){
		$query= $query.' and asistencias.FECHAASI >="'.$fechaini.'"  ';
	}

		if($fechafin ){
			$query= $query.' and asistencias.FECHAASI <="'.$fechafin.'"  ';
		}		
	}
	if($corredor){
		$query = $query.' and corredor.IDCORREDOR='.$corredor;		
	}
	if($tipoparticipante){
		$query = $query.' AND participantes.IDTIPOPARTICIPANTE='.$tipoparticipante;
	}
	
 	$query= $query.' GROUP BY participantes.IDMUNICIPIO,participantes.IDCOMUNIDAD';
	
 	 $rs = ejecutarConsulta($query);
  	 //	return $query;
		return $rs;
 }
/* */

 function getReporte_ParticipantesDetalle($fechaini,$fechafin,$idmunicipio, $idcomunidad, $factorbusqueda,$corredor,$tipoparticipante){
	$query="select COUNT(DISTINCT(participantes.IDPARTICIPANTE))Nparticipantes
					  from participantes
					  INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
             where   participantes.IDMUNICIPIO='".$idmunicipio."' and participantes.IDCOMUNIDAD='".$idcomunidad."'    "; 
 
 	if($fechaini  and  $fechafin){
		$query= $query.' and asistencias.FECHAASI between "'.$fechaini.'" and "'.$fechafin.'" ';
	}else{
		if($fechaini ){
		$query= $query.' and asistencias.FECHAASI >="'.$fechaini.'"  ';
	}

		if($fechafin ){
			$query= $query.' and asistencias.FECHAASI <="'.$fechafin.'"  ';
		}		
	}
	if($corredor){
		$query = $query.' and corredor.IDCORREDOR='.$corredor;		
	}
	if($tipoparticipante){
		$query = $query.' AND participantes.IDTIPOPARTICIPANTE='.$tipoparticipante;
	}
 
 switch( $factorbusqueda){
	 case 'M':
	 	$query=$query." and participantes.SEXOPAR  = 'M' ";
	 break;
	 case 'F':
	 	$query=$query." and participantes.SEXOPAR  = 'F' ";
	 break;
	 case 'A0':
	 $query=$query." and (participantes.EDADPAR IS NULL or participantes.EDADPAR =0) ";
	 break;
     case 'A1_9':$query=$query." and participantes.EDADPAR between  1 and 9  ";
	 break;
     case 'A10_14':$query=$query." and participantes.EDADPAR between  10 and 14  ";
	 break;
     case 'A15_19':$query=$query." and participantes.EDADPAR between  15 and 19  ";
	 break;
     case 'A20_24':$query=$query." and participantes.EDADPAR between  20 and 24  ";
	 break;
     case 'A25_29':$query=$query." and participantes.EDADPAR between  25 and 29  ";
	 break;
     case 'A30MAS':$query=$query." and participantes.EDADPAR>=30 ";
	 break;
	 
	 }
      $rs = ejecutarConsultaSimpleFila($query);
      
       //return $query;
	 
		return $rs['Nparticipantes'];
 }
 function selectTipoParticipante(){ 
    $sql="SELECT * FROM tipoparticipante";
    return ejecutarConsulta($sql);
}	
 function selectCorredor(){
    $sql="SELECT * FROM corredor WHERE ESTADOCOR=1";
    return ejecutarConsulta($sql);
}
?>