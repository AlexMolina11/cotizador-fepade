<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";

class Participantes{
    //Implementar nuestro constructor
    public function __construct()
    {

    }

    public function consulta_existencia($tipoparticiparnte,$idcomunidad,$iddepartamento,$idmunicipio,$idinstitucion,$primernombre,$segundosnombre,$pirmerapellido,$fechanacpar,$sexopar){
        if($segundosnombre == "") {
            $Whr2Nom = "";
        } else {
            $Whr2Nom = "AND SEGUNDOSNOMBRE = '$segundosnombre'";
        }
        //VALIDAR SI EXISTE UNO CON LOS MISMOS DATOS
        $sql_consult = "SELECT * FROM participantes WHERE IDTIPOPARTICIPANTE = $tipoparticiparnte AND IDCOMUNIDAD = $idcomunidad AND IDDEPARTAMENTO = $iddepartamento AND IDMUNICIPIO = $idmunicipio AND IDINSTITUCION = $idinstitucion AND PRIMERNOMBRE = '$primernombre' $Whr2Nom AND PRIMERAPELLIDO = '$pirmerapellido' AND FECHANACPAR = '$fechanacpar' AND SEXOPAR = '$sexopar'";
        return ejecutarConsultaSimpleFila($sql_consult);
    }

    //Implementamos un método para insertar registros
    public function insertar($iddiscapacidad,$idmunicipio,$idinstitucion,$idcomunidad,$codigopar,$primernombre,$segundosnombre,$pirmerapellido,$segundosapellido,$fecha,$sexopar,$edadpar,$ocupacionpar,$concentimeintoparafotopar,$estudioactualpar,$escuelapar,$seccion,$turno,$profesor,$nombreresponsablepar,$telresponsablepar,$especialidadpar,$niveleducativopar,$director,$tipoparticiparnte,$usuario,$ip,$iddepartamento,$corredorpar){ //Se agregó departamento
		
        $usuario      =$_SESSION["login"];
        $ip           =$_SERVER["REMOTE_ADDR"];					  
		$sql="INSERT INTO participantes(IDDISCAPACIDAD, IDMUNICIPIO, IDINSTITUCION, IDCOMUNIDAD,IDTIPOPARTICIPANTE, CODIGOPAR, PRIMERNOMBRE, SEGUNDOSNOMBRE, PRIMERAPELLIDO, SEGUNDOSAPELLIDO, FECHANACPAR, SEXOPAR, EDADPAR, OCUPACIONPAR, CONCENTIMEINTOPARAFOTOPAR, ESTUDIOACTUALPAR, ESCUELAPAR, SECCION, TURNO, PROFESOR, NOMBRERESPONSABLEPAR, TELRESPONSABLEPAR, ESPECIALIDADPAR, NIVELEDUCATIVOPAR, DIRECTOR, USUREGPAR, IPREGPAR, FECHAREGPAR, ESTADOPAR, IDCORREDOR, IDDEPARTAMENTO, FECHAMODPAR, USUMODPAR) 
	 	   VALUES ($iddiscapacidad, $idmunicipio, $idinstitucion, $idcomunidad, $tipoparticiparnte,'$codigopar',UPPER('$primernombre'), UPPER('$segundosnombre'), UPPER('$pirmerapellido'), UPPER('$segundosapellido'), '$fecha', '$sexopar', '$edadpar', '$ocupacionpar', '$concentimeintoparafotopar', '$estudioactualpar', '$escuelapar', '$seccion', '$turno', '$profesor', '$nombreresponsablepar', '$telresponsablepar','$especialidadpar', '$niveleducativopar', '$director', '$usuario', '$ip', NOW(), '1', $corredorpar, '$iddepartamento', NOW(), '$usuario')";
		#echo $sql; 
        return ejecutarConsulta($sql);
    }
    
