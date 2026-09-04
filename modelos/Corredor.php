<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Corredor{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($plan,$nombrecor,$descripcioncor,$idalianza,$idalianza_comas,$usuario,$ip,$iddepartamento){ //Agrgamos variable $idalianza y $iddepartamento
        
        // Insertar en la tabla "corredor"
        $sql = "INSERT INTO corredor(IDPLAN, NOMBRECOR, DESCRIPCIONCOR, USUREGCOR, IPREGCOR, FECHACOR, ESTADOCOR, IDDEPARTAMENTO) VALUES($plan, '$nombrecor', '$descripcioncor', '$usuario', '$ip', NOW(), 1, '$iddepartamento')";
        ejecutarConsulta($sql);
    
        // Obtener el ID del corredor recién insertado
        $idcorredor = obtenerUltimoIDInsertado(); 
    
        // Insertar en la tabla "corredoralianza" para cada alianza seleccionada
        foreach ($idalianza_comas as $alianza) {
            $sql = "INSERT INTO corredoralianza(IDCORREDOR, IDALIANZA) VALUES($idcorredor, '$alianza')";
            ejecutarConsulta($sql);
        }
        
        return "Se registró satisfactoriamente";
    }

    //Implementamos un método para editar registros
    public function editar($idcorredor,$plan,$nombrecor,$descripcioncor,$idalianza,$idalianza_comas,$iddepartamento){ //Agregamos variable $idalianza
        
        try {
            // Actualizar la tabla "corredor"
            $sql = "UPDATE corredor SET IDPLAN=$plan, NOMBRECOR='$nombrecor', DESCRIPCIONCOR='$descripcioncor', FECHACOR=NOW(), IDDEPARTAMENTO='$iddepartamento' WHERE IDCORREDOR='$idcorredor'";
            ejecutarConsulta($sql);
            
            
            
            // Insertar las nuevas asociaciones en la tabla "corredoralianza" para cada alianza seleccionada
            if (!empty($idalianza_comas)) {
                 // Eliminar las asociaciones existentes en la tabla "corredoralianza" para el corredor
                $sql2 = "DELETE FROM corredoralianza WHERE IDCORREDOR='$idcorredor'";
                ejecutarConsulta($sql2);
                
                foreach ($idalianza_comas as $alianza) {
                    $sql3 = "INSERT INTO corredoralianza(IDCORREDOR, IDALIANZA) VALUES($idcorredor, '$alianza')";
                    ejecutarConsulta($sql3);
                }
            }
            
            return "Se modificó satisfactoriamente";
        } catch (Exception $e) {
            return "Error en la base de datos: " . $e->getMessage();
        }
    }

      //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcorredor){
        $sql="SELECT * FROM corredor WHERE IDCORREDOR='$idcorredor'";
        return ejecutarConsultaSimpleFila($sql);
    }
	
	//Implementamos un método para desactivar registros
    public function desactivar($idcorredor){
        $sql="UPDATE corredor SET ESTADOCOR=0 WHERE IDCORREDOR='$idcorredor'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idcorredor){
        $sql="UPDATE corredor SET ESTADOCOR=1 WHERE IDCORREDOR='$idcorredor'";
        return ejecutarConsulta($sql);
    }

     //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT a.*, b.*, ca.IDCORREDOR as IDCORREDOR_CA, ca.IDALIANZA as IDALIANZA_CA, o.*, d.*, GROUP_CONCAT(o.NOMBREORG SEPARATOR ', ') AS NOMBREALIANZAS
                FROM corredor a
                LEFT OUTER JOIN plan b ON a.IDPLAN = b.IDPLAN
                LEFT OUTER JOIN corredoralianza ca ON a.IDCORREDOR = ca.IDCORREDOR
                LEFT OUTER JOIN organizacion o ON o.IDORGANIZACION = ca.IDALIANZA
                LEFT OUTER JOIN departamento d ON a.IDDEPARTAMENTO = d.IDDEPARTAMENTO
                GROUP BY a.IDCORREDOR, a.NOMBRECOR"; 
        return ejecutarConsulta($sql);
    }
	public function selectPlanes(){
	   $sql ="SELECT * FROM plan";
	 return ejecutarConsulta($sql);
    }
    //Query para selectAlianza
    public function selectAlianzas(){
        $sql ="SELECT * FROM organizacion";
      return ejecutarConsulta($sql);
     }

    //Implementar un método para listar los registros y mostrar en el select Departamento
    public function selectDepartamento(){
        $sql="SELECT * FROM departamento a              
            ORDER BY NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }
    
    public function obtenerAlianzas($idcorredor) {
        $sql = "SELECT ca.IDALIANZA, o.NOMBREORG FROM corredoralianza as ca INNER JOIN organizacion as o ON ca.IDALIANZA = o.IDORGANIZACION WHERE ca.IDCORREDOR = '$idcorredor'";
        return ejecutarConsulta($sql);
    }
}