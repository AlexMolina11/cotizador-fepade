<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

/****************************** Vulnerables ****************************************/
 
function getReporteVulnerables($fechaini,$fechafin){
	$query="SELECT corredor.IDCORREDOR,
            corredor.NOMBRECOR,comunidad.IDCOMUNIDAD,
            comunidad.NOMBRECOMUNIDAD,institucion.IDINSTITUCION,
            institucion.NOMBREINS
            from asistencias
            inner join participantes on asistencias.IDPARTICIPANTE= participantes.IDPARTICIPANTE
            inner join corredor on corredor.NOMBRECOR=participantes.CORREDORPAR
            inner join comunidad on comunidad.IDCOMUNIDAD= participantes.IDCOMUNIDAD
            inner join institucion on institucion.IDINSTITUCION = participantes.IDINSTITUCION";

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
 	$query= $query.' GROUP BY corredor.IDCORREDOR,comunidad.IDCOMUNIDAD,institucion.IDINSTITUCION';
	
 	 $rs = ejecutarConsulta($query);
  	 //	return $query;
		return $rs;
 }
/* */

 function getReporteDetalle($fechaini,$fechafin,$corredor,$comunidad,$institucion,$factorbusqueda,$nuevos){
	$query="SELECT
    IFNULL(
     if(sum(hour(jornadas.HORAFINJOR)-hour(jornadas.HORAINIJOR))>=8,
                         truncate(sum(hour(jornadas.HORAFINJOR)-hour(jornadas.HORAINIJOR))/8,0),0),0)Nparticipantes
            from asistencias
            inner join jornadas on jornadas.IDJORNADAS = asistencias.IDJORNADAS
            inner join participantes on asistencias.IDPARTICIPANTE= participantes.IDPARTICIPANTE
            inner join corredor on corredor.NOMBRECOR=participantes.CORREDORPAR
            inner join comunidad on comunidad.IDCOMUNIDAD= participantes.IDCOMUNIDAD
            inner join institucion on institucion.IDINSTITUCION = participantes.IDINSTITUCION
            where  corredor.IDCORREDOR=".$corredor." and comunidad.IDCOMUNIDAD=".$comunidad." and institucion.IDINSTITUCION=".$institucion; 
 
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
    
    if($nuevos){
        $query= $query."  AND asistencias.PRIMERAVEZASI=1";
    }
 
 switch( $factorbusqueda){
	 /*case 'M':
	 	$query=$query." and participantes.SEXOPAR  = 'M' ";
	 break;
	 case 'F':
	 	$query=$query." and participantes.SEXOPAR  = 'F' ";
	 break;*/
	 case 'A0M':
	 $query=$query." and participantes.SEXOPAR  = 'M' and (participantes.EDADPAR IS NULL or participantes.EDADPAR =0) ";
	 break;
     case 'A1_17M':$query=$query." and participantes.SEXOPAR  = 'M' and participantes.EDADPAR between  1 and 17  ";
	 break;
     case 'A18M':$query=$query." and participantes.SEXOPAR  = 'M' and participantes.EDADPAR>=18  ";
	 break;   
	  case 'A0F':
	 $query=$query." and participantes.SEXOPAR  = 'F' and (participantes.EDADPAR IS NULL or participantes.EDADPAR =0) ";
	 break;
     case 'A1_17F':$query=$query." and participantes.SEXOPAR  = 'F' and participantes.EDADPAR between  1 and 17  ";
	 break;
     case 'A18F':$query=$query." and participantes.SEXOPAR  = 'F' and participantes.EDADPAR>=18  ";
	 break;   
	 
     }
     //echo $query;
      $rs = ejecutarConsultaSimpleFila($query);
      
       //return $query;
	 
		return $rs['Nparticipantes'];
 }

?>