	//Implementamos un método para editar registros
    public function editar($idparticipante,$iddiscapacidad,$idmunicipio,$idinstitucion,$idcomunidad,$codigopar,$primernombre,$segundosnombre,$pirmerapellido,$segundosapellido,$fecha,$sexopar,$edadpar,$ocupacionpar,$concentimeintoparafotopar,$estudioactualpar,$escuelapar,$seccion,$turno,$profesor,$nombreresponsablepar,$telresponsablepar,$especialidadpar,$niveleducativopar,$director,$tipoparticiparnte,$iddepartamento,$corredorpar){ //Se agregó departamento
        $usuario      =$_SESSION["login"];
		$sql="UPDATE participantes 	SET IDDISCAPACIDAD=$iddiscapacidad,
                                        IDMUNICIPIO=$idmunicipio,
                                        IDINSTITUCION=$idinstitucion,
                                        IDCOMUNIDAD=$idcomunidad,
                                        IDTIPOPARTICIPANTE=$tipoparticiparnte,
                                        CODIGOPAR ='$codigopar',
                                        PRIMERNOMBRE=UPPER('$primernombre'),
                                        SEGUNDOSNOMBRE=UPPER('$segundosnombre'),
                                        PRIMERAPELLIDO=UPPER('$pirmerapellido'),
                                        SEGUNDOSAPELLIDO=UPPER('$segundosapellido'),
                                        FECHANACPAR='$fecha',
                                        SEXOPAR='$sexopar',
                                        EDADPAR=$edadpar,
                                        OCUPACIONPAR='$ocupacionpar',
                                        CONCENTIMEINTOPARAFOTOPAR='$concentimeintoparafotopar',
                                        ESTUDIOACTUALPAR='$estudioactualpar',
                                        ESCUELAPAR='$escuelapar',
                                        SECCION='$seccion',
                                        TURNO='$turno',
                                        PROFESOR='$profesor',
                                        NOMBRERESPONSABLEPAR='$nombreresponsablepar',
                                        TELRESPONSABLEPAR='$telresponsablepar',
                                        ESPECIALIDADPAR='$especialidadpar',
                                        NIVELEDUCATIVOPAR='$niveleducativopar',				
                                        DIRECTOR='$director',
                                        IDCORREDOR=$corredorpar,
                                        IDDEPARTAMENTO='$iddepartamento', /*Se agregó departamento*/				
                                        FECHAMODPAR=NOW(),
                                        USUMODPAR='$usuario'
                                        WHERE IDPARTICIPANTE=$idparticipante";
        return ejecutarConsulta($sql);
    }

