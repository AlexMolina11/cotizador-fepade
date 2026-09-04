<?php
session_start();
require_once "../modelos/Organizacion.php";

$organizacion= new Organizacion();

$idorganizacion=isset($_POST["idorganizacion"])?limpiarCadena($_POST["idorganizacion"]):"";
$idtipoorganizacion=isset($_POST["idtipoorganizacion"])?limpiarCadena($_POST["idtipoorganizacion"]):"";
$nombreorg=isset($_POST["nombreorg"])?limpiarCadena($_POST["nombreorg"]):"";

//validando Acción Editar
if(isset($_SESSION["030EDI17"])&&$_SESSION["030EDI17"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["031ACT17"])&&$_SESSION["031ACT17"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["032DES17"])&&$_SESSION["032DES17"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':

        if (empty($idorganizacion)) {
           $rspta=$organizacion->insertar($idtipoorganizacion,$nombreorg);
           echo $rspta? "Alianza Registrada":"Alianza no se pudo registrar";
        }else {
            $rspta=$organizacion->editar($idorganizacion,$idtipoorganizacion,$nombreorg);
           echo $rspta? "Alianza Actualizada":"Alianza no se pudo actualizar";
        }
        break;   

    case 'desactivar':
        $rspta=$organizacion->desactivar($idorganizacion);
        echo $rspta?"Alianza Desactivada":"Alianza no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$organizacion->activar($idorganizacion);
        echo $rspta?"Alianza Activada":"Alianza no se pudo activar";
    break;
        
    case 'mostrar':
        $rspta=$organizacion->mostrar($idorganizacion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$organizacion->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
               
                "0"=>$reg->NOMBRETOR,
                "1"=>$reg->NOMBREORG,
                "2"=>($reg->ESTADOORG)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
                "3"=>($reg->ESTADOORG)?'<div class="btn-group btn-group-sm" style="display:flex;">
                  <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Editar Alianza"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Desactivar Alianza"><i class="fa fa-close"></i></button>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                  <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Editar Alianza"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDORGANIZACION.')" data-toggle="tooltip" data-placement="top" title="Activar Alianzan"><i class="fa fa-check"></i></button>'
                
                
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'selectTipoOrganizacion':
        $rspta=$organizacion->select();
        echo '<option value="">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOORGANIZACION.'>'.$reg->NOMBRETOR.'</option>';
        }
    break;
}
?>