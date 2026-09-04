<?php
session_start();
require_once "../modelos/Corredor.php";

$corredor= new Corredor();

$idcorredor=isset($_POST["idcorredor"])?limpiarCadena($_POST["idcorredor"]):"";
$idplan=isset($_POST["idplan"])?limpiarCadena($_POST["idplan"]):"";
//$idalianza=isset($_POST["idalianza"])?limpiarCadena($_POST["idalianza"]):""; //se agrega Alianza
$idalianza_comas = isset($_POST["idalianza"]) ? $_POST["idalianza"] : array(); // Obtener un array de valores
$nombrecor=isset($_POST["nombrecor"])?limpiarCadena($_POST["nombrecor"]):"";
$descripcioncor=isset($_POST["descripcioncor"])?limpiarCadena($_POST["descripcioncor"]):"";
//$sectorcor=isset($_POST["sector"])?limpiarCadena($_POST["sector"]):""; //Se creó variable Sector
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):""; //Se agregó Departamento

$usuario = $_SESSION["login"];
$ip= $_SERVER["REMOTE_ADDR"];

if(empty($idplan)){$plan='NULL';}else{$plan=$idplan;}

//validando Acción Editar
if(isset($_SESSION["007EDI12"])&&$_SESSION["007EDI12"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["008ACT12"])&&$_SESSION["008ACT12"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["009DES12"])&&$_SESSION["009DES12"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idcorredor)) {
            $rspta=$corredor->insertar($plan,$nombrecor,$descripcioncor,$idalianza,$idalianza_comas,$usuario,$ip,$iddepartamento); //Se agrego sector y Alianza
           //echo $rspta? "Corredor Registrado":"Corredor no se pudo registrar";
           echo $rspta;
        }else {
            $rspta=$corredor->editar($idcorredor,$plan,$nombrecor,$descripcioncor,$idalianza,$idalianza_comas,$iddepartamento); //Se agregó Sector y Alianza
            //echo $rspta? "Corredor Actualizado":"Corredor no se pudo actualizar";
            echo $rspta;
        }
    break;

    case 'mostrar':
        $rspta=$corredor->mostrar($idcorredor);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
	
	case 'desactivar':
        $rspta=$corredor->desactivar($idcorredor);
        echo $rspta?"Corredor Desactivado":"Corredor no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$corredor->activar($idcorredor);
        echo $rspta?"Corredor activado":"Corredor no se pudo activar";
    break;

    case 'listar':
        $rspta=$corredor->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBREPLA,
                "1"=>$reg->NOMBRECOR,
                "2"=>$reg->DESCRIPCIONCOR,
                "3"=>$reg->NOMBREALIANZAS , //Se agregó Organizacion 
                "4"=>$reg->NOMBREDEP, //se agregó departamento
                //"3"=>$reg->SECTORCOR, //Se agregó Sector en la lista
                "5"=>($reg->ESTADOCOR)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "6"=>($reg->ESTADOCOR)?'<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCORREDOR.')"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDCORREDOR.')" data-toggle="tooltip" data-placement="top" title="Desactivar corredor"><i class="fa fa-close"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCORREDOR.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDCORREDOR.')" data-toggle="tooltip" data-placement="top" title="Activar corredor"><i class="fa fa-check"></i></button></div>'
                    
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	
	case 'selectPlanes':
	    $rspta =$corredor->selectPlanes();
	    //echo '<option value="">--Seleccione Plan--</option>'; 
        //echo '<option value="0">--Seleccione Plan--</option>'; //No debe llevar value="0" debe ir vacío o hará que falle el registro.
	    while($reg =$rspta->fetch_object()){
	         echo '<option value='.$reg->IDPLAN.'>'.$reg->NOMBREPLA.'</option>';
	    }	
	break;
    //Se agregó el selectAlianza
    case 'selectAlianzas':
	    $rspta =$corredor->selectAlianzas();
	    echo '<option value="">--Seleccione Alianza--</option>'; 
	    while($reg =$rspta->fetch_object()){
	         echo '<option value='.$reg->IDORGANIZACION.'>'.$reg->NOMBREORG.'</option>';
	    }	
	break;
    //Se agregó Departamento
    case 'selectDepartamento':
        $rspta=$corredor->selectDepartamento();
        echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;
    
    case 'obtenerAlianzas':
        $alianzas = $corredor->obtenerAlianzas($idcorredor);
        // Vamos a declarar un array
        $data = array();
        while ($reg = $alianzas->fetch_object()) { // Cambiar $rspta a $alianzas
            $data[] = array(
                "IDALIANZA" => $reg->IDALIANZA,
                "NOMBREORG" => $reg->NOMBREORG
            );
        }
        echo json_encode($data);
    break;
}
?>