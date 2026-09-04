<?php
session_start();
require_once "../modelos/Arearesponsable.php";

$arearesponsable= new Arearesponsable();

$idarearesponsable=isset($_POST["idarearesponsable"])?limpiarCadena($_POST["idarearesponsable"]):"";
$nombreareares=isset($_POST["nombreareares"])?limpiarCadena($_POST["nombreareares"]):"";

//validando Acción Editar
if(isset($_SESSION["061EDI25"])&&$_SESSION["061EDI25"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["062ELI25"])&&$_SESSION["062ELI25"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["063ACT25"])&&$_SESSION["063ACT25"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["064DES25"])&&$_SESSION["064DES25"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idarearesponsable)) {
           $rspta=$arearesponsable->insertar($nombreareares);
           echo $rspta? "Área Responsable Registrada":"Área Responsable no se pudo registrar";
        }else {
            $rspta=$arearesponsable->editar($idarearesponsable,$nombreareares);
           echo $rspta? "Área Responsable Actualizada":"Área Responsable no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$arearesponsable->eliminar($idarearesponsable);
        echo $rspta?"Área Responsable Eliminada!":"Área Responsable no se pudo eliminar!";
    break;

    case 'desactivar':
        $rspta=$arearesponsable->desactivar($idarearesponsable);
        echo $rspta?"Área Responsable Desactivado":"Área Responsable no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$arearesponsable->activar($idarearesponsable);
        echo $rspta?"Área Responsable activado":"Área Responsable no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$arearesponsable->mostrar($idarearesponsable);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$arearesponsable->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
            
                "0"=>$reg->NOMBREAREARES,
                "1"=>($reg->ESTADOAREARES)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "2"=>($reg->ESTADOAREARES)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDAREARES.')" data-toggle="tooltip" data-placement="top" title="Editar Área Responsable"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDAREARES.')" data-toggle="tooltip" data-placement="top" title="Desactivar Área Responsable"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDAREARES.')" data-toggle="tooltip" data-placement="top" title="Eliminar Área Responsable"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDAREARES.')" data-toggle="tooltip" data-placement="top" title="Editar Área Responsable"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDAREARES.')" data-toggle="tooltip" data-placement="top" title="Activar Área Responsable"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDAREARES.')" data-toggle="tooltip" data-placement="top" title="Eliminar Área Responsable"><i class="fa fa-trash"></i></button></div>'

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