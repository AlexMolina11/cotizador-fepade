<?php
session_start();
require_once "../modelos/Comunidad.php";

$comunidad= new Comunidad();

$idcomunidad=isset($_POST["idcomunidad"])?limpiarCadena($_POST["idcomunidad"]):"";
$nombrecomunidad=isset($_POST["nombrecomunidad"])?limpiarCadena($_POST["nombrecomunidad"]):"";
$idmunicipio=isset($_POST["idmunicipio"])?limpiarCadena($_POST["idmunicipio"]):""; //Se agregó Distrito
$idnvomunicipio=isset($_POST["idnvomunicipio"])?limpiarCadena($_POST["idnvomunicipio"]):""; //Se agregó Nuevo municipio
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):""; 
$latitud=isset($_POST["latitud"])?limpiarCadena($_POST["latitud"]):"";
$longitud=isset($_POST["longitud"])?limpiarCadena($_POST["longitud"]):"";

//validando Acción Editar
if(isset($_SESSION["002EDI11"])&&$_SESSION["002EDI11"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["003ELI11"])&&$_SESSION["003ELI11"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["004ACT11"])&&$_SESSION["004ACT11"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["005DES11"])&&$_SESSION["005DES11"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idcomunidad)) {
            $rspta_consulta=$comunidad->consulta_existencia($nombrecomunidad,$idmunicipio,$idnvomunicipio,$iddepartamento);
            if($rspta_consulta['IDCOMUNIDAD'] == NULL) {
                $rspta=$comunidad->insertar($nombrecomunidad,$idmunicipio,$idnvomunicipio,$iddepartamento,$latitud,$longitud);
                echo $rspta? "Comunidad Registrada":"Comunidad no se pudo registrar";
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
            $rspta=$comunidad->editar($idcomunidad,$nombrecomunidad,$idmunicipio,$idnvomunicipio,$iddepartamento,$latitud,$longitud);  // Se agregó variables de municipio y departamento
           echo $rspta? "Comunidad Actualizada":"Comunidad no se pudo actualizar";
        }
    break;

    case 'eliminar':
        $rspta=$comunidad->eliminar($idcomunidad);
        echo $rspta?"¡Comunidad Eliminada!":"¡Comunidad no se pudo eliminar!";
    break;

    case 'desactivar':
        $rspta=$comunidad->desactivar($idcomunidad);
        echo $rspta?"Comunidad Desactivada":"Comunidad no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$comunidad->activar($idcomunidad);
        echo $rspta?"Comunidad Activada":"Comunidad no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$comunidad->mostrar($idcomunidad);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$comunidad->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
            
                "0"=>$reg->NOMBRECOMUNIDAD,
                "1"=>$reg->NOMBREMUN, //Agregamos el nombre del distrito
                "2"=>$reg->NOMBRENVOMUN, //Agregamos el nombre del NUEVO municipio
                "3"=>$reg->NOMBREDEP,
                "4"=>($reg->ESTADO)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "5"=>($reg->ESTADO)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCOMUNIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDCOMUNIDAD.')" data-toggle="tooltip" data-placement="top" title="Desactivar comunidad"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDCOMUNIDAD.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' id="btn-editar" class="btn btn-warning" onclick="mostrar('.$reg->IDCOMUNIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información" disabled><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDCOMUNIDAD.')" data-toggle="tooltip" data-placement="top" title="Activar comunidad"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDCOMUNIDAD.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

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
        $rspta=$comunidad->selectDistrito();
        echo '<option value="">--Seleccione Distrito--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un Departamento
    case 'selectDist':
        $rspta=$comunidad->selectDist($_POST['iddepartamento']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un municipio
    case 'selectDistByNvoMuni':
        $rspta=$comunidad->selectDistByNvoMuni($_POST['idnvomunicipio']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select Todos los municipios
    case 'selectNvoMunicipio':
        $rspta=$comunidad->selectNvoMunicipio();
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un departamento
    case 'selectnvoMuni':
        $rspta=$comunidad->selectnvoMuni($iddepartamento);
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un distrito
    case 'selectnvoMuniByDist':
        $rspta=$comunidad->selectnvoMuniByDist($idmunicipio);
		//echo '<option value="0">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
	break;

    //Select todos los departamentos
    case 'selectDepartamento':
        $rspta=$comunidad->selectDepartamento();
        echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select los departamentos pertenecientes a un distrito
    case 'selectDepa':
        $rspta=$comunidad->selectDepa($_POST['idmunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select departamento perteneciente a un municipio
    case 'selectDepaByNvoMuni':
        $rspta=$comunidad->selectDepaByNvoMuni($_POST['idnvomunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //se agregó el Corr para cuando se selecciona un Departamento
    case 'selectCorr':
        echo '<option value="">--Seleccione Corredor--</option>';
        $rspta=$comunidad->selectCorr($iddepartamento);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;

}
?>