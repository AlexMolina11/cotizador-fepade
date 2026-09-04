<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Grupoasignado{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
     public function insertar($idgrupo,$participante){
	    $num_elementos=0;
		$sw=TRUE;
		 while ($num_elementos< count($participante)){
			$sql="INSERT INTO `participantegrupo`(`IDGRUPO`, `IDPARTICIPANTE`) VALUES ($idgrupo,'$participante[$num_elementos]')";
			 ejecutarConsulta($sql) or $sw=FALSE;
			 //echo $sql;
			 $num_elementos=$num_elementos+1;
        }        
        return $sw;	       
    }

    //Implementamos un método para editar registros
    public function editar($idgrupo,$participante){
    	$num_elementos=0;
		$sw=TRUE;
		 while ($num_elementos> count($participante)){
        $sql="UPDATE `participantegrupo` SET `IDPARTICIPANTE`='$participante[$num_elementos]' WHERE `IDGRUPO`=$idgrupo";
        ejecutarConsulta($sql) or $sw=FALSE;
			 //echo $sql;
			 $num_elementos=$num_elementos+1;
        }        
        return $sw;	       
	}
	
	  //Implementamos un método para eliminar registros
	  public function eliminar($idgrupo,$participante){
        $sw=TRUE;
    	$sql="DELETE FROM `participantegrupo` WHERE `IDGRUPO`=$idgrupo AND `IDPARTICIPANTE`=$participante";
        ejecutarConsulta($sql) or $sw=FALSE;
			
        return $sw;	       
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idgrupo){
        $sql="SELECT * FROM `participantegrupo` a 
              INNER JOIN grupo b ON a.`IDGRUPO`=b.IDGRUPO
			  INNER JOIN participantes c ON c.IDPARTICIPANTE=a.`IDPARTICIPANTE`
		      WHERE a.IDGRUPO=$idgrupo";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
       	$sql = "SELECT b.`IDGRUPO`, c.`IDPARTICIPANTE`,b.NOMBREGRUPO,concat_ws(' ',c.PRIMERNOMBRE,c.SEGUNDOSNOMBRE,c.PRIMERAPELLIDO,c.SEGUNDOSAPELLIDO) AS NOMBRE,
					d.NOMBREMUN,e.NOMBRETIP,c.SEXOPAR,c.EDADPAR  
				FROM `participantegrupo` a 
				INNER JOIN grupo b ON a.`IDGRUPO`=b.IDGRUPO
				INNER JOIN participantes c ON c.IDPARTICIPANTE=a.`IDPARTICIPANTE`
				INNER JOIN municipio d ON d.IDMUNICIPIO=c.IDMUNICIPIO
				INNER JOIN tipoparticipante e ON e.IDTIPOPARTICIPANTE=c.IDTIPOPARTICIPANTE";
        return ejecutarConsulta($sql);
    }	

	public function selectGrupo(){
        $sql="SELECT * FROM grupo WHERE ESTADOGRUPO=1";
        return ejecutarConsulta($sql);
    }

	public function selectTipo(){
        $sql="SELECT * FROM tipoparticipante WHERE ESTADOTIP=1";
        return ejecutarConsulta($sql);
    }

	public function selectMunicipio(){
        $sql="SELECT * FROM `municipio`";
        return ejecutarConsulta($sql);
    }

	public function listarParticipantes($municipioact,$tipoparticipante){
		
		/*if($tipoparticipante!=0 AND $municipioact!=0) {
			$sql ="SELECT `IDPARTICIPANTE`, 
		              concat_ws(' ',`PRIMERNOMBRE`, 
					           `SEGUNDOSNOMBRE`, 
							   `PRIMERAPELLIDO`, 
							   `SEGUNDOSAPELLIDO`) AS NOMBRE 
			FROM `participantes` p
			WHERE p.`IDTIPOPARTICIPANTE`=$tipoparticipante AND p.`IDMUNICIPIO`=$municipioact
			ORDER BY NOMBRE ASC";
		} 
		
		if($municipioact!=0){
			$sql="SELECT `IDPARTICIPANTE`, 
		              concat_ws(' ',`PRIMERNOMBRE`, 
					           `SEGUNDOSNOMBRE`, 
							   `PRIMERAPELLIDO`, 
							   `SEGUNDOSAPELLIDO`) AS NOMBRE 
			FROM `participantes` p WHERE p.`IDMUNICIPIO`=$municipioact ORDER BY NOMBRE ASC";
		}

		if ($tipoparticipante!=0){
			$sql ="SELECT `IDPARTICIPANTE`, 
						concat_ws(' ',`PRIMERNOMBRE`, 
								`SEGUNDOSNOMBRE`, 
								`PRIMERAPELLIDO`, 
								`SEGUNDOSAPELLIDO`) AS NOMBRE 
				FROM `participantes` p WHERE p.`IDTIPOPARTICIPANTE`=$tipoparticipante ORDER BY NOMBRE ASC";
				}
			else{
				$sql ="SELECT `IDPARTICIPANTE`, 
							concat_ws(' ',`PRIMERNOMBRE`, 
							`SEGUNDOSNOMBRE`, 
							`PRIMERAPELLIDO`, 
							`SEGUNDOSAPELLIDO`) AS NOMBRE 
						FROM `participantes` p 
						ORDER BY NOMBRE ASC
						LIMMIT 0";
		}*/

		//Se crearon query acorde a si municipio o participante recibe un valor o si este es cero
		if($municipioact == 0 && $tipoparticipante == 0) {
			/*$sql ="SELECT `IDPARTICIPANTE`, 
		              concat_ws(' ',`PRIMERNOMBRE`, 
					           `SEGUNDOSNOMBRE`, 
							   `PRIMERAPELLIDO`, 
							   `SEGUNDOSAPELLIDO`) AS NOMBRE 
			FROM `participantes` p
			ORDER BY NOMBRE ASC";*/
		} else if ($municipioact == 0) {
			$sql ="SELECT `IDPARTICIPANTE`, 
		              concat_ws(' ',`PRIMERNOMBRE`, 
					           `SEGUNDOSNOMBRE`, 
							   `PRIMERAPELLIDO`, 
							   `SEGUNDOSAPELLIDO`) AS NOMBRE 
			FROM `participantes` p
			WHERE p.`IDTIPOPARTICIPANTE`=$tipoparticipante
			ORDER BY NOMBRE ASC";
		} else if ($tipoparticipante == 0) {
			$sql ="SELECT `IDPARTICIPANTE`, 
		              concat_ws(' ',`PRIMERNOMBRE`, 
					           `SEGUNDOSNOMBRE`, 
							   `PRIMERAPELLIDO`, 
							   `SEGUNDOSAPELLIDO`) AS NOMBRE 
			FROM `participantes` p
			WHERE p.`IDMUNICIPIO`=$municipioact
			ORDER BY NOMBRE ASC";
		} else {
			$sql ="SELECT `IDPARTICIPANTE`, 
		              concat_ws(' ',`PRIMERNOMBRE`, 
					           `SEGUNDOSNOMBRE`, 
							   `PRIMERAPELLIDO`, 
							   `SEGUNDOSAPELLIDO`) AS NOMBRE 
			FROM `participantes` p
			WHERE p.`IDTIPOPARTICIPANTE`=$tipoparticipante AND p.`IDMUNICIPIO`=$municipioact
			ORDER BY NOMBRE ASC";
		}

        return ejecutarConsulta($sql);
    }

    public function listarSeleccionados($idgrupo){
       	$sql = "SELECT b.`IDGRUPO`, c.`IDPARTICIPANTE`,b.NOMBREGRUPO,concat_ws(' ',c.PRIMERNOMBRE,c.SEGUNDOSNOMBRE,c.PRIMERAPELLIDO,c.SEGUNDOSAPELLIDO) AS NOMBRE,
					   d.NOMBREMUN,e.NOMBRETIP,c.SEXOPAR,c.EDADPAR,c.IDTIPOPARTICIPANTE, c.IDMUNICIPIO 
				FROM `participantegrupo` a 
				INNER JOIN grupo b ON a.`IDGRUPO`=b.IDGRUPO
				INNER JOIN participantes c ON c.IDPARTICIPANTE=a.`IDPARTICIPANTE`
				INNER JOIN municipio d ON d.IDMUNICIPIO=c.IDMUNICIPIO
				INNER JOIN tipoparticipante e ON e.IDTIPOPARTICIPANTE=c.IDTIPOPARTICIPANTE
				WHERE b.`IDGRUPO`=$idgrupo";
        return ejecutarConsulta($sql);
    }	
	
	
}