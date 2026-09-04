<?php
session_start();
require_once "../modelos/Consultas_Asistencia.php";

$consultas= new Consultas();

//validando Acción Agregar
if(isset($_SESSION["083AGR30"])&&$_SESSION["083AGR30"]==1){
    $btn_stl_agr = "style='display: block;'"; 
} else {
    $btn_stl_agr = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["084ACT30"])&&$_SESSION["084ACT30"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["085DES30"])&&$_SESSION["085DES30"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    
    case 'consultaasistencia':
        
        $actividad  = $_REQUEST["actividad"];
        //Agregamos variables de corredor, fecha inicio y fin
        $corredor     = $_REQUEST["corredor"];
	    $fechainicio  = $_REQUEST["fechainicio"];    
        $fechafin     = $_REQUEST["fechafin"];

        $rspta=$consultas->consultaasistencia($actividad,$corredor,$fechainicio,$fechafin); 
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $vista_usuario = (($reg->ESTADOJOR=='1')?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_agr.' class="btn btn-success" onclick="mostrar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Agregar Asistencia"><i class="fa fa-plus"></i></button>'. 
                    '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="anular('.$reg->IDJORNADAS.')"  data-toggle="tooltip" data-placement="top" title="Desactivar Asistencia"><i class="fa fa-close"></i></button></div>':
                    '<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_agr.' class="btn btn-success" onclick="mostrar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Agregar Asistencia"><i class="fa fa-plus"></i></button>'.
                    '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDJORNADAS.')"  data-toggle="tooltip" data-placement="top" title="Activar Asistencia"><i class="fa fa-check"></i></button></div>');
        
            $data[]= array(
                "0"=>$reg->IDACTIVIDADES,
                "1"=>$reg->NOMBREACT,
                "2"=>$reg->IDJORNADAS,
                "3"=>$reg->NOMBREJOR,
                "4"=>$reg->DESCRIPCIONACT,
				"5"=>$reg->FECHA,
				"6"=>$reg->HORAINIJOR,
                "7"=>$reg->HORAFINJOR,
                "8"=>$reg->asistencia,
                "9"=>$reg->USUREGJOR,
                "10"=>($reg->ESTADOJOR=='1')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>',
				"11"=>$vista_usuario);
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'selectActividad':
        $rspta=$consultas->selectActividad();
		echo '<option value="0">--Seleccione una Actividad--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDACTIVIDADES.'>'.$reg->NOMBREACT.'</option>';
        }
	break;

    //Se agregó la opción para seleccionar corredor
    case 'selectCorredor':
        $rspta=$consultas->selectCorredor();
		echo '<option value="0">--Seleccione una Corredor--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;
   
}
?>