    //Se agregó query para eliminar
    public function eliminar($idparticipante){
        $sql="DELETE FROM participantes WHERE IDPARTICIPANTE='$idparticipante'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrarPar($TipoPar,$ComunidadPar,$DepartamentoPar,$MunicipioPar,$CentroEducPar,$primernombrepar,$primerapellidopar,$edadpar2,$FechaNacPar,$SexoPar2){
        $sql="SELECT `participantes`.`IDPARTICIPANTE`, 
                        `IDDISCAPACIDAD`, 
                        `IDMUNICIPIO`, 
                        `IDINSTITUCION`, 
                        `IDCOMUNIDAD`, 
                        `IDTIPOPARTICIPANTE`,
                        `CODIGOPAR`,
                        `PRIMERNOMBRE`,
                        `SEGUNDOSNOMBRE`,
                        `PRIMERAPELLIDO`,
                        `SEGUNDOSAPELLIDO`,
                        `FECHANACPAR`,
                        `SEXOPAR`,
                        `EDADPAR`,
                        `OCUPACIONPAR`,
                        `CONCENTIMEINTOPARAFOTOPAR`,
                        `ESTUDIOACTUALPAR`,
                        `ESCUELAPAR`,
                        `SECCION`,
                        `TURNO`,
                        `PROFESOR`,
                        `NOMBRERESPONSABLEPAR`,
                        `TELRESPONSABLEPAR`,
                        `ESPECIALIDADPAR`,
                        `NIVELEDUCATIVOPAR`,
                        `DIRECTOR`,
                        `USUREGPAR`,
                        `IPREGPAR`,
                        `FECHAREGPAR`,
                        `ESTADOPAR` ,
                        /*DATE_FORMAT(FECHANACPAR,'%d/%m/%Y') as fecha_dmy*/
                        `FECHANACPAR`,
                        `IDDEPARTAMENTO`, /*Se agregó departamento*/
                        `participantegrupo`.`IDGRUPO`,
                        `IDCORREDOR`
                FROM `participantes` 
                LEFT OUTER JOIN `participantegrupo` ON `participantegrupo`.`IDPARTICIPANTE` = `participantes`.`IDPARTICIPANTE`
                WHERE `IDTIPOPARTICIPANTE`=$TipoPar AND `IDCOMUNIDAD`=$ComunidadPar AND `IDDEPARTAMENTO`=$DepartamentoPar AND `IDMUNICIPIO`=$MunicipioPar AND `IDINSTITUCION`=$CentroEducPar
                AND `PRIMERNOMBRE`='$primernombrepar' AND `PRIMERAPELLIDO`='$primerapellidopar' AND `EDADPAR`=$edadpar2 AND `FECHANACPAR`='$FechaNacPar' AND `SEXOPAR`='$SexoPar2'
                ORDER BY IDPARTICIPANTE DESC";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un metodo para mostrar el id del corredor seleccionado
    /*public function mostrarCorredor($idcorredorpar){
        $sql="SELECT IDCORREDOR FROM corredor WHERE NOMBRECOR='$idcorredorpar' AND ESTADOCOR=1";
        return ejecutarConsultaSimpleFila($sql);
    }*/

    //Implementamos un método para insertar registros
    public function insertarGrupoAsignado($idgrupo_as,$idparticipante_as){
		$sql="INSERT INTO `participantegrupo`(`IDGRUPO`, `IDPARTICIPANTE`) VALUES ('$idgrupo_as','$idparticipante_as')";   
        return ejecutarConsulta($sql);	       
    }

    //Implementamos un método para editar registros
    public function editarGrupoAsignado($idgrupo_as,$idparticipante_as){
        $sql="UPDATE `participantegrupo` SET `IDPARTICIPANTE`='$idparticipante_as' WHERE `IDGRUPO`='$idgrupo_as'";       
        return ejecutarConsulta($sql);	       
	}

    //Implementamos un método para desactivar registros
    public function desactivar($idparticipante){
        $sql="UPDATE participantes SET ESTADOPAR='0' WHERE IDPARTICIPANTE='$idparticipante'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar registros
    public function activar($idparticipante){
        $sql="UPDATE participantes SET ESTADOPAR='1' WHERE IDPARTICIPANTE='$idparticipante'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idparticipante){
        $sql="SELECT `participantes`.`IDPARTICIPANTE`, 
                    `IDDISCAPACIDAD`, 
                    `IDMUNICIPIO`, 
                    `IDINSTITUCION`, 
                    `IDCOMUNIDAD`, 
                    `IDTIPOPARTICIPANTE`,
                    `CODIGOPAR`,
                    `PRIMERNOMBRE`,
                    `SEGUNDOSNOMBRE`,
                    `PRIMERAPELLIDO`,
                    `SEGUNDOSAPELLIDO`,
                    `FECHANACPAR`,
                    `SEXOPAR`,
                    `EDADPAR`,
                    `OCUPACIONPAR`,
                    `CONCENTIMEINTOPARAFOTOPAR`,
                    `ESTUDIOACTUALPAR`,
                    `ESCUELAPAR`,
                    `SECCION`,
                    `TURNO`,
                    `PROFESOR`,
                    `NOMBRERESPONSABLEPAR`,
                    `TELRESPONSABLEPAR`,
                    `ESPECIALIDADPAR`,
                    `NIVELEDUCATIVOPAR`,
                    `DIRECTOR`,
                    `USUREGPAR`,
                    `IPREGPAR`,
                    `FECHAREGPAR`,
                    `ESTADOPAR` ,
                    `FECHANACPAR`,
                    `IDCORREDOR`,
                    `IDDEPARTAMENTO`, /*Se agregó departamento*/
                    `participantegrupo`.`IDGRUPO`
            FROM `participantes` 
            LEFT OUTER JOIN `participantegrupo` ON `participantegrupo`.`IDPARTICIPANTE` = `participantes`.`IDPARTICIPANTE`
            WHERE `participantes`.`IDPARTICIPANTE`=$idparticipante";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function selectTipoParticipante(){ 
        $sql="SELECT * FROM tipoparticipante WHERE ESTADOTIP=1";
        return ejecutarConsulta($sql);
    }	

    public function selectComunidad(){ 
        //$sql="SELECT * FROM comunidad WHERE ESTADO=1 ORDER BY NOMBRECOMUNIDAD ASC";
        $sql="SELECT * FROM comunidad c
                LEFT OUTER JOIN municipio m ON m.IDMUNICIPIO=c.IDMUNICIPIO 
                LEFT OUTER JOIN departamento d ON d.IDDEPARTAMENTO=c.IDDEPARTAMENTO 
                WHERE c.ESTADO=1
                ORDER BY c.NOMBRECOMUNIDAD ASC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Departamento
    public function selectDepartamento(){
        $sql="SELECT * FROM departamento a              
            ORDER BY NOMBREDEP ASC";
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

    public function selectDepartamento_mostrar($iddepartamento){
        $sql="SELECT * FROM departamento d 
            WHERE d.IDDEPARTAMENTO=$iddepartamento
            ORDER BY d.NOMBREDEP ASC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Municipio
    public function selectMunicipio(){
        $sql="SELECT DISTINCT a.* FROM municipio a 
            INNER JOIN departamento b ON a.IDDEPARTAMENTO=b.IDDEPARTAMENTO
            ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros y mostrar en el select Municipio al seleccionar el Departamento.
    public function selectMuni($iddepartamento){
        $sql="SELECT * FROM municipio m              
            WHERE m.IDDEPARTAMENTO=$iddepartamento
            ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    public function selectMunicipio_mostrar($iddepartamento,$idmunicipio){
        $sql="SELECT * FROM municipio m WHERE m.IDDEPARTAMENTO=$iddepartamento AND m.IDMUNICIPIO=$idmunicipio ORDER BY NOMBREMUN ASC";
        return ejecutarConsulta($sql);
    }

    public function selectCorredor(){ 
        $sql="SELECT * FROM corredor WHERE ESTADOCOR=1 ORDER BY `NOMBRECOR` ASC";
        return ejecutarConsulta($sql);
    }

    public function selectCorr($iddepartamento){ 
        $sql="SELECT * FROM corredor c
            WHERE c.IDDEPARTAMENTO=$iddepartamento AND c.ESTADOCOR=1 ORDER BY `NOMBRECOR` ASC";
        return ejecutarConsulta($sql);
    }

    public function selectCorredor_mostrar($iddepartamento,$corredorpar){
        $sql="SELECT * FROM corredor c WHERE c.IDDEPARTAMENTO=$iddepartamento AND c.IDCORREDOR=$corredorpar AND c.ESTADOCOR=1 ORDER BY NOMBRECOR ASC";
        return ejecutarConsulta($sql);
    }

	public function selectInstitucion(){ 
        $sql="SELECT * FROM institucion WHERE ESTADOINS=1";
        return ejecutarConsulta($sql); 
    }

    public function selectInstituciones($corredorpar){
        $sql="SELECT a.IDINSTITUCION, a.NOMBREINS, b.IDCORREDOR, b.NOMBRECOR FROM institucion a 
        inner join corredor b on a.IDCORREDOR=b.IDCORREDOR 
        WHERE b.IDCORREDOR=$corredorpar AND ESTADOINS=1";
        return ejecutarConsulta($sql);
    }

    public function selectCentroEducativo_mostrar($corredorpar,$idinstitucion) {
        $sql="SELECT * FROM institucion i WHERE i.IDCORREDOR=$corredorpar AND i.IDINSTITUCION=$idinstitucion AND ESTADOINS=1 ORDER BY i.NOMBREINS ASC";
        return ejecutarConsulta($sql);
    }

    public function selectGrupo(){ 
        $sql="SELECT * FROM grupo WHERE ESTADOGRUPO=1";
        return ejecutarConsulta($sql);
    }

	public function selectDiscapacidad(){ 
        $sql="SELECT * FROM discapacidad order by 1";
        return ejecutarConsulta($sql);
    }
	
	
}