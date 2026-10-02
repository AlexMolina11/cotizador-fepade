<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once "../modelos/Gestioninsumo.php";

$gestioninsumo = new Gestioninsumo();

//Variables del form
$idinsumo   	    =isset($_POST["idinsumo"])?limpiarCadena($_POST["idinsumo"]):"";
$nombreinsumo		=isset($_POST["nombreinsumo"])?limpiarCadena($_POST["nombreinsumo"]):"";
$aliasinsumo            =isset($_POST["aliasinsumo"])?limpiarCadena($_POST["aliasinsumo"]):"";
$descripcion_insumo	    =isset($_POST["descripcion_insumo"])?limpiarCadena($_POST["descripcion_insumo"]):"";
$tipoinsumo	        =isset($_POST["tipoinsumo"])?limpiarCadena($_POST["tipoinsumo"]):"";

//Variables de sesión
$usuario        = $_SESSION["login"];
$permisorol     = $_SESSION["permiso"];
$idorgeje       = $_SESSION["idorgeje"];
$ip             = $_SERVER["REMOTE_ADDR"];

//validando Acción Editar
if(isset($_SESSION["147EDI48"])&&$_SESSION["147EDI48"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["148ELI48"])&&$_SESSION["148ELI48"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["149ACT48"])&&$_SESSION["149ACT48"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["150DES48"])&&$_SESSION["150DES48"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

//validando Acción Ver 
/*if(isset($_SESSION["126VER44"])&&$_SESSION["126VER44"]==1){
    $btn_stl_ver = "style='display: block;'"; 
} else {
    $btn_stl_ver = "style='display: none;'"; 
}*/

switch ($_GET["op"]) {

    case 'guardaryeditar':

        if (empty($idinsumo)) {
            $rspta = $gestioninsumo->insertar(
                $idinsumo,
                $nombreinsumo,
                $aliasinsumo,
                $descripcion_insumo,
                $tipoinsumo,
                $usuario,
                $ip,
                $idorgeje
            );

            if ($rspta) {
                echo "Insumo Registrado";
            } else {
                echo "El insumo no se pudo registrar";
            }

        } else {

            $rspta = $gestioninsumo->editar(
                $idinsumo,
                $nombreinsumo,
                $aliasinsumo,
                $descripcion_insumo,
                $tipoinsumo
            );

            if ($rspta) {
                echo "Insumo Actualizado";
            } else {
                echo "El insumo no se pudo actualizar";
            }
        }

    break;

    case 'eliminar':

        $rspta=$gestioninsumo->eliminar($idinsumo);
        echo $rspta?"¡El insumo ha sido Eliminado!":"El insumo no se pudo eliminar";

    break;

    case 'mostrar':

        $rspta=$gestioninsumo->mostrar($idinsumo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);		

    break;

    case 'listar':

        $rspta=$gestioninsumo->listar();
        //Vamos a declarar un array
        $data= Array();
        while ($reg = $rspta->fetch_object()) {            

            $data[]= array(
                "0"=>$reg->IDINSUMO,
                "1"=>$reg->NOMBRETIPOINSUMO,
                "2"=>$reg->NOMBREINSUMO,
                "3"=>$reg->PRECIO8H,
                "4"=>$reg->PRECIO4H,
                "5"=>$reg->PRECIOSALAREMODELADA,
                "6"=>$reg->PRECIOUNITARIO,
                "7"=>$reg->PRECIOVENTA,
                "8"=>$reg->EDITABLEINS,
                "9"=>$reg->FECHAREGINS,
                "10"=>$reg->FECHAMODINS,
                "11"=>($reg->ESTADOINS)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
                "12"=>($reg->ESTADOINS)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDINSUMO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDINSUMO.')" data-toggle="tooltip" data-placement="top" title="Desactivar insumo"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDINSUMO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDINSUMO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDINSUMO.')" data-toggle="tooltip" data-placement="top" title="Activar Insumo"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDINSUMO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'
            );
        }

        $results = array("sEcho" => 1, //Información para el datatables

                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);

        echo json_encode($results);                 

    break;

	case 'selectTipoInsumo':

        $rspta=$gestioninsumo->selectTipoInsumo();
		echo '<option value="">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOINSUMO.'>'.$reg->NOMBRETIPOINSUMO.'</option>';
        }

	break;

    case 'desactivar':
        $rspta=$gestioninsumo->desactivar($idinsumo);
        echo $rspta?"El insumo ha sido Desactivado":"El insumo no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$gestioninsumo->activar($idinsumo);
        echo $rspta?"El insumo ha sido Activado":"El insumo no se pudo activar";
    break;

}

?>