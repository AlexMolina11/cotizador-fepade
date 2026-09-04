<?php

//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Actividad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
     public function insertar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$lugaract,$responsableact,$municipioact,$categoriaact,$corredoract,$institucionact,$financiamientoact,$usuario,$ip,$idorganizacion){
	    $usuario = $_SESSION["login"];
        $ip= $_SERVER["REMOTE_ADDR"];
        $fecha = substr($fechaact, 6, 4)."-".substr($fechaact, 3, 2)."-".substr($fechaact, 0, 2);
		
       	$sql="INSERT INTO actividades(IDMUNICIPIO, IDCATEGORIAACTIVIDAD, IDCORREDOR, IDINSTITUCION, NOMBREACT, CODIGORES, OBJETIVOACT, FECHAINICIOACT, DESCRIPCIONACT, LUGARACT, FINANCIAMIENTOACT, USUREACT, IPREGACT, FECHAREGACT, ESTADOACT) 
		      values($municipioact,$categoriaact,$corredoract,$institucionact,'$nombreact','$responsableact','$objetivoact','$fecha','$descripcionact','$lugaract','$financiamientoact','$usuario','$ip',NOW(),1)";
     	if($idorganizacion!=null){
		$idactividadnew=ejecutarConsulta_retornaID($sql);
        $num_elementos=0;
        $sw=TRUE;
        while ($num_elementos< count($idorganizacion)){
            $sql_detalle="INSERT INTO `actividadorganizacion`(`IDACTIVIDADES`, `IDORGANIZACION`) VALUES ($idactividadnew,'$idorganizacion[$num_elementos]')";           
            ejecutarConsulta($sql_detalle) or $sw=FALSE;            
            $num_elementos=$num_elementos+1;
        }        
        return $sw;
		}
		else{
		return ejecutarConsulta($sql);
		}
    }

    //Implementamos un método para editar registros
    public function editar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$lugaract,$responsableact,$municipioact,$categoriaact,$corredoract,$institucionact,$financiamientoact){
	    $fecha = substr($fechaact, 6, 4)."-".substr($fechaact, 3, 2)."-".substr($fechaact, 0, 2);
        $sql="UPDATE actividades SET IDMUNICIPIO=$municipioact,							   
							   IDCATEGORIAACTIVIDAD=$categoriaact,
							   IDCORREDOR=$corredoract,
							   IDINSTITUCION=$institucionact,
							   NOMBREACT='$nombreact',
							   CODIGORES=$responsableact,							   							 
							   OBJETIVOACT='$objetivoact',
							   FECHAINICIOACT='$fecha',
							   DESCRIPCIONACT='$descripcionact',
							   LUGARACT='$lugaract',
							   FINANCIAMIENTOACT='$financiamientoact'								 
					    WHERE IDACTIVIDADES=$idactividad";		   		
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
    public function mostrar($idactividad){
        $sql="SELECT a.*,d.*,e.* FROM actividades a 
				INNER JOIN categoriaactividad d 
				ON d.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD 
				INNER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=d.IDTIPOACTIVIDAD 
				WHERE a.IDACTIVIDADES=$idactividad";
		//echo $sql;	
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
       $usuario        = $_SESSION["login"];
       $permisorol     = $_SESSION["permiso"];
       if($permisorol=="1"){
       $sql = "SELECT a.`IDACTIVIDADES`,`NOMBREACT`,`OBJETIVOACT`,f.NOMBREMUN,e.NOMBRETAC,b.NOMBRERES,c.NOMBREINS,d.NOMBRECOR,date_format(a.FECHAINICIOACT,'%d/%m/%Y') as FECHA,`LUGARACT`,`ESTADOACT` 
                FROM `actividades` a INNER JOIN institucion c ON a.`IDINSTITUCION`=c.IDINSTITUCION 
                INNER JOIN corredor d ON a.`IDCORREDOR`=d.IDCORREDOR 
                INNER JOIN responsables b ON b.CODIGORES=a.`CODIGORES` 
                INNER JOIN categoriaactividad g ON g.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD
                INNER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=g.`IDTIPOACTIVIDAD` 
                INNER JOIN municipio f ON f.IDMUNICIPIO=a.`IDMUNICIPIO`";
       
        }
       else{
       $sql = "SELECT a.`IDACTIVIDADES`,`NOMBREACT`,`OBJETIVOACT`,f.NOMBREMUN,e.NOMBRETAC,b.NOMBRERES,c.NOMBREINS,d.NOMBRECOR,date_format(a.FECHAINICIOACT,'%d/%m/%Y') as FECHA,`LUGARACT`,`ESTADOACT` 
               FROM `actividades` a INNER JOIN institucion c ON a.`IDINSTITUCION`=c.IDINSTITUCION 
               INNER JOIN corredor d ON a.`IDCORREDOR`=d.IDCORREDOR 
               INNER JOIN responsables b ON b.CODIGORES=a.`CODIGORES` 
               INNER JOIN categoriaactividad g ON g.IDCATEGORIAACTIVIDAD=a.IDCATEGORIAACTIVIDAD
               INNER JOIN tipoactividad e ON e.IDTIPOACTIVIDAD=g.`IDTIPOACTIVIDAD` 
               INNER JOIN municipio f ON f.IDMUNICIPIO=a.`IDMUNICIPIO`
               WHERE `USUREACT`='$usuario'";
        }
        return ejecutarConsulta($sql);
    }	
	 public function selectTipoActividad(){
        $sql="SELECT DISTINCT a.* FROM tipoactividad a 
              INNER JOIN categoriaactividad b ON a.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD
              ORDER BY NOMBRETAC ASC";
        return ejecutarConsulta($sql);
    }
	public function selectOrganizacion(){
        $sql="SELECT IDORGANIZACION,NOMBREORG FROM organizacion ORDER BY NOMBREORG ASC";
        return ejecutarConsulta($sql);
    }
	public function selectMunicipio(){ 
        $sql="SELECT * FROM municipio ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }
	public function selectCategoria($tipoact){
        $sql="SELECT * FROM categoriaactividad a              
              WHERE a.IDTIPOACTIVIDAD=$tipoact 
              ORDER BY NOMBRECAC ASC";
        return ejecutarConsulta($sql);
    }
	public function selectResponsable(){
        $sql="SELECT * FROM responsables where ESTADORES=1 ORDER BY NOMBRERES ASC";
        return ejecutarConsulta($sql);
    }
	public function selectCorredor(){
        $sql="SELECT * FROM `corredor` WHERE `ESTADOCOR`=1 ORDER BY 1 ASC";
        return ejecutarConsulta($sql);
    }
	public function selectInstituciones(){
        $sql="SELECT * FROM `institucion` WHERE `ESTADOINS`=1 ORDER BY 1 ASC";
        return ejecutarConsulta($sql);
    }
    public function selectInstitucion($corredoract){
        $sql="SELECT * FROM institucion a 
              inner join corredor b on a.IDCORREDOR=b.IDCORREDOR 
              WHERE a.IDCORREDOR=$corredoract";
        return ejecutarConsulta($sql);
    }
	public function selCategoria(){
        $sql="SELECT * FROM categoriaactividad a              
              ORDER BY NOMBRECAC ASC";
        return ejecutarConsulta($sql);
    }
}