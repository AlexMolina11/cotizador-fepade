<?php
session_start();
require_once "../modelos/Consultas.php";

$consultas= new Consultas();

//validando Acción Ver
if(isset($_SESSION["065VER26"])&&$_SESSION["065VER26"]==1){
    $btn_stl_ver = "style='display: block;'"; 
} else {
    $btn_stl_ver = "style='display: none;'"; 
}

//validando Acción Editar
if(isset($_SESSION["067EDI26"])&&$_SESSION["067EDI26"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["068ELI26"])&&$_SESSION["068ELI26"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["069ACT26"])&&$_SESSION["069ACT26"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["070DES26"])&&$_SESSION["070DES26"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    
    case 'consultaparticipante':
        
        $municipio  = $_REQUEST["municipio"];
        $departamento  = $_REQUEST["departamento"]; //Se agregó departamento
        $institucion  = $_REQUEST["institucion"];
        $corredor  = $_REQUEST["corredor"]; //Se agregó Corredor
        $tipopar  = $_REQUEST["tipopar"];

        //$rspta=$consultas->listar($municipio,$institucion,$tipopar);
        //$rspta=$consultas->consultaparticipante($municipio,$institucion,$tipopar);
        $rspta=$consultas->consultaparticipante($municipio,$departamento,$institucion,$corredor,$tipopar); //Se agregó Corredor y departamento
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $vista_usuario = (($reg->ESTADOPAR=='1')?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_ver.' class="btn btn-success" onclick="ver('.$reg->IDPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Ver Participante"><i class="fa fa-eye"></i></button>'.
                    '<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Editar Participante"><i class="fa fa-pencil"></i></button>'. 
                    '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDPARTICIPANTE.')"  data-toggle="tooltip" data-placement="top" title="Desactivar Participante"><i class="fa fa-close"></i></button>'.
                    '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Eliminar Participante"><i class="fa fa-trash"></i></button></div>': //Se agregó bonton Eliminar
                    '<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_ver.' class="btn btn-success" onclick="ver('.$reg->IDPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Ver Participante"><i class="fa fa-eye"></i></button>'.
                    '<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Editar Participante"><i class="fa fa-pencil"></i></button>'.
                    '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDPARTICIPANTE.')"  data-toggle="tooltip" data-placement="top" title="Activar Participante"><i class="fa fa-check"></i></button>'.
                    '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Eliminar Participante"><i class="fa fa-trash"></i></button></div>'); //Se agregó boton eliminar
        
            $data[]= array(
                "0"=>$reg->IDPARTICIPANTE,
                "1"=>$reg->PRIMERNOMBRE.' '.$reg->SEGUNDOSNOMBRE.' '.$reg->PRIMERAPELLIDO.' '.$reg->SEGUNDOSAPELLIDO,
                "2"=>$reg->SEXOPAR,
                "3"=>$reg->EDADPAR,
                "4"=>$reg->NOMBRECOR,
                "5"=>$reg->NOMBREINS,
				"6"=>$vista_usuario);
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'selectMunicipio':
        $rspta=$consultas->selectMunicipio();
		echo '<option value="0">--Seleccione un municipio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
	break;

    //Se agregó opción para el select Departamento
    case 'selectDepartamento':
        $rspta=$consultas->selectDepartamento();
		echo '<option value="0">--Seleccione un departamento--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;
	
	//Se agregó el Depa para cuando se selecciona un Municipio
    case 'selectDepa':
        $rspta=$consultas->selectDepa($idmunicipio);
		echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;

    //Se agregó el Depa para cuando se selecciona un Departamento
    case 'selectMuni':
        $rspta=$consultas->selectMuni($iddepartamento);
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

	case 'selectInstitucion':
        $rspta=$consultas->selectInstitucion();
		echo '<option value="0">--Seleccione un centro educativo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
	break;	
    //Se agregó opción para el select Corredor
    case 'selectCorredor':
        $rspta=$consultas->selectCorredor();
		echo '<option value="0">--Seleccione un Corredor--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;
   
}
?>