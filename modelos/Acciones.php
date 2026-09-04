<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Acciones{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombreaccion,$descripcionacc,$idmodulo){
        //Debemos asignarle el id de la acción. Pero primero debemos insertar los datos y retornar el id_insertado
        $sql="INSERT INTO `acciones`(`NOMBREACC`,`DESCRIPCIONACC`,`IDMODULO`) VALUES ('$nombreaccion','$descripcionacc','$idmodulo')";
        $idaccionnew=ejecutarConsulta_retornaID($sql);
        
        //SQL para obtener el nombre de la Acción creada
        $sql_accnew="SELECT NOMBREACC, IDMODULO FROM acciones WHERE IDACCION = '$idaccionnew'";
        $nombreaccionnew=ejecutarConsulta($sql_accnew);
        $rsptNomAccNEW=$nombreaccionnew->fetch_object();
        //Haremos un UPDATE para modificar los datos de ACCIONCODE
        //Convertimos en string el NOMBREACC y luego todo en MAYUSCULAs
        $str_NombreAccNew=strtoupper(strval($rsptNomAccNEW->NOMBREACC));
        
        //Creamos el MODULOCODE
        $length = 3;
        $DigitosCODE = substr(str_repeat(0, $length).$idaccionnew, - $length); //Primeros 3 dígitos, son numericos 0ID
        $TextoCODE = substr($str_NombreAccNew, 0,3);

        //Asignamos IDMODULO
        $idmod = $rsptNomAccNEW->IDMODULO;
        //Concatenamos y obtenemos el MODULO CODE
        $AccionCodigo=$DigitosCODE.$TextoCODE.$idmod;

        //Ejecutamos consulta UPDATE
        $sql_updateaccion="UPDATE `acciones` SET `ACCIONCODE`='$AccionCodigo' WHERE `IDACCION`=$idaccionnew";
        return ejecutarConsulta($sql_updateaccion);
    }

    //Implementamos un método para editar registros
    public function editar($idaccion,$nombreaccion,$descripcionacc,$idmodulo){
        //Ejecutamos consulta UPDATE
        $sql_updateaccion="UPDATE `acciones` SET `NOMBREACC`='$nombreaccion', `DESCRIPCIONACC`='$descripcionacc', `IDMODULO`='$idmodulo' WHERE `IDACCION`=$idaccion";
        return ejecutarConsulta($sql_updateaccion);
    }
   
    
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idaccion){
        $sql="SELECT * FROM `acciones` WHERE IDACCION=$idaccion";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT a.IDACCION, a.NOMBREACC, a.DESCRIPCIONACC, m.IDMODULO, m.NOMBREMOD FROM acciones a LEFT OUTER JOIN modulos m ON m.IDMODULO = a.IDMODULO ORDER BY a.IDMODULO";
        return ejecutarConsulta($sql);
    }  
    
    //Query para selectModulo
    public function selectModulo(){
        $sql ="SELECT * FROM modulos ORDER BY MODPADRE";
      return ejecutarConsulta($sql);
     }
}
?>