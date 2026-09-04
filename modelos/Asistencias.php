<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Asistencias{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idjornadas,$idactividades, $nombrejor, $objetivojor,$fechajor,$horainijor, $horafinjor,$idparticipante,$usuario,$ip,$idorgeje){
	    $usuario = $_SESSION["login"];
		$ip= $_SERVER["REMOTE_ADDR"];
		$fecha = substr($fechajor, 6, 4)."-".substr($fechajor, 3, 2)."-".substr($fechajor, 0, 2);
        $sql="INSERT INTO jornadas(IDACTIVIDADES, NOMBREJOR, OBJETIVOJOR, HORAINIJOR, HORAFINJOR, FECHAJOR, USUREGJOR, IPREGJOR, IDORGEJE, FECHAREGJOR, ESTADOJOR) VALUES ($idactividades,'$nombrejor','$objetivojor','$horainijor', '$horafinjor','$fecha','$usuario','$ip','$idorgeje',NOW(),1)";
        #echo $sql;
        //return ejecutarConsulta($sql);
        $idjornadasnew=ejecutarConsulta_retornaID($sql);
		$num_elementos=0;
        $sw=TRUE;
        while ($num_elementos< count($idparticipante)){
            $sql_detalle="INSERT INTO asistencias(IDJORNADAS,IDPARTICIPANTE,FECHAASI,USUREGASI,FECHAREGASI,IPREGASI,IDORGEJEASI,PRIMERAVEZASI) VALUES ('$idjornadasnew','$idparticipante[$num_elementos]','$fecha','$usuario',NOW(),'$ip','$idorgeje',(SELECT IF(COUNT(*)>0,0,1)  FROM `asistencias` a WHERE a.`IDPARTICIPANTE`='$idparticipante[$num_elementos]'))";            
			ejecutarConsulta($sql_detalle) or $sw=FALSE;
            //echo $sql_detalle;
            $num_elementos=$num_elementos+1;
        }        
        return $sw;		
	}
	public function editar($idjornadas,$idactividades,$nombrejor,$objetivojor,$fechajor,$horainijor,$horafinjor,$idparticipante,$idorgeje){
		$usuario = $_SESSION["login"];
		$ip= $_SERVER["REMOTE_ADDR"];
		$fecha = substr($fechajor, 6, 4)."-".substr($fechajor, 3, 2)."-".substr($fechajor, 0, 2);
		$sql="UPDATE `jornadas` SET `IDACTIVIDADES`=$idactividades,
									`NOMBREJOR`='$nombrejor',
									`OBJETIVOJOR`='$objetivojor',
									`HORAINIJOR`='$horainijor',
									`HORAFINJOR`='$horafinjor',
									`FECHAJOR`='$fecha'
							 WHERE `IDJORNADAS`= $idjornadas";
		ejecutarConsulta($sql);
		$num_elementos=0;
        $sw=TRUE;
        while ($num_elementos< count($idparticipante)){
            $sql_detalle="INSERT INTO asistencias(IDJORNADAS,IDPARTICIPANTE,FECHAASI,USUREGASI,FECHAREGASI,IPREGASI,IDORGEJEASI,PRIMERAVEZASI) VALUES ('$idjornadas','$idparticipante[$num_elementos]','$fecha','$usuario',NOW(),'$ip','$idorgeje',(SELECT IF(COUNT(*)>0,0,1)  FROM `asistencias` a WHERE a.`IDPARTICIPANTE`='$idparticipante[$num_elementos]'))";            
			ejecutarConsulta($sql_detalle) or $sw=FALSE;
            #echo $sql_detalle;
            $num_elementos=$num_elementos+1;
        }        
        return $sw;		
		//return ejecutarConsulta($sql);
	}
	public function eliminar($idjornadas,$idparticipante){
		$sql="DELETE FROM `asistencias` WHERE `IDJORNADAS`=$idjornadas AND `IDPARTICIPANTE`=$idparticipante";	
		$var=TRUE;
		ejecutarConsulta($sql) or $var=FALSE;
        #echo $var;
		return $var;
		 
	}
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idjornadas){
        $sql="SELECT IDJORNADAS,
                     a.IDACTIVIDADES,
                     NOMBREACT,
                     NOMBREJOR,
                     OBJETIVOJOR,
                     date_format(FECHAJOR,'%d/%m/%Y') as FECHA,
                     HORAINIJOR,
                     HORAFINJOR,
					 FECHAINICIOACT  
			  FROM jornadas a 
			  INNER JOIN actividades b ON a.IDACTIVIDADES=b.IDACTIVIDADES
			  WHERE a.IDJORNADAS=$idjornadas";
        return ejecutarConsultaSimpleFila($sql);
    }
	
	public function anular($idjornadas){
        $sql="UPDATE jornadas SET ESTADOJOR='0' WHERE IDJORNADAS=$idjornadas";
        return ejecutarConsulta($sql);
    }
	
	public function activar($idjornadas){
        $sql="UPDATE jornadas SET ESTADOJOR='1' WHERE IDJORNADAS=$idjornadas";
        return ejecutarConsulta($sql);
    }
	
    //Implementar un método para mostrar todos los registros
    public function listar(){
		$usuario = $_SESSION["login"];
		$permisorol  = $_SESSION["permiso"];
        $orgeje         = $_SESSION["idorgeje"];

		if($permisorol=="1"){
			//Permiso rol == 1 es Permiso total o Acceso Total
        	$sql="SELECT distinct a.IDJORNADAS,
							NOMBREJOR,
							d.IDACTIVIDADES,
							d.NOMBREACT,
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
					INNER JOIN actividades d ON d.IDACTIVIDADES=a.IDACTIVIDADES";
		} else if($permisorol=="2") {
            //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora
			$sql="SELECT distinct a.IDJORNADAS,
							NOMBREJOR,
							d.IDACTIVIDADES,
							d.NOMBREACT,
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
					WHERE a.IDORGEJE = '$idorgeje'";
		} else {
            //Permiso rol == 0 es Permiso unicamente a los registros del usuario
			$sql="SELECT distinct a.IDJORNADAS,
							NOMBREJOR,
							d.IDACTIVIDADES,
							d.NOMBREACT,
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
					WHERE a.USUREGJOR='$usuario'";
		    }			
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select
      public function selectActividad(){
		 $usuario = $_SESSION["login"];
		 $permisorol  = $_SESSION["permiso"];

		 if($permisorol=="1"){
		 $sql="SELECT IDACTIVIDADES, 
						NOMBREACT, 
						FECHAINICIOACT, 
						NOMBRETAC 
				FROM actividades a 
				INNER JOIN categoriaactividad b ON a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
				INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
				WHERE a.ESTADOACT=1
				ORDER BY FECHAINICIOACT ASC";
		 }
		 else{
		 $sql="SELECT IDACTIVIDADES, 
						NOMBREACT, 
						FECHAINICIOACT, 
						NOMBRETAC 
				FROM actividades a 
				INNER JOIN categoriaactividad b ON a.IDCATEGORIAACTIVIDAD=b.IDCATEGORIAACTIVIDAD
				INNER JOIN tipoactividad c ON c.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
				WHERE a.ESTADOACT=1 AND a.USUREACT='$usuario'
				ORDER BY FECHAINICIOACT ASC";
		 }
	     return ejecutarConsulta($sql);
     }
	 
	  
    public function selectGrupos(){
		$sql="SELECT IDGRUPO,NOMBREGRUPO FROM grupo ORDER BY NOMBREGRUPO ASC";
		return ejecutarConsulta($sql);
   	}
   //Se agregó query para Departamentos
   	public function selectDepartamentos(){
		$sql="SELECT IDDEPARTAMENTO, NOMBREDEP FROM departamento ORDER BY NOMBREDEP ASC";
		return ejecutarConsulta($sql);
	} 
   
    public function selectMunicipios(){
		$sql="SELECT IDMUNICIPIO, NOMBREMUN FROM municipio ORDER BY NOMBREMUN ASC";
		return ejecutarConsulta($sql);
   	}
	//Se agregó query para centro educativo
	public function selectCentroeducativo(){
		$sql="SELECT IDINSTITUCION, NOMBREINS FROM institucion ORDER BY NOMBREINS ASC";
		return ejecutarConsulta($sql);
	} 
  
   public function listarParticipantes($idgrupo,$idmunicipio,$iddepartamento,$idcentroeducativo){
	if($idgrupo!=0 AND $idmunicipio!=0 AND $iddepartamento!=0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND a.IDMUNICIPIO=$idmunicipio AND dp.IDDEPARTAMENTO=$iddepartamento AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo!=0 AND $idmunicipio!=0 AND $iddepartamento!=0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND a.IDMUNICIPIO=$idmunicipio AND dp.IDDEPARTAMENTO=$iddepartamento
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo!=0 AND $idmunicipio!=0 AND $iddepartamento==0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND a.IDMUNICIPIO=$idmunicipio AND i.IDINSTITUCION=$idcentroeducativo
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo!=0 AND $idmunicipio==0 AND $iddepartamento!=0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND dp.IDDEPARTAMENTO=$iddepartamento AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio!=0 AND $iddepartamento!=0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND a.IDMUNICIPIO=$idmunicipio AND dp.IDDEPARTAMENTO=$iddepartamento AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	}else if($idgrupo!=0 AND $idmunicipio!=0 AND $iddepartamento==0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND a.IDMUNICIPIO=$idmunicipio
		   ORDER BY NOMBRE ASC";
	}else if($idgrupo!=0 AND $idmunicipio==0 AND $iddepartamento!=0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND dp.IDDEPARTAMENTO=$iddepartamento
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo!=0 AND $idmunicipio==0 AND $iddepartamento==0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio!=0 AND $iddepartamento!=0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND a.IDMUNICIPIO=$idmunicipio AND dp.IDDEPARTAMENTO=$iddepartamento
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio!=0 AND $iddepartamento==0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND a.IDMUNICIPIO=$idmunicipio AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio==0 AND $iddepartamento!=0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND dp.IDDEPARTAMENTO=$iddepartamento AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo!=0 AND $idmunicipio==0 AND $iddepartamento==0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND b.IDGRUPO=$idgrupo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio!=0 AND $iddepartamento==0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND a.IDMUNICIPIO=$idmunicipio
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio==0 AND $iddepartamento!=0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND dp.IDDEPARTAMENTO=$iddepartamento
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio==0 AND $iddepartamento==0 AND $idcentroeducativo!=0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 AND i.IDINSTITUCION=$idcentroeducativo 
		   ORDER BY NOMBRE ASC";
	} else if($idgrupo==0 AND $idmunicipio==0 AND $iddepartamento==0 AND $idcentroeducativo==0){
		$sql="SELECT a.IDPARTICIPANTE, CONCAT_WS(' ',PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO) AS NOMBRE,
		   a.EDADPAR,
		   a.SEXOPAR,
		   c.NOMBREGRUPO,
		   d.NOMBRETIP,
		   e.NOMBREMUN,
		   f.NOMBRECOMUNIDAD,
		   i.NOMBREINS
		   FROM participantes a 
		   LEFT OUTER JOIN participantegrupo b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE
		   LEFT OUTER JOIN grupo c ON c.IDGRUPO=b.IDGRUPO
		   LEFT OUTER JOIN tipoparticipante d ON d.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
		   LEFT OUTER JOIN municipio e ON e.IDMUNICIPIO=a.IDMUNICIPIO
		   LEFT OUTER JOIN comunidad f ON f.IDCOMUNIDAD=a.IDCOMUNIDAD
		   LEFT OUTER JOIN institucion i ON i.IDINSTITUCION=a.IDINSTITUCION
		   LEFT OUTER JOIN departamento dp ON dp.IDDEPARTAMENTO=a.IDDEPARTAMENTO
		   WHERE a.ESTADOPAR=1 ORDER BY a.IDPARTICIPANTE DESC          			
		LIMIT 5";
	}
	return ejecutarConsulta($sql);
   }
    
	public function listarDetalle($idjornadas){
        $sql="SELECT a.IDPARTICIPANTE,concat_ws(' ',b.PRIMERNOMBRE,b.SEGUNDOSNOMBRE,b.PRIMERAPELLIDO,b.SEGUNDOSAPELLIDO) as NOMBRE,a.IDJORNADAS, a.PRIMERAVEZASI as PRIMERAVEZ
			  FROM asistencias a INNER JOIN participantes b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE 
			  WHERE a.IDJORNADAS=$idjornadas";  
			  
        return ejecutarConsulta($sql);  
	}

	public function mostrarNombre($idjornadas){
        $sql="SELECT a.IDPARTICIPANTE,concat_ws(' ',b.PRIMERNOMBRE,b.SEGUNDOSNOMBRE,b.PRIMERAPELLIDO,b.SEGUNDOSAPELLIDO) as NOMBRE,a.IDJORNADAS
			  FROM asistencias a INNER JOIN participantes b ON a.IDPARTICIPANTE=b.IDPARTICIPANTE 
			  WHERE a.IDJORNADAS=$idjornadas";  
			  
        return ejecutarConsulta($sql);  
	}
	
	 public function actualizarDuracion($idactividades){
        $sql="UPDATE ACTIVIDADES SET DURACIONACT = (SELECT SUM(IFNULL(DATE_FORMAT(TIMEDIFF(HORAFINJOR,HORAINIJOR),'%H')+0,0)) 
		FROM jornadas WHERE jornadas.IDACTIVIDADES=actividades.IDACTIVIDADES AND ESTADOACT=1 ) 
		WHERE actividades.IDACTIVIDADES =$idactividades";
        return ejecutarConsulta($sql);
    }	
}
?>