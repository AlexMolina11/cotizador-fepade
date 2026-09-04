<?php
require_once "../modelos/RepAlianzas.php";

$repAlianzas= new RepAlianzas();    

switch ($_GET["op"]) {
    
    case 'listar':
	    $fechainicio        = $_REQUEST["fechainicio"];    
        $fechafin           = $_REQUEST["fechafin"];       
		$tipoorganizacion   = $_REQUEST["tipoorganizacion"];
		
        $rspta=$repAlianzas->listar($fechainicio,$fechafin,$tipoorganizacion);
        //Vamos a declarar un array
        $data= Array(); 
        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->IDALIANZA,
                "1"=>$reg->NOMBREALI,
                "2"=>$reg->NOMBRECOR,
                "3"=>$reg->Trimestre,
                "4"=>$reg->Estado,
				"5"=>$reg->RESPONSABLESALI,
				"6"=>$reg->Socios,
                "7"=>'$'.$reg->INVERSIONALI,
				"8"=>'$'.$reg->Especie,
				"9"=>'$'.$reg->Efectivo,
                "10"=>'$'.$reg->Total
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	
	case 'selectTipoOrganizacion':
        $rspta=$repAlianzas->selectTipoOrganizacion();
		echo '<option value="0">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOORGANIZACION.'>'.$reg->NOMBRETOR.'</option>';
        }
	break;	   
}
?>