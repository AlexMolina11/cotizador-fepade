<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Discapacidad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombredis){
        $sql="INSERT INTO `discapacidad`(`NOMBREDIS`) VALUES (UPPER('$nombredis'))";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($iddiscapacidad,$nombredis){
        $sql="UPDATE `discapacidad` SET `NOMBREDIS`=UPPER('$nombredis') WHERE IDDISCAPACIDAD=$iddiscapacidad";
        return ejecutarConsulta($sql);
    }
   
        
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($iddiscapacidad){
        $sql="SELECT * FROM `discapacidad` WHERE `IDDISCAPACIDAD`=$iddiscapacidad";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM `discapacidad` ORDER BY IDDISCAPACIDAD ASC";
        return ejecutarConsulta($sql);
    }
    
  
}
?>