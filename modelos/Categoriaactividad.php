<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Categoriaactividad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombrecac,$idtipoactividad){
        $sql="INSERT INTO categoriaactividad(NOMBRECAC,IDTIPOACTIVIDAD) values('$nombrecac',$idtipoactividad)";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idcategoriaactividad,$nombrecac,$idtipoactividad){
        $sql="UPDATE categoriaactividad SET NOMBRECAC='$nombrecac',IDTIPOACTIVIDAD=$idtipoactividad WHERE IDCATEGORIAACTIVIDAD='$idcategoriaactividad'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idcategoriaactividad){
        $sql="DELETE FROM categoriaactividad WHERE IDCATEGORIAACTIVIDAD='$idcategoriaactividad'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcategoriaactividad){
        $sql="SELECT * FROM categoriaactividad a INNER JOIN tipoactividad b ON a.IDTIPOACTIVIDAD=b.IDTIPOACTIVIDAD WHERE IDCATEGORIAACTIVIDAD=$idcategoriaactividad ";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idcategoriaactividad){
        $sql="UPDATE categoriaactividad SET ESTADOCATACT=0 WHERE IDCATEGORIAACTIVIDAD='$idcategoriaactividad'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idcategoriaactividad){
        $sql="UPDATE categoriaactividad SET ESTADOCATACT=1 WHERE IDCATEGORIAACTIVIDAD='$idcategoriaactividad'";
        return ejecutarConsulta($sql);
        }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM categoriaactividad a inner join  `tipoactividad` b WHERE a.IDTIPOACTIVIDAD=b.`IDTIPOACTIVIDAD`";
        return ejecutarConsulta($sql);
    }

    public function selectTipoActividad(){
        $sql="SELECT * FROM tipoactividad ORDER BY NOMBRETAC ASC";
        echo $sql;
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select
//    public function select(){
//        $sql="SELECT * FROM categoriaactividad WHERE condicion=1";
//        return ejecutarConsulta($sql);
//    }
}
?>