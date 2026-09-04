<?php
session_start();
require_once "../modelos/Acciones.php";

$acciones= new Acciones();

$idaccion=isset($_POST["idaccion"])?limpiarCadena($_POST["idaccion"]):"";
$nombreaccion=isset($_POST["nombreaccion"])?limpiarCadena($_POST["nombreaccion"]):"";
$descripcionacc=isset($_POST["descripcionacc"])?limpiarCadena($_POST["descripcionacc"]):"";
$idmodulo=isset($_POST["idmodulo"])?limpiarCadena($_POST["idmodulo"]):""; //se agrega Modulo
$ipusu = $_SERVER["REMOTE_ADDR"];
$regusu = $_SESSION["login"];

//validando Acción Editar
if(isset($_SESSION["098EDI34"])&&$_SESSION["098EDI34"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':

        if (empty($idaccion)) {
           $rspta=$acciones->insertar($nombreaccion,$descripcionacc,$idmodulo);
           echo $rspta? "Acción Registrada":"Acción no se pudo registrar";
        }else {
            $rspta=$acciones->editar($idaccion,$nombreaccion,$descripcionacc,$idmodulo);
           echo $rspta? "Acción Actualizada":"Acción no se pudo actualizar";
        }
        break;   

          
    case 'mostrar':
        $rspta=$acciones->mostrar($idaccion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$acciones->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->IDMODULO,
                "1"=>$reg->NOMBREACC,
                "2"=>$reg->DESCRIPCIONACC,
                "3"=>$reg->NOMBREMOD,
                "4"=>'<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDACCION.')" data-toggle="tooltip" data-placement="top" title="Editar Acciones"><i class="fa fa-pencil"></i></button>'               
                
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break; 
    
    //Se agregó el selectModulo
    case 'selectModulo':
	    $rspta =$acciones->selectModulo();
	    echo '<option value="">--Seleccione Módulo--</option>'; 
	    while($reg =$rspta->fetch_object()){
            if($reg->PADREOHIJO == 1) {
                $padreohijo = "Modulo Principal";
            } else if($reg->PADREOHIJO == 0) {
                $padreohijo = "SubModulo";
            }
	         echo '<option value='.$reg->IDMODULO.'>'.$reg->NOMBREMOD.' ( '.$padreohijo.' )</option>';
	    }	
	break;
}
?>