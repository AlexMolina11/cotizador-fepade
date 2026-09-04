<?php
session_start();
require_once "../modelos/Actividad.php";

$actividad= new Actividad();

$idactividad	   =isset($_POST["idactividad"])?limpiarCadena($_POST["idactividad"]):"";
$nombreact		   =isset($_POST["nombreact"])?limpiarCadena($_POST["nombreact"]):"";
$descripcionact    =isset($_POST["descripcionact"])?limpiarCadena($_POST["descripcionact"]):"";
$objetivoact	   =isset($_POST["objetivoact"])?limpiarCadena($_POST["objetivoact"]):"";
$arearesponsable   =isset($_POST["arearesponsable"])?limpiarCadena($_POST["arearesponsable"]):""; //se agregó area responsable
$tipoparticipante  =isset($_POST["tipoparticipante"])?limpiarCadena($_POST["tipoparticipante"]):""; //se agregó tipo de Participante
$fechaact		   =isset($_POST["fechaact"])?limpiarCadena($_POST["fechaact"]):"";
//$lugaract		   =isset($_POST["lugaract"])?limpiarCadena($_POST["lugaract"]):"";
//$responsableact    =isset($_POST["responsableact"])?limpiarCadena($_POST["responsableact"]):"";
$municipioact	   =isset($_POST["municipioact"])?limpiarCadena($_POST["municipioact"]):"";
$departamentoact   =isset($_POST["departamentoact"])?limpiarCadena($_POST["departamentoact"]):""; //Se agregó Departamento
$tipoact		   =isset($_POST["tipoact"])?limpiarCadena($_POST["tipoact"]):"";
$categoriaact	   =isset($_POST["categoriaact"])?limpiarCadena($_POST["categoriaact"]):"";
$corredoract       =isset($_POST["corredoract"])?limpiarCadena($_POST["corredoract"]):"";
$institucionact    =isset($_POST["institucionact"])?limpiarCadena($_POST["institucionact"]):"";
$financiamientoact =isset($_POST["financiamientoact"])?limpiarCadena($_POST["financiamientoact"]):"";

$actividadcomunidades = isset($_POST["actividad_comunidades"]) ? array_map('limpiarCadena', $_POST["actividad_comunidades"]) : []; // se agregó comunidades

$usuario           =$_SESSION["login"];
$permisorol     = $_SESSION["permiso"];
$idorgeje       = $_SESSION["idorgeje"];

//$fecha_convert = strtotime($fechaact);
//$fecha = date("Y-m-d",$fecha_convert);

