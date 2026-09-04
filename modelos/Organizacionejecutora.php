<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Organizacionejecutora{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombreorgeje){
        $sql="INSERT INTO `organizacionejecutora`(`NOMBREORGEJE`, `ESTADOORGEJE`) VALUES (UPPER('$nombreorgeje'),1)";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($codigororgeje,$nombreorgeje){
        $sql="UPDATE `organizacionejecutora` SET `NOMBREORGEJE`=UPPER('$nombreorgeje') WHERE `IDORGEJE`=$codigororgeje";
        return ejecutarConsulta($sql);
    }

    public function eliminar($codigororgeje){
        $sql="DELETE FROM organizacionejecutora WHERE IDORGEJE='$codigororgeje'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar comunidads
    public function desactivar($codigororgeje){
        $sql="UPDATE `organizacionejecutora` SET `ESTADOORGEJE`=0 WHERE `IDORGEJE`=$codigororgeje";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar comunidads
    public function activar($codigororgeje){
        $sql="UPDATE `organizacionejecutora` SET `ESTADOORGEJE`=1 WHERE `IDORGEJE`=$codigororgeje";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($codigororgeje){
        $sql="SELECT * FROM organizacionejecutora WHERE IDORGEJE=$codigororgeje";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM `organizacionejecutora`";
        return ejecutarConsulta($sql);
    }
   
}
?>