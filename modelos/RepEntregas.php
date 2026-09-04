<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class RepEntregas{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementar un método para mostrar todos los registros
    public function listar($fechainicio,$fechafin,$catproduto){
    if($catproduto!=0 and $fechainicio!="" and $fechafin!=""){ 
	  $sql = "SELECT b.IDACTAENTREGA, 
	   				  c.IDMATERIALES,
						c.NOMBREMAT,
						b.FECHAAEN,
						d.NOMBREINS,
						b.NOMBRERECIBEAEN,
						a.CANTIDADACD 
				FROM `actaentdesc` a 
				INNER JOIN actasentrega b on a.`IDACTAENTREGA`=b.IDACTAENTREGA
				INNER JOIN materiales c on c.IDMATERIALES=a.`IDMATERIALES`
				INNER JOIN institucion d ON d.IDINSTITUCION=b.IDINSTITUCION
				WHERE b.ESTADOAEN=1 and c.IDCATEGORIAMATERIAL= $catproduto
				and DATE(b.FECHAAEN) between  '$fechainicio' and '$fechafin'
				ORDER BY b.`IDACTAENTREGA` ASC "; 
	   }
	   
    else if($fechainicio!="" and  $fechafin!=""){
	    $sql = "SELECT b.IDACTAENTREGA, 
						c.IDMATERIALES,
					c.NOMBREMAT,
					b.FECHAAEN,
					d.NOMBREINS,
					b.NOMBRERECIBEAEN,
					a.CANTIDADACD 
				FROM `actaentdesc` a 
				INNER JOIN actasentrega b on a.`IDACTAENTREGA`=b.IDACTAENTREGA
				INNER JOIN materiales c on c.IDMATERIALES=a.`IDMATERIALES`
				INNER JOIN institucion d ON d.IDINSTITUCION=b.IDINSTITUCION
				WHERE b.ESTADOAEN=1 and DATE(b.FECHAAEN) between  '$fechainicio' and '$fechafin'
				ORDER BY b.`IDACTAENTREGA` ASC "; 
	}
	 else if($catproduto!=0){ 
		$sql = "SELECT b.IDACTAENTREGA, 
						c.IDMATERIALES,
					c.NOMBREMAT,
					b.FECHAAEN,
					d.NOMBREINS,
					b.NOMBRERECIBEAEN,
					a.CANTIDADACD 
				  FROM `actaentdesc` a 
				  INNER JOIN actasentrega b on a.`IDACTAENTREGA`=b.IDACTAENTREGA
				  INNER JOIN materiales c on c.IDMATERIALES=a.`IDMATERIALES`
				  INNER JOIN institucion d ON d.IDINSTITUCION=b.IDINSTITUCION
				  WHERE b.ESTADOAEN=1 and c.IDCATEGORIAMATERIAL= $catproduto
				  ORDER BY b.`IDACTAENTREGA` ASC "; 
		 }
		 else{ 
			$sql = "SELECT b.IDACTAENTREGA, 
							c.IDMATERIALES,
						c.NOMBREMAT,
						b.FECHAAEN,
						d.NOMBREINS,
						b.NOMBRERECIBEAEN,
						a.CANTIDADACD 
					  FROM `actaentdesc` a 
					  INNER JOIN actasentrega b on a.`IDACTAENTREGA`=b.IDACTAENTREGA
					  INNER JOIN materiales c on c.IDMATERIALES=a.`IDMATERIALES`
					  INNER JOIN institucion d ON d.IDINSTITUCION=b.IDINSTITUCION
					  WHERE b.ESTADOAEN=1 
					  ORDER BY b.`IDACTAENTREGA` ASC "; 
			 }
	    //echo $sql;
		 
        return ejecutarConsulta($sql);
    }	
	
	public function selectCategorias(){
        $sql="SELECT `IDCATEGORIAMATERIAL`, `NOMBRECMA` FROM `categoriamaterial` WHERE 1";
        return ejecutarConsulta($sql);
    }
	
}