<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class RepAlianzas{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementar un método para mostrar todos los registros
    public function listar($fechainicio,$fechafin,$tipoorganizacion){
     
		if($tipoorganizacion!=0 AND $fechainicio!="" AND $fechafin!=""){
	       $sql = "SELECT QUARTER(g.FECHAINV) NoTrim, year(g.FECHAINV) Anio,a.IDALIANZA, a.NOMBREALI,a.INVERSIONALI,IF(a.estadoali=1,'EN EJECUCION','FINALIZADA')Estado,
											a.RESPONSABLESALI,
											IF(QUARTER(g.FECHAINV)=1,'Enero-Marzo',IF(QUARTER(g.FECHAINV)=2,'Abril-Junio',if(QUARTER(g.FECHAINV)=3,'Julio-Septiembre','Octubre-Diciembre'))) Trimestre,
											(SELECT GROUP_CONCAT(org.NOMBREORG) ORGA FROM organizacion org INNER JOIN alianzasorganizacion aliorg  ON org.IDORGANIZACION=aliorg.IDORGANIZACION WHERE aliorg.IDALIANZA=a.IDALIANZA) Socios,
											e.NOMBRECOR,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=1 GROUP BY d.IDALIANZA)Efectivo,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=2 GROUP BY d.IDALIANZA)Especie,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) GROUP BY d.IDALIANZA)Total      
								FROM alianzas a  
								INNER JOIN aliorginversion g ON g.IDALIANZA=a.IDALIANZA 
								INNER JOIN corredor e ON e.IDCORREDOR=a.IDCORREDOR
								INNER JOIN alianzasorganizacion f ON f.IDALIANZA=a.IDALIANZA
								INNER JOIN organizacion h ON h.IDORGANIZACION=f.IDORGANIZACION
								WHERE g.FECHAINV BETWEEN '$fechainicio' AND '$fechafin' AND h.IDTIPOORGANIZACION=$tipoorganizacion
								GROUP BY QUARTER(g.FECHAINV), year(g.FECHAINV),a.IDALIANZA,a.NOMBREALI,a.INVERSIONALI,Estado,a.RESPONSABLESALI,Trimestre,Socios,e.NOMBRECOR,Efectivo,Especie,Total
								order by  year(g.FECHAINV),QUARTER(g.FECHAINV)";
		}
		elseif($fechainicio!="" AND $fechafin!=""){ 
			$sql = "SELECT QUARTER(g.FECHAINV) NoTrim, year(g.FECHAINV) Anio,a.IDALIANZA ,a.NOMBREALI,a.INVERSIONALI,IF(a.estadoali=1,'EN EJECUCION','FINALIZADA')Estado,
										a.RESPONSABLESALI,
										IF(QUARTER(g.FECHAINV)=1,'Enero-Marzo',IF(QUARTER(g.FECHAINV)=2,'Abril-Junio',if(QUARTER(g.FECHAINV)=3,'Julio-Septiembre','Octubre-Diciembre'))) Trimestre,
										(SELECT GROUP_CONCAT(org.NOMBREORG) ORGA FROM organizacion org INNER JOIN alianzasorganizacion aliorg  ON org.IDORGANIZACION=aliorg.IDORGANIZACION WHERE aliorg.IDALIANZA=a.IDALIANZA) Socios,
										e.NOMBRECOR,
										(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
										FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=1 GROUP BY d.IDALIANZA)Efectivo,
										(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
										FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=2 GROUP BY d.IDALIANZA)Especie,
										(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
										FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) GROUP BY d.IDALIANZA)Total      
							FROM alianzas a  
							INNER JOIN aliorginversion g ON g.IDALIANZA=a.IDALIANZA 
							INNER JOIN corredor e ON e.IDCORREDOR=a.IDCORREDOR
							INNER JOIN alianzasorganizacion f ON f.IDALIANZA=a.IDALIANZA
							INNER JOIN organizacion h ON h.IDORGANIZACION=f.IDORGANIZACION
							WHERE g.FECHAINV BETWEEN '$fechainicio' AND '$fechafin'
							GROUP BY QUARTER(g.FECHAINV), year(g.FECHAINV),a.IDALIANZA,a.NOMBREALI,a.INVERSIONALI,Estado,a.RESPONSABLESALI,Trimestre,Socios,e.NOMBRECOR,Efectivo,Especie,Total
							order by  year(g.FECHAINV),QUARTER(g.FECHAINV)";
		}	  
			
		elseif($tipoorganizacion!=0){
			 $sql = "SELECT QUARTER(g.FECHAINV) NoTrim, year(g.FECHAINV) Anio,a.IDALIANZA ,a.NOMBREALI,a.INVERSIONALI,IF(a.estadoali=1,'EN EJECUCION','FINALIZADA')Estado,
											a.RESPONSABLESALI,
											IF(QUARTER(g.FECHAINV)=1,'Enero-Marzo',IF(QUARTER(g.FECHAINV)=2,'Abril-Junio',if(QUARTER(g.FECHAINV)=3,'Julio-Septiembre','Octubre-Diciembre'))) Trimestre,
											(SELECT GROUP_CONCAT(org.NOMBREORG) ORGA FROM organizacion org INNER JOIN alianzasorganizacion aliorg  ON org.IDORGANIZACION=aliorg.IDORGANIZACION WHERE aliorg.IDALIANZA=a.IDALIANZA) Socios,
											e.NOMBRECOR,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=1 GROUP BY d.IDALIANZA)Efectivo,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=2 GROUP BY d.IDALIANZA)Especie,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) GROUP BY d.IDALIANZA)Total      
								FROM alianzas a  
								INNER JOIN aliorginversion g ON g.IDALIANZA=a.IDALIANZA 
								INNER JOIN corredor e ON e.IDCORREDOR=a.IDCORREDOR
								INNER JOIN alianzasorganizacion f ON f.IDALIANZA=a.IDALIANZA
								INNER JOIN organizacion h ON h.IDORGANIZACION=f.IDORGANIZACION
								WHERE h.IDTIPOORGANIZACION=$tipoorganizacion
								GROUP BY QUARTER(g.FECHAINV), year(g.FECHAINV),a.IDALIANZA,a.NOMBREALI,a.INVERSIONALI,Estado,a.RESPONSABLESALI,Trimestre,Socios,e.NOMBRECOR,Efectivo,Especie,Total
								order by  year(g.FECHAINV),QUARTER(g.FECHAINV)";
			   }
			
			  else{
				$sql = "SELECT QUARTER(g.FECHAINV) NoTrim, year(g.FECHAINV) Anio,a.IDALIANZA ,a.NOMBREALI,a.INVERSIONALI,IF(a.estadoali=1,'EN EJECUCION','FINALIZADA')Estado,
											a.RESPONSABLESALI,
											IF(QUARTER(g.FECHAINV)=1,'Enero-Marzo',IF(QUARTER(g.FECHAINV)=2,'Abril-Junio',if(QUARTER(g.FECHAINV)=3,'Julio-Septiembre','Octubre-Diciembre'))) Trimestre,
											(SELECT GROUP_CONCAT(org.NOMBREORG) ORGA FROM organizacion org INNER JOIN alianzasorganizacion aliorg  ON org.IDORGANIZACION=aliorg.IDORGANIZACION WHERE aliorg.IDALIANZA=a.IDALIANZA) Socios,
											e.NOMBRECOR,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=1 GROUP BY d.IDALIANZA)Efectivo,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) AND d.IDTIPOINVERSION=2 GROUP BY d.IDALIANZA)Especie,
											(select IFNULL(sum(d.MONTOALM),0) as monto_efectivo	
											FROM aliorginversion d WHERE d.IDALIANZA=a.IDALIANZA AND QUARTER(d.FECHAINV)=QUARTER(g.FECHAINV) GROUP BY d.IDALIANZA)Total      
								FROM alianzas a  
								INNER JOIN aliorginversion g ON g.IDALIANZA=a.IDALIANZA 
								INNER JOIN corredor e ON e.IDCORREDOR=a.IDCORREDOR
								INNER JOIN alianzasorganizacion f ON f.IDALIANZA=a.IDALIANZA
								INNER JOIN organizacion h ON h.IDORGANIZACION=f.IDORGANIZACION								
								GROUP BY QUARTER(g.FECHAINV), year(g.FECHAINV),a.IDALIANZA,a.NOMBREALI,a.INVERSIONALI,Estado,a.RESPONSABLESALI,Trimestre,Socios,e.NOMBRECOR,Efectivo,Especie,Total
								order by  year(g.FECHAINV),QUARTER(g.FECHAINV)";
				} 
		
        return ejecutarConsulta($sql);
    }	
	
	public function selectTipoOrganizacion(){
        $sql="SELECT `IDTIPOORGANIZACION`, `NOMBRETOR` FROM `tipoorganizacion` ORDER BY NOMBRETOR ASC";
        return ejecutarConsulta($sql);
    }
	
}