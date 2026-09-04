<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Arearesponsable{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombreareares){
        $sql="INSERT INTO arearesponsable(NOMBREAREARES) values('$nombreareares')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idarearesponsable,$nombreareares){
        $sql="UPDATE arearesponsable SET NOMBREAREARES='$nombreareares' WHERE IDAREARES='$idarearesponsable'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idarearesponsable){
        $sql="DELETE FROM arearesponsable WHERE IDAREARES='$idarearesponsable'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idarearesponsable){
        $sql="SELECT * FROM arearesponsable WHERE IDAREARES='$idarearesponsable'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idarearesponsable){
        $sql="UPDATE arearesponsable SET ESTADOAREARES=0 WHERE IDAREARES='$idarearesponsable'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idarearesponsable){
        $sql="UPDATE arearesponsable SET ESTADOAREARES=1 WHERE IDAREARES='$idarearesponsable'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM arearesponsable";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select
//    public function select(){
//        $sql="SELECT * FROM arearesponsable WHERE condicion=1";
//        return ejecutarConsulta($sql);
//    }
}
?>