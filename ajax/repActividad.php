<?php
require_once "../modelos/RepActividad.php";

$repActividad= new RepActividad();    

switch ($_GET["op"]) {
    
    case 'listar':
	    $fechainicio  = $_REQUEST["fechainicio"];    
        $fechafin     = $_REQUEST["fechafin"];
        $tipoact  	  = $_REQUEST["tipoact"];
		$corredoract  = $_REQUEST["corredoract"];
		
        $rspta=$repActividad->listar($fechainicio,$fechafin,$tipoact,$corredoract);
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBREACT,
				"1"=>$reg->LUGARACT,
                "2"=>$reg->fechadma,
				"3"=>$reg->tjornadas,
				"4"=>$reg->sumashoras,
				"5"=>$reg->tmasculino,  
                "6"=>$reg->tfemenino,
				"7"=>$reg->tmf                
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	
	case 'selectTipoActividad':
        $rspta=$repActividad->selectTipoActividad();
		echo '<option value="0">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOACTIVIDAD.'>'.$reg->NOMBRETAC.'</option>';
        }
	break;	
    case 'selectCorredor':
        $rspta=$repActividad->selectCorredor();
		echo '<option value="0">--Seleccione un corredor educativo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;   	
}
?>