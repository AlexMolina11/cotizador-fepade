<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Tipoinsumo{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombretipoinsumo){
        $sql="INSERT INTO `cot_tipo_insumo`(`NOMBRETIPOINSUMO`, `ESTADOTIPOINSUMO`) VALUES (UPPER('$nombretipoinsumo'),1)";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($codigotipoinsumo,$nombretipoinsumo){
        $sql="UPDATE `cot_tipo_insumo` SET `NOMBRETIPOINSUMO`=UPPER('$nombretipoinsumo') WHERE `IDTIPOINSUMO`=$codigotipoinsumo";
        return ejecutarConsulta($sql);
    }

    public function eliminar($codigotipoinsumo){
        $sql="DELETE FROM cot_tipo_insumo WHERE IDTIPOINSUMO='$codigotipoinsumo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar comunidads
    public function desactivar($codigotipoinsumo){
        $sql="UPDATE `cot_tipo_insumo` SET `ESTADOTIPOINSUMO`=0 WHERE `IDTIPOINSUMO`=$codigotipoinsumo";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar comunidads
    public function activar($codigotipoinsumo){
        $sql="UPDATE `cot_tipo_insumo` SET `ESTADOTIPOINSUMO`=1 WHERE `IDTIPOINSUMO`=$codigotipoinsumo";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($codigotipoinsumo){
        $sql="SELECT * FROM cot_tipo_insumo WHERE IDTIPOINSUMO=$codigotipoinsumo";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT * FROM `cot_tipo_insumo`";
        return ejecutarConsulta($sql);
    }
   
}
?>