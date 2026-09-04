<?php
session_start();
require_once "../modelos/Discapacidad.php";

$discapacidad= new Discapacidad();

$iddiscapacidad  =isset($_POST["iddiscapacidad"])?limpiarCadena($_POST["iddiscapacidad"]):"";
$nombredis       =isset($_POST["nombredis"])?limpiarCadena($_POST["nombredis"]):"";

//validando Acción Editar
if(isset($_SESSION["054EDI23"])&&$_SESSION["054EDI23"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':

        if (empty($iddiscapacidad )) {
           $rspta=$discapacidad->insertar($nombredis);
           echo $rspta? "Discapacidad Registrada":"Discapacidad no se pudo registrar";
        }else {
            $rspta=$discapacidad->editar($iddiscapacidad,$nombredis);
           echo $rspta? "Discapacidad Actualizada":"Discapacidad no se pudo actualizar";
        }
        break;   
   
    case 'mostrar':
        $rspta=$discapacidad->mostrar($iddiscapacidad);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$discapacidad->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                
                "0"=>$reg->IDDISCAPACIDAD,
                "1"=>$reg->NOMBREDIS,                           
                "2"=>'<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDDISCAPACIDAD.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'                            
             );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
   
}
?>