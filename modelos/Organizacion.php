<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Organizacion{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idtipoorganizacion,$nombreorg){
        $sql="INSERT INTO organizacion( IDTIPOORGANIZACION, NOMBREORG, FECHAREGORG) values('$idtipoorganizacion','$nombreorg',NOW())";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idorganizacion,$idtipoorganizacion,$nombreorg){
        $sql="UPDATE organizacion SET IDTIPOORGANIZACION='$idtipoorganizacion',NOMBREORG='$nombreorg' WHERE IDORGANIZACION='$idorganizacion'";
        return ejecutarConsulta($sql);
    }
   
    //Implementamos un método para desactivar registros
    public function desactivar($idorganizacion){
        $sql="UPDATE organizacion SET ESTADOORG='0' WHERE IDORGANIZACION='$idorganizacion'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idorganizacion){
        $sql="UPDATE organizacion SET ESTADOORG='1' WHERE IDORGANIZACION='$idorganizacion'";
        return ejecutarConsulta($sql);
    }
    
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idorganizacion){
        $sql="SELECT * FROM organizacion WHERE IDORGANIZACION='$idorganizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT o.`IDORGANIZACION`,t.`NOMBRETOR`,o.`NOMBREORG`,o.ESTADOORG FROM organizacion o INNER JOIN tipoorganizacion t ON o.`IDTIPOORGANIZACION` = t.`IDTIPOORGANIZACION`";
        return ejecutarConsulta($sql);
    }
    
     //Implementar un método para listar los registros y mostrar en el select
    public function select(){
        $sql="SELECT IDTIPOORGANIZACION,NOMBRETOR FROM tipoorganizacion ORDER BY 1;"; //Quitamos el UNION puesto que dejaba "SELECCIONE" como opción.
        //$sql="SELECT IDTIPOORGANIZACION,NOMBRETOR FROM tipoorganizacion UNION ALL SELECT 0,'SELECCIONE' ORDER BY 1;";
        return ejecutarConsulta($sql);
    }
}
?>