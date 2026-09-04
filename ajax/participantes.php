<?php
session_start();
require_once "../modelos/Participantes.php";

$participantes= new Participantes();  

$idparticipante           =isset($_POST["idparticipante"])?limpiarCadena($_POST["idparticipante"]):"";
$iddiscapacidad           =isset($_POST["iddiscapacidad"])?limpiarCadena($_POST["iddiscapacidad"]):"";
$idmunicipio              =isset($_POST["idmunicipio"])?limpiarCadena($_POST["idmunicipio"]):"";
$idnvomunicipio           =isset($_POST["idnvomunicipio"])?limpiarCadena($_POST["idnvomunicipio"]):""; //se agregó nuevo municipio
$iddepartamento           =isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):""; //Se agregó departamento
$idinstitucion            =isset($_POST["idinstitucion"])?limpiarCadena($_POST["idinstitucion"]):"";
$idcomunidad              =isset($_POST["idcomunidad"])?limpiarCadena($_POST["idcomunidad"]):"";
$codigopar                =isset($_POST["codigopar"])?limpiarCadena($_POST["codigopar"]):"";
$primernombre             =isset($_POST["primernombre"])?limpiarCadena($_POST["primernombre"]):"";
$segundosnombre           =isset($_POST["segundonombre"])?limpiarCadena($_POST["segundonombre"]):"";
$pirmerapellido           =isset($_POST["primerapellido"])?limpiarCadena($_POST["primerapellido"]):"";
$segundosapellido         =isset($_POST["segundoapellido"])?limpiarCadena($_POST["segundoapellido"]):"";
$fechanacpar              =isset($_POST["fechanacpar"])?limpiarCadena($_POST["fechanacpar"]):"";
$edadpar                  =isset($_POST["edadpar"])?limpiarCadena($_POST["edadpar"]):"";
$sexopar                  =isset($_POST["sexopar"])?limpiarCadena($_POST["sexopar"]):"";
$ocupacionpar             =isset($_POST["ocupacionpar"])?limpiarCadena($_POST["ocupacionpar"]):"";
$concentimeintoparafotopar=isset($_POST["concentimeinto"])?limpiarCadena($_POST["concentimeinto"]):"";
$estudioactualpar         =isset($_POST["estudiopar"])?limpiarCadena($_POST["estudiopar"]):"";
$escuelapar               =isset($_POST["escuelapar"])?limpiarCadena($_POST["escuelapar"]):"";
$seccion                  =isset($_POST["seccion"])?limpiarCadena($_POST["seccion"]):"";
$turno                    =isset($_POST["turno"])?limpiarCadena($_POST["turno"]):"";
$profesor                 =isset($_POST["profesor"])?limpiarCadena($_POST["profesor"]):"";
$nombreresponsablepar     =isset($_POST["nombreresponsablepar"])?limpiarCadena($_POST["nombreresponsablepar"]):"";
$telresponsablepar        =isset($_POST["telresponsablepar"])?limpiarCadena($_POST["telresponsablepar"]):"";
$especialidadpar          =isset($_POST["especialidadpar"])?limpiarCadena($_POST["especialidadpar"]):"";
$corredorpar              =isset($_POST["corredorpar"])?limpiarCadena($_POST["corredorpar"]):"";
$idcorredor               =isset($_POST["idcorredorpar"])?limpiarCadena($_POST["idcorredorpar"]):""; //Se agregó el idcorredor
$niveleducativopar        =isset($_POST["niveleducativopar"])?limpiarCadena($_POST["niveleducativopar"]):"";
$director                 =isset($_POST["directorcepar"])?limpiarCadena($_POST["directorcepar"]):"";
$tipoparticiparnte        =isset($_POST["tipoparticipante"])?limpiarCadena($_POST["tipoparticipante"]):"";
$usuario                  =$_SESSION["login"];
$ip                       =$_SERVER["REMOTE_ADDR"];

//Creamos objeto para MostrarPar
$TipoPar                    =isset($_POST["tipopar"])?limpiarCadena($_POST["tipopar"]):"";
$ComunidadPar               =isset($_POST["comunidadpar"])?limpiarCadena($_POST["comunidadpar"]):"";
$DepartamentoPar            =isset($_POST["departamentopar"])?limpiarCadena($_POST["departamentopar"]):"";
$MunicipioPar               =isset($_POST["municipiopar"])?limpiarCadena($_POST["municipiopar"]):"";
$CentroEducPar              =isset($_POST["centroeducpar"])?limpiarCadena($_POST["centroeducpar"]):"";
$primernombrepar            =isset($_POST["primernombrepar"])?limpiarCadena($_POST["primernombrepar"]):"";
$primerapellidopar          =isset($_POST["primerapellidopar"])?limpiarCadena($_POST["primerapellidopar"]):"";
$edadpar2                   =isset($_POST["edadpar2"])?limpiarCadena($_POST["edadpar2"]):"";
$FechaNacPar                =isset($_POST["fechanacpar"])?limpiarCadena($_POST["fechanacpar"]):""; 
$SexoPar2                   =isset($_POST["sexopar2"])?limpiarCadena($_POST["sexopar2"]):""; 

