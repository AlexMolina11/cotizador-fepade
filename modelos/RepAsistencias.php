<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class RepAsistencias{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementar un método para mostrar todos los registros
    public function listar($fechaini,$fechafin,$actividades,$tipoactividad,$tipoparticipante){
	  
		if($tipoparticipante!=0 and $tipoactividad!=0 and $actividades!=0 and $fechaini!='' and  $fechafin!=''){

         $sql = "select participantes.IDPARTICIPANTE,
						CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
						participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
						participantes.SEXOPAR,
						participantes.EDADPAR,
						(select count(asistencias.IDJORNADAS))cantidadjorandas,
						(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
				FROM participantes
				INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
				inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
				INNER JOIN categoriaactividad on categoriaactividad.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD
				inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
				where SEXOPAR is not null AND ESTADOPAR=1 and participantes.IDTIPOPARTICIPANTE= $tipoparticipante
				and categoriaactividad.IDTIPOACTIVIDAD = $tipoactividad and actividades.IDACTIVIDADES= $actividades
				and DATE(jornadas.FECHAJOR) between '$fechaini' and '$fechafin'
				GROUP BY participantes.IDPARTICIPANTE"; 
		}
	    else if($actividades!=0 and $fechaini!='' and  $fechafin!=''){
			$sql = "select participantes.IDPARTICIPANTE,
							CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
							participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
							participantes.SEXOPAR,
							participantes.EDADPAR,
							(select count(asistencias.IDJORNADAS))cantidadjorandas,
							(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
					FROM participantes
					INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
					inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
					inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
					inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
					where SEXOPAR is not null AND ESTADOPAR=1 and actividades.IDACTIVIDADES= $actividades
					and DATE(jornadas.FECHAJOR) between '$fechaini' and '$fechafin'
					GROUP BY participantes.IDPARTICIPANTE"; 
			  }
		else if($tipoactividad!=0 and $fechaini!='' and  $fechafin!=''){
			$sql = "select participantes.IDPARTICIPANTE,
							CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
							participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
							participantes.SEXOPAR,
							participantes.EDADPAR,
							(select count(asistencias.IDJORNADAS))cantidadjorandas,
							(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
					FROM participantes
					INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
					inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
					inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
					INNER JOIN categoriaactividad on categoriaactividad.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD
					inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
					where SEXOPAR is not null AND ESTADOPAR=1 and categoriaactividad.IDTIPOACTIVIDAD = $tipoactividad 
					and DATE(jornadas.FECHAJOR) between '$fechaini' and '$fechafin'
					GROUP BY participantes.IDPARTICIPANTE";    
			  
		} 
		else if($tipoparticipante!=0 and $fechaini!='' and  $fechafin!='') { 
		$sql = "select participantes.IDPARTICIPANTE,
						CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
						participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
						participantes.SEXOPAR,
						participantes.EDADPAR,
						(select count(asistencias.IDJORNADAS))cantidadjorandas,
						(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
				FROM participantes
				INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
				inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
				inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
				where SEXOPAR is not null AND ESTADOPAR=1 and participantes.IDTIPOPARTICIPANTE= $tipoparticipante				
				and DATE(jornadas.FECHAJOR) between '$fechaini' and '$fechafin'
				GROUP BY participantes.IDPARTICIPANTE"; 
		} 
		else if($tipoparticipante!=0 and $tipoactividad!=0 and  $actividades!=0) { 
		$sql = "select participantes.IDPARTICIPANTE,
						   CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
						   participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
						   participantes.SEXOPAR,
						   participantes.EDADPAR,
						   (select count(asistencias.IDJORNADAS))cantidadjorandas,
						   (select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
				   FROM participantes
				   INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				   inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
				   inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
				   INNER JOIN categoriaactividad on categoriaactividad.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD
				   inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
				   where SEXOPAR is not null AND ESTADOPAR=1 and actividades.IDACTIVIDADES=$actividades
				   and categoriaactividad.IDTIPOACTIVIDAD = $tipoactividad
				   and participantes.IDTIPOPARTICIPANTE= $tipoparticipante
				   GROUP BY participantes.IDPARTICIPANTE"; 
		} 
		else if($tipoparticipante!=0 and $tipoactividad!=0){

			$sql = "select participantes.IDPARTICIPANTE,
						   CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
						   participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
						   participantes.SEXOPAR,
						   participantes.EDADPAR,
						   (select count(asistencias.IDJORNADAS))cantidadjorandas,
						   (select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
				   FROM participantes
				   INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				   inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
				   inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
				   INNER JOIN categoriaactividad on categoriaactividad.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD
				   inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
				   where SEXOPAR is not null AND ESTADOPAR=1 and participantes.IDTIPOPARTICIPANTE= $tipoparticipante
				   and categoriaactividad.IDTIPOACTIVIDAD = $tipoactividad
				   GROUP BY participantes.IDPARTICIPANTE"; 
		   }
		else if($actividades!=0 and $tipoactividad!=0){

			$sql = "select participantes.IDPARTICIPANTE,
						   CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
						   participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
						   participantes.SEXOPAR,
						   participantes.EDADPAR,
						   (select count(asistencias.IDJORNADAS))cantidadjorandas,
						   (select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
				   FROM participantes
				   INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				   inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
				   inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
				   INNER JOIN categoriaactividad on categoriaactividad.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD
				   inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
				   where SEXOPAR is not null AND ESTADOPAR=1 and actividades.IDACTIVIDADES=$actividades
				   and categoriaactividad.IDTIPOACTIVIDAD = $tipoactividad
				   GROUP BY participantes.IDPARTICIPANTE"; 
		   }
		else if($fechaini!='' and  $fechafin!='') { 
			$sql = "select participantes.IDPARTICIPANTE,
							CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
							participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
							participantes.SEXOPAR,
							participantes.EDADPAR,
							(select count(asistencias.IDJORNADAS))cantidadjorandas,
							(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
					FROM participantes
					INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
					inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
					inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
					inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
					where SEXOPAR is not null AND ESTADOPAR=1 and DATE(jornadas.FECHAJOR) between '$fechaini' and '$fechafin'
					GROUP BY participantes.IDPARTICIPANTE"; 
			} 
		else if($tipoparticipante!=0) { 
				$sql = "select participantes.IDPARTICIPANTE,
								CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
								participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
								participantes.SEXOPAR,
								participantes.EDADPAR,
								(select count(asistencias.IDJORNADAS))cantidadjorandas,
								(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
						FROM participantes
						INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
						inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
						inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
						inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
						where SEXOPAR is not null AND ESTADOPAR=1 and participantes.IDTIPOPARTICIPANTE= $tipoparticipante					
						GROUP BY participantes.IDPARTICIPANTE"; 
				} 
		else if($tipoactividad!=0){
					$sql = "select participantes.IDPARTICIPANTE,
									CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
									participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
									participantes.SEXOPAR,
									participantes.EDADPAR,
									(select count(asistencias.IDJORNADAS))cantidadjorandas,
									(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
							FROM participantes
							INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
							inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
							inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
							INNER JOIN categoriaactividad on categoriaactividad.IDCATEGORIAACTIVIDAD=actividades.IDCATEGORIAACTIVIDAD
							inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
							where SEXOPAR is not null AND ESTADOPAR=1 and categoriaactividad.IDTIPOACTIVIDAD = $tipoactividad						
							GROUP BY participantes.IDPARTICIPANTE"; 				  
				} 
		else if($actividades!=0){
					$sql = "select participantes.IDPARTICIPANTE,
									CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
									participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
									participantes.SEXOPAR,
									participantes.EDADPAR,
									(select count(asistencias.IDJORNADAS))cantidadjorandas,
									(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
							FROM participantes
							INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
							inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
							inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
							inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
							where SEXOPAR is not null AND ESTADOPAR=1 and actividades.IDACTIVIDADES= $actividades							
							GROUP BY participantes.IDPARTICIPANTE"; 
					  }
        else { 
			$sql = "select participantes.IDPARTICIPANTE,
							CONCAT_WS(' ',participantes.PRIMERNOMBRE,participantes.SEGUNDOSNOMBRE,
							participantes.PRIMERAPELLIDO,participantes.SEGUNDOSAPELLIDO) nombre,
							participantes.SEXOPAR,
							participantes.EDADPAR,
							(select count(asistencias.IDJORNADAS))cantidadjorandas,
							(select ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) )sumahoras
					FROM participantes
					INNER JOIN asistencias on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
					inner join jornadas on asistencias.IDJORNADAS= jornadas.IDJORNADAS
					inner join actividades on jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES
					inner join corredor on corredor.IDCORREDOR=actividades.IDCORREDOR
					where SEXOPAR is not null AND ESTADOPAR=1
					GROUP BY participantes.IDPARTICIPANTE"; 
			} 
	
		#echo $sql;	   	
        return ejecutarConsulta($sql);
    }	
	
	
   /*public function selectActividades(){
   $sql="SELECT DISTINCT a.IDACTIVIDADES, NOMBREACT, DATE_FORMAT(FECHAINICIOACT,'%d/%m/%Y') AS FECHA, NOMBRETAC 
		   FROM actividades a 
		   INNER JOIN categoriaactividad b on a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
		   INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
		   INNER JOIN jornadas d ON a.IDACTIVIDADES=d.IDACTIVIDADES
		   INNER JOIN asistencias e ON d.IDJORNADAS= e.IDJORNADAS
		   where a.ESTADOACT=1";
	 #echo $sql;	   
     return ejecutarConsulta($sql); 
   }*/
   public function selectTipoParticipante(){ 
		$sql="SELECT * FROM tipoparticipante";
		return ejecutarConsulta($sql);
    }	
	 public function selectTipoActividad(){
		$sql="SELECT * FROM tipoactividad";
		#echo $sql;
        return ejecutarConsulta($sql);
    }
   
    public function selectActividadTipo($tipoactividad){
		$sql="SELECT DISTINCT a.IDACTIVIDADES, NOMBREACT, DATE_FORMAT(FECHAINICIOACT,'%d/%m/%Y') AS FECHA, NOMBRETAC 
			  FROM actividades a 
			  INNER JOIN categoriaactividad b ON b.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD
			  INNER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
			  INNER JOIN jornadas c ON c.IDACTIVIDADES=a.IDACTIVIDADES
			  INNER JOIN asistencias d ON d.IDJORNADAS= c.IDJORNADAS
			  where a.ESTADOACT=1 AND b.IDTIPOACTIVIDAD=$tipoactividad";
	   #echo $sql;	   
		return ejecutarConsulta($sql); 
	  }
	public function selectActividad($actividades){
	 $sql="SELECT DISTINCT a.IDACTIVIDADES, NOMBREACT, DATE_FORMAT(FECHAINICIOACT,'%d/%m/%Y') AS FECHA, NOMBRETAC 
		   FROM actividades a 
		   INNER JOIN categoriaactividad b on a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
		   INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
		   INNER JOIN jornadas d ON a.IDACTIVIDADES=d.IDACTIVIDADES
		   INNER JOIN asistencias e ON d.IDJORNADAS= e.IDJORNADAS
		   where a.ESTADOACT=1 AND a.IDACTIVIDADES=$actividades";
	 #echo $sql;	   
     return ejecutarConsulta($sql); 
   }
	
}
?>