<?php 
 require "../config/Conexion.php";
 function RepReporteGeneral( $fechaini,  $fechafin){
     
					/*(select GROUP_CONCAT( grupo.NOMBREGRUPO) from grupo inner join participantegrupo on grupo.IDGRUPO= participantegrupo.IDGRUPO where participantegrupo.IDPARTICIPANTE=participantes.IDPARTICIPANTE )'' AS grupos,*/
   $sql = "SELECT actividades.IDACTIVIDADES,  
					actividades.NOMBREACT,
					actividades.LUGARACT,
					actividades.OBJETIVOACT,
					responsables.NOMBRERES,
					DATE_FORMAT(actividades.FECHAINICIOACT,'%d-%m-%Y')fechaini,
					DATE_FORMAT(jornadas.FECHAJOR,'%d-%m-%Y')fechafin  ,
					CONCAT( HORAINIJOR ,'-' ,HORAFINJOR) horario,
					DATE_FORMAT(TIMEDIFF( HORAFINJOR,HORAINIJOR ),'%H')horas,
					jornadas.IDJORNADAS,
					jornadas.NOMBREJOR,
					participantes.IDPARTICIPANTE,
					concat_ws(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE, participantes.PRIMERAPELLIDO, participantes.SEGUNDOSAPELLIDO)nombrepar,
					participantes.SEXOPAR,
					participantes.EDADPAR,
					participantes.OCUPACIONPAR,
					(select tipoparticipante.NOMBRETIP from tipoparticipante where tipoparticipante.idtipoparticipante =  participantes.IDTIPOPARTICIPANTE)participantetipo,
					(select a.NOMBRETAC from tipoactividad a INNER JOIN categoriaactividad b on a.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD where b.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD)tipoactividad,
					(select municipio.NOMBREMUN from municipio where municipio.IDMUNICIPIO=participantes.IDMUNICIPIO) AS nombremun,
					corredor.NOMBRECOR,
					(select comunidad.NOMBRECOMUNIDAD from comunidad where comunidad.idcomunidad=participantes.idcomunidad)nombrecomunidad,
					(select institucion.NOMBREINS from institucion where institucion.IDINSTITUCION=actividades.IDINSTITUCION) nombreinstitucion,
					(select (select tipoinstitucion.NOMBRETIN from tipoinstitucion where tipoinstitucion.IDTIPOINSTITUCION=institucion.IDTIPOINSTITUCION limit 0,1)   from institucion where institucion.IDINSTITUCION=actividades.IDINSTITUCION)tipoinstitu,
					participantes.ESCUELAPAR,
					(select distinct institucion.ZONAINS FROM institucion where participantes.IDINSTITUCION=institucion.IDINSTITUCION)zona										
			from participantes
			INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
			inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
			inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
			inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
			INNER JOIN responsables on responsables.CODIGORES=actividades.CODIGORES
			WHERE 1";

   if($fechaini and   $fechafin){
	   $sql = $sql.'  and  jornadas.FECHAJOR between  "'.$fechaini.'" and "'.$fechafin.'"'; 
	   } 
	   else{
		   if($fechaini){
			   $sql = $sql.'  and  jornadas.FECHAJOR >=  "'.$fechaini.'" '; 
			   }
		   if($fechafin){
			   $sql = $sql.'  and  jornadas.FECHAJOR <=  "'.$fechafin.'" '; 
			   }
		   } 
	
    return ejecutarConsulta($sql);
 
	}

?>