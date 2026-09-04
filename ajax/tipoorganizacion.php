<?php
session_start();
require_once "../modelos/Tipoorganizacion.php";

$tipoorg= new Tipoorganizacion();

$idtipoorganizacion=isset($_POST["idtipoorganizacion"])?limpiarCadena($_POST["idtipoorganizacion"]):"";
$nombretipoorg=isset($_POST["nombretipoorg"])?limpiarCadena($_POST["nombretipoorg"]):"";

//validando Acción Editar
if(isset($_SESSION["026EDI16"])&&$_SESSION["026EDI16"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["027ACT16"])&&$_SESSION["027ACT16"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["028DES16"])&&$_SESSION["028DES16"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idtipoorganizacion)) {
           $rspta=$tipoorg->insertar($nombretipoorg);
           echo $rspta? "Tipo Organización Registrada":"Tipo Organización no se pudo registrar";
        }else {
            $rspta=$tipoorg->editar($idtipoorganizacion,$nombretipoorg);
           echo $rspta? "Tipo Organización Actualizada":"Tipo Organización no se pudo actualizar";
        }
        break;
    
    //Se agregó opciones desactivar y activar tipo de organización
    case 'desactivar':
        $rspta=$tipoorg->desactivar($idtipoorganizacion);
        echo $rspta?"Tipo organizacion Desactivada":"Tipo organizacion no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$tipoorg->activar($idtipoorganizacion);
        echo $rspta?"Tipo organizacion Activada":"Tipo organizacion no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$tipoorg->mostrar($idtipoorganizacion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$tipoorg->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
              
                "0"=>$reg->NOMBRETOR,
                "1"=>($reg->ESTADOTOR)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>', //Se agregó el Estado y valida que si es 1 dirá Activada sino Desactivada
                "2"=>($reg->ESTADOTOR)?'<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDTIPOORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Desactivar comunidad"><i class="fa fa-close"></i></button>': //Boton Desactivar
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDTIPOORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Activar comunidad"><i class="fa fa-check"></i></button>' //Boton Activar

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