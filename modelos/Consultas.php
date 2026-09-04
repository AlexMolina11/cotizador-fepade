<?php
//Incluimos la conexion a la base de datos
if(!isset($_SESSION)) 
{ 
session_start(); 
} 

require "../config/Conexion.php";

class Consultas{
        //Implementar nuestro constructor
        public function __construct()
        {

        }

        //Implementar un método para mostrar todos los registros

        public function consultaparticipante($municipio,$departamento,$institucion,$corredor,$tipopar){ //Se agregó el corredor y Departamento
                $idrol  = $_SESSION["idrol"];
                if($idrol=="1"){ 
                        $where = " ";
                        $where1 =" AND ";   
                }
                else{
                        $where = "AND p.ESTADOPAR=1";
                        $where1 =" AND ";
                }
                
                if ($municipio!=0 and $departamento!=0 and $institucion!=0 and $corredor!=0 and $tipopar!=0){ //Si se elige un municipio/departamento/institucion/corredor/tipopar al mismo tiempo 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agregó corredor y Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $departamento!=0 and $institucion!=0 and $corredor!=0){ //Si se elige un municipio/departamento/institucion/corredor al mismo tiempo 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' /*Se agregó corredor y Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $departamento!=0 and $institucion!=0 and $tipopar!=0){ //Si se elige un municipio/departamento/institucion/tipopar al mismo tiempo 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion AND IDTIPOPARTICIPANTE=$tipopar /*Se agregó Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $departamento!=0 and $corredor!=0 and $tipopar!=0){ //Si se elige un municipio/departamento/corredor/tipopar al mismo tiempo 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agregó Corredor y Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $institucion!=0 and $corredor!=0 and $tipopar!=0){ //Si se elige un municipio/institucion/corredor/tipopar al mismo tiempo 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agregó corredor*/
                                ORDER BY IDPARTICIPANTE DESC";
                }  
                else if ($departamento!=0 and $institucion!=0 and $corredor!=0 and $tipopar!=0){ //Si se elige un departamento/institucion/corredor/tipopar al mismo tiempo 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agregó corredor*/
                                ORDER BY IDPARTICIPANTE DESC";
                }  
                else if ($municipio!=0 and $departamento!=0 and $institucion!=0){ //Si se elige municipio/departamento/institucion 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion /*se agregó Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                }  
                else if ($municipio!=0 and $departamento!=0 and $corredor!=0){ //Si se elige municipio/departamento/corredor 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND p.IDCORREDOR='$corredor' /*se agregó Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                }  
                else if ($municipio!=0 and $departamento!=0 and $tipopar!=0){ //Si se elige municipio/departamento/tipopar 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento AND IDTIPOPARTICIPANTE=$tipopar /*se agregó Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $institucion!=0 and $corredor!=0){ //Si se elige municipio/institucion/corredor 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' /*se agregó corredor*/
                                ORDER BY IDPARTICIPANTE DESC";
                }
                else if ($municipio!=0 and $institucion!=0 and $tipopar!=0){
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDINSTITUCION=$institucion AND IDTIPOPARTICIPANTE=$tipopar
                                ORDER BY IDPARTICIPANTE DESC";
                }  
                else if ($municipio!=0 and $corredor!=0 and $tipopar!=0){ //Municipio/corredor/tipopar
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDMUNICIPIO=$municipio AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agrego Corredor*/
                                ORDER BY IDPARTICIPANTE DESC";
                }  
                else if ($departamento!=0 and $institucion!=0 and $corredor!=0){ //Departamento/institucion/corredor
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' /*Se agrego Corredor y Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($departamento!=0 and $institucion!=0 and $tipopar!=0){ //Departamento/institucion/tipopar
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion AND IDTIPOPARTICIPANTE=$tipopar /*Se agrego Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($departamento!=0 and $corredor!=0 and $tipopar!=0){ //Departamento/corredor/tipopar
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDDEPARTAMENTO=$departamento AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agrego corredor y Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($institucion!=0 and $corredor!=0 and $tipopar!=0){ //institucion/corredor/tipopar
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1  $where $where1  p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*Se agrego corredor y Departamento*/
                                ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $departamento!=0){ /*municipio/departamento*/
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDMUNICIPIO=$municipio AND p.IDDEPARTAMENTO=$departamento
                        ORDER BY IDPARTICIPANTE DESC";
                } 
                else if ($municipio!=0 and $institucion!=0){
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDMUNICIPIO=$municipio AND p.IDINSTITUCION=$institucion
                        ORDER BY IDPARTICIPANTE DESC";
                }   
                else if ($municipio!=0 and $corredor!=0){ //Si elige municipio/corredor 
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDMUNICIPIO=$municipio AND p.IDCORREDOR='$corredor' //Se agregó corredor
                        ORDER BY IDPARTICIPANTE DESC";
                }
                else if($municipio!=0 and $tipopar!=0){
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDMUNICIPIO=$municipio AND IDTIPOPARTICIPANTE=$tipopar
                        ORDER BY IDPARTICIPANTE DESC";
                } 
                else if($departamento!=0 and $institucion!=0){ //Si selecciona Departamento/Instutición 
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDDEPARTAMENTO=$departamento AND p.IDINSTITUCION=$institucion /*Se agregó corredor*/
                        ORDER BY IDPARTICIPANTE DESC";
                } 
                else if($departamento!=0 and $corredor!=0){ //Si selecciona Departamento/Instutición 
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDDEPARTAMENTO=$departamento AND p.IDCORREDOR='$corredor' /*Se agregó corredor*/
                        ORDER BY IDPARTICIPANTE DESC";
                } 
                else if($departamento!=0 and $tipopar!=0){ //Si selecciona Departamento/tipopar 
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDDEPARTAMENTO=$departamento AND IDTIPOPARTICIPANTE=$tipopar /*Se agregó corredor*/
                        ORDER BY IDPARTICIPANTE DESC";
                }
                else if($institucion!=0 and $corredor!=0){ //Si selecciona instiitución/corredor 
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDINSTITUCION=$institucion AND p.IDCORREDOR='$corredor' /*Se agregó corredor*/
                        ORDER BY IDPARTICIPANTE DESC";
                }
                else if($institucion!=0 and $tipopar!=0){
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                        WHERE 1 $where $where1 p.IDINSTITUCION=$institucion AND IDTIPOPARTICIPANTE=$tipopar
                        ORDER BY IDPARTICIPANTE DESC";
                }  
                else if($corredor!=0 and $tipopar!=0){ //si se elige corredor/tipoparticipante 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1 $where $where1 p.IDCORREDOR='$corredor' AND IDTIPOPARTICIPANTE=$tipopar /*se agregó corredor*/
                                ORDER BY IDPARTICIPANTE DESC";
                }            
                else if ($municipio!=0){ 
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1 $where $where1 p.IDMUNICIPIO=$municipio
                                ORDER BY IDPARTICIPANTE DESC";      
                }           
                else if ($departamento!=0){  //Departamento
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR
                                WHERE 1 $where $where1 p.IDDEPARTAMENTO=$departamento
                                ORDER BY IDPARTICIPANTE DESC";      
                }
                else if ($institucion!=0){
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR   
                                WHERE 1 $where $where1 p.IDINSTITUCION=$institucion
                                ORDER BY IDPARTICIPANTE DESC";
                }
                else if ($corredor!=0){ /*Si se elige corredor unicamente */
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                        INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                        INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR   
                                WHERE 1 $where $where1 p.IDCORREDOR=$corredor /*se agregó corredor*/
                                ORDER BY IDPARTICIPANTE DESC";
                }
                else if ($tipopar!=0){
                        $sql="SELECT IDPARTICIPANTE,
                                        PRIMERNOMBRE,
                                        SEGUNDOSNOMBRE,
                                        PRIMERAPELLIDO,
                                        SEGUNDOSAPELLIDO,
                                        SEXOPAR,
                                        EDADPAR,
                                        IDTIPOPARTICIPANTE,
                                        m.NOMBREMUN,
                                        i.NOMBREINS,
                                        c.NOMBRECOR,
                                        cm.NOMBRECOMUNIDAD,
                                        ESTADOPAR		
                                FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR            
                                WHERE 1 $where $where1 IDTIPOPARTICIPANTE=$tipopar
                                ORDER BY IDPARTICIPANTE DESC";
                }       
                else{                
                        $sql="SELECT IDPARTICIPANTE,
                                PRIMERNOMBRE,
                                SEGUNDOSNOMBRE,
                                PRIMERAPELLIDO,
                                SEGUNDOSAPELLIDO,
                                SEXOPAR,
                                EDADPAR,
                                IDTIPOPARTICIPANTE,
                                m.NOMBREMUN,
                                i.NOMBREINS,
                                c.NOMBRECOR,
                                cm.NOMBRECOMUNIDAD,
                                ESTADOPAR		
                        FROM participantes p INNER JOIN municipio m on p.IDMUNICIPIO=m.IDMUNICIPIO
                                INNER JOIN institucion i ON i.IDINSTITUCION=p.IDINSTITUCION                
                                INNER JOIN comunidad cm ON cm.IDCOMUNIDAD=p.IDCOMUNIDAD
                                        INNER JOIN corredor c ON c.IDCORREDOR=p.IDCORREDOR 
                                        WHERE 1 $where ORDER BY IDPARTICIPANTE DESC
                                        LIMIT 10";
                }        	  		
                #echo $sql;
                return ejecutarConsulta($sql);
        }

        public function selectDepartamento(){ 
                $sql="SELECT * FROM departamento ORDER BY NOMBREDEP ASC";
                return ejecutarConsulta($sql);
        }

        //Implementar un método para listar los registros y mostrar en el select Departamento al seleccionar el Municipio.
        public function selectDepa($idmunicipio){
                $sql="SELECT * FROM departamento d 
                INNER JOIN municipio m ON m.IDDEPARTAMENTO = d.IDDEPARTAMENTO
                WHERE m.IDMUNICIPIO=$idmunicipio
                ORDER BY d.NOMBREDEP ASC";
                return ejecutarConsulta($sql);
        }
        
        public function selectMunicipio(){ 
                $sql="SELECT * FROM municipio ORDER BY NOMBREMUN ASC";
                return ejecutarConsulta($sql);
        }
        
        //Implementar un método para listar los registros y mostrar en el select Municipio al seleccionar el Departamento.
        public function selectMuni($iddepartamento){
                $sql="SELECT * FROM municipio m              
                WHERE m.IDDEPARTAMENTO=$iddepartamento
                ORDER BY NOMBREMUN ASC";
                return ejecutarConsulta($sql);
        }

        public function selectInstitucion(){ 
                $sql="SELECT * FROM institucion i WHERE i.ESTADOINS=1 ORDER BY NOMBREINS ASC";
                return ejecutarConsulta($sql);
        }
        //Se agregá funcion selecCorrredor
        public function selectCorredor(){ 
                $sql="SELECT * FROM corredor c WHERE c.ESTADOCOR=1 ORDER BY NOMBRECOR ASC";
                return ejecutarConsulta($sql);
        }

}
?>