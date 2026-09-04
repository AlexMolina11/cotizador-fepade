<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Modulos{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombremod,$descripcionmo,$padreohijo,$idmodpadre,$regusu,$ipusu){
        if($padreohijo == 1){
            //Si es padre, el Modulo padre viene vacio.
            //Debemos asignarle el id del modulo. Pero primero debemos insertar los datos y retornar el id_insertado
            $sql="INSERT INTO `modulos`(`NOMBREMOD`, `DESCRIPCIONMOD`,`PADREOHIJO`) VALUES ('$nombremod','$descripcionmo',$padreohijo)";
            $idmodulonew=ejecutarConsulta_retornaID($sql);
            
            //SQL para obtener el nombre del Modulo creado
            $sql_modnew="SELECT NOMBREMOD FROM modulos WHERE IDMODULO = '$idmodulonew'";
            $nombremodnew=ejecutarConsulta($sql_modnew);
            $rsptNomModNEW=$nombremodnew->fetch_object();
            //Haremos un UPDATE para modificar los datos de MODULOCODE, MODPADRE, NIVELMOD
            //Convertimos en string el NOMBREMOD y luego todo en MAYUSCULAs
            $str_NombreModNew=strtoupper(strval($rsptNomModNEW->NOMBREMOD));
            
            //Creamos el MODULOCODE
            $length = 3;
            $DigitosCODE = substr(str_repeat(0, $length).$idmodulonew, - $length); //Primeros 3 dígitos, son numericos 0ID
            $TextoCODE = substr($str_NombreModNew, 0,3);
            //Concatenamos y obtenemos el MODULO CODE
            $ModuloCodigo=$DigitosCODE.$TextoCODE;

            //Asignamos el MODPADRE
            $modpadre=$idmodulonew;

            //Asingamos el NIVELMOD
            $nivelMod=1;

            //Ejecutamos consulta UPDATE
            $sql_updatemodulo="UPDATE `modulos` SET `MODULOCODE`='$ModuloCodigo',`MODPADRE`='$modpadre',`NIVELMOD`='$nivelMod' WHERE `IDMODULO`=$idmodulonew";
            return ejecutarConsulta($sql_updatemodulo);

        } else if($padreohijo == 0){
            //Si es hijo, se insertá el id Modulo padre
            //Debemos asignarle el id del modulo. Pero primero debemos insertar los datos y retornar el id_insertado
            $sql="INSERT INTO `modulos`(`NOMBREMOD`, `DESCRIPCIONMOD`,`PADREOHIJO`,`MODPADRE`) VALUES ('$nombremod','$descripcionmo',$padreohijo,$idmodpadre)";
            $idmodulonew=ejecutarConsulta_retornaID($sql);

            //SQL para obtener el nombre del Modulo creado
            $sql_modnew="SELECT NOMBREMOD FROM modulos WHERE IDMODULO = '$idmodulonew'";
            $nombremodnew=ejecutarConsulta($sql_modnew);
            $rsptNomModNEW=$nombremodnew->fetch_object();
            //Haremos un UPDATE para modificar los datos de MODULOCODE, MODPADRE, NIVELMOD
            //Convertimos en string el NOMBREMOD y luego todo en MAYUSCULAs
            $str_NombreModNew=strtoupper(strval($rsptNomModNEW->NOMBREMOD));
            
            //Creamos el MODULOCODE
            $length = 3;
            $DigitosCODE = substr(str_repeat(0, $length).$idmodulonew, - $length); //Primeros 3 dígitos, son numericos 0ID
            $TextoCODe = substr($str_NombreModNew, 0,3);
            //Concatenamos y obtenemos el MODULO CODE
            $ModuloCodigo=$DigitosCODE.$TextoCODe;

            //Asingamos el NIVELMOD
            $nivelMod=2;

            //Ejecutamos consulta UPDATE
            $sql_updatemodulo="UPDATE `modulos` SET `MODULOCODE`='$ModuloCodigo',`NIVELMOD`='$nivelMod' WHERE `IDMODULO`=$idmodulonew";
            return ejecutarConsulta($sql_updatemodulo);
        }
    }

    //Implementamos un método para editar registros
    public function editar($idmodulo,$nombremod,$descripcionmo,$padreohijo,$idmodpadre,$regusu,$ipusu){
        if($padreohijo == 1){
            //Significa que es padre, por ende Modulo padre viene vacío
            //Asignamos Modulo padre
            $modpadre=$idmodulo;
            //Asingamos el NIVELMOD
            $nivelMod=1;
            //Ejecutamos consulta UPDATE
            $sql_updatemodulo="UPDATE `modulos` SET `NOMBREMOD`='$nombremod',`DESCRIPCIONMOD`='$descripcionmo',`PADREOHIJO`=$padreohijo,`MODPADRE`='$modpadre',`NIVELMOD`='$nivelMod' WHERE `IDMODULO`=$idmodulo";
            return ejecutarConsulta($sql_updatemodulo);
        } else if($padreohijo == 0){
            //Significa que es hijo
            //Asingamos el NIVELMOD
            $nivelMod=2;
            //Ejecutamos consulta UPDATE
            $sql_updatemodulo="UPDATE `modulos` SET `NOMBREMOD`='$nombremod',`DESCRIPCIONMOD`='$descripcionmo',`PADREOHIJO`=$padreohijo,`MODPADRE`='$idmodpadre',`NIVELMOD`='$nivelMod' WHERE `IDMODULO`=$idmodulo";
            return ejecutarConsulta($sql_updatemodulo);
        }
    }
   
    
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idmodulo){
        $sql="SELECT * FROM `modulos` WHERE IDMODULO=$idmodulo";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT `IDMODULO`, `NOMBREMOD`, `DESCRIPCIONMOD` FROM `modulos` ORDER BY IDMODULO";
        return ejecutarConsulta($sql);
    }    

    //Query para selectModuloPadre
    public function selectModuloPadre(){
        $sql ="SELECT * FROM modulos WHERE PADREOHIJO = 1";
      return ejecutarConsulta($sql);
    }  

    //Implementamos un método para desactivar registros
    public function desactivar($idtipoactividad){
        $sql="UPDATE modulos SET ESTADOMODULO=0 WHERE IDMODULO=$idmodulo";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idtipoactividad){
        $sql="UPDATE modulos SET ESTADOMODULO=1 WHERE IDMODULO=$idmodulo";
        return ejecutarConsulta($sql);
    }
}
?>