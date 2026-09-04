<?php
require_once "../modelos/RepAsistencias.php";

$repAsistencias= new RepAsistencias();    
$actividades	   =isset($_POST["actividades"])?limpiarCadena($_POST["actividades"]):"";
$tipoactividad	   =isset($_POST["tipoactividad"])?limpiarCadena($_POST["tipoactividad"]):"";

switch ($_GET["op"]) {
    
    case 'listar':
	    $fechaini          = $_REQUEST["fechaini"];    
        $fechafin          = $_REQUEST["fechafin"];       
		$actividades       = $_REQUEST["actividades"];
		$tipoactividad     = $_REQUEST["tipoactividad"];
		$tipoparticipante  = $_REQUEST["tipoparticipante"];
		
		$rspta=$repAsistencias->listar($fechaini,$fechafin,$actividades,$tipoactividad,$tipoparticipante);
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->IDPARTICIPANTE,
				"1"=>$reg->nombre,
                "2"=>$reg->SEXOPAR,
				"3"=>$reg->EDADPAR,
				"4"=>$reg->cantidadjorandas,
                "5"=>$reg->sumahoras);
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	
	case 'selectTipoActividad':
        $rspta=$repAsistencias->selectTipoActividad();
		echo '<option value="0">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOACTIVIDAD.'>'.$reg->NOMBRETAC.'</option>';
        }
	break;
	case 'selectTipoParticipante':
        $rspta=$repAsistencias->selectTipoParticipante();
		echo '<option value="0">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOPARTICIPANTE.'>'.$reg->NOMBRETIP.'</option>';
        }
	break;

    case 'selectActividadTipo':
        $rspta=$repAsistencias->selectActividadTipo($tipoactividad);
        echo '<option value="0">--Seleccione una actividad--</option>';
		while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDACTIVIDADES.'>'.$reg->NOMBRETAC.'-'.$reg->NOMBREACT.'-'.$reg->FECHA.'</option>';
        }
	break;
	
	case 'selectActividad':
        $rspta=$repAsistencias->selectActividad($actividades);
		echo '<option value="0">--Seleccione una actividad--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDACTIVIDADES.'>'.$reg->NOMBRETAC.'-'.$reg->NOMBREACT.'-'.$reg->FECHA.'</option>';
        }
    break;	
	 
}
?>