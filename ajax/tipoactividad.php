<?php
session_start();
require_once "../modelos/Tipoactividad.php";

$tipoactividad= new Tipoactividad();

$idtipoactividad=isset($_POST["idtipoactividad"])?limpiarCadena($_POST["idtipoactividad"]):"";
$nombretac=isset($_POST["nombretac"])?limpiarCadena($_POST["nombretac"]):"";

//validando Acción Editar
if(isset($_SESSION["016EDI14"])&&$_SESSION["016EDI14"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["017ELI14"])&&$_SESSION["017ELI14"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["018ACT14"])&&$_SESSION["018ACT14"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["019DES14"])&&$_SESSION["019DES14"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idtipoactividad)) {
           $rspta=$tipoactividad->insertar($nombretac);
           echo $rspta? "Tipo de actividad Registrada":"Tipo de actividad no se pudo registrar";
        }else {
            $rspta=$tipoactividad->editar($idtipoactividad,$nombretac);
           echo $rspta? "Tipo de actividad Actualizada":"Tipo de actividad no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$tipoactividad->eliminar($idtipoactividad);
        echo $rspta?"Tipo Actividad Eliminada!":"Tipo Actividad no se pudo eliminar! Ya que pertenece a una Categoría de Actividad.";
    break;

    case 'desactivar':
        $rspta=$tipoactividad->desactivar($idtipoactividad);
        echo $rspta?"Tipo de Actividad Desactivado":"Tipo de Actividad no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$tipoactividad->activar($idtipoactividad);
        echo $rspta?"Tipo de Actividad activado":"Tipo de Actividad no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$tipoactividad->mostrar($idtipoactividad);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$tipoactividad->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
            
                "0"=>$reg->NOMBRETAC,
                "1"=>($reg->ESTADOTIPOACT)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "2"=>($reg->ESTADOTIPOACT)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDTIPOACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Desactivar Tipo Actividad"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDTIPOACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDTIPOACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDTIPOACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Activar Tipo Actividad"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDTIPOACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

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