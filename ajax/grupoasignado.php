<?php
session_start();
require_once "../modelos/Grupoasignado.php";

$grupoasignado= new Grupoasignado();

$idgrupo=isset($_POST["idgrupo"])?limpiarCadena($_POST["idgrupo"]):"";
$tipoparticipante=isset($_POST["tipoparticipante"])?limpiarCadena($_POST["tipoparticipante"]):"";
$municipioact=isset($_POST["municipioact"])?limpiarCadena($_POST["municipioact"]):"";
//$participante=isset($_POST["participante"])?limpiarCadena($_POST["participante"]):"";

//validando Acción Eliminar
if(isset($_SESSION["072ELI27"])&&$_SESSION["072ELI27"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar': 
           if(!empty($_POST["participante"])){   
            $rspta=$grupoasignado->insertar($idgrupo,$_POST["participante"]);
			 echo $rspta? "Grupo Asignado Correctamente":"Grupo no pudo ser asignado";          
		   }
		   else{
		     $rspta=$grupoasignado->editar($idgrupo,$_POST["participante"]);
			 echo $rspta? "Grupo Asignado Actualizado":"Grupo asignado no se pudo actualizar";              
		   }
    break;
    case 'eliminar':
    if(!empty($_POST["participante"])){
        $rspta = $grupoasignado->eliminar($idgrupo,$_POST["participante"]);
        echo $rspta?"Participante eliminado del grupo: ".$idgrupo.".":"Participante no pudo ser eliminado";
    }
    break;

    case 'mostrar':
        $rspta=$grupoasignado->mostrar($idgrupo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$grupoasignado->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->NOMBREGRUPO,
                "1"=>$reg->NOMBRE,
				"2"=>$reg->EDADPAR,
				"3"=>$reg->SEXOPAR,
				"4"=>$reg->NOMBREMUN,
                "5"=>'<button '.$btn_stl_elim.' class="btn btn-danger" onclick="eliminar('.$reg->IDGRUPO.',\''.$reg->IDPARTICIPANTE.'\')"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Eliminar Asignacion"></i></button>'
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'listarSeleccionados':
        //Recibimos el idingreso
        $id=$_GET['id'];
        
        $rspta = $grupoasignado->listarSeleccionados($id);
        
        while ($reg = $rspta->fetch_object())
            {
                echo '<option selected value='.$reg->IDPARTICIPANTE.'>'.$reg->NOMBRE.'</option>';                      
            }
    break;

	case 'selectGrupo':
        $rspta=$grupoasignado->selectGrupo();
		echo '<option value="">--Seleccione un grupo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDGRUPO.'>'.$reg->NOMBREGRUPO.'</option>';
        }
	break;

	case 'selectTipo':
        $rspta=$grupoasignado->selectTipo();
		echo '<option value="0">--Seleccione un tipo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOPARTICIPANTE.'>'.$reg->NOMBRETIP.'</option>';
        }
	break;

	case 'selectMunicipio':
        $rspta=$grupoasignado->selectMunicipio();
		echo '<option value="0">--Seleccione un municipio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
	break;	
	
	case 'listarParticipantes':
	    
		$municipioact      = $_REQUEST["municipioact"];
        $tipoparticipante  = $_REQUEST["tipoparticipante"];
	    
		$rspta=$grupoasignado->listarParticipantes($municipioact,$tipoparticipante); 
          while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDPARTICIPANTE.'>'.$reg->NOMBRE.'</option>';
		  }	
    break;	  
}
?>