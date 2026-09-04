<?php
//Incluimos la conexion a la base de datos
if(!isset($_SESSION)) 
{ 
session_start(); 
} 

require "../config/Conexion.php";

class Consultas{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementar un método para mostrar todos los registros

public function consultaasistencia($actividad,$corredor,$fechainicio,$fechafin){ //Se agregó el corredor
        //Agregamos condicionante para el idrol sl query
        $idrol  = $_SESSION["idrol"];
        $usuario     = $_SESSION["login"];
        $permisorol  = $_SESSION["permiso"];
        $orgeje      = $_SESSION["idorgeje"];
        $areares        = $_SESSION['areares'];

        //Agregamos condicionante de fechas al query
        if($fechainicio and $fechafin) {
                $where2 = ' WHERE DATE(FECHAJOR) between  "'.$fechainicio.'" and "'.$fechafin.'" ';
                $where2b = ' WHERE DATE(FECHAJOR) between  "'.$fechainicio.'" and "'.$fechafin.'" AND';  
                $where2c = ' WHERE DATE(FECHAJOR) between  "'.$fechainicio.'" and "'.$fechafin.'"';
                $where2d = '  DATE(FECHAJOR) between  "'.$fechainicio.'" and "'.$fechafin.'" AND ';  
                //Agregamos condicionante de área responsable
                if($areares == "0"){
                        //Significa que tomará todas las áreas responsables
                        $where3 = "";
                        $where3b = "";
                        $where3c = "";
                        $where3d = "";
                } else {
                        $where3 = 'AND d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3b = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                        $where3c = 'AND d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3d = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                }
        } else if($fechainicio) {
                $where2 = ' WHERE DATE(FECHAJOR) >=  "'.$fechainicio.'" '; 
                $where2b = ' WHERE DATE(FECHAJOR) >=  "'.$fechainicio.'" AND ';
                $where2c = ' WHERE DATE(FECHAJOR) >=  "'.$fechainicio.'" '; 
                $where2d = '  DATE(FECHAJOR) >=  "'.$fechainicio.'" AND ';
                //Agregamos condicionante de área responsable
                if($areares == "0"){
                        //Significa que tomará todas las áreas responsables
                        $where3 = "";
                        $where3b = "";
                        $where3c = "";
                        $where3d = "";
                } else {
                        $where3 = 'AND d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3b = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                        $where3c = 'AND d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3d = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                }
        } else if($fechafin) {
                $where2 = ' WHERE DATE(FECHAJOR) <=  "'.$fechafin.'" '; 
                $where2b = ' WHERE DATE(FECHAJOR) <=  "'.$fechafin.'" AND ';
                $where2c = ' WHERE DATE(FECHAJOR) <=  "'.$fechafin.'" '; 
                $where2d = '  DATE(FECHAJOR) <=  "'.$fechafin.'" AND ';
                //Agregamos condicionante de área responsable
                if($areares == "0"){
                        //Significa que tomará todas las áreas responsables
                        $where3 = "";
                        $where3b = "";
                        $where3c = "";
                        $where3d = "";
                } else {
                        $where3 = 'AND d.IDAREARESPONSABLE = "'.$areares.'" ';
                        $where3b = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                        $where3c = 'AND d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3d = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                }
        } else {
                $where2 = "";
                $where2b = ' WHERE ';
                $where2c = ""; 
                $where2d = "";
                //Agregamos condicionante de área responsable
                if($areares == "0"){
                        //Significa que tomará todas las áreas responsables
                        $where3 = "";
                        $where3b = "";
                        $where3c = "";
                        $where3d = "";
                } else {
                        $where3 = 'WHERE d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3b = ' d.IDAREARESPONSABLE = "'.$areares.'" AND';
                        $where3c = 'WHERE d.IDAREARESPONSABLE = "'.$areares.'"';
                        $where3d = 'd.IDAREARESPONSABLE = "'.$areares.'" AND';
                }
        }

        //if($idrol=="1"){
        if($permisorol=="1"){ 
                //Permiso rol == 1 es Permiso total o Acceso Total                
                //Modificamos las validaciones respecto a la actividad y corredor
                if($actividad!=0 and $corredor!=0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0),
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2 $where3 AND d.IDACTIVIDADES=$actividad AND c.IDCORREDOR=$corredor
                        ORDER BY IDACTIVIDADES DESC"; 

                } else if($actividad!=0 and $corredor==0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2 $where3 AND d.IDACTIVIDADES=$actividad 
                        ORDER BY IDACTIVIDADES DESC"; 
                        
                } else if($actividad==0 and $corredor!=0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2 $where3 AND c.IDCORREDOR=$corredor 
                        ORDER BY IDACTIVIDADES DESC"; 

                } else if($actividad==0 and $corredor==0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
                                d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        $where2 $where3";
                } 
        } else if($permisorol=="2"){ 
                //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora                
                //Modificamos las validaciones respecto a la actividad y corredor
                if($actividad!=0 and $corredor!=0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2 $where3 AND d.IDACTIVIDADES=$actividad AND c.IDCORREDOR=$corredor AND ESTADOJOR=1 AND d.IDORGEJE = '$orgeje'
                        ORDER BY IDACTIVIDADES DESC"; 

                } else if($actividad!=0 and $corredor==0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2 $where3 AND d.IDACTIVIDADES=$actividad AND ESTADOJOR=1 AND d.IDORGEJE = '$orgeje'
                        ORDER BY IDACTIVIDADES DESC"; 
                        
                } else if($actividad==0 and $corredor!=0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2 $where3 AND c.IDCORREDOR=$corredor AND ESTADOJOR=1 AND d.IDORGEJE = '$orgeje'
                        ORDER BY IDACTIVIDADES DESC"; 

                } else if($actividad==0 and $corredor==0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
                                d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        $where2c $where3c AND ESTADOJOR=1 AND d.IDORGEJE = '$orgeje'";
                } 
        } else {
                //Permiso rol == 0 es Permiso unicamente a los registros del usuario
                //Modificamos las validaciones respecto a la actividad y corredor
                if($actividad!=0 and $corredor!=0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2b $where3b ESTADOJOR=1 AND d.IDACTIVIDADES=$actividad AND c.IDCORREDOR=$corredor
                        AND d.USUREACT='$usuario'
                        ORDER BY IDACTIVIDADES DESC"; 

                } else if($actividad!=0 and $corredor==0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2b $where3b ESTADOJOR=1 AND d.IDACTIVIDADES=$actividad
                        AND d.USUREACT='$usuario'
                        ORDER BY IDACTIVIDADES DESC"; 
                        
                } else if($actividad==0 and $corredor!=0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
				d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        INNER JOIN corredor c on c.IDCORREDOR=d.IDCORREDOR
                        $where2b $where3b ESTADOJOR=1 AND c.IDCORREDOR=$corredor
                        AND d.USUREACT='$usuario'
                        ORDER BY IDACTIVIDADES DESC"; 

                } else if($actividad==0 and $corredor==0) {
                        $sql="SELECT distinct a.IDJORNADAS,
                                NOMBREJOR,
                                d.IDACTIVIDADES,
                                d.NOMBREACT,
                                d.DESCRIPCIONACT,
                                date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                                HORAINIJOR,
                                HORAFINJOR,
                                ESTADOJOR,
                                (SELECT IFNULL(COUNT(*),0) 
                                FROM jornadas INNER JOIN asistencias on asistencias.IDJORNADAS=jornadas.IDJORNADAS
                                INNER JOIN participantes on participantes.IDPARTICIPANTE=asistencias.IDPARTICIPANTE
                                WHERE jornadas.IDJORNADAS=a.IDJORNADAS) as asistencia,
                                a.USUREGJOR 
                        FROM jornadas a			
                        INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES
                        WHERE $where2d $where3d ESTADOJOR=1 AND d.USUREACT='$usuario'";
                } 
        }
        
        return ejecutarConsulta($sql);
        }
        
