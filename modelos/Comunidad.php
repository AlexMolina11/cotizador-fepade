<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Comunidad{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para consultar si existe el registro
    public function consulta_existencia($nombrecomunidad,$idmunicipio,$idnvomunicipio,$iddepartamento){
        //VALIDAR SI EXISTE UNO CON LOS MISMOS DATOS
        $sql_consult = "SELECT * FROM comunidad WHERE NOMBRECOMUNIDAD = '$nombrecomunidad' AND IDMUNICIPIO = $idmunicipio AND IDNVOMUN = $idnvomunicipio AND IDDEPARTAMENTO = $iddepartamento";
        return ejecutarConsultaSimpleFila($sql_consult);
    }

    //Implementamos un método para insertar registros
    //public function insertar($nombrecomunidad){
    public function insertar($nombrecomunidad, $idmunicipio, $idnvomunicipio, $iddepartamento, $latitud, $longitud) {
        // Inicializamos un arreglo con las columnas y sus valores correspondientes
        $columns = ['NOMBRECOMUNIDAD', 'IDMUNICIPIO', 'IDDEPARTAMENTO', 'IDNVOMUN', 'LATITUD', 'LONGITUD'];
        $values = ["'$nombrecomunidad'"];
    
        // Agregamos los valores correspondientes a las columnas
        $values[] = $idmunicipio == 0 ? 'NULL' : "'$idmunicipio'";
        $values[] = $iddepartamento == 0 ? 'NULL' : "'$iddepartamento'";
        $values[] = $idnvomunicipio == 0 ? 'NULL' : "'$idnvomunicipio'";
        $values[] = "'$latitud'";
        $values[] = "'$longitud'";
    
        // Convertimos los arreglos a cadenas separadas por comas
        $columns_str = implode(', ', $columns);
        $values_str = implode(', ', $values);
    
        // Construimos la consulta SQL
        $sql = "INSERT INTO comunidad($columns_str) VALUES ($values_str)";
    
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idcomunidad, $nombrecomunidad, $idmunicipio, $idnvomunicipio, $iddepartamento, $latitud, $longitud) {
        // Inicializamos un arreglo con las asignaciones de las columnas y sus valores correspondientes
        $set = ["NOMBRECOMUNIDAD='$nombrecomunidad'"];
        $set[] = "LATITUD='$latitud'";
        $set[] = "LONGITUD='$longitud'";
    
        // Agregamos las asignaciones correspondientes a las columnas
        $set[] = $idmunicipio == 0 ? 'IDMUNICIPIO=NULL' : "IDMUNICIPIO='$idmunicipio'";
        $set[] = $iddepartamento == 0 ? 'IDDEPARTAMENTO=NULL' : "IDDEPARTAMENTO='$iddepartamento'";
        $set[] = $idnvomunicipio == 0 ? 'IDNVOMUN=NULL' : "IDNVOMUN='$idnvomunicipio'";
    
        // Convertimos el arreglo a una cadena separada por comas
        $set_str = implode(', ', $set);
    
        // Construimos la consulta SQL
        $sql = "UPDATE comunidad SET $set_str WHERE IDCOMUNIDAD='$idcomunidad' AND ESTADO='1'";
    
        return ejecutarConsulta($sql);
    }

    public function eliminar($idcomunidad){
        $sql="DELETE FROM comunidad WHERE IDCOMUNIDAD='$idcomunidad'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar comunidads
    public function desactivar($idcomunidad){
        $sql="UPDATE comunidad SET ESTADO='0' WHERE IDCOMUNIDAD='$idcomunidad'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar comunidads
    public function activar($idcomunidad){
        $sql="UPDATE comunidad SET ESTADO='1' WHERE IDCOMUNIDAD='$idcomunidad'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcomunidad){
        $sql="SELECT * FROM comunidad WHERE IDCOMUNIDAD='$idcomunidad'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT 
                c.`IDCOMUNIDAD`,
                c.`NOMBRECOMUNIDAD`,
                IFNULL(m.`NOMBREMUN`,'N/A')NOMBREMUN,
                IFNULL(nm.`NOMBRENVOMUN`,'N/A')NOMBRENVOMUN,
                IFNULL(d.`NOMBREDEP`,'N/A')NOMBREDEP,
                c.`ESTADO`
              FROM 
                comunidad c
              LEFT OUTER JOIN municipio m
                ON c.`IDMUNICIPIO` = m.`IDMUNICIPIO`
              LEFT OUTER JOIN nuevo_municipio nm
                ON c.`IDNVOMUN` = nm.`IDNVOMUN`
              LEFT OUTER JOIN departamento d
                ON c.`IDDEPARTAMENTO` = d.`IDDEPARTAMENTO`
                ORDER BY c.`NOMBRECOMUNIDAD`";
        return ejecutarConsulta($sql);
    }

    //Select Todos los Distritos
    public function selectDistrito(){
        $sql="SELECT DISTINCT a.* FROM municipio a 
            INNER JOIN departamento b ON a.IDDEPARTAMENTO=b.IDDEPARTAMENTO
            ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Select distritos pertenecientes a un departamento
    public function selectDist($iddepartamento){
        $sql="SELECT * FROM municipio m              
            WHERE m.IDDEPARTAMENTO=$iddepartamento
            ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Select distritos pertenecientes a un municipio
    public function selectDistByNvoMuni($idnvomunicipio){
        $sql="SELECT * FROM municipio m 
            WHERE IDNVOMUN = $idnvomunicipio
            ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Select todos los municipios
    public function selectNvoMunicipio(){
        $sql="SELECT * FROM nuevo_municipio              
            ORDER BY NOMBRENVOMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Select municipio perteneciente a un departamento
    public function selectnvoMuni($iddepartamento){
        $sql="SELECT * FROM nuevo_municipio nm              
            WHERE nm.IDDEPARTAMENTO=$iddepartamento
            ORDER BY NOMBRENVOMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Seleccionar municipio perteneciente a un distrito
    public function selectnvoMuniByDist($idmunicipio){
        $sql="SELECT * FROM nuevo_municipio nm 
            LEFT OUTER JOIN municipio m ON m.IDNVOMUN = nm.IDNVOMUN
            WHERE m.IDMUNICIPIO=$idmunicipio
            ORDER BY nm.NOMBRENVOMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Select todos los departamentos
    public function selectDepartamento(){
        $sql="SELECT * FROM departamento             
            ORDER BY NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    //Select departamento perteneciente a un distrito
    public function selectDepa($idmunicipio){
        $sql="SELECT * FROM departamento d 
            INNER JOIN municipio m ON m.IDDEPARTAMENTO = d.IDDEPARTAMENTO
            WHERE m.IDMUNICIPIO=$idmunicipio
            ORDER BY d.NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    //Select departamento perteneciente a un municipio
    public function selectDepaByNvoMuni($idnvomunicipio){
        $sql="SELECT * FROM departamento d 
            INNER JOIN nuevo_municipio nm ON nm.IDDEPARTAMENTO = d.IDDEPARTAMENTO
            WHERE nm.IDNVOMUN=$idnvomunicipio
            ORDER BY d.NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para listar los registros y mostrar en el select Corr al seleccionar un departamento
    public function selectCorr($iddepartamento){
        $sql="SELECT * FROM corredor c 
        inner join departamento d on d.IDDEPARTAMENTO=c.IDDEPARTAMENTO 
        WHERE d.IDDEPARTAMENTO=$iddepartamento ORDER BY c.NOMBRECOR ASC";
        return ejecutarConsulta($sql);
    }


}
?>