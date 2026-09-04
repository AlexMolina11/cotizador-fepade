<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Tipoactividad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombretac){
        $sql="INSERT INTO tipoactividad(NOMBRETAC) values('$nombretac')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idtipoactividad,$nombretac){
        $sql="UPDATE tipoactividad SET NOMBRETAC='$nombretac' WHERE IDTIPOACTIVIDAD='$idtipoactividad'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idtipoactividad){
        $sql="DELETE FROM tipoactividad WHERE IDTIPOACTIVIDAD='$idtipoactividad'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtipoactividad){
        $sql="SELECT * FROM tipoactividad WHERE IDTIPOACTIVIDAD='$idtipoactividad'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idtipoactividad){
        $sql="UPDATE tipoactividad SET ESTADOTIPOACT=0 WHERE IDTIPOACTIVIDAD='$idtipoactividad'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idtipoactividad){
        $sql="UPDATE tipoactividad SET ESTADOTIPOACT=1 WHERE IDTIPOACTIVIDAD='$idtipoactividad'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM tipoactividad";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select
//    public function select(){
//        $sql="SELECT * FROM tipoactividad WHERE condicion=1";
//        return ejecutarConsulta($sql);
//    }
}
?>