//validando Acción Editar
if(isset($_SESSION["074EDI28"])&&$_SESSION["074EDI28"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["075ELI28"])&&$_SESSION["075ELI28"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["076ACT28"])&&$_SESSION["076ACT28"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["077DES28"])&&$_SESSION["077DES28"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

$ip= $_SERVER["REMOTE_ADDR"];

switch ($_GET["op"]) {
    /*case 'guardaryeditar':
        if (empty($idactividad)) {
            //$rspta=$actividad->insertar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$lugaract,$responsableact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$usuario,$ip,$idorgeje,$tipoparticipante,$arearesponsable); //Se agregó Departamento y tipo participante y area responsable
            $rspta=$actividad->insertar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$usuario,$ip,$idorgeje,$tipoparticipante,$arearesponsable); //Se agregó Departamento y tipo participante y area responsable
           echo $rspta? "Actividad Registrada":"Actividad no se pudo registrar";
        }else {
            //$rspta=$actividad->editar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$lugaract,$responsableact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$tipoparticipante,$arearesponsable); //Se agregó Departamento y tipo participante y area responsable
            $rspta=$actividad->editar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$tipoparticipante,$arearesponsable); //Se agregó Departamento y tipo participante y area responsable
           echo $rspta? "Actividad Actualizada":"Actividad no se pudo actualizar";
        }		
    break;*/

    case 'guardaryeditar':
        if (empty($idactividad)) {
            $rspta=$actividad->insertar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$usuario,$ip,$idorgeje,$tipoparticipante,$arearesponsable); //Se agregó Departamento y tipo participante y area responsable
            
            if ($rspta) {
                // Insertar las comunidades asociadas
                $idactividadNueva = $actividad->ultimoID(); 
                $actividad->insertarComunidades($idactividadNueva, $actividadcomunidades);
                echo "Actividad Registrada";
            } else {
                echo "La actividad no se pudo registrar";
            }
        }else {
            $rspta=$actividad->editar($idactividad,$nombreact,$descripcionact,$objetivoact,$fechaact,$municipioact,$departamentoact,$categoriaact,$corredoract,$institucionact,$tipoparticipante,$arearesponsable); //Se agregó Departamento y tipo participante y area responsable
            if ($rspta) {
                // Eliminar las comunidades actuales
                $actividad->eliminarComunidades($idactividad);
                // Insertar los nuevos niveles educativos asociados
                $actividad->insertarComunidades($idactividad, $actividadcomunidades);
                echo "Actividad Actualizada";
            } else {
                echo "La actividad no se pudo actualizar";
            }
        }		
    break;
    //Se agregó opción eliminar
    case 'eliminar':
        $rspta=$actividad->eliminar($idactividad);
        echo $rspta?"Actividad Eliminada!":"Actividad no se pudo eliminar debido a Jornadas aún vinculadas a ella.";
    break;

    case 'desactivar':
        $rspta=$actividad->desactivar($idactividad);
        echo $rspta?"actividad Desactivada":"Actividad no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$actividad->activar($idactividad);
        echo $rspta?"actividad Activada":"Actividad no se pudo activar";
    break;
        
    case 'mostrar':
        $rspta=$actividad->mostrar($idactividad);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);		
    break;

    case 'listar':
        //Agregamos variables de fecha inicio y fin
	    $fechainicio  = $_REQUEST["fechainicio"];    
        $fechafin     = $_REQUEST["fechafin"];

        $rspta=$actividad->listar($fechainicio,$fechafin);
        //Vamos a declarar un array
        $data= Array();
        
        while ($reg = $rspta->fetch_object()) {            
            $data[]= array(
                "0"=>$reg->IDACTIVIDADES, //Se agregó el id
                "1"=>$reg->NOMBREACT,
                "2"=>$reg->FECHA,
                "3"=>$reg->NOMBREDEP, //Se agregó departamento
                "4"=>$reg->NOMBREMUN,
                "5"=>$reg->NOMBRECOR,
                "6"=>$reg->NOMBREINS,
                "7"=>$reg->NOMBREAREARES,
                "8"=>$reg->DESCRIPCIONACT,
                "9"=>$reg->USUREACT,
                "10"=>($reg->ESTADOACT)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "11"=>($reg->ESTADOACT)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDACTIVIDADES.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDACTIVIDADES.')" data-toggle="tooltip" data-placement="top" title="Desactivar Actividad"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDACTIVIDADES.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDACTIVIDADES.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDACTIVIDADES.')" data-toggle="tooltip" data-placement="top" title="Activar Actividad"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDACTIVIDADES.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
    
    //Se agregó área responsable
    case 'selectAreaResponsable':
        $rspta=$actividad->selectAreaResponsable();
		echo '<option value="">--Seleccione un área--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDAREARES.'>'.$reg->NOMBREAREARES.'</option>';
        }
	break;

    //Se agregó tipo de participante o Actividad Dirigida a
    case 'selectTipoParticipante':
        $rspta=$actividad->selectTipoParticipante();
		echo '<option value="">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOPARTICIPANTE.'>'.$reg->NOMBRETIP.'</option>';
        }
	break;

    //Se agregpó SelectDepartamento
    case 'selectDepartamento':
        $rspta=$actividad->selectDepartamento();
		echo '<option value="">--Seleccione Departamento--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;

    case 'selectDepaCE':
        $rspta=$actividad->selectDepaCE($institucionact);
		//echo '<option value="">--Seleccione Departamento--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;

    case 'selectDepartamento_mostrar':
        $rspta=$actividad->selectDepartamento_mostrar($departamentoact);
		//echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;

    //Se agregó SelectMunicipio
    case 'selectMunicipio':
        $rspta=$actividad->selectMunicipio();
		echo '<option value="">--Seleccione Distrito --</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
	break;

    case 'selectMunCE':
        $rspta=$actividad->selectMunCE($institucionact);
		//echo '<option value="">--Seleccione Municipio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
	break;

    case 'selectMunicipio_mostrar':
        $rspta=$actividad->selectMunicipio_mostrar($departamentoact,$municipioact);
		echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.' selected>'.$reg->NOMBREMUN.'</option>';
        }
	break;
	
	case 'selectTipoActividad':
        $rspta=$actividad->selectTipoActividad();
		echo '<option value="">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOACTIVIDAD.'>'.$reg->NOMBRETAC.'</option>';
        }
	break;

    //Select Tipo Actividad cuando se selecciona una Categoría 
    case 'selectTipoAct':
        $rspta=$actividad->selectTipoAct($categoriaact);
		//echo '<option value="">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOACTIVIDAD.'>'.$reg->NOMBRETAC.'</option>';
        }
	break;

    case 'selCategoria':
        $rspta=$actividad->selCategoria();
		echo '<option value="">--Seleccione una categoria--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCATEGORIAACTIVIDAD.'>'.$reg->NOMBRECAC.'</option>';
        }
	break;

    //Select Categoría cuando tiene un tipo de actuvo seleccionado
    case 'selectCategoria':
        $rspta=$actividad->selectCategoria($tipoact);
		echo '<option value="">--Seleccione una categoria--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCATEGORIAACTIVIDAD.'>'.$reg->NOMBRECAC.'</option>';
        }
	break;
	
	case 'selectOrganizacion':
        $rspta=$actividad->selectOrganizacion();
		echo '<option value="" disabled>--Seleccione las Alianzas--</option>'; //Organizaciones por Alianzas
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDORGANIZACION.'>'.$reg->NOMBREORG.'</option>';
        }
	break;
	/*case 'selectResponsable':
        $rspta=$actividad->selectResponsable();
		echo '<option value="">--Seleccione un facilitador--</option>';
        while ($reg = $rspta->fetch_object()) {
            //echo '<option value='.$reg->CODIGORES.'>'.$reg->NOMBRERES.'</option>';
            echo '<option value='.$reg->CODIGORES.'>'.$reg->NOMBRERES.' - '.$reg->NOMBREORGEJE.'</option>';
        }
	break;	*/
	case 'selectCorredor':
        echo '<option value="">--Seleccione un Corredor--</option>';
        $rspta=$actividad->selectCorredor();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;

    case 'selectCorr':
        echo '<option value="">--Seleccione Corredor--</option>';
        $rspta=$actividad->selectCorr();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;

    case 'selectCorrCE':
        //echo '<option value="">--Seleccione Corredor--</option>';
        $rspta=$actividad->selectCorrCE($institucionact);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;

    case 'selectCorredor_mostrar':
        $rspta=$actividad->selectCorredor_mostrar($departamentoact,$corredoract);
		//echo '<option value="">--Seleccione Corredor--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.' selected>'.$reg->NOMBRECOR.'</option>';
        }
	break;

    case 'selectInstituciones':
        echo '<option value="">--Seleccione Centro Educativo--</option>';
        $rspta=$actividad->selectInstituciones();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
	break;

	case 'selectInstitucion':
        echo '<option value="">--Seleccione un Centro Educativo--</option>';
        $rspta=$actividad->selectInstitucion($corredoract);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
	break;

    case 'selectCentroEducativo_mostrar':
        $rspta=$actividad->selectCentroEducativo_mostrar($corredoract,$institucionact);
		//echo '<option value="">--Seleccione Centro Educativo--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.' selected>'.$reg->NOMBREINS.'</option>';
        }
	break;

    case 'selectComunidades':
        echo '<option value="">--Seleccione una comunidad--</option>';
        $rspta=$actividad->selectComunidades();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCOMUNIDAD.'>'.$reg->NOMBRECOMUNIDAD.' - ' .$reg->NOMBREMUN. ' - ' .$reg->NOMBREDEP. '</option>';
        }
	break;

}
?>