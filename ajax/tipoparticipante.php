<?php
session_start();
require_once "../modelos/Tipoparticipante.php";

$tipoparticipante= new Tipoparticipante();

$idtipoparticipante =isset($_POST["idtipoparticipante"])?limpiarCadena($_POST["idtipoparticipante"]):"";
$nombretip          =isset($_POST["nombretip"])?limpiarCadena($_POST["nombretip"]):"";

//validando Acción Editar
if(isset($_SESSION["050EDI22"])&&$_SESSION["050EDI22"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["051ACT22"])&&$_SESSION["051ACT22"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["052DES22"])&&$_SESSION["052DES22"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idtipoparticipante)) {
           $rspta=$tipoparticipante->insertar($nombretip);
           echo $rspta? "Tipo Participante Registrada":"Tipo Participante no se pudo registrar";
        }else {
            $rspta=$tipoparticipante->editar($idtipoparticipante,$nombretip);
           echo $rspta? "Tipo Participante Actualizada":"Tipo Participante no se pudo actualizar";
        }
    break;

    //Se agregó opciones desactivar y activar tipo de participante
    case 'desactivar':
        $rspta=$tipoparticipante->desactivar($idtipoparticipante);
        echo $rspta?"Tipo participante Desactivada":"Tipo participante no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$tipoparticipante->activar($idtipoparticipante);
        echo $rspta?"Tipo participante Activada":"Tipo participante no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$tipoparticipante->mostrar($idtipoparticipante);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$tipoparticipante->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
              
                "0"=>$reg->IDTIPOPARTICIPANTE,
                "1"=>$reg->NOMBRETIP,
                "2"=>($reg->ESTADOTIP)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>', //Se agregó el Estado y valida que si es 1 dirá Activada sino Desactivada
                "3"=>($reg->ESTADOTIP)?'<div class="btn-group btn-group-sm" style="display:flex;">
                  <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDTIPOPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Desactivar comunidad"><i class="fa fa-close"></i></button>': //Boton Desactivar
                '<div class="btn-group btn-group-sm" style="display:flex;">
                  <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDTIPOPARTICIPANTE.')" data-toggle="tooltip" data-placement="top" title="Activar comunidad"><i class="fa fa-check"></i></button>' //Boton Activar              
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
}
?>