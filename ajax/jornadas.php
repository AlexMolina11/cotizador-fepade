<?php
session_start();
require_once "../modelos/Jornadas.php";

$jornadas= new Jornadas();

$idjornadas     =isset($_POST["idjornadas"])?limpiarCadena($_POST["idjornadas"]):"";
$idactividades  =isset($_POST["idactividades"])?limpiarCadena($_POST["idactividades"]):"";
$nombrejor      =isset($_POST["nombrejor"])?limpiarCadena($_POST["nombrejor"]):"";
$objetivojor    =isset($_POST["objetivojor"])?limpiarCadena($_POST["objetivojor"]):"";
$lugarjor		   =isset($_POST["lugarjor"])?limpiarCadena($_POST["lugarjor"]):""; //se agregó lugar
$responsablejor    =isset($_POST["responsablejor"])?array_map('limpiarCadena',$_POST["responsablejor"]):[]; //se agregó el responsable
$fechajor       =isset($_POST["fechajor"])?limpiarCadena($_POST["fechajor"]):"";
$horainijor     =isset($_POST["horainijor"])?limpiarCadena($_POST["horainijor"]):"";
$horafinjor     =isset($_POST["horafinjor"])?limpiarCadena($_POST["horafinjor"]):"";
$duracion       =isset($_POST["duracion"])?limpiarCadena($_POST["duracion"]):"";
$usuario        = $_SESSION["login"];
$permisorol     = $_SESSION["permiso"];
$idorgeje       = $_SESSION["idorgeje"];
$ip= $_SERVER["REMOTE_ADDR"];

//$fecha_convert = strtotime($fechajor);
//$fecha = date("Y-m-d",$fechajor);


//validando Acción Editar
if(isset($_SESSION["079EDI29"])&&$_SESSION["079EDI29"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Eliminar
if(isset($_SESSION["080ELI29"])&&$_SESSION["080ELI29"]==1){
    $btn_stl_elim = "style='display: block;'"; 
} else {
    $btn_stl_elim = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["081ACT29"])&&$_SESSION["081ACT29"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["082DES29"])&&$_SESSION["082DES29"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
        /*if (empty($idjornadas)) {
           $rspta=$jornadas->insertar($idactividades,$nombrejor,$objetivojor,$lugarjor,$responsablejor,$fechajor,$horainijor,$horafinjor,$usuario,$ip,$idorgeje,$duracion);
		   $jornadas->actualizarDuracion($idactividades);
           echo $rspta? "Jornada Registrada":"Jornada no se pudo registrar";
        }else {
            $rspta=$jornadas->editar($idjornadas,$idactividades,$nombrejor,$objetivojor,$lugarjor,$responsablejor,$fechajor,$horainijor,$horafinjor,$duracion);
           echo $rspta? "Jornada Actualizada":"Jornada no se pudo actualizar";
        }*/

        if (empty($idjornadas)) {
            //Insertar Jornada
            $rspta=$jornadas->insertar($idactividades,$nombrejor,$objetivojor,$lugarjor,$fechajor,$horainijor,$horafinjor,$usuario,$ip,$idorgeje,$duracion);
            if ($rspta) {
                $idjornadaNueva = $jornadas->ultimoID();
                $jornadas->insertarResponsablesJornada($idjornadaNueva,$responsablejor);
                echo "Jornada Registrada";
            } else {
                echo "La Jornada no se pudo registrar";
            }
        } else {
            //Actualizar Jornada
            $rspta=$jornadas->editar($idjornadas,$idactividades,$nombrejor,$objetivojor,$lugarjor,$fechajor,$horainijor,$horafinjor,$duracion);
            if($rspta) {
                //Eliminar Responsables actuales
                $jornadas->eliminarResponsablesJornada($idjornadas);
                //Insertar nuevos responsables
                $jornadas->insertarResponsablesJornada($idjornadas,$responsablejor);
                echo "Jornada actualizada";
            } else {
                echo "La Jornada no se pudo actualizar";
            }
        }
    break;
    //Se agregó opción eliminar
    case 'eliminar':
        $rspta=$jornadas->eliminar($idjornadas);
        echo $rspta?"Jornada Eliminada!":"Jornada no se pudo eliminar debido que aún cuenta con asistentes.";
    break;

    case 'mostrar':
        $rspta=$jornadas->mostrar($idjornadas);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'desactivar':
        $rspta=$jornadas->desactivar($idjornadas);
        echo $rspta?"Jornada Desactivada":"Jornada no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$jornadas->activar($idjornadas);
        echo $rspta?"Jornada Activada":"Jornada no se pudo activar";
    break;

    case 'listar':
        //Agregamos variables de fecha inicio y fin
	    $fechainicio  = $_REQUEST["fechainicio"];    
        $fechafin     = $_REQUEST["fechafin"];  
        
        $rspta=$jornadas->listar($fechainicio,$fechafin);
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                "0"=>$reg->IDACTIVIDADES, //Se agrgo el ID
                "1"=>$reg->NOMBREACT,
                "2"=>$reg->IDJORNADAS, //Se agrgo el ID
                "3"=>$reg->NOMBREJOR,
                "4"=>$reg->FECHA,
                "5"=>$reg->HORAINIJOR,
                "6"=>$reg->HORAFINJOR,
                "7"=>$reg->USUREGJOR,
                "8"=>($reg->ESTADOJOR)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "9"=>($reg->ESTADOJOR)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Desactivar Jornada"><i class="fa fa-close"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Activar Jornada"><i class="fa fa-check"></i></button>'.
                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDJORNADAS.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'

            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;
	case 'selectActividad':
        $rspta=$jornadas->selectActividad();
		echo '<option value="">--Seleccione una actividad--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDACTIVIDADES.'>'.$reg->NOMBREACT.' - '.$reg->NOMBRETAC.' - '.$reg->FECHAINIA.'</option>';
        }
    break;

    case 'obtenerFecha':
        $rspta=$jornadas->obtenerFecha($idactividades);
        $reg = $rspta->fetch_object();
        echo $reg->FECHAINICIOACT;       
    break;

    case 'selectResponsable':
        $rspta=$jornadas->selectResponsable();
		//echo '<option value="">--Seleccione un facilitador--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->CODIGORES.'>'.$reg->NOMBRERES.' - '.$reg->NOMBREORGEJE.'</option>';
        }
	break;
    
}
?>