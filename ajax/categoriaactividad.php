<?php
session_start();
require_once "../modelos/Categoriaactividad.php";

$categoriaactividad= new Categoriaactividad(); 

$idcategoriaactividad=isset($_POST["idcategoriaactividad"])?limpiarCadena($_POST["idcategoriaactividad"]):"";
$nombrecac=isset($_POST["nombrecac"])?limpiarCadena($_POST["nombrecac"]):"";
$idtipoactividad=isset($_POST["idtipoactividad"])?limpiarCadena($_POST["idtipoactividad"]):"";

//validando Acción Editar
if(isset($_SESSION["021EDI15"])&&$_SESSION["021EDI15"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["022ELI15"])&&$_SESSION["022ELI15"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["023ACT15"])&&$_SESSION["023ACT15"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["024DES15"])&&$_SESSION["024DES15"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idcategoriaactividad)) {
           $rspta=$categoriaactividad->insertar($nombrecac,$idtipoactividad);
           echo $rspta? "Tipo de actividad Registrada":"Tipo de actividad no se pudo registrar";
        }else {
            $rspta=$categoriaactividad->editar($idcategoriaactividad,$nombrecac,$idtipoactividad);
           echo $rspta? "Tipo de actividad Actualizada":"Tipo de actividad no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$categoriaactividad->eliminar($idcategoriaactividad);
        echo $rspta?"Categoría de Actividad Eliminada!":"Categoría de Actividad no se pudo eliminar! Ya que pertenece a una actividad.";
    break;

    case 'desactivar':
        $rspta=$categoriaactividad->desactivar($idcategoriaactividad);
        echo $rspta?"Categoría de Actividad Desactivado":"Categoría de Actividad no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$categoriaactividad->activar($idcategoriaactividad);
        echo $rspta?"Categoría de Actividad activado":"Categoría de Actividad no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$categoriaactividad->mostrar($idcategoriaactividad);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$categoriaactividad->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
            
                "0"=>$reg->NOMBRETAC,
                "1"=>$reg->NOMBRECAC,
                "2"=>($reg->ESTADOCATACT)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "3"=>($reg->ESTADOCATACT)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCATEGORIAACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDCATEGORIAACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Desactivar Categoría Actividad"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDCATEGORIAACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCATEGORIAACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDCATEGORIAACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Activar Categoría Actividad"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDCATEGORIAACTIVIDAD.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'selectTipoActividad':
        $rspta=$categoriaactividad->selectTipoActividad();
		echo '<option value="">--Seleccione un tipo--</option>'; 
        //echo '<option value="0">--Seleccione un tipo--</option>'; //No debe llevar value="0" debe ir vacío o hará que falle el registro.
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOACTIVIDAD.'>'.$reg->NOMBRETAC.'</option>';
        }
	break;
}
?>