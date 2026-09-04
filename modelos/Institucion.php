<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Institucion{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idtipoinstitucion, $idcorredor, $idcantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento, $codigoins,$nombreins, $ubicacionins, $latitud, $longitud, $zonains, $fechaingresoins, $fechasalidains, $nombredirectorins, $correodir, $telefonoins, $telefonodirins, $mat2018ins, $mat2019ins, $mat2020ins, $mat2021ins, $mat2022ins, $mat2023ins, $mat2024ins, $mat2025ins, $mat2026ins, $numerodocfemeninoins, $numerodocmasculinoins, $turnoins,$regusu,$ipusu, $mat18inshom, $mat18insmuj, $mat19inshom, $mat19insmuj, $mat20inshom, $mat20insmuj, $mat21inshom, $mat21insmuj, $mat22inshom, $mat22insmuj, $mat23inshom, $mat23insmuj, $mat24inshom, $mat24insmuj, $mat25inshom, $mat25insmuj, $mat26inshom, $mat26insmuj){
        if($fechaingresoins == '') {
            $fechaingresoins = '2000-01-01';
        }
        $fecha1 = date('Y-m-d',strtotime($fechaingresoins));
        
        if($fechasalidains == '') {
            $fechasalidains = '2000-01-01';
        }
            $fecha2 = date('Y-m-d',strtotime($fechasalidains));
            
            // Obtener la fecha y hora actual de El Salvador
            $fechaElSalvador = date_create(null, new DateTimeZone('America/El_Salvador'));
            $fechaElSalvadorFormatoSQL = date_format($fechaElSalvador, 'Y-m-d H:i:s');
            
            $sql="INSERT INTO institucion(IDTIPOINSTITUCION, IDCORREDOR, IDCANTONCASERIO, IDMUNICIPIO, IDNVOMUN, IDDEPARTAMENTO, CODIGOINS, NOMBREINS, UBICACIONINS, LATITUDINS, LONGITUDINS, ZONAINS, FECHAINGRESOINS, FECHASALIDAINS, NOMBREDIRECTORINS, CORREODIRINS, TELEFONOINS, TELEFONODIRINS, MAT2018INS, MAT2019INS, MAT2020INS, MAT2021INS, MAT2022INS,  MAT2023INS, MAT2024INS, MAT2025INS, MAT2026INS, NUMERODOCFEMENINOINS, NUMERODOCMASCULINOINS, TURNOINS, USUREGINS, IPREGINS, FECHAREGINS, FECHAMODINS, USUMODINS, MAT2018INSHOM, MAT2018INSMUJ, MAT2019INSHOM, MAT2019INSMUJ, MAT2020INSHOM, MAT2020INSMUJ, MAT2021INSHOM, MAT2021INSMUJ, MAT2022INSHOM, MAT2022INSMUJ, MAT2023INSHOM, MAT2023INSMUJ, MAT2024INSHOM, MAT2024INSMUJ, MAT2025INSHOM, MAT2025INSMUJ, MAT2026INSHOM, MAT2026INSMUJ) values('$idtipoinstitucion', '$idcorredor', '$idcantoncaserio' ,'$idmunicipio', '$idnvomunicipio' ,'$iddepartamento', '$codigoins','$nombreins', '$ubicacionins', '$latitud', '$longitud' ,'$zonains',
                '$fecha1', '$fecha2', '$nombredirectorins', '$correodir' ,'$telefonoins', '$telefonodirins', '$mat2018ins', '$mat2019ins', '$mat2020ins', '$mat2021ins', '$mat2022ins', '$mat2023ins', '$mat2024ins', '$mat2025ins', '$mat2026ins', '$numerodocfemeninoins', '$numerodocmasculinoins', '$turnoins','$regusu','$ipusu','$fechaElSalvadorFormatoSQL', '$fechaElSalvadorFormatoSQL','$regusu', '$mat18inshom', '$mat18insmuj', '$mat19inshom', '$mat19insmuj', '$mat20inshom', '$mat20insmuj', '$mat21inshom', '$mat21insmuj', '$mat22inshom', '$mat22insmuj', '$mat23inshom', '$mat23insmuj', '$mat24inshom', '$mat24insmuj', '$mat25inshom', '$mat25insmuj', '$mat26inshom', '$mat26insmuj')";
            return ejecutarConsulta($sql);
        }
    
    //Implementamos un método para editar registros
    public function editar($idinstitucion,$idtipoinstitucion, $idcorredor, $idcantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento, $codigoins,$nombreins, $ubicacionins, $latitud, $longitud, $zonains, $fechaingresoins, $fechasalidains, $nombredirectorins, $correodir, $telefonoins, $telefonodirins, $mat2018ins, $mat2019ins, $mat2020ins, $mat2021ins, $mat2022ins,  $mat2023ins, $mat2024ins, $mat2025ins, $mat2026ins, $numerodocfemeninoins, $numerodocmasculinoins, $turnoins, $regusu, $mat18inshom, $mat18insmuj, $mat19inshom, $mat19insmuj, $mat20inshom, $mat20insmuj, $mat21inshom, $mat21insmuj, $mat22inshom, $mat22insmuj, $mat23inshom, $mat23insmuj, $mat24inshom, $mat24insmuj, $mat25inshom, $mat25insmuj, $mat26inshom, $mat26insmuj){ 
        if($fechaingresoins == '') {
            $fechaingresoins = '2000-01-01';
        }
        $fecha1 = date('Y-m-d',strtotime($fechaingresoins));
        
        if($fechasalidains == '') {
            $fechasalidains = '2000-01-01';
        }
        $fecha2 = date('Y-m-d',strtotime($fechasalidains));
        
        // Obtener la fecha y hora actual de El Salvador
        $fechaElSalvador = date_create(null, new DateTimeZone('America/El_Salvador'));
        $fechaElSalvadorFormatoSQL = date_format($fechaElSalvador, 'Y-m-d H:i:s');
        
        $sql="UPDATE institucion SET IDTIPOINSTITUCION='$idtipoinstitucion',IDCORREDOR='$idcorredor', IDCANTONCASERIO='$idcantoncaserio', IDMUNICIPIO='$idmunicipio', IDNVOMUN='$idnvomunicipio', IDDEPARTAMENTO='$iddepartamento', CODIGOINS='$codigoins', NOMBREINS='$nombreins', UBICACIONINS='$ubicacionins', LATITUDINS='$latitud', LONGITUDINS='$longitud', ZONAINS='$zonains', FECHAINGRESOINS='$fecha1', FECHASALIDAINS='$fecha2', NOMBREDIRECTORINS='$nombredirectorins', CORREODIRINS='$correodir', TELEFONOINS='$telefonoins', TELEFONODIRINS='$telefonodirins', MAT2018INS='$mat2018ins', MAT2019INS='$mat2019ins', MAT2020INS='$mat2020ins', MAT2021INS='$mat2021ins', MAT2022INS='$mat2022ins', MAT2023INS='$mat2023ins', MAT2024INS='$mat2024ins', MAT2025INS='$mat2025ins', MAT2026INS='$mat2026ins', NUMERODOCFEMENINOINS='$numerodocfemeninoins', NUMERODOCMASCULINOINS='$numerodocmasculinoins', TURNOINS='$turnoins', FECHAMODINS='$fechaElSalvadorFormatoSQL', USUMODINS='$regusu', MAT2018INSHOM = '$mat18inshom', MAT2018INSMUJ = '$mat18insmuj', MAT2019INSHOM = '$mat19inshom', MAT2019INSMUJ = '$mat19insmuj', MAT2020INSHOM = '$mat20inshom', MAT2020INSMUJ = '$mat20insmuj', MAT2021INSHOM = '$mat21inshom', MAT2021INSMUJ = '$mat21insmuj', MAT2022INSHOM = '$mat22inshom', MAT2022INSMUJ = '$mat22insmuj', MAT2023INSHOM = '$mat23inshom', MAT2023INSMUJ = '$mat23insmuj', MAT2024INSHOM = '$mat24inshom', MAT2024INSMUJ = '$mat24insmuj', MAT2025INSHOM = '$mat25inshom', MAT2025INSMUJ = '$mat25insmuj', MAT2026INSHOM = '$mat26inshom', MAT2026INSMUJ = '$mat26insmuj' WHERE IDINSTITUCION='$idinstitucion'"; 
        return ejecutarConsulta($sql);
    }

    //Función para insertar los niveles educativos
    public function insertarNivelesEducativos($idinstitucion, $nivelesEducativos) {
        foreach ($nivelesEducativos as $idnivel) {
            $sql = "INSERT INTO institucion_nivel (IDINSTITUCION, IDNIVELEDUCATIVO) VALUES ('$idinstitucion', '$idnivel')";
            ejecutarConsulta($sql);
        }
    }
    
    //Funcion para eliminar los niveles educativos
    public function eliminarNivelesEducativos($idinstitucion) {
        $sql = "DELETE FROM institucion_nivel WHERE IDINSTITUCION = '$idinstitucion'";
        return ejecutarConsulta($sql);
    }
    
    //Función para obtener la ultima institución registrada.
    public function ultimoID() {
        $sql = "SELECT MAX(IDINSTITUCION) as ultimo_id FROM institucion";
        $query = ejecutarConsultaSimpleFila($sql);
        return $query['ultimo_id'];
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idinstitucion){
        $sql="UPDATE institucion SET ESTADOINS='0' WHERE IDINSTITUCION='$idinstitucion'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idinstitucion){
        $sql="UPDATE institucion SET ESTADOINS='1' WHERE IDINSTITUCION='$idinstitucion'";
        return ejecutarConsulta($sql);
    }
    
    //Implementar un método para mostrar los datos de un registro a modificar
    /*public function mostrar($idinstitucion){
        $sql="SELECT * FROM institucion WHERE IDINSTITUCION='$idinstitucion'";
        return ejecutarConsultaSimpleFila($sql);
    }*/

    public function mostrar($idinstitucion) {
        // Consulta para obtener los datos de la institución
        $sqlInstitucion = "SELECT * FROM institucion WHERE IDINSTITUCION='$idinstitucion'";
        $institucion = ejecutarConsultaSimpleFila($sqlInstitucion);
    
        // Consulta para obtener los niveles educativos asociados a la institución
        $sqlNiveles = "SELECT IDNIVELEDUCATIVO FROM institucion_nivel WHERE IDINSTITUCION='$idinstitucion'";
        $nivelesEducativos = ejecutarConsulta($sqlNiveles);
        
        // Crear un arreglo para almacenar los IDs de los niveles educativos
        $niveles = array();
        while ($row = $nivelesEducativos->fetch_assoc()) {
            $niveles[] = $row['IDNIVELEDUCATIVO'];
        }
    
        // Agregar los niveles educativos al resultado de la institución
        $institucion['niveles_educativos'] = $niveles;
    
        return $institucion;
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT 
        i.`IDINSTITUCION`,
        IFNULL(i.`CODIGOINS`,'N/A')CODIGOINS,
        IFNULL(i.`NOMBREINS`,'N/A')NOMBREINS,
        IFNULL(t.`NOMBRETIN`,'N/A')NOMBRETIN,
        IFNULL(c.`NOMBRECOR`,'N/A')NOMBRECOR,
        IFNULL(i.`UBICACIONINS`,'N/A')UBICACIONINS,
        IFNULL(i.`ZONAINS`,'N/A')ZONAINS,
        IFNULL(i.`TELEFONOINS`,'N/A')TELEFONOINS,
        IFNULL(i.`MAT2024INS`,'N/A')MAT2024INS,
        IFNULL(i.`NOMBREDIRECTORINS`,'N/A')NOMBREDIRECTORINS,
        IFNULL(i.`TELEFONODIRINS`,'N/A')TELEFONODIRINS, /*Se agregó el telefono director*/
        IFNULL(i.`NUMERODOCFEMENINOINS`,'N/A')NUMERODOCFEMENINOINS,
        IFNULL(i.`NUMERODOCMASCULINOINS`,'N/A')NUMERODOCMASCULINOINS,
        ESTADOINS 
      FROM
        institucion i 
        INNER JOIN tipoinstitucion t 
          ON i.`IDTIPOINSTITUCION` = t.`IDTIPOINSTITUCION` 
        INNER JOIN corredor c 
          ON i.`IDCORREDOR` = c.`IDCORREDOR` ORDER BY i.`IDINSTITUCION` DESC";
        return ejecutarConsulta($sql);
    }
    
     //Implementar un método para listar los registros y mostrar en el select institución
    public function selectTipoIns(){
        $sql="SELECT * FROM tipoinstitucion";
        return ejecutarConsulta($sql);
    }

     //Implementar un método para listar los registros y mostrar en el select institución
     public function selectCorr(){
        $sql="SELECT * FROM corredor";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Corredor al seleccionar el Departamento.
    public function selectCorredorDep($iddepartamento){
        $sql="SELECT * FROM corredor c 
        INNER JOIN departamento d ON c.IDDEPARTAMENTO=d.IDDEPARTAMENTO
        WHERE c.IDDEPARTAMENTO=$iddepartamento";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros de cantones/caserios
    public function selectCantoncaserio(){
        $sql="SELECT * FROM cantonescaserios";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Cantón/caserío al seleccionar el Departamento.
    public function selectCantoncaserioDep($iddepartamento){
        $sql="SELECT * FROM cantonescaserios c 
        INNER JOIN departamento d ON c.IDDEPARTAMENTO=d.IDDEPARTAMENTO
        WHERE c.IDDEPARTAMENTO=$iddepartamento";
        return ejecutarConsulta($sql);
    }

    //Agregamos, Distrito, Municipio y Departamento
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