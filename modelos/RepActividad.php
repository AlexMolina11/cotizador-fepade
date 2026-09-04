<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class RepActividad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementar un método para mostrar todos los registros
    public function listar($fechainicio,$fechafin,$tipoact,$corredoract){
       $sql = "SELECT actividades.IDACTIVIDADES,actividades.NOMBREACT,
				actividades.FECHAINICIOACT,
				DATE_FORMAT(actividades.FECHAINICIOACT,'%d-%m-%Y')fechadma,
				(SELECT IFNULL(COUNT(*),0) FROM jornadas WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES)tjornadas,
				(SELECT ifnull(sum(DATE_FORMAT(HORAFINJOR ,'%H')-DATE_FORMAT(HORAINIJOR,'%H')),0) 
				FROM jornadas WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES)sumashoras,
				(SELECT IFNULL(COUNT(DISTINCT asistencias.IDPARTICIPANTE),0) 
				FROM jornadas 
				INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
				INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				 WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES and SEXOPAR='M')tmasculino,
				(SELECT IFNULL(COUNT(DISTINCT asistencias.IDPARTICIPANTE),0) FROM jornadas 
				INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
				INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				 WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES and SEXOPAR='F')tfemenino,
				(SELECT IFNULL(COUNT(DISTINCT asistencias.IDPARTICIPANTE),0) FROM jornadas 
				INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
				INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
				  WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES)tmf,	actividades.LUGARACT
				from actividades
				WHERE  ESTADOACT=1";
			   if($tipoact){
			   $sql = $sql.' and actividades.IDCATEGORIAACTIVIDAD in (SELECT IDCATEGORIAACTIVIDAD FROM categoriaactividad WHERE categoriaactividad.IDTIPOACTIVIDAD='.$tipoact.')'; 
			   }			   
			  if($corredoract){
			  $sql = $sql.' and actividades.IDCORREDOR = '.$corredoract; 
			  }
			  if($fechainicio and   $fechafin){
			   $sql = $sql.'  and DATE(actividades.FECHAINICIOACT) between  "'.$fechainicio.'" and "'.$fechafin.'"'; 
			   } 
			   else{
				   if($fechainicio){
					   $sql = $sql.'  and DATE(actividades.FECHAINICIOACT) >=  "'.$fechainicio.'" '; 
					   }
				   if($fechafin){
					   $sql = $sql.'  and DATE(actividades.FECHAINICIOACT) <=  "'.$fechafin.'" '; 
					   }
				} 
        return ejecutarConsulta($sql);
    }	
	 public function selectTipoActividad(){
        $sql="SELECT * FROM tipoactividad";
        return ejecutarConsulta($sql);
    }
	public function selectCorredor(){
        $sql="SELECT * FROM corredor WHERE ESTADOCOR=1";
        return ejecutarConsulta($sql);
    }
	
}