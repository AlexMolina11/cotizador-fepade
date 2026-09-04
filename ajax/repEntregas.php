<?php
require_once "../modelos/RepEntregas.php";

$repEntregas= new RepEntregas();    

switch ($_GET["op"]) {
    
    case 'listar':
	    $fechainicio  = $_REQUEST["fechainicio"];    
        $fechafin     = $_REQUEST["fechafin"];       
		$catproduto   = $_REQUEST["catproduto"];
		
        $rspta=$repEntregas->listar($fechainicio,$fechafin,$catproduto);
        //Vamos a declarar un array
        $data= Array();
        //$reg = $rspta->fetch_object();
        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBREMAT,
				"1"=>$reg->FECHAAEN,
                "2"=>$reg->NOMBREINS,
				"3"=>$reg->NOMBRERECIBEAEN,
				"4"=>$reg->CANTIDADACD			         
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	
	case 'selectCategorias':
        $rspta=$repEntregas->selectCategorias();
		echo '<option value="0">--Seleccione una categoria--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCATEGORIAMATERIAL.'>'.$reg->NOMBRECMA.'</option>';
        }
	break;	   
}
?>