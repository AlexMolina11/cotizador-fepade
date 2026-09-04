<?php
session_start();
require_once "../modelos/Cantonesycaserios.php";

$cantonesycaserios = new Cantonesycaserios();

$idcantoncaserio=isset($_POST["idcantoncaserio"])?limpiarCadena($_POST["idcantoncaserio"]):"";
$nombrecantoncaserio=isset($_POST["nombrecantoncaserio"])?limpiarCadena($_POST["nombrecantoncaserio"]):"";
$idmunicipio=isset($_POST["idmunicipio"])?limpiarCadena($_POST["idmunicipio"]):"";
$idnvomunicipio=isset($_POST["idnvomunicipio"])?limpiarCadena($_POST["idnvomunicipio"]):"";
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):"";

//validando Acción Editar
if(isset($_SESSION["122EDI40"])&&$_SESSION["122EDI40"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["123ELI40"])&&$_SESSION["123ELI40"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["124ACT40"])&&$_SESSION["124ACT40"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["125DES40"])&&$_SESSION["125DES40"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idcantoncaserio)) {
            $rspta_consulta=$cantonesycaserios->consulta_existencia($nombrecantoncaserio,$idmunicipio,$idnvomunicipio,$iddepartamento);
            if($rspta_consulta['IDCANTONCASERIO'] == NULL) {
                $rspta=$cantonesycaserios->insertar($nombrecantoncaserio,$idmunicipio,$idnvomunicipio,$iddepartamento);
                echo $rspta? "Cantón/Caserío Registrado":"Cantón/Caserío no se pudo registrar";
            } else {
                echo "Existente";
            }
        }else {
            if($idmunicipio == 0){
                $idmunicipio = NULL;
            }
            if($idnvomunicipio == 0){
                $idnvomunicipio = NULL;
            }
            if($iddepartamento == 0){
                $iddepartamento = NULL;
            }
            $rspta=$cantonesycaserios->editar($idcantoncaserio,$nombrecantoncaserio,$idmunicipio,$idnvomunicipio,$iddepartamento);
           echo $rspta? "Cantón/Caserío Actualizado":"Cantón/Caserío no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$cantonesycaserios->eliminar($idcantoncaserio);
        echo $rspta?"¡Cantón/Caserío Eliminado!":"¡Cantón/Caserío no se pudo eliminar!";
    break;

    case 'desactivar':
        $rspta=$cantonesycaserios->desactivar($idcantoncaserio);
        echo $rspta?"Cantón/Caserío Desactivado":"Cantón/Caserío no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$cantonesycaserios->activar($idcantoncaserio);
        echo $rspta?"Cantón/Caserío Activado":"Cantón/Caserío no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$cantonesycaserios->mostrar($idcantoncaserio);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$cantonesycaserios->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
            
                "0"=>$reg->NOMBRECANTONCASERIO,
                "1"=>$reg->NOMBREMUN, 
                "2"=>$reg->NOMBRENVOMUN, 
                "3"=>$reg->NOMBREDEP,
                "4"=>($reg->ESTADO)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "5"=>($reg->ESTADO)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCANTONCASERIO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDCANTONCASERIO.')" data-toggle="tooltip" data-placement="top" title="Desactivar Cantón/caserío"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDCANTONCASERIO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' id="btn-editar" class="btn btn-warning" onclick="mostrar('.$reg->IDCANTONCASERIO.')" data-toggle="tooltip" data-placement="top" title="Editar Información" disabled><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDCANTONCASERIO.')" data-toggle="tooltip" data-placement="top" title="Activar Cantón/caserío"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDCANTONCASERIO.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    //Se agregó Distrito
    case 'selectDistrito':
        $rspta=$cantonesycaserios->selectDistrito();
        echo '<option value="">--Seleccione Distrito--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un Departamento
    case 'selectDist':
        $rspta=$cantonesycaserios->selectDist($_POST['iddepartamento']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un municipio
    case 'selectDistByNvoMuni':
        $rspta=$cantonesycaserios->selectDistByNvoMuni($_POST['idnvomunicipio']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select Todos los municipios
    case 'selectNvoMunicipio':
        $rspta=$cantonesycaserios->selectNvoMunicipio();
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un departamento
    case 'selectnvoMuni':
        $rspta=$cantonesycaserios->selectnvoMuni($iddepartamento);
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un distrito
    case 'selectnvoMuniByDist':
        $rspta=$cantonesycaserios->selectnvoMuniByDist($idmunicipio);
		//echo '<option value="0">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
	break;

    //Select todos los departamentos
    case 'selectDepartamento':
        $rspta=$cantonesycaserios->selectDepartamento();
        echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select los departamentos pertenecientes a un distrito
    case 'selectDepa':
        $rspta=$cantonesycaserios->selectDepa($_POST['idmunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select departamento perteneciente a un municipio
    case 'selectDepaByNvoMuni':
        $rspta=$cantonesycaserios->selectDepaByNvoMuni($_POST['idnvomunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

}
?>