        public function selectActividad(){
                $usuario     = $_SESSION["login"];
                $permisorol  = $_SESSION["permiso"];
                $orgeje      = $_SESSION["idorgeje"];
                $areares        = $_SESSION['areares'];

                //Agregamos condicionante de área responsable
                if($areares == "0"){
                        //Significa que tomará todas las áreas responsables
                        $where3 = "";
                } else {
                        $where3 = "AND IDAREARESPONSABLE = '$areares'";
                }

                if($permisorol == "1") {
                        $sql="SELECT * FROM actividades WHERE ESTADOACT = 1 $where3 ORDER BY NOMBREACT ASC";
                } else if($permisorol == "2"){
                        $sql="SELECT * FROM actividades WHERE IDORGEJE='$orgeje' AND ESTADOACT = 1 $where3 ORDER BY NOMBREACT ASC";
                } else {
                        $sql="SELECT * FROM actividades WHERE USUREACT='$usuario' AND ESTADOACT = 1 $where3 ORDER BY NOMBREACT ASC";
                }
                return ejecutarConsulta($sql);
        }

        public function selectCorredor(){ 
                $usuario     = $_SESSION["login"];
                $permisorol  = $_SESSION["permiso"];
                $orgeje      = $_SESSION["idorgeje"];
                $areares        = $_SESSION['areares'];

                //Agregamos condicionante de área responsable
                if($areares == "0"){
                        //Significa que tomará todas las áreas responsables
                        $where3 = "";
                } else {
                        $where3 = "AND a.IDAREARESPONSABLE = '$areares'";
                }

                if($permisorol == "1") {
                        $sql="SELECT * FROM corredor WHERE ESTADOCOR = 1 $where3 ORDER BY NOMBRECOR ASC";
                } else if($permisorol == "2") {
                        $sql="SELECT DISTINCTROW c.IDCORREDOR, c.IDPLAN, c.NOMBRECOR FROM corredor c INNER JOIN actividades a ON a.IDCORREDOR = c.IDCORREDOR WHERE a.IDORGEJE='$orgeje' AND c.ESTADOCOR = 1 $where3 ORDER BY NOMBRECOR ASC";
                } else {
                        $sql="SELECT DISTINCTROW c.IDCORREDOR, c.IDPLAN, c.NOMBRECOR FROM corredor c INNER JOIN actividades a ON a.IDCORREDOR = c.IDCORREDOR WHERE a.USUREACT='$usuario' AND c.ESTADOCOR = 1 $where3 ORDER BY NOMBRECOR ASC";
                }
                return ejecutarConsulta($sql);
        }
}
?>