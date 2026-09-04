<?php
session_start();
require_once "../modelos/Modulos.php";

$modulos= new Modulos();

$idmodulo=isset($_POST["idmodulo"])?limpiarCadena($_POST["idmodulo"]):"";
$nombremod=isset($_POST["nombremod"])?limpiarCadena($_POST["nombremod"]):"";
$descripcionmod=isset($_POST["descripcionmod"])?limpiarCadena($_POST["descripcionmod"]):"";
$padreohijo=isset($_POST["padreohijo"])?limpiarCadena($_POST["padreohijo"]):""; //se agrega Modulo Padre o hijo
$idmodpadre=isset($_POST["idmodpadre"])?limpiarCadena($_POST["idmodpadre"]):""; //se agrega Modulo Padre
$ipusu = $_SERVER["REMOTE_ADDR"];
$regusu = $_SESSION["login"];

//validando Acción Editar
if(isset($_SESSION["087EDI31"])&&$_SESSION["087EDI31"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':

        if (empty($idmodulo)) {
           //$rspta=$modulos->insertar($nombremod,$descripcionmod);
           $rspta=$modulos->insertar($nombremod,$descripcionmod,$padreohijo,$idmodpadre,$regusu,$ipusu);
           echo $rspta? "Modulo Registrado":"Modulo no se pudo registrar";
        }else {
            //$rspta=$modulos->editar($idmodulo,$nombremod,$descripcionmod);
            $rspta=$modulos->editar($idmodulo,$nombremod,$descripcionmod,$padreohijo,$idmodpadre,$regusu,$ipusu);
           echo $rspta? "Modulo Actualizado":"Modulo no se pudo actualizar";
        }
        break;   

          
    case 'mostrar':
        $rspta=$modulos->mostrar($idmodulo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$modulos->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                
                "0"=>$reg->IDMODULO,
                "1"=>$reg->NOMBREMOD,
                "2"=>$reg->DESCRIPCIONMOD,
                "3"=>'<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDMODULO.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'               
                
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    //Se agregó el selectModuloPadre
    case 'selectModuloPadre':
	    $rspta =$modulos->selectModuloPadre();
	    echo '<option value="">--Seleccione Módulo Padre--</option>'; 
	    while($reg =$rspta->fetch_object()){
	         echo '<option value='.$reg->IDMODULO.'>'.$reg->NOMBREMOD.'</option>';
	    }	
	break;
    
}
?>