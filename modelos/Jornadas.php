<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Jornadas{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    /*public function insertar($idactividades,$nombrejor,$objetivojor,$lugarjor,$responsablejor,$fechajor,$horainijor,$horafinjor,$usuario,$ip,$idorgeje,$duracion){
	    $usuario = $_SESSION["login"];
		$ip= $_SERVER["REMOTE_ADDR"];
		//$fecha = date("Y-m-d",$fechajor);
		$fecha = substr($fechajor, 6, 4)."-".substr($fechajor, 3, 2)."-".substr($fechajor, 0, 2);
        $sql="INSERT INTO jornadas(IDACTIVIDADES,NOMBREJOR,OBJETIVOJOR,CODIGORES,LUGARJOR,HORAINIJOR,HORAFINJOR,FECHAJOR,USUREGJOR,IPREGJOR,IDORGEJE,FECHAREGJOR, ESTADOJOR, DURACIONJOR, FECHAMODJOR, USUMODJOR) 
		       VALUES ($idactividades,'$nombrejor','$objetivojor','$responsablejor','$lugarjor','$horainijor','$horafinjor','$fecha','$usuario','$ip','$idorgeje',NOW(),1,'$duracion',NOW(),'$usuario')";
		#echo $sql;
        return ejecutarConsulta($sql);
    }*/

    public function insertar($idactividades,$nombrejor,$objetivojor,$lugarjor,$fechajor,$horainijor,$horafinjor,$usuario,$ip,$idorgeje,$duracion){
	    $usuario = $_SESSION["login"];
		$ip= $_SERVER["REMOTE_ADDR"];
		$fecha = substr($fechajor, 6, 4)."-".substr($fechajor, 3, 2)."-".substr($fechajor, 0, 2);
        $sql="INSERT INTO jornadas(IDACTIVIDADES,NOMBREJOR,OBJETIVOJOR,LUGARJOR,HORAINIJOR,HORAFINJOR,FECHAJOR,USUREGJOR,IPREGJOR,IDORGEJE,FECHAREGJOR, ESTADOJOR, DURACIONJOR, FECHAMODJOR, USUMODJOR) 
		       VALUES ($idactividades,'$nombrejor','$objetivojor','$lugarjor','$horainijor','$horafinjor','$fecha','$usuario','$ip','$idorgeje',NOW(),1,'$duracion',NOW(),'$usuario')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    /*public function editar($idjornadas,$idactividades,$nombrejor,$objetivojor,$lugarjor,$responsablejor,$fechajor,$horainijor,$horafinjor,$duracion){
        $fecha = substr($fechajor, 6, 4)."-".substr($fechajor, 3, 2)."-".substr($fechajor, 0, 2);
        $usuario = $_SESSION["login"];
        //$fecha = date_format('Y-m-d',trtotime($fechajor));
        $sql="UPDATE `jornadas` SET `IDACTIVIDADES`=$idactividades,
					  `NOMBREJOR`='$nombrejor',
					  `OBJETIVOJOR`='$objetivojor',
					  `CODIGORES`='$responsablejor',
					  `LUGARJOR`='$lugarjor',
					  `HORAINIJOR`='$horainijor',
					  `HORAFINJOR`='$horafinjor',
					  `FECHAJOR`='$fecha',
                      `DURACIONJOR`='$duracion',
                      `FECHAMODJOR`=NOW(),
                      `USUMODJOR`='$usuario'
			  WHERE `IDJORNADAS`=$idjornadas";
	    #echo $sql;
        return ejecutarConsulta($sql);
    }*/

    public function editar($idjornadas,$idactividades,$nombrejor,$objetivojor,$lugarjor,$fechajor,$horainijor,$horafinjor,$duracion){
        $fecha = substr($fechajor, 6, 4)."-".substr($fechajor, 3, 2)."-".substr($fechajor, 0, 2);
        $usuario = $_SESSION["login"];
        $sql="UPDATE `jornadas` SET `IDACTIVIDADES`=$idactividades,
					  `NOMBREJOR`='$nombrejor',
					  `OBJETIVOJOR`='$objetivojor',
					  `LUGARJOR`='$lugarjor',
					  `HORAINIJOR`='$horainijor',
					  `HORAFINJOR`='$horafinjor',
					  `FECHAJOR`='$fecha',
                      `DURACIONJOR`='$duracion',
                      `FECHAMODJOR`=NOW(),
                      `USUMODJOR`='$usuario'
			  WHERE `IDJORNADAS`=$idjornadas";
        return ejecutarConsulta($sql);
    }

    //Función para insertar los facilitadores 
    public function insertarResponsablesJornada($idjornadas, $responsablejor) {
        foreach ($responsablejor as $idresponsable) {
            $sql = "INSERT INTO jornada_facilitador (IDJORNADAS, CODIGORES) VALUES ('$idjornadas','$idresponsable')";
            ejecutarConsulta($sql);
        }
    }
    
    //Funcion para eliminar los facilitadores
    public function eliminarResponsablesJornada($idjornadas) {
        $sql = "DELETE FROM jornada_facilitador WHERE IDJORNADAS = '$idjornadas'";
        return ejecutarConsulta($sql);
    }
    
    //Función para obtener la ultima institución registrada.
    public function ultimoID() {
        $sql = "SELECT MAX(IDJORNADAS) as ultimo_id FROM jornadas";
        $query = ejecutarConsultaSimpleFila($sql);
        return $query['ultimo_id'];
    }


    //Se agregó query para eliminar
    public function eliminar($idjornadas){
        $sql="DELETE FROM jornadas WHERE IDJORNADAS='$idjornadas'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idjornadas){
        $sql="UPDATE jornadas SET ESTADOJOR='0' WHERE IDJORNADAS='$idjornadas'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idjornadas){
        $sql="UPDATE jornadas SET ESTADOJOR='1' WHERE IDJORNADAS='$idjornadas'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idjornadas){
        /*$sql="SELECT j.IDJORNADAS, 
                     j.IDACTIVIDADES, 
                     j.NOMBREJOR, 
                     j.OBJETIVOJOR, 
                     j.CODIGORES, 
                     r.NOMBRERES, 
                     j.LUGARJOR, 
                     date_format(j.FECHAJOR,'%d/%m/%Y') as FECHA, 
                     j.HORAINIJOR, 
                     j.HORAFINJOR, 
                     a.FECHAINICIOACT, 
                     j.DURACIONJOR 
                FROM jornadas as j
                LEFT OUTER JOIN actividades as a ON a.IDACTIVIDADES=j.IDACTIVIDADES
                LEFT OUTER JOIN responsables as r ON r.CODIGORES=j.CODIGORES
                WHERE j.IDJORNADAS=$idjornadas";
			  
        return ejecutarConsultaSimpleFila($sql);*/

        $sqlJornadas="SELECT j.IDJORNADAS, 
                     j.IDACTIVIDADES, 
                     j.NOMBREJOR, 
                     j.OBJETIVOJOR, 
                     r.NOMBRERES, 
                     j.LUGARJOR, 
                     date_format(j.FECHAJOR,'%d/%m/%Y') as FECHA, 
                     j.HORAINIJOR, 
                     j.HORAFINJOR, 
                     a.FECHAINICIOACT, 
                     j.DURACIONJOR 
                FROM jornadas as j
                LEFT OUTER JOIN actividades as a ON a.IDACTIVIDADES=j.IDACTIVIDADES
                LEFT OUTER JOIN responsables as r ON r.CODIGORES=j.CODIGORES
                WHERE j.IDJORNADAS=$idjornadas";

        $jornada = ejecutarConsultaSimpleFila($sqlJornadas);

        //Consulta para obtener los facilitadores asociados a la jornada
        $sqlFacilitadores="SELECT CODIGORES FROM jornada_facilitador WHERE IDJORNADAS='$idjornadas'";
        $facilitadoresjor=ejecutarConsulta($sqlFacilitadores);

        //Crear un nuevo arreglo para almacenar los IDs de los facilitadores
        $facilitadores = array();
        while($row = $facilitadoresjor->fetch_assoc()){
            $facilitadores[]=$row['CODIGORES'];
        }

        //Agregamos los facilitadores al resultado de la jornada
        $jornada['responsable_jornada']=$facilitadores;

        return $jornada;
    }

    //Implementar un método para mostrar todos los registros
    public function listar($fechainicio,$fechafin){
        $usuario     = $_SESSION["login"];
        $permisorol  = $_SESSION["permiso"];
        $orgeje         = $_SESSION["idorgeje"];
        $areares        = $_SESSION['areares'];

        //Agregamos condicionante de fechas al query
        if($fechainicio and $fechafin) {
            $where2 = ' WHERE DATE(FECHAJOR) between "'.$fechainicio.'" and "'.$fechafin.'" ';
            $where2b = 'WHERE DATE(FECHAJOR) between  "'.$fechainicio.'" and "'.$fechafin.'" AND';  
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "";
            } else {
                $where3 = 'AND b.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = ' b.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        } else if($fechainicio) {
            $where2 = ' WHERE DATE(FECHAJOR) >=  "'.$fechainicio.'" '; 
            $where2b = 'WHERE DATE(FECHAJOR) >=  "'.$fechainicio.'" AND '; 
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "";
            } else {
                $where3 = 'AND b.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = ' b.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        } else if($fechafin) {
            $where2 = ' WHERE DATE(FECHAJOR) <=  "'.$fechafin.'" '; 
            $where2b = 'WHERE DATE(FECHAJOR) <=  "'.$fechafin.'" AND'; 
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "";
            } else {
                $where3 = 'AND b.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = ' b.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        } else {
            $where2 = "";
            $where2b = "";
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "WHERE";
            } else {
                $where3 = 'WHERE b.IDAREARESPONSABLE = "'.$areares.'" ';
                $where3b = 'WHERE b.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        }

        if($permisorol=="1"){
            //Permiso rol == 1 es Permiso total o Acceso Total
            $sql="SELECT IDJORNADAS,
                        a.IDACTIVIDADES,
                        NOMBREACT,
                        NOMBREJOR,
                        date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                        HORAINIJOR,
                        HORAFINJOR,
                        ESTADOJOR,
                        a.USUREGJOR 
                 FROM jornadas a 
                 INNER JOIN actividades b ON a.IDACTIVIDADES=b.IDACTIVIDADES $where2 $where3";
        } else if($permisorol=="2") {
            //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora
            $sql="SELECT IDJORNADAS, 
                        a.IDACTIVIDADES,
                            NOMBREACT, 
                            NOMBREJOR, 
                            date_format(FECHAJOR,'%d/%m/%Y') as FECHA, 
                            HORAINIJOR, 
                            HORAFINJOR, 
                            ESTADOJOR, 
                            b.USUREACT, 
                            b.IDORGEJE,
                            a.USUREGJOR 
                    FROM jornadas a 
                    INNER JOIN actividades b ON a.IDACTIVIDADES=b.IDACTIVIDADES 
                    $where2b $where3b b.IDORGEJE = '$orgeje'";
        } else {
            //Permiso rol == 0 es Permiso unicamente a los registros del usuario
            $sql="SELECT IDJORNADAS,
                        a.IDACTIVIDADES,
                            NOMBREACT,
                            NOMBREJOR,
                            date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                            HORAINIJOR,
                            HORAFINJOR,
                            ESTADOJOR,
                            a.USUREGJOR 
                FROM jornadas a 
                INNER JOIN actividades b ON a.IDACTIVIDADES=b.IDACTIVIDADES
                $where2b $where3b b.USUREACT='$usuario'"; 	
        }
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select
    public function selectActividad(){
        $usuario        = $_SESSION["login"];
        $permisorol     = $_SESSION["permiso"];
        $orgeje         = $_SESSION["idorgeje"];
        $areares        = $_SESSION['areares'];

        //Agregamos condicionante de área responsable
        if($areares == "0"){
            //Significa que tomará todas las áreas responsables
            $where = "";
            $where2 = "";
        } else {
            $where = ' AND a.IDAREARESPONSABLE = "'.$areares.'"';
            $where2 = ' AND a.IDAREARESPONSABLE = "'.$areares.'" AND';
        }

        if($permisorol=="1") {
            //Permiso rol == 1 es Permiso total o Acceso Total
             $sql = "SELECT IDACTIVIDADES, 
                       NOMBREACT, 
                       date_format(FECHAINICIOACT,'%d/%m/%Y') as FECHAINIA, 
                       NOMBRETAC 
                FROM actividades a 
                INNER JOIN categoriaactividad b ON a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
                INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
                WHERE a.ESTADOACT=1 $where
                ORDER BY FECHAINICIOACT ASC"; /*Se agregó departamento*/
         } else if($permisorol=="2") {
             //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora
             $sql = "SELECT IDACTIVIDADES, 
                       NOMBREACT, 
                       date_format(FECHAINICIOACT,'%d/%m/%Y') as FECHAINIA, 
                       NOMBRETAC 
                FROM actividades a 
                INNER JOIN categoriaactividad b ON a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
                INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
                WHERE a.ESTADOACT=1 $where2 a.`IDORGEJE` = '$orgeje'
                ORDER BY FECHAINICIOACT ASC";
         } else {
             //Permiso rol == 0 es Permiso unicamente a los registros del usuario
             $sql = "SELECT IDACTIVIDADES, 
                       NOMBREACT, 
                       date_format(FECHAINICIOACT,'%d/%m/%Y') as FECHAINIA, 
                       NOMBRETAC 
                FROM actividades a 
                INNER JOIN categoriaactividad b ON a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
                INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
                WHERE a.ESTADOACT=1 $where2 `USUREACT`='$usuario'
                ORDER BY FECHAINICIOACT ASC";
         }
        return ejecutarConsulta($sql);
   }
   
    public function actualizarDuracion($idactividades){
        $sql="UPDATE ACTIVIDADES SET DURACIONACT = (SELECT SUM(IFNULL(DATE_FORMAT(TIMEDIFF(HORAFINJOR,HORAINIJOR),'%H')+0,0)) 
		FROM jornadas WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES AND ESTADOACT=1 ) 
		WHERE actividades.IDACTIVIDADES =$idactividades";
        return ejecutarConsulta($sql);
    }

    public function obtenerFecha($idactividades){
       $sql ="SELECT `FECHAINICIOACT` FROM `actividades` WHERE `IDACTIVIDADES`=$idactividades";
       return ejecutarConsulta($sql);
    }

    public function selectResponsable(){
        $sql="SELECT * FROM responsables LEFT OUTER JOIN organizacionejecutora ON responsables.IDORGEJE = organizacionejecutora.IDORGEJE where ESTADORES=1 ORDER BY responsables.NOMBRERES ASC";
        return ejecutarConsulta($sql);
    }
  
}
?>