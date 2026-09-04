<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Municipio{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM municipio";
        return ejecutarConsulta($sql);
    }
}
?>