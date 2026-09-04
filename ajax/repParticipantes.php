<?php
require_once "../modelos/RepParticipantes.php";

$repParticipantes= new RepParticipantes();    

switch ($_GET["op"]) {
    
    case 'listar':
	    $municipio          = $_REQUEST["municipio"];    
        $corredor           = $_REQUEST["corredor"];       
		$tipoparticipante   = $_REQUEST["tipoparticipante"];
		$fechaini           = $_REQUEST["fechaini"];       
		$fechafin           = $_REQUEST["fechafin"];
		
        $rspta=$repParticipantes->listar($fechaini,  $fechafin, $tipoparticipante, $municipio,  $corredor);
        //Vamos a declarar un array
        $data= Array();
	
		 while($reg = $rspta->fetch_object()){
		
		   $data[]= array(
			
			"0"=>$reg->nombremun,
			"1"=>$reg->nombrecom,
			"2"=>$reg->Masculino,
			"3"=>$reg->Femenino,
			"4"=>$reg->edad1014,
			"5"=>$reg->edad1519,
            "6"=>$reg->edad2024,
            "7"=>$reg->edad2029,
			"8"=>$reg->edad30,
			"9"=>$reg->edad0,
			"10"=>$reg->Masculino+$reg->Femenino,
			"11"=>$reg->nuevos			
			);
		}
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	
	case 'selectTipoParticipante':
        $rspta=$repParticipantes->selectTipoParticipante();
		echo '<option value="0">--Seleccione un participante--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOPARTICIPANTE.'>'.$reg->NOMBRETIP.'</option>';
        }
	break;
    case 'selectMunicipio':
        $rspta=$repParticipantes->selectMunicipio();
		echo '<option value="0">--Seleccione un municipio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
	break;	   
    case 'selectCorredor':
        $rspta=$repParticipantes->selectCorredor();
		echo '<option value="0">--Seleccione un corredor--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
	break;	   	
}
?>