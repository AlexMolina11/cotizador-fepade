<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Cantonesycaserios{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para consultar si existe el registro
    public function consulta_existencia($nombrecantoncaserio,$idmunicipio,$idnvomunicipio,$iddepartamento){
        //VALIDAR SI EXISTE UNO CON LOS MISMOS DATOS
        $sql_consult = "SELECT * FROM cantonescaserios WHERE NOMBRECANTONCASERIO = '$nombrecantoncaserio' AND IDMUNICIPIO = $idmunicipio AND IDNVOMUN = $idnvomunicipio AND IDDEPARTAMENTO = $iddepartamento";
        return ejecutarConsultaSimpleFila($sql_consult);
    }

    //Implementamos un método para insertar registros
    public function insertar($nombrecantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento) {
        // Inicializamos un arreglo con las columnas y sus valores correspondientes
        $columns = ['NOMBRECANTONCASERIO', 'IDMUNICIPIO', 'IDDEPARTAMENTO', 'IDNVOMUN'];
        $values = ["'$nombrecantoncaserio'"];
    
        // Agregamos los valores correspondientes a las columnas
        $values[] = $idmunicipio == 0 ? 'NULL' : "'$idmunicipio'";
        $values[] = $iddepartamento == 0 ? 'NULL' : "'$iddepartamento'";
        $values[] = $idnvomunicipio == 0 ? 'NULL' : "'$idnvomunicipio'";
    
        // Convertimos los arreglos a cadenas separadas por comas
        $columns_str = implode(', ', $columns);
        $values_str = implode(', ', $values);
    
        // Construimos la consulta SQL
        $sql = "INSERT INTO cantonescaserios($columns_str) VALUES ($values_str)";
    
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idcantoncaserio, $nombrecantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento) {
        // Inicializamos un arreglo con las asignaciones de las columnas y sus valores correspondientes
        $set = ["NOMBRECANTONCASERIO='$nombrecantoncaserio'"];
    
        // Agregamos las asignaciones correspondientes a las columnas
        $set[] = $idmunicipio == 0 ? 'IDMUNICIPIO=NULL' : "IDMUNICIPIO='$idmunicipio'";
        $set[] = $iddepartamento == 0 ? 'IDDEPARTAMENTO=NULL' : "IDDEPARTAMENTO='$iddepartamento'";
        $set[] = $idnvomunicipio == 0 ? 'IDNVOMUN=NULL' : "IDNVOMUN='$idnvomunicipio'";
    
        // Convertimos el arreglo a una cadena separada por comas
        $set_str = implode(', ', $set);
    
        // Construimos la consulta SQL
        $sql = "UPDATE cantonescaserios SET $set_str WHERE IDCANTONCASERIO='$idcantoncaserio' AND ESTADO='1'";
    
        return ejecutarConsulta($sql);
    }

    public function eliminar($idcantoncaserio){
        $sql="DELETE FROM cantonescaserios WHERE IDCANTONCASERIO='$idcantoncaserio'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar cantonescaserios
    public function desactivar($idcantoncaserio){
        $sql="UPDATE cantonescaserios SET ESTADO='0' WHERE IDCANTONCASERIO='$idcantoncaserio'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar comunidads
    public function activar($idcantoncaserio){
        $sql="UPDATE cantonescaserios SET ESTADO='1' WHERE IDCANTONCASERIO='$idcantoncaserio'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcantoncaserio){
        $sql="SELECT * FROM cantonescaserios WHERE IDCANTONCASERIO='$idcantoncaserio'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT 
                c.`IDCANTONCASERIO`,
                c.`NOMBRECANTONCASERIO`,
                IFNULL(m.`NOMBREMUN`,'N/A')NOMBREMUN,
                IFNULL(nm.`NOMBRENVOMUN`,'N/A')NOMBRENVOMUN,
                IFNULL(d.`NOMBREDEP`,'N/A')NOMBREDEP,
                c.`ESTADO`
              FROM 
                cantonescaserios c
              LEFT OUTER JOIN municipio m
                ON c.`IDMUNICIPIO` = m.`IDMUNICIPIO`
              LEFT OUTER JOIN nuevo_municipio nm
                ON c.`IDNVOMUN` = nm.`IDNVOMUN`
              LEFT OUTER JOIN departamento d
                ON c.`IDDEPARTAMENTO` = d.`IDDEPARTAMENTO`
                ORDER BY c.`NOMBRECANTONCASERIO`";
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


}
?>