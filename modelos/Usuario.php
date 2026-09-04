<?php
//Incluimos la conexion a la base de datos
require_once "../config/Conexion.php";
class Usuario{
    //Implementar nuestro constructor
    public function __construct()
    {

    }
    
    //Implementamos un método para insertar registros
    
    public function unico($login){
        $sql="SELECT COUNT(*) cantidad FROM usuarios WHERE USERUSU like '%$login%'"; 
        return ejecutarConsulta($sql);
    }
    
    public function insertar($login,$idrol,$nombreusu,$clavehash,$email,$telusu,$extensionusu,$regusu,$ipusu,$orgejecutora){
       
        $sql="INSERT INTO usuarios(USERUSU,IDROL,NOMBREUSU,CONTRASENAUSU,EMAIL,TELUSU,EXTENSIONUSU,REGUSU,IPUSU,FECHAREGUSU,IDORGEJE) values('$login','$idrol','$nombreusu','$clavehash','$email','$telusu','$extensionusu','$regusu','$ipusu',NOW(),$orgejecutora)";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idusuariosusu,$login,$idrol,$nombreusu,$email,$telusu,$extensionusu,$orgejecutora,$modusu){
        $sql="UPDATE usuarios SET USERUSU = '$login',IDROL='$idrol',NOMBREUSU='$nombreusu', EMAIL='$email',TELUSU='$telusu',EXTENSIONUSU='$extensionusu',IDORGEJE=$orgejecutora,MODUSU='$modusu',FECHAMODUSU=NOW() WHERE IDUSUARIOSUSU='$idusuariosusu'";
        return ejecutarConsulta($sql);
       
    }

    //Implementamos un método para desactivar usuarios
    public function desactivar($idusuariosusu){
        $sql="UPDATE usuarios SET ESTADOUSU='0' WHERE IDUSUARIOSUSU='$idusuariosusu'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar usuarios
    public function activar($idusuariosusu){
        $sql="UPDATE usuarios SET ESTADOUSU='1' WHERE IDUSUARIOSUSU='$idusuariosusu'";
        return ejecutarConsulta($sql);
    }
    
    //Implementar método para actualizar clave
    public function editaClave($idusuario,$passhash){
        $sql="UPDATE usuarios SET CONTRASENAUSU='$passhash' WHERE IDUSUARIOSUSU='$idusuario'";
        return ejecutarConsulta($sql);
    }
    
    //Implementar método para actualizar clave por parte de usuario administrador
    public function editarClave($usuariosusu,$newclavehash){
        $sql="UPDATE usuarios SET CONTRASENAUSU='$newclavehash' WHERE IDUSUARIOSUSU='$usuariosusu'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idusuariosusu){
        $sql="SELECT * FROM usuarios WHERE IDUSUARIOSUSU='$idusuariosusu'";
        #echo $sql;
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM usuarios";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function roles(){
        $sql="SELECT IDROL,NOMBREROL FROM rol";
        return ejecutarConsulta($sql);
    }

    //Función para verificar el acceso al sistema
    public function verificar($login,$clave){
        $sql="SELECT IDUSUARIOSUSU,USERUSU,u.IDROL,r.`NOMBREROL`,NOMBREUSU,CONTRASENAUSU,EMAIL,PERMISOROL,u.IDORGEJE,o.NOMBREORGEJE,r.PERMISOAREARES FROM usuarios u 
        INNER JOIN rol r ON u.`IDROL`=r.`IDROL`  
        INNER JOIN organizacionejecutora o ON o.IDORGEJE = u.IDORGEJE
        WHERE USERUSU='$login' AND CONTRASENAUSU='$clave' AND ESTADOUSU='1'";
        return ejecutarConsulta($sql);
    }

    //-----FUNCIONES PARA VER LOS PERMISOS DE MÓDULOS-----
    //Función para listar permisos marcados
    public function listarmarcados($idrol) {
        $sql="SELECT * FROM acceso WHERE IDROL='$idrol'";
	  return ejecutarConsulta($sql);
    }

    //Función para Contar todos los Modulos
    public function totalmodulos() {
        $sql="SELECT COUNT(MODULOCODE) as TOTALMOD FROM modulos";
	  return ejecutarConsulta($sql);
    }

    //Función para listar Modulos
    public function codigoModulo($i) {
        $sql="SELECT MODULOCODE FROM modulos WHERE IDMODULO='$i'";
	  return ejecutarConsulta($sql);
    }

    //-----FUNCIONES PARA VER LOS PERMISOS DE ACCIONES-----
    //Función para listar permisos marcados
    public function listaraccionesmarcados($idrol) {
        $sql="SELECT * FROM accesoacciones WHERE IDROL='$idrol' ORDER BY IDACCION";
	  return ejecutarConsulta($sql);
    }

    //Función para Contar todos las Acciones
    public function totalacciones() {
        $sql="SELECT COUNT(ACCIONCODE) as TOTALACC FROM acciones";
	  return ejecutarConsulta($sql);
    }

    //Función para listar Acciones
    public function codigoAccion($j) {
        $sql="SELECT ACCIONCODE FROM acciones WHERE IDACCION='$j'";
	  return ejecutarConsulta($sql);
    }

    public function selectOrganizacionEjecutora(){ 
        $sql="SELECT * FROM organizacionejecutora WHERE ESTADOORGEJE=1";
        return ejecutarConsulta($sql);
    }

    public function listarPorRoles($roles = [])
    {
        $roles = implode(',', array_map('intval', $roles));
        $sql = "SELECT EMAIL FROM usuarios WHERE IDROL IN ($roles) AND ESTADOUSU = 1";
        return ejecutarConsulta($sql);
    }
}
?>