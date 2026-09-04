<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Tipoorganizacion{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombretipoorg){
        //$sql="INSERT INTO tipoorganizacion(NOMBRETOR) values('$nombretipoorg')";
        $sql="INSERT INTO tipoorganizacion(NOMBRETOR,ESTADOTOR) values('$nombretipoorg',1)"; //Agregamos ESTADO con valor 1 
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idtipoorganizacion,$nombretipoorg){
        $sql="UPDATE tipoorganizacion SET NOMBRETOR='$nombretipoorg' WHERE IDTIPOORGANIZACION='$idtipoorganizacion'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar Tipo Organización
    public function desactivar($idtipoorganizacion){
        $sql="UPDATE tipoorganizacion SET ESTADOTOR='0' WHERE IDTIPOORGANIZACION='$idtipoorganizacion'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar Tipo Organización
    public function activar($idtipoorganizacion){
        $sql="UPDATE tipoorganizacion SET ESTADOTOR='1' WHERE IDTIPOORGANIZACION='$idtipoorganizacion'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtipoorganizacion){
        $sql="SELECT * FROM tipoorganizacion WHERE IDTIPOORGANIZACION='$idtipoorganizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM tipoorganizacion";
        
        return ejecutarConsulta($sql);
    }

 
}
?>