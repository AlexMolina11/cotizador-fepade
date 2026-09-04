<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Grupo{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    //public function insertar($nombregrupo){
    public function insertar($nombregrupo,$iddepartamento,$idmunicipio,$idcentroedu){ //Agregamos Municipio y centro educativo
        //$sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO) values('$nombregrupo',1)";
        //Al no seleccionar departamento, municipio o centro educativo este nos registra un valor 0, es por ello que debemos modificar el query a tal modo que este sea NULL
        if($iddepartamento == 0 && $idmunicipio == 0 && $idcentroedu == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,NULL,NULL,NULL)"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($iddepartamento == 0 && $idmunicipio == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,NULL,NULL,'$idcentroedu')"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($iddepartamento == 0 && $idcentroedu == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,NULL,'$idmunicipio',NULL)"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($idmunicipio == 0 && $idcentroedu == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,'$iddepartamento',NULL,NULL)"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($iddepartamento == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,NULL,'$idmunicipio','$idcentroedu')"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($idmunicipio == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,'$iddepartamento',NULL,'$idcentroedu')"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } if($idcentroedu == 0) {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,'$iddepartamento','$idmunicipio',NULL)"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);
        } else {
            $sql="INSERT INTO grupo(NOMBREGRUPO,ESTADOGRUPO,IDDEPARTAMENTO,IDMUNICIPIO,IDINSTITUCION) values('$nombregrupo',1,'$iddepartamento','$idmunicipio','$idcentroedu')"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);
        }
    }

    //Implementamos un método para editar registros
    //public function editar($idgrupo,$nombregrupo){
    public function editar($idgrupo,$nombregrupo,$iddepartamento,$idmunicipio,$idcentroedu){ //Agregamos Municipio y centro educativo
        //$sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo' WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'";
        
        //Al no seleccionar departamento, municipio o centro educativo este nos registra un valor 0, es por ello que debemos modificar el query a tal modo que este sea NULL
        if($iddepartamento == 0 && $idmunicipio == 0 && $idcentroedu == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=NULL, IDMUNICIPIO=NULL, IDINSTITUCION=NULL WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($iddepartamento == 0 && $idmunicipio == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=NULL, IDMUNICIPIO=NULL, IDINSTITUCION=$idcentroedu WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($iddepartamento == 0 && $idcentroedu == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=NULL, IDMUNICIPIO=$idmunicipio, IDINSTITUCION=NULL WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($idmunicipio == 0 && $idcentroedu == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=$iddepartamento, IDMUNICIPIO=NULL, IDINSTITUCION=NULL WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($iddepartamento == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=NULL, IDMUNICIPIO='$idmunicipio', IDINSTITUCION=$idcentroedu WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } else if($idmunicipio == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=$iddepartamento, IDMUNICIPIO=NULL, IDINSTITUCION=$idcentroedu WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);

        } if($idcentroedu == 0) {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=$iddepartamento, IDMUNICIPIO=$idmunicipio, IDINSTITUCION=NULL WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);
        } else {
            $sql="UPDATE grupo SET NOMBREGRUPO='$nombregrupo', IDDEPARTAMENTO=$iddepartamento, IDMUNICIPIO=$idmunicipio, IDINSTITUCION=$idcentroedu WHERE IDGRUPO='$idgrupo' AND ESTADOGRUPO='1'"; //Agregamos Municipio y centro educativo
            return ejecutarConsulta($sql);
        }
    }

    //Se agregó query para eliminar
    public function eliminar($idgrupo){
        $sql="DELETE FROM grupo WHERE IDGRUPO='$idgrupo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar grupos
    public function desactivar($idgrupo){
        $sql="UPDATE grupo SET ESTADOGRUPO='0' WHERE IDGRUPO='$idgrupo'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar grupos
    public function activar($idgrupo){
        $sql="UPDATE grupo SET ESTADOGRUPO='1' WHERE IDGRUPO='$idgrupo'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idgrupo){
        $sql="SELECT * FROM grupo WHERE IDGRUPO='$idgrupo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        //$sql="SELECT * FROM grupo";
        $sql="SELECT 
        g.`IDGRUPO`,
        g.`NOMBREGRUPO`,
        IFNULL(d.`NOMBREDEP`,'N/A')NOMBREDEP,
        IFNULL(m.`NOMBREMUN`,'N/A')NOMBREMUN,
        IFNULL(i.`NOMBREINS`,'N/A')NOMBREINS,
        g.`ESTADOGRUPO`
      FROM 
        grupo g
        LEFT OUTER JOIN departamento d
            ON g.`IDDEPARTAMENTO` = d.`IDDEPARTAMENTO`
        LEFT OUTER JOIN municipio m
            ON g.`IDMUNICIPIO` = m.`IDMUNICIPIO`
        LEFT OUTER JOIN institucion i
            ON g.`IDINSTITUCION` = i.`IDINSTITUCION`";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select
//    public function select(){
//        $sql="SELECT * FROM grupo WHERE condicion=1";
//        return ejecutarConsulta($sql);
//    }
    //Agregamos Municipio y Departamento
    //Implementar un método para listar los registros y mostrar en el select Municipio
    public function selectMunicipio(){
        $sql="SELECT DISTINCT a.* FROM municipio a 
            INNER JOIN departamento b ON a.IDDEPARTAMENTO=b.IDDEPARTAMENTO
            ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Departamento
    public function selectDepartamento(){
        $sql="SELECT * FROM departamento a              
            ORDER BY NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Municipio al seleccionar el Departamento.
    public function selectMuni($iddepartamento){
        $sql="SELECT * FROM municipio m              
            WHERE m.IDDEPARTAMENTO=$iddepartamento
            ORDER BY NOMBREMUN ASC";
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

    //Implementar un método para listar los registros y mostrar en el select Centro Educativo
    public function selectCentroEducativo(){
        $sql="SELECT * FROM institucion";
        return ejecutarConsulta($sql);
    }
}
?>