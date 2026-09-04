<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Responsables{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    /*public function insertar($nombreres){
	    //$codigores = substr("$nombreres",0,4).'0'.strlen($nombreres);
        $sql="INSERT INTO `responsables`(`NOMBRERES`, `ESTADORES`) VALUES (UPPER('$nombreres'),1)";
        return ejecutarConsulta($sql);
    }*/
    public function insertar($nombreres,$idorgejecutora){
        if($idorgejecutora == 0){
            $sql="INSERT INTO `responsables`(`NOMBRERES`, `IDORGEJE`, `ESTADORES`) VALUES (UPPER('$nombreres'),NULL,1)";
        } else {
            $sql="INSERT INTO `responsables`(`NOMBRERES`, `IDORGEJE`, `ESTADORES`) VALUES (UPPER('$nombreres'),$idorgejecutora,1)";
        }
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    /*public function editar($codigores,$nombreres){
        $sql="UPDATE `responsables` SET `NOMBRERES`=UPPER('$nombreres') WHERE `CODIGORES`=$codigores";
        return ejecutarConsulta($sql);
    }*/
    public function editar($codigores,$nombreres,$idorgejecutora){
        if($idorgejecutora == 0){
            $sql="UPDATE `responsables` SET `NOMBRERES`=UPPER('$nombreres'), `IDORGEJE`=NULL WHERE `CODIGORES`=$codigores";
        } else {
            $sql="UPDATE `responsables` SET `NOMBRERES`=UPPER('$nombreres'), `IDORGEJE`='$idorgejecutora' WHERE `CODIGORES`=$codigores";
        }
        return ejecutarConsulta($sql);
    }

    public function eliminar($codigores){
        $sql="DELETE FROM responsables WHERE CODIGORES='$codigores'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar comunidads
    public function desactivar($codigores){
        $sql="UPDATE `responsables` SET `ESTADORES`=0 WHERE `CODIGORES`=$codigores";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar comunidads
    public function activar($codigores){
        $sql="UPDATE `responsables` SET `ESTADORES`=1 WHERE `CODIGORES`=$codigores";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($codigores){
        //$sql="SELECT * FROM `responsables` WHERE CODIGORES=$codigores";
        $sql="SELECT * FROM `responsables` r LEFT OUTER JOIN `organizacionejecutora` o ON r.`IDORGEJE`=o.`IDORGEJE` WHERE CODIGORES=$codigores";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        //$sql="SELECT `CODIGORES`, `NOMBRERES`, `ESTADORES` FROM `responsables`";
        $sql="SELECT * FROM `responsables` r LEFT OUTER JOIN `organizacionejecutora` o ON r.`IDORGEJE`=o.`IDORGEJE`";
        return ejecutarConsulta($sql);
    }

     //Implementar un método para listar los registros y mostrar en el select
     public function selectOrganizacionEjecutora(){
        $sql="SELECT * FROM organizacionejecutora";
        return ejecutarConsulta($sql);
    }
   
}
?>