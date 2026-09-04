<?php

//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Actividad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    //public function insertar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$lugaract,$responsableact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$usuario,$ip,$idorgeje,$tipoparticipante,$arearesponsable) { 
    public function insertar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$usuario,$ip,$idorgeje,$tipoparticipante,$arearesponsable) { 
        $usuario = $_SESSION["login"];
        $ip= $_SERVER["REMOTE_ADDR"];
        /*$sql="INSERT INTO actividades(IDMUNICIPIO, IDDEPARTAMENTO, IDCATEGORIAACTIVIDAD, IDCORREDOR, IDINSTITUCION, NOMBREACT, CODIGORES, OBJETIVOACT, FECHAINICIOACT, DESCRIPCIONACT, LUGARACT, USUREACT, IPREGACT, IDORGEJE, FECHAREGACT, ESTADOACT, IDTIPOPARTICIPANTE, IDAREARESPONSABLE, FECHAMODACT, USUMODACT) 
            values($municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,'$nombreact','$responsableact','$objetivoact','$fechaact','$descripcionact','$lugaract','$usuario','$ip','$idorgeje',NOW(),1,NULLIF($tipoparticipante,'0'),$arearesponsable, NOW(),'$usuario')"; */
        $sql="INSERT INTO actividades(IDMUNICIPIO, IDDEPARTAMENTO, IDCATEGORIAACTIVIDAD, IDCORREDOR, IDINSTITUCION, NOMBREACT, OBJETIVOACT, FECHAINICIOACT, DESCRIPCIONACT, USUREACT, IPREGACT, IDORGEJE, FECHAREGACT, ESTADOACT, IDTIPOPARTICIPANTE, IDAREARESPONSABLE, FECHAMODACT, USUMODACT) 
            values($municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,'$nombreact','$objetivoact','$fechaact','$descripcionact','$usuario','$ip','$idorgeje',NOW(),1,NULLIF($tipoparticipante,'0'),$arearesponsable, NOW(),'$usuario')"; 
    
        return ejecutarConsulta($sql);
        
    }

    //Implementamos un método para editar registros
    //public function editar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$lugaract,$responsableact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$tipoparticipante,$arearesponsable){ 
    public function editar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$tipoparticipante,$arearesponsable){ 
            $usuario = $_SESSION["login"];
            /*$sql="UPDATE actividades SET IDMUNICIPIO=$municipioact,
                               IDDEPARTAMENTO=$departamentoact,							   
							   IDCATEGORIAACTIVIDAD=$categoriaact,
							   IDCORREDOR=$corredoract,
							   IDINSTITUCION=$institucionact,
							   NOMBREACT='$nombreact',
							   CODIGORES=$responsableact,							   							 
							   OBJETIVOACT='$objetivoact',
							   FECHAINICIOACT='$fechaact',
							   DESCRIPCIONACT='$descripcionact',
							   LUGARACT='$lugaract',
                               IDTIPOPARTICIPANTE=NULLIF($tipoparticipante,'0'),
                               IDAREARESPONSABLE=$arearesponsable,
                               FECHAMODACT=NOW(),
                               USUMODACT='$usuario'						 
					    WHERE IDACTIVIDADES=$idactividad";*/		
        	$sql="UPDATE actividades SET IDMUNICIPIO=$municipioact,
                               IDDEPARTAMENTO=$departamentoact, /*Se agregó Departamento*/							   
							   IDCATEGORIAACTIVIDAD=$categoriaact,
							   IDCORREDOR=$corredoract,
							   IDINSTITUCION=$institucionact,
							   NOMBREACT='$nombreact',							   							 
							   OBJETIVOACT='$objetivoact',
							   FECHAINICIOACT='$fechaact',
							   DESCRIPCIONACT='$descripcionact',
                               IDTIPOPARTICIPANTE=NULLIF($tipoparticipante,'0') /*Se agregó tipo participante*/,
                               IDAREARESPONSABLE=$arearesponsable,
                               FECHAMODACT=NOW(),
                               USUMODACT='$usuario'						 
					    WHERE IDACTIVIDADES=$idactividad";		   		
        	return ejecutarConsulta($sql);		
    }

    //Función para insertar las comunidades
    public function insertarComunidades($idactividad, $actividadcomunidades) {
        foreach ($actividadcomunidades as $idcomu) {
            $sql = "INSERT INTO actividad_comunidades (IDACTIVIDADES, IDCOMUNIDAD) VALUES ('$idactividad', '$idcomu')";
            ejecutarConsulta($sql);
        }
    }
    
    //Funcion para eliminar las comunidades asociadas
    public function eliminarComunidades($idactividad) {
        $sql = "DELETE FROM actividad_comunidades WHERE IDACTIVIDADES = '$idactividad'";
        return ejecutarConsulta($sql);
    }
    
    //Función para obtener la ultima ACTIVIDAD registrada.
    public function ultimoID() {
        $sql = "SELECT MAX(IDACTIVIDADES) as ultimo_id FROM actividades";
        $query = ejecutarConsultaSimpleFila($sql);
        return $query['ultimo_id'];
    }

    //Se agregó el query para función eliminar
    public function eliminar($idactividad){
        $sql="DELETE FROM actividades WHERE IDACTIVIDADES='$idactividad'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idactividad){
        $sql="UPDATE actividades SET ESTADOACT='0' WHERE IDACTIVIDADES='$idactividad'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idactividad){
        $sql="UPDATE actividades SET ESTADOACT='1' WHERE IDACTIVIDADES='$idactividad'";
        return ejecutarConsulta($sql);
    }

    //Implementar un mastodon para mostrar los datos de un registro a modificar
    /*public function mostrar($idactividad){
        $sql="SELECT  a.IDACTIVIDADES,
                      a.NOMBREACT,
                      a.FECHAINICIOACT,
                      a.IDCATEGORIAACTIVIDAD,
                      a.IDMUNICIPIO,
                      a.IDDEPARTAMENTO,
                      a.IDCORREDOR,
                      a.IDINSTITUCION,
                      a.DURACIONACT,
                      a.OBJETIVOACT,
                      a.DESCRIPCIONACT,
                      a.FINANCIAMIENTOACT,
                      d.NOMBRECAC,
                      d.IDTIPOACTIVIDAD,
                      e.NOMBRETAC,
                      p.IDTIPOPARTICIPANTE,
                      r.IDAREARES,
                      GROUP_CONCAT(f.IDORGANIZACION) IDORG
                FROM actividades a 
                LEFT OUTER JOIN categoriaactividad d ON d.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD 
                LEFT OUTER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=d.IDTIPOACTIVIDAD
                LEFT OUTER JOIN actividadorganizacion f ON f.IDACTIVIDADES=a.IDACTIVIDADES
                LEFT OUTER JOIN organizacion g ON f.IDORGANIZACION=g.IDORGANIZACION
                LEFT OUTER JOIN tipoparticipante p ON p.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
                LEFT OUTER JOIN arearesponsable r ON r.IDAREARES=a.IDAREARESPONSABLE
                WHERE a.IDACTIVIDADES=$idactividad";
		//echo $sql;	
        return ejecutarConsultaSimpleFila($sql);
        
    }*/

    //Implementar un mastodon para mostrar los datos de un registro a modificar
    public function mostrar($idactividad){
        $sqlActividad="SELECT  a.IDACTIVIDADES,
                      a.NOMBREACT,
                      a.FECHAINICIOACT,
                      a.IDCATEGORIAACTIVIDAD,
                      a.IDMUNICIPIO,
                      a.IDDEPARTAMENTO,
                      a.IDCORREDOR,
                      a.IDINSTITUCION,
                      a.DURACIONACT,
                      a.OBJETIVOACT,
                      a.DESCRIPCIONACT,
                      a.FINANCIAMIENTOACT,
                      d.NOMBRECAC,
                      d.IDTIPOACTIVIDAD,
                      e.NOMBRETAC,
                      p.IDTIPOPARTICIPANTE,
                      r.IDAREARES,
                      GROUP_CONCAT(f.IDORGANIZACION) IDORG
                FROM actividades a 
                LEFT OUTER JOIN categoriaactividad d ON d.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD 
                LEFT OUTER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=d.IDTIPOACTIVIDAD
                LEFT OUTER JOIN actividadorganizacion f ON f.IDACTIVIDADES=a.IDACTIVIDADES
                LEFT OUTER JOIN organizacion g ON f.IDORGANIZACION=g.IDORGANIZACION
                LEFT OUTER JOIN tipoparticipante p ON p.IDTIPOPARTICIPANTE=a.IDTIPOPARTICIPANTE
                LEFT OUTER JOIN arearesponsable r ON r.IDAREARES=a.IDAREARESPONSABLE
                WHERE a.IDACTIVIDADES=$idactividad";
	
        $actividad = ejecutarConsultaSimpleFila($sqlActividad);

        //Consulta para obtener las comunidades asosiadas 
        $sqlComunidades = "SELECT IDCOMUNIDAD FROM actividad_comunidades WHERE IDACTIVIDADES='$idactividad'";
        $comunidades = ejecutarConsulta($sqlComunidades);

        //Crear un arreglo para almacenar los IDs de las comunidades
        $comus = array();
        while ($row = $comunidades->fetch_assoc()) {
            $comus[] = $row['IDCOMUNIDAD'];
        }

        // Agregar las comunidades al resultado de la actividad
        $actividad['actividad_comunidades'] = $comus;

        return $actividad;
    }

    //Implementar un método para mostrar todos los registros
    public function listar($fechainicio,$fechafin){
        $usuario        = $_SESSION["login"];
        $permisorol     = $_SESSION["permiso"];
        $orgeje         = $_SESSION["idorgeje"];
        $areares        = $_SESSION['areares'];

        //Agregamos condicionante de fechas al query
        if($fechainicio and $fechafin) {
            $where2 = ' WHERE DATE(a.FECHAINICIOACT) between "'.$fechainicio.'" and "'.$fechafin.'"';
            $where2b = 'WHERE DATE(a.FECHAINICIOACT) between "'.$fechainicio.'" and "'.$fechafin.'" AND';  
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "";
            } else {
                $where3 = ' AND a.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = ' a.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        } else if($fechainicio) {
            $where2 = ' WHERE DATE(a.FECHAINICIOACT) >=  "'.$fechainicio.'"'; 
            $where2b = 'WHERE DATE(a.FECHAINICIOACT) >=  "'.$fechainicio.'" AND ';
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "";
            } else {
                $where3 = 'AND a.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = ' a.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        } else if($fechafin) {
            $where2 = ' WHERE DATE(a.FECHAINICIOACT) <=  "'.$fechafin.'"'; 
            $where2b = 'WHERE DATE(a.FECHAINICIOACT) <=  "'.$fechafin.'" AND'; 
            //Agregamos condicionante de área responsable
            if($areares == "0"){
                //Significa que tomará todas las áreas responsables
                $where3 = "";
                $where3b = "";
            } else {
                $where3 = 'AND a.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = ' a.IDAREARESPONSABLE = "'.$areares.'" AND';
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
                $where3 = 'WHERE a.IDAREARESPONSABLE = "'.$areares.'"';
                $where3b = 'WHERE a.IDAREARESPONSABLE = "'.$areares.'" AND';
            }
        }

       if($permisorol=="1") {
           //Permiso rol == 1 es Permiso total o Acceso Total
            $sql = "SELECT a.`IDACTIVIDADES`,`NOMBREACT`,`OBJETIVOACT`,`DESCRIPCIONACT`,f.NOMBREMUN,dep.NOMBREDEP,e.NOMBRETAC,c.NOMBREINS,d.NOMBRECOR,date_format(a.FECHAINICIOACT,'%d/%m/%Y') as FECHA,`ESTADOACT`,ar.NOMBREAREARES, a.`USUREACT`,a.`IDORGEJE`
                    FROM `actividades` a LEFT OUTER JOIN institucion c ON a.`IDINSTITUCION`=c.IDINSTITUCION 
                    LEFT OUTER JOIN corredor d ON a.`IDCORREDOR`=d.IDCORREDOR 
                    LEFT OUTER JOIN categoriaactividad g ON g.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD
                    LEFT OUTER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=g.`IDTIPOACTIVIDAD` 
                    LEFT OUTER JOIN municipio f ON f.IDMUNICIPIO=a.`IDMUNICIPIO`
                    LEFT OUTER JOIN departamento dep ON dep.IDDEPARTAMENTO=a.`IDDEPARTAMENTO`
                    LEFT OUTER JOIN arearesponsable ar ON ar.IDAREARES=a.`IDAREARESPONSABLE`
                    $where2 $where3"; 
        } else if($permisorol=="2") {
            //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora
            $sql = "SELECT a.`IDACTIVIDADES`,`NOMBREACT`,`OBJETIVOACT`,`DESCRIPCIONACT`,f.NOMBREMUN,dep.NOMBREDEP,e.NOMBRETAC,c.NOMBREINS,d.NOMBRECOR,date_format(a.FECHAINICIOACT,'%d/%m/%Y') as FECHA,`ESTADOACT`,ar.NOMBREAREARES,a.`USUREACT`,a.`IDORGEJE`
                    FROM `actividades` a LEFT OUTER JOIN institucion c ON a.`IDINSTITUCION`=c.IDINSTITUCION 
                    LEFT OUTER JOIN corredor d ON a.`IDCORREDOR`=d.IDCORREDOR 
                    LEFT OUTER JOIN categoriaactividad g ON g.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD
                    LEFT OUTER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=g.`IDTIPOACTIVIDAD` 
                    LEFT OUTER JOIN municipio f ON f.IDMUNICIPIO=a.`IDMUNICIPIO`
                    LEFT OUTER JOIN departamento dep ON dep.IDDEPARTAMENTO=a.`IDDEPARTAMENTO`
                    LEFT OUTER JOIN arearesponsable ar ON ar.IDAREARES=a.`IDAREARESPONSABLE`
                    LEFT OUTER join usuarios u ON u.IDUSUARIOSUSU = a.`USUREACT`
                    $where2b $where3b a.`IDORGEJE` = '$orgeje'";
        } else {
            //Permiso rol == 0 es Permiso unicamente a los registros del usuario
            $sql = "SELECT a.`IDACTIVIDADES`,`NOMBREACT`,`OBJETIVOACT`,`DESCRIPCIONACT`,f.NOMBREMUN,dep.NOMBREDEP,e.NOMBRETAC,c.NOMBREINS,d.NOMBRECOR,date_format(a.FECHAINICIOACT,'%d/%m/%Y') as FECHA,`ESTADOACT`,ar.NOMBREAREARES,a.`USUREACT`,a.`IDORGEJE` 
                    FROM `actividades` a LEFT OUTER JOIN institucion c ON a.`IDINSTITUCION`=c.IDINSTITUCION 
                    LEFT OUTER JOIN corredor d ON a.`IDCORREDOR`=d.IDCORREDOR 
                    LEFT OUTER JOIN categoriaactividad g ON g.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD
                    LEFT OUTER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=g.`IDTIPOACTIVIDAD` 
                    LEFT OUTER JOIN municipio f ON f.IDMUNICIPIO=a.`IDMUNICIPIO` /*Se agregó departamento*/
                    LEFT OUTER JOIN departamento dep ON dep.IDDEPARTAMENTO=a.`IDDEPARTAMENTO`
                    LEFT OUTER JOIN arearesponsable ar ON ar.IDAREARES=a.`IDAREARESPONSABLE`
                    $where2b $where3b `USUREACT`='$usuario'";
        }
        return ejecutarConsulta($sql);
    }
    
    //Se agregó query para area responsable
    public function selectAreaResponsable(){ 
        //$sql="SELECT * FROM arearesponsable";
        //Validando que muestre solo los activos
        $sql="SELECT * FROM arearesponsable WHERE arearesponsable.ESTADOAREARES = 1";
        return ejecutarConsulta($sql);
    }	

    //Se agregó query para tipo de participante o Actividad dirigida a
    public function selectTipoParticipante(){ 
        //$sql="SELECT * FROM tipoparticipante";
        //Validando que muestre solo los activos
        $sql="SELECT * FROM tipoparticipante WHERE tipoparticipante.ESTADOTIP = 1";
        return ejecutarConsulta($sql);
    }

    //Se agregó el metodo selectDepartamento
    public function selectDepartamento(){ 
        $sql="SELECT * FROM departamento ORDER BY NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    //Se agregó el metodo selectDepartamento
    public function selectDepaCE($institucionact){ 
        $sql="SELECT * FROM departamento d 
        inner join institucion i ON i.IDDEPARTAMENTO=d.IDDEPARTAMENTO 
        WHERE i.IDINSTITUCION=$institucionact";
        return ejecutarConsulta($sql);
    }

    public function selectDepartamento_mostrar($departamentoact){
        $sql="SELECT * FROM departamento d 
            WHERE d.IDDEPARTAMENTO=$departamentoact
            ORDER BY d.NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    public function selectMunicipio(){ 
        $sql="SELECT * FROM municipio ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Se agregó el metodo selectDepartamento
    public function selectMunCE($institucionact){ 
        $sql="SELECT * FROM municipio m 
        inner join institucion i on i.IDMUNICIPIO=m.IDMUNICIPIO
        WHERE i.IDINSTITUCION=$institucionact";
        return ejecutarConsulta($sql);
    }

    public function selectMunicipio_mostrar($departamentoact,$municipioact){
        $sql="SELECT * FROM municipio m WHERE m.IDDEPARTAMENTO=$departamentoact AND m.IDMUNICIPIO=$municipioact ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

	public function selectTipoActividad(){
        //Validando que muestre solo los activos
        $sql="SELECT DISTINCT a.* FROM tipoactividad a 
                INNER JOIN categoriaactividad b ON a.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD 
                WHERE a.ESTADOTIPOACT = 1 
                ORDER BY NOMBRETAC ASC";
        return ejecutarConsulta($sql);
    }

    public function selectTipoAct($categoriaact){
        $sql="SELECT DISTINCT a.* FROM tipoactividad a 
                INNER JOIN categoriaactividad b ON a.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD 
                WHERE a.ESTADOTIPOACT = 1 
                AND b.IDCATEGORIAACTIVIDAD=$categoriaact
                ORDER BY NOMBRETAC ASC";
        return ejecutarConsulta($sql);
    }

    public function selCategoria(){
        //$sql="SELECT * FROM categoriaactividad a ORDER BY NOMBRECAC ASC";
        $sql="SELECT * FROM categoriaactividad WHERE ESTADOCATACT = 1 ORDER BY NOMBRECAC ASC";
        return ejecutarConsulta($sql);
    }

    //Query con tipo actividad
    public function selectCategoria($tipoact){
        //$sql="SELECT * FROM categoriaactividad a WHERE a.IDTIPOACTIVIDAD=1 ORDER BY NOMBRECAC ASC";
        $sql="SELECT * FROM categoriaactividad a WHERE a.IDTIPOACTIVIDAD=$tipoact AND ESTADOCATACT = 1 ORDER BY NOMBRECAC ASC";
        return ejecutarConsulta($sql);
    }


	public function selectOrganizacion(){
        //$sql="SELECT IDORGANIZACION,NOMBREORG FROM organizacion ORDER BY NOMBREORG ASC";
        $sql="SELECT IDORGANIZACION,NOMBREORG FROM organizacion WHERE ESTADOORG = 1 ORDER BY NOMBREORG ASC";
        return ejecutarConsulta($sql);
    }
	
	/*public function selectResponsable(){
        $sql="SELECT * FROM responsables LEFT OUTER JOIN organizacionejecutora ON responsables.IDORGEJE = organizacionejecutora.IDORGEJE where ESTADORES=1 ORDER BY responsables.NOMBRERES ASC";
        return ejecutarConsulta($sql);
    }*/

	public function selectCorredor(){
        //$sql="SELECT * FROM `corredor` WHERE `ESTADOCOR`=1 ORDER BY 1 ASC";
        //En orden alfabetico por nombre
        $sql="SELECT * FROM `corredor` WHERE `ESTADOCOR`=1 ORDER BY `NOMBRECOR` ASC";
        return ejecutarConsulta($sql);
    }
    
    //Agregamos validador de corredores 
    public function selectCorr($iddepartamento){
        $sql="SELECT * FROM corredor c 
        inner join departamento d on d.IDDEPARTAMENTO=c.IDDEPARTAMENTO 
        WHERE d.IDDEPARTAMENTO=$corredoract AND c.ESTADOCOR=1";
        return ejecutarConsulta($sql);
    }

    //Agregamos validador de corredores 
    public function selectCorrCE($institucionact){
        $sql="SELECT * FROM corredor c 
        inner join institucion i on i.IDCORREDOR=c.IDCORREDOR 
        WHERE i.IDINSTITUCION=$institucionact AND c.ESTADOCOR=1";
        return ejecutarConsulta($sql);
    }

    public function selectCorredor_mostrar($departamentoact,$corredoract){
        $sql="SELECT * FROM corredor c WHERE c.IDDEPARTAMENTO=$departamentoact AND c.IDCORREDOR=$corredoract AND c.ESTADOCOR=1 ORDER BY NOMBRECOR ASC";
        return ejecutarConsulta($sql);
    }

	public function selectInstituciones(){
        $sql="SELECT * FROM `institucion` WHERE `ESTADOINS`=1 ORDER BY 1 ASC";
        return ejecutarConsulta($sql);
    }

    public function selectInstitucion($corredoract){
        $sql="SELECT * FROM institucion a 
              inner join corredor b on a.IDCORREDOR=b.IDCORREDOR 
              WHERE a.IDCORREDOR=$corredoract AND a.ESTADOINS=1";
        return ejecutarConsulta($sql);
    }

    public function selectCentroEducativo_mostrar($corredoract,$institucionact) {
        $sql="SELECT * FROM institucion i WHERE i.IDCORREDOR=$corredoract AND i.IDINSTITUCION=$institucionact AND ESTADOINS=1 ORDER BY i.NOMBREINS ASC";
        return ejecutarConsulta($sql);
    }

    public function selectComunidades(){
        $sql="SELECT * FROM comunidad c
                LEFT OUTER JOIN municipio m ON m.IDMUNICIPIO=c.IDMUNICIPIO 
                LEFT OUTER JOIN departamento d ON d.IDDEPARTAMENTO=c.IDDEPARTAMENTO 
                WHERE c.ESTADO=1
                ORDER BY c.NOMBRECOMUNIDAD ASC;";
        return ejecutarConsulta($sql);
    }
}