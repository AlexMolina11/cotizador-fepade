<?php
session_start();
require_once "../modelos/Responsables.php";

$responsables= new Responsables();
 
$codigores=isset($_POST["codigores"])?limpiarCadena($_POST["codigores"]):"";
$nombreres=isset($_POST["nombreres"])?limpiarCadena($_POST["nombreres"]):"";
$idorgejecutora=isset($_POST["idorgejecutora"])?limpiarCadena($_POST["idorgejecutora"]):""; //Agregamos variable idorgejecutora

//validando Acción Editar
if(isset($_SESSION["040EDI20"])&&$_SESSION["040EDI20"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["041ELI20"])&&$_SESSION["041ELI20"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["042ACT20"])&&$_SESSION["042ACT20"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["043DES20"])&&$_SESSION["043DES20"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}


switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($codigores)) {
           //$rspta=$responsables->insertar($nombreres);
           $rspta=$responsables->insertar($nombreres,$idorgejecutora); //Se agregó organización ejecutora
           echo $rspta? "Responsable Registrado":"Responsable no se pudo registrar";
        }else {
            //$rspta=$responsables->editar($codigores,$nombreres);
            $rspta=$responsables->editar($codigores,$nombreres,$idorgejecutora); //Se agregó organización ejecutora
           echo $rspta? "Responsable Actualizado":"Responsable no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$responsables->eliminar($codigores);
        echo $rspta?"Responsable Eliminado!":"Responsable no se pudo eliminar!";
    break;

    case 'desactivar':
        $rspta=$responsables->desactivar($codigores);
        echo $rspta?"Responsable Desactivado":"Responsable no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$responsables->activar($codigores);
        echo $rspta?"Responsable Activado":"Responsable no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$responsables->mostrar($codigores);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$responsables->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBRERES,
                "1"=>$reg->NOMBREORGEJE, //Se agregó org. ejecutora
                "2"=>($reg->ESTADORES)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "3"=>($reg->ESTADORES)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->CODIGORES.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->CODIGORES.')" data-toggle="tooltip" data-placement="top" title="Desactivar Responsable"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->CODIGORES.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->CODIGORES.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->CODIGORES.')" data-toggle="tooltip" data-placement="top" title="Activar Responsable"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->CODIGORES.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

            );
        }
        $results = array("sEcho" => 'CODIGORES', //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
    
    //se agregó la opción select Organización Ejecutora
    case 'selectOrganizacionEjecutora':
        $rspta=$responsables->selectOrganizacionEjecutora();
        echo '<option value="">--Seleccione una organización ejecutora--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDORGEJE.'>'.$reg->NOMBREORGEJE.'</option>';
        }
    break;
}
?>