//Creamos objeto para Insertar y Editar grupoAsignado
$idgrupo_ac                 =isset($_POST["IDGRUPO2"])?limpiarCadena($_POST["IDGRUPO2"]):"";
$idgrupo_as                 =isset($_POST["IDGRUPOASIGNADO2"])?limpiarCadena($_POST["IDGRUPOASIGNADO2"]):"";
$idparticipante_as          =isset($_POST["IDPARTICIPANTE2"])?limpiarCadena($_POST["IDPARTICIPANTE2"]):"";  

//Creamos objeto para Seleccionar id corredor
$idcorredorpar              =isset($_POST["idcorredor"])?limpiarCadena($_POST["idcorredor"]):""; //Agregamos idcorredor
$idcorredorCE              =isset($_POST["idcorredorCE"])?limpiarCadena($_POST["idcorredorCE"]):""; //Agregamos idcorredor


if((isset($fechanacpar))&&(!empty($fechanacpar))){
    $fecha_convert = strtotime($fechanacpar);
    $fecha = date("Y-m-d",$fecha_convert);
}
else {
    $fecha = '2018-03-15';
}

if(isset($_POST["concentimeinto"])){ 
    $concentimeintoparafotopar=1;
} else {
    $concentimeintoparafotopar=0;
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idparticipante)) {
            $rspta_consulta=$participantes->consulta_existencia($tipoparticiparnte,$idcomunidad,$iddepartamento,$idmunicipio,$idinstitucion,$primernombre,$segundosnombre,$segu,$pirmerapellido,$fechanacpar,$sexopar); //Agregamos segundo nombre
            if($rspta_consulta['IDPARTICIPANTE'] == NULL) {
                $rspta=$participantes->insertar($iddiscapacidad,$idmunicipio,$idnvomunicipio,$idinstitucion,$idcomunidad,$codigopar,$primernombre,$segundosnombre,$pirmerapellido,$segundosapellido,$fecha,$sexopar,$edadpar,$ocupacionpar,$concentimeintoparafotopar,$estudioactualpar,$escuelapar,$seccion,$turno,$profesor,$nombreresponsablepar,$telresponsablepar,$especialidadpar,$corredorpar,$niveleducativopar,$director,$tipoparticiparnte,$usuario,$ip,$iddepartamento,$idcorredor); //Se agregó el departamento
                echo $rspta? "Participante Registrado":"Participante no se pudo registrar";
            } else {
                echo "Existente";
            }
        }else {
           $rspta=$participantes->editar($idparticipante,$iddiscapacidad,$idmunicipio,$idnvomunicipio,$idinstitucion,$idcomunidad,$codigopar,$primernombre,$segundosnombre,$pirmerapellido,$segundosapellido, $fecha,$sexopar,$edadpar,$ocupacionpar,$concentimeintoparafotopar,$estudioactualpar,$escuelapar,$seccion,$turno,$profesor,$nombreresponsablepar,$telresponsablepar,$especialidadpar,$corredorpar,$niveleducativopar,$director,$tipoparticiparnte,$iddepartamento,$idcorredor); //Se agregó el departamento
           echo $rspta? "Participante Actualizado":"Participante no se pudo actualizar";
        }
    break;

    case 'mostrarPar':
        $rspta=$participantes->mostrarPar($TipoPar,$ComunidadPar,$DepartamentoPar,$MunicipioPar,$CentroEducPar,$primernombrepar,$primerapellidopar,$edadpar2,$FechaNacPar,$SexoPar2);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);		
    break;
    
    case 'mostrarCorredor':
        $rspta=$participantes->mostrarCorredor($idcorredorpar);
        echo json_encode($rspta);
    break;

    case 'guardaryeditargrupo': 
        if($idgrupo_ac == ""){   
          $rspta=$participantes->insertarGrupoAsignado($idgrupo_as,$idparticipante_as);
          echo $idgrupo_ac;
          echo $rspta? "Grupo Asignado Correctamente":"Grupo no pudo ser asignado";          
        }
        else{
          $rspta=$participantes->editarGrupoAsignado($idgrupo_as,$idparticipante_as);
          echo $rspta? "Grupo Asignado Actualizado":"Grupo asignado no se pudo actualizar";              
        }
    break;

    //Se agregó opción Eliminar
    case 'eliminar':
        $rspta=$participantes->eliminar($idparticipante);
        echo $rspta?"¡Participante Eliminado!":"El Participante no se pudo eliminar! Ya que pertenece a un grupo, o posee una asistencia en alguna jornada.";
    break;

    case 'desactivar':
        $rspta=$participantes->desactivar($idparticipante);
        echo $rspta?"Participante Desactivado":"Participante no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$participantes->activar($idparticipante);
        echo $rspta?"Participante Activado":"Participante no se pudo activar";
    break;
        
    case 'mostrar':
        $rspta=$participantes->mostrar($idparticipante);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);		
    break;

    case 'selectTipoParticipante':
        $rspta=$participantes->selectTipoParticipante();
		echo '<option value="">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOPARTICIPANTE.'>'.$reg->NOMBRETIP.'</option>';
        }
	break;

    case 'selectComunidad':
        $rspta=$participantes->selectComunidad();
		echo '<option value="">--Seleccione una comunidad--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCOMUNIDAD.'>'.$reg->NOMBRECOMUNIDAD.' - ' .$reg->NOMBREMUN. ' - ' .$reg->NOMBREDEP. '</option>';
        }
	break;

    //Se agregó Distrito
    case 'selectDistrito':
        $rspta=$participantes->selectDistrito();
        echo '<option value="">--Seleccione Distrito--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un Departamento
    case 'selectDist':
        $rspta=$participantes->selectDist($_POST['iddepartamento']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un municipio
    case 'selectDistByNvoMuni':
        $rspta=$participantes->selectDistByNvoMuni($_POST['idnvomunicipio']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select Todos los municipios
    case 'selectNvoMunicipio':
        $rspta=$participantes->selectNvoMunicipio();
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un departamento
    case 'selectnvoMuni':
        $rspta=$participantes->selectnvoMuni($iddepartamento);
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un distrito
    case 'selectnvoMuniByDist':
        $rspta=$participantes->selectnvoMuniByDist($idmunicipio);
		//echo '<option value="0">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
	break;

    //Select todos los departamentos
    case 'selectDepartamento':
        $rspta=$participantes->selectDepartamento();
        echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select los departamentos pertenecientes a un distrito
    case 'selectDepa':
        $rspta=$participantes->selectDepa($_POST['idmunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select departamento perteneciente a un municipio
    case 'selectDepaByNvoMuni':
        $rspta=$participantes->selectDepaByNvoMuni($_POST['idnvomunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    case 'selectMunicipio_mostrar':
        $rspta=$participantes->selectMunicipio_mostrar($iddepartamento,$idmunicipio);
		echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.' selected>'.$reg->NOMBREMUN.'</option>';
        }
	break;

    case 'selectCorredor':
        $rspta=$participantes->selectCorredor();
        echo '<option value="">--Seleccione Corredor--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="'.$reg->NOMBRECOR.'">'.$reg->NOMBRECOR.'</option>';
        }
	break;

    case 'selectCorr':
        $rspta=$participantes->selectCorr($iddepartamento);
        echo '<option value="">--Seleccione Corredor--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="'.$reg->NOMBRECOR.'">'.$reg->NOMBRECOR.'</option>';
        }
	break; 
    
    case 'selectCorredor_mostrar':
        $rspta=$participantes->selectCorredor_mostrar($iddepartamento,$idcorredorpar);
		//echo '<option value="">--Seleccione Corredor--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->NOMBRECOR.' selected>'.$reg->NOMBRECOR.'</option>';
        }
	break;
    	
	case 'selectInstitucion':
        echo '<option value="">--Seleccione Centro Educativo--</option>';
        $rspta=$participantes->selectInstitucion();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
	break;

    case 'selectInstituciones':
        echo '<option value="">--Seleccione Centro Educativo--</option>';
        $rspta=$participantes->selectInstituciones($idcorredorpar);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
	break;

    case 'selectCentroEducativo_mostrar':
        $rspta=$participantes->selectCentroEducativo_mostrar($idcorredorpar,$idinstitucion);
		//echo '<option value="">--Seleccione Centro Educativo--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.' selected>'.$reg->NOMBREINS.'</option>';
        }
	break;

    //Seleccionar Grupo
    case 'selectGrupo':
        $rspta=$participantes->selectGrupo();
		echo '<option value="">--Seleccione grupo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDGRUPO.'>'.$reg->NOMBREGRUPO.'</option>';
        }
	break;
	
	 case 'selectDiscapacidad':
        $rspta=$participantes->selectDiscapacidad();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDISCAPACIDAD.'>'.$reg->NOMBREDIS.'</option>';
        }
	break;
	 
}
?>