<?php
session_start();
require_once "../modelos/Tipoinsumo.php";

$tipoinsumo= new tipoinsumo();
 
$codigotipoinsumo=isset($_POST["codigotipoinsumo"])?limpiarCadena($_POST["codigotipoinsumo"]):"";
$nombretipoinsumo=isset($_POST["nombretipoinsumo"])?limpiarCadena($_POST["nombretipoinsumo"]):"";

//validando Acción Editar
if(isset($_SESSION["142EDI49"])&&$_SESSION["142EDI49"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["143ELI49"])&&$_SESSION["143ELI49"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["144ACT49"])&&$_SESSION["144ACT49"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["145DES49"])&&$_SESSION["145DES49"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($codigotipoinsumo)) {
           $rspta=$tipoinsumo->insertar($nombretipoinsumo); 
           echo $rspta? "Tipo de insumo Registrado":"Tipo de insumo no se pudo registrar";
        }else {
            $rspta=$tipoinsumo->editar($codigotipoinsumo,$nombretipoinsumo); 
           echo $rspta? "Tipo de insumo Actualizado":"Tipo de insumo no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$tipoinsumo->eliminar($codigotipoinsumo);
        echo $rspta?"Tipo de insumo Eliminado!":"Tipo de insumo no se pudo eliminar!";
    break;

    case 'desactivar':
        $rspta=$tipoinsumo->desactivar($codigotipoinsumo);
        echo $rspta?"Tipo de insumo Desactivado":"Tipo de insumo no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$tipoinsumo->activar($codigotipoinsumo);
        echo $rspta?"Tipo de insumo Activado":"Tipo de insumo no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$tipoinsumo->mostrar($codigotipoinsumo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$tipoinsumo->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBRETIPOINSUMO,
                "1"=>($reg->ESTADOTIPOINSUMO)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
                "2"=>($reg->ESTADOTIPOINSUMO)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOINSUMO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDTIPOINSUMO.')" data-toggle="tooltip" data-placement="top" title="Desactivar Tipo de insumo"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDTIPOINSUMO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOINSUMO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDTIPOINSUMO.')" data-toggle="tooltip" data-placement="top" title="Activar Tipo de Insumo"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDTIPOINSUMO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'
            );
        }
        $results = array("sEcho" => 'IDTIPOINSUMO', //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
}
?>