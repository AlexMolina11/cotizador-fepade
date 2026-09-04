<?php
session_start();
require_once "../modelos/Organizacionejecutora.php";

$organizacioneje= new Organizacionejecutora();
 
$codigororgeje=isset($_POST["codigororgeje"])?limpiarCadena($_POST["codigororgeje"]):"";
$nombreorgeje=isset($_POST["nombreorgeje"])?limpiarCadena($_POST["nombreorgeje"]):"";

//validando Acción Editar
if(isset($_SESSION["056EDI24"])&&$_SESSION["056EDI24"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["057ELI24"])&&$_SESSION["057ELI24"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["058ACT24"])&&$_SESSION["058ACT24"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["059DES24"])&&$_SESSION["059DES24"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($codigororgeje)) {
           $rspta=$organizacioneje->insertar($nombreorgeje); 
           echo $rspta? "Organización Ejecutora Registrada":"Organización Ejecutora no se pudo registrar";
        }else {
            $rspta=$organizacioneje->editar($codigororgeje,$nombreorgeje); 
           echo $rspta? "Organización Ejecutora Actualizada":"Organización Ejecutora no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$organizacioneje->eliminar($codigororgeje);
        echo $rspta?"Organización Ejecutora Eliminada!":"Organización Ejecutora no se pudo eliminar!";
    break;

    case 'desactivar':
        $rspta=$organizacioneje->desactivar($codigororgeje);
        echo $rspta?"Organización Ejecutora Desactivada":"Organización Ejecutora no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$organizacioneje->activar($codigororgeje);
        echo $rspta?"Organización Ejecutora Activada":"Organización Ejecutora no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$organizacioneje->mostrar($codigororgeje);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$organizacioneje->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBREORGEJE,
                "1"=>($reg->ESTADOORGEJE)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "2"=>($reg->ESTADOORGEJE)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDORGEJE.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDORGEJE.')" data-toggle="tooltip" data-placement="top" title="Desactivar Organización Ejecutora"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDORGEJE.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDORGEJE.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDORGEJE.')" data-toggle="tooltip" data-placement="top" title="Activar Organización Ejecutora"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDORGEJE.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'
            );
        }
        $results = array("sEcho" => 'IDORGEJE', //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
}
?>