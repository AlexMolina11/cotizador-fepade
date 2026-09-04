<?php
session_start();
require_once "../modelos/Grupo.php";

$grupo= new Grupo();

$idgrupo=isset($_POST["idgrupo"])?limpiarCadena($_POST["idgrupo"]):"";
$nombregrupo=isset($_POST["nombregrupo"])?limpiarCadena($_POST["nombregrupo"]):"";
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):""; //Se agregó Departamento
$idmunicipio=isset($_POST["idmunicipio"])?limpiarCadena($_POST["idmunicipio"]):""; //Se agregó Municipio
$idcentroeducativo=isset($_POST["idcentroedu"])?limpiarCadena($_POST["idcentroedu"]):""; //Se agregó Centro educativo

//validando Acción Editar
if(isset($_SESSION["045EDI21"])&&$_SESSION["045EDI21"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["046ELI21"])&&$_SESSION["046ELI21"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["047ACT21"])&&$_SESSION["047ACT21"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["048DES21"])&&$_SESSION["048DES21"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}


switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idgrupo)) {
            //$rspta=$grupo->insertar($nombregrupo);
            $rspta=$grupo->insertar($nombregrupo,$iddepartamento,$idmunicipio,$idcentroeducativo); //Se agregan variables Departamento, Municipio y Centro Educativo
           echo $rspta? "Grupo Registrado":"Grupo no se pudo registrar";
        }else {
            //$rspta=$grupo->editar($idgrupo,$nombregrupo);
            $rspta=$grupo->editar($idgrupo,$nombregrupo,$iddepartamento,$idmunicipio,$idcentroeducativo); //Se agregan variables Departamento, Municipio y Centro Educativo
           echo $rspta? "Grupo Actualizado":"Grupo no se pudo actualizar";
        }
    break;
    
    //Se agregó opción Eliminar
    case 'eliminar':
        $rspta=$grupo->eliminar($idgrupo);
        echo $rspta?"Grupo Eliminado!":"Grupo no se pudo eliminar, debido a que este aún cuenta con participantes.";
    break;

    case 'desactivar':
        $rspta=$grupo->desactivar($idgrupo);
        echo $rspta?"Grupo Desactivado":"Grupo no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$grupo->activar($idgrupo);
        echo $rspta?"Grupo Activado":"Grupo no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$grupo->mostrar($idgrupo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$grupo->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->IDGRUPO, //se agegó el id
                "1"=>$reg->NOMBREGRUPO,
                "2"=>$reg->NOMBREDEP, //Agregamos el nombre del departamento
                "3"=>$reg->NOMBREMUN, //Agregamos el nombre del municipio
                "4"=>$reg->NOMBREINS, //Agregamos el nombre del Centro Educativo
                "5"=>($reg->ESTADOGRUPO)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "6"=>($reg->ESTADOGRUPO)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDGRUPO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDGRUPO.')" data-toggle="tooltip" data-placement="top" title="Desactivar Grupo"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDGRUPO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDGRUPO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDGRUPO.')" data-toggle="tooltip" data-placement="top" title="Activar Grupo"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDGRUPO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    //Se agregó Municipio
    case 'selectMunicipio':
        $rspta=$grupo->selectMunicipio();
        echo '<option value="0">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Se agregó Departamento
    case 'selectDepartamento':
        $rspta=$grupo->selectDepartamento();
        echo '<option value="0">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Se agregó el Depa para cuando se selecciona un Municipio
    case 'selectDepa':
        $rspta=$grupo->selectDepa($idmunicipio);
		//echo '<option value="0">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;

    //Se agregó el Depa para cuando se selecciona un Departamento
    case 'selectMuni':
        $rspta=$grupo->selectMuni($iddepartamento);
        echo '<option value="0">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Se agregó Centro Educativo
    case 'selectCentroEducativo':
        $rspta=$grupo->selectCentroEducativo();
        echo '<option value="0">--Seleccione Centro Educativo--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
    break;
}
?>