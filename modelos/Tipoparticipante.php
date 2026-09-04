<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Tipoparticipante{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombretip){
        //$sql="INSERT INTO `tipoparticipante`(`NOMBRETIP`) VALUES ('$nombretip')";
        $sql="INSERT INTO `tipoparticipante`(`NOMBRETIP`,`ESTADOTIP`) VALUES ('$nombretip',1)"; //Agregamos ESTADO con valor 1 
        #echo $sql;
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idtipoparticipante,$nombretip){
        $sql="UPDATE `tipoparticipante` SET`NOMBRETIP`='$nombretip' WHERE `IDTIPOPARTICIPANTE`=$idtipoparticipante";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar Tipo Organización
    public function desactivar($idtipoparticipante){
        $sql="UPDATE tipoparticipante SET ESTADOTIP='0' WHERE IDTIPOPARTICIPANTE='$idtipoparticipante'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar Tipo Organización
    public function activar($idtipoparticipante){
        $sql="UPDATE tipoparticipante SET ESTADOTIP='1' WHERE IDTIPOPARTICIPANTE='$idtipoparticipante'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idtipoparticipante){
        $sql="SELECT * FROM `tipoparticipante` WHERE IDTIPOPARTICIPANTE=$idtipoparticipante";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT `IDTIPOPARTICIPANTE`, `NOMBRETIP`, `ESTADOTIP` FROM `tipoparticipante` WHERE 1";
        return ejecutarConsulta($sql);
    }

}
?>