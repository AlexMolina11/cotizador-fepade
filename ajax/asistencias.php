<?php
session_start();
require_once "../modelos/Asistencias.php";

$asistencias= new Asistencias(); 
   
$nombrejor         =isset($_POST["nombrejor"])?limpiarCadena($_POST["nombrejor"]):"";
$objetivojor       =isset($_POST["objetivojor"])?limpiarCadena($_POST["objetivojor"]):"";
$fechajor          =isset($_POST["fechajorn"])?limpiarCadena($_POST["fechajorn"]):"";
$horainijor        =isset($_POST["horainijor"])?limpiarCadena($_POST["horainijor"]):"";
$horafinjor        =isset($_POST["horafinjor"])?limpiarCadena($_POST["horafinjor"]):"";
$idjornadas        =isset($_POST["idjornadas"])?limpiarCadena($_POST["idjornadas"]):"";
$idactividades     =isset($_POST["idactividades"])?limpiarCadena($_POST["idactividades"]):"";
$idmunicipio       =isset($_POST["idmunicipio"])?limpiarCadena($_POST["idmunicipio"]):"";
$idgrupo           =isset($_POST["idgrupo"])?limpiarCadena($_POST["idgrupo"]):"";

$idparticipante    =isset($_POST["idparticipante"])?limpiarCadena($_POST["idparticipante"]):"";

$usuario            = $_SESSION["login"];
$permisorol         = $_SESSION["permiso"];
$ip                 = $_SERVER["REMOTE_ADDR"];
$idorgeje           = $_SESSION["idorgeje"];

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idjornadas)){
                $rspta=$asistencias->insertar($idjornadas,$idactividades, $nombrejor, $objetivojor,$fechajor,$horainijor, $horafinjor,$_POST["idparticipante"],$usuario,$ip,$idorgeje);		  		   
                $asistencias->actualizarDuracion($idactividades);
                echo $rspta? "Asistencia Registrada":"Asistencia no se pudo registrar";           
        }else{ 
            $rspta = $asistencias->editar($idjornadas,$idactividades, $nombrejor, $objetivojor,$fechajor,$horainijor, $horafinjor,$_POST["idparticipante"],$idorgeje);
            echo $rspta? "Asistencia Actualizada":"Asistencia no se pudo actualizar, Puede que hayan participantes previamente registrados.";
        }       
    break;

    case 'eliminar':
        if(!empty($_POST["idparticipante"])){
            $rspta = $asistencias->eliminar($idjornadas,$_POST["idparticipante"]);
            echo $rspta?"Participante fue eliminado de la jornada correctamente":"Participante no puede ser eliminado";
        }
        else{}
       
    break;
    case 'mostrar':
        $rspta=$asistencias->mostrar($idjornadas);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
	
	case 'anular':
        $rspta=$asistencias->anular($idjornadas);
        echo $rspta?"Jornada anulada":"Jornada no se pudo anular";
    break;

    case 'activar':
        $rspta=$asistencias->activar($idjornadas);
        echo $rspta?"Jornada Activada":"Jornada no se pudo activar";
    break;
    
	case 'listarParticipantes':
	     $idgrupo 	  = $_REQUEST["idgrupo"];
		 $idmunicipio = $_REQUEST["idmunicipio"];
         $iddepartamento =$_REQUEST["iddepartamento"]; //se agregó departamento
         $idcentroeducativo =$_REQUEST["idcentroeducativo"]; //se agregó centro educativo
		 
        $rspta=$asistencias->listarParticipantes($idgrupo,$idmunicipio,$iddepartamento,$idcentroeducativo);
        //Vamos a declarar un array
        $data= Array();
        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
			    "0" => "<button class='btn btn-success' id='" . $reg->IDPARTICIPANTE . "' onClick=\"agregarDetalle(" . $reg->IDPARTICIPANTE . ", '" . addslashes($reg->NOMBRE) . "');\" title='Agregar participante a la jornada'><span class='fa fa-plus'></span></button>",
                //"0"=>'<input type="radio" id="'.$reg->IDPARTICIPANTE.'" value="'.$reg->IDPARTICIPANTE.'" onClick="agregarDetalle('.$reg->IDPARTICIPANTE.',\''.$reg->NOMBRE.'\');">',
                "1"=>$reg->IDPARTICIPANTE,
                "2"=>$reg->NOMBRE,
                "3"=>$reg->EDADPAR,
                "4"=>$reg->SEXOPAR,
                "5"=>$reg->NOMBREINS, //Se agregó el nombre del centro educativo
                "6"=>$reg->NOMBRECOMUNIDAD
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results); 
	
    break;

	case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
       
        $rspta = $asistencias->listarDetalle($id);
       
        echo '<thead style="background-color:#A9D0F5">
                <th>#</th>
                <th>Nombre de Participante</th>
                <th>Primera vez</th>  
                <th>Opciones</th>             
            </thead>';

       $cont = 1;
 
        while ($reg = $rspta->fetch_object())
        {
            echo '<tr class="filas" id="fila '.$cont.'">
                    <td>'.$cont.'</td>
                    <td class="count-me">'.$reg->NOMBRE.'</td>';
            
            // Verificar el valor de PRIMERAVEZ
            if ($reg->PRIMERAVEZ == 1) {
                // Mostrar un icono de cheque si PRIMERAVEZ es igual a 1
                echo '<td class="count-me" style="text-align: center;"><i class="fa fa-check"></i></td>';
            } else {
                // No mostrar nada si PRIMERAVEZ no es igual a 1
                echo '<td></td>';
            }
            
            echo '<td><button type="button" class="btn btn-xs btn-danger" onClick="eliminar('.$reg->IDJORNADAS.',\''.$reg->IDPARTICIPANTE.'\');eliminarDetalle('.$cont.');"><span class="fa fa-trash"></span></button></td>
                   </tr>';
            
            $cont++;
        }
        /*echo '<tfoot>
                <th></th>
                <th></th>               
            </tfoot>';  */   
    break;	
	case 'selectActividad':
        $rspta=$asistencias->selectActividad();
		echo '<option value="0">--Seleccione una actividad--</option>';
       while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDACTIVIDADES.'>'.$reg->NOMBREACT.'-'.$reg->NOMBRETAC.'-'.$reg->FECHAINICIOACT.'</option>';
        }
	break;
    //Agregamos Opción departamento
    case 'selectDepartamentos':
        $rspta=$asistencias->selectDepartamentos();
		echo '<option value="0">--Seleccione un departamento--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
	break;
	case 'selectMunicipios':
        $rspta=$asistencias->selectMunicipios();
		echo '<option value="0">--Seleccione un municipio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
	break;
    //Se agregó Opción Centro Educativo
    case 'selectCentroeducativo':
        $rspta=$asistencias->selectCentroeducativo();
		echo '<option value="0">--Seleccione un centro educativo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDINSTITUCION.'>'.$reg->NOMBREINS.'</option>';
        }
	break;
	case 'selectGrupos':
        $rspta=$asistencias->selectGrupos();
		echo '<option value="0">--Seleccione un grupo--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDGRUPO.'>'.$reg->NOMBREGRUPO.'</option>';
        }
    break;
    case 'mostrarNombre':
      $id=$_GET['id'];
       
      $rspta=$asistencias->mostrarNombre($id);		
      $reg = $rspta->fetch_object();
      echo $reg->NOMBRE;
     
    break;	      
    
    
}
?>