<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Asignargrupo{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
     public function insertar($idgrupo,$participante){
        $sql="INSERT INTO `participantegrupo`(`IDGRUPO`, `IDPARTICIPANTE`) VALUES ($idgrupo,$participante)";
        return ejecutarConsulta($sql);
    }

}