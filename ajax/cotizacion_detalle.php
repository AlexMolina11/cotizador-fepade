<?php

session_start();

require_once "../modelos/Cotizacion_detalle.php";



$detalle= new Cotizacion_detalle();



//Datos del formulario

$iddetallecot     =isset($_POST["iddetallecot"])?limpiarCadena($_POST["iddetallecot"]):"";

$idcotizacion        =isset($_POST["idcotizacion"])?limpiarCadena($_POST["idcotizacion"]):"";

$cantpar         =isset($_POST["cantpar"])?limpiarCadena($_POST["cantpar"]):"";

$fechacot         =isset($_POST["fechacot"])?limpiarCadena($_POST["fechacot"]):"";

$horainicot       =isset($_POST["horainicot"])?limpiarCadena($_POST["horainicot"]):"";

$horafincot       =isset($_POST["horafincot"])?limpiarCadena($_POST["horafincot"]):"";

$duracion        =isset($_POST["duracion"])?limpiarCadena($_POST["duracion"]):"";



//Datos de sesión

$usuario        = $_SESSION["login"];

$permisorol     = $_SESSION["permiso"];

$idorgeje       = $_SESSION["idorgeje"];

$ip= $_SERVER["REMOTE_ADDR"];



//validando Acción Ver

if(isset($_SESSION["131VER45"])&&$_SESSION["131VER45"]==1){

    $btn_stl_ver = "style='display: block;'"; 

} else {

    $btn_stl_ver = "style='display: none;'"; 

}

//validando Acción Editar

if(isset($_SESSION["133EDI45"])&&$_SESSION["133EDI45"]==1){

    $btn_stl_edi = "style='display: block;'"; 

} else {

    $btn_stl_edi = "style='display: none;'"; 

}



//validando Acción Eliminar

if(isset($_SESSION["134ELI45"])&&$_SESSION["134ELI45"]==1){

    $btn_stl_elim = "style='display: block;'"; 

} else {

    $btn_stl_elim = "style='display: none;'"; 

}



//validando Acción Activar

if(isset($_SESSION["135ACT45"])&&$_SESSION["135ACT45"]==1){

    $btn_stl_act = "style='display: block;'"; 

} else {

    $btn_stl_act = "style='display: none;'"; 

}



//validando Acción Desactivar

if(isset($_SESSION["136DES45"])&&$_SESSION["136DES45"]==1){

    $btn_stl_desact = "style='display: block;'"; 

} else {

    $btn_stl_desact = "style='display: none;'"; 

}



switch ($_GET["op"]) {

    case 'guardaryeditar':



        if (empty($iddetallecot)) {

            //Insertar detalle

            $rspta=$detalle->insertar($idcotizacion,$cantpar,$fechacot,$horainicot,$horafincot,$duracion,$idorgeje,$ip,$usuario);

            if ($rspta) {

                echo "Detalle de cotizacion Registrada";

            } else {

                echo "El Detalle de cotizacion no se pudo registrar";

            }

        } else {

            //Actualizar Detalle de cotizacion

            $rspta=$detalle->editar($iddetallecot,$idcotizacion,$cantpar,$fechacot,$horainicot,$horafincot,$duracion);

            if($rspta) {

                echo "Detalle de cotizacion actualizada";

            } else {

                echo "El Detalle de cotizacion no se pudo actualizar";

            }

        }

    break;

    //Se agregó opción eliminar

    /*case 'eliminar':

        $rspta=$detalle->eliminar($iddetallecot);

        echo $rspta?"Detalle de cotizacion Eliminada!":"El Detalle de cotizacion no se pudo eliminar.";

    break;*/



    case 'mostrar':

        $rspta=$detalle->mostrar($iddetallecot);

        //Codificar el resultado utilizando json

        echo json_encode($rspta);

    break;



    case 'desactivar':

        $rspta=$detalle->desactivar($iddetallecot);

        echo $rspta?"Detalle de cotizacion Desactivada":"Detalle de cotizacion no se pudo desactivar";

    break;



    case 'activar':

        $rspta=$detalle->activar($iddetallecot);

        echo $rspta?"Detalle de cotizacion Activada":"Detalle de cotizacion no se pudo activar";

    break;



    case 'listar':

        //Agregamos variables de fecha inicio y fin

	    $fecha      = $_REQUEST["fecha"];    

        $cotizacion     = $_REQUEST["cotizacion"];  

        

        $rspta=$detalle->listar($fecha,$cotizacion);

        //Vamos a declarar un array

        $data= Array();



        while ($reg = $rspta->fetch_object()) {

            $data[]= array(

                "0"=>$reg->IDDETALLECOT,

                "1"=>$reg->CODREFERENCIA,

                "2"=>$reg->FECHACOT,

                "3"=>$reg->HORAINICOT,

                "4"=>$reg->HORAFINCOT,

                "5"=>$reg->CANTIDADCOT,

                "6"=>($reg->ESTADOCOT)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',

                "7"=>($reg->ESTADOCOT)?'<div class="btn-group btn-group-sm" style="display:flex;">

				<button '.$btn_stl_ver.' class="btn btn-info" type="button" onclick="verdetalle('.$reg->IDDETALLECOT.')" id="btnVistaPrevia" name="btnVistaPrevia" title="Ver detalle"><i class="fa fa-eye"></i></button>'.    

                '<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDDETALLECOT.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.

                '<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDDETALLECOT.')" data-toggle="tooltip" data-placement="top" title="Desactivar Detalle de cotizacion"><i class="fa fa-close"></i></button>'.

                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDDETALLECOT.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>':

                '<div class="btn-group btn-group-sm" style="display:flex;">

				<button '.$btn_stl_ver.' class="btn btn-info" type="button" onclick="verdetalle('.$reg->IDDETALLECOT.')" id="btnVistaPrevia" name="btnVistaPrevia" title="Ver detalle"><i class="fa fa-eye"></i> Vista previa</button>'.  

                '<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDDETALLECOT.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.

                '<button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDDETALLECOT.')" data-toggle="tooltip" data-placement="top" title="Activar Detalle de cotizacion"><i class="fa fa-check"></i></button>'.

                '<button '.$btn_stl_elim.' class="btn btn-info" onclick="eliminar('.$reg->IDDETALLECOT.')" data-toggle="tooltip" data-placement="top" title="Eliminar Información"><i class="fa fa-trash"></i></button></div>'



            );

        }

        $results = array("sEcho" => 1, //Información para el datatables

                         "iTotalRecords" => count($data),//enviamos el total registros al datatable

                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar

                         "aaData" =>$data);

        echo json_encode($results);                 

    break;



	case 'selectCotizacion':

        $rspta=$detalle->selectCotizacion();

		echo '<option value="">-- Seleccione una cotización --</option>';

        while ($reg = $rspta->fetch_object()) {

            echo '<option value='.$reg->IDCOTIZACION.'>'.$reg->CODREFERENCIA.' - '.$reg->EMPRESA.' - '.$reg->NOMBRETIPOEVENTO.'</option>';

        }

    break;



    case 'listarTiposInsumos':

        $rspta = $detalle->listarTiposInsumos();

        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(

                "id" => $reg->IDTIPOINSUMO,

                "nombre" => $reg->NOMBRETIPOINSUMO

            );

        }

        echo json_encode($data);

    break;



    case 'listarInsumosPorTipo':

        $idtipoinsumo = $_POST['idtipoinsumo'];

        $iddetallecot = $_POST['iddetallecot'];

        $rspta = $detalle->listarInsumosPorTipoConDetalle($idtipoinsumo, $iddetallecot);



        $columns = [];

        $rows = [];

        while ($reg = $rspta->fetch_assoc()) {

            // Detectar solo columnas con datos no nulos

            if (empty($columns)) {

                foreach ($reg as $key => $val) {

                    if (!is_null($val)) $columns[] = $key;

                }

            }

            $row = [];

            foreach ($columns as $col) {

                $row[$col] = $reg[$col];

            }

            $rows[] = $row;

        }

        echo json_encode(["columns" => $columns, "data" => $rows]);

    break;



    case 'guardarInsumo':

        $iddetallecot = $_POST['iddetallecot'];

        $idinsumo = $_POST['idinsumo'];

        $cantidad = $_POST['cantidad'];

        $precio = $_POST['precio'];

        $total = $_POST['total'];



        // Verificar si ya existe

        $existe = ejecutarConsultaSimpleFila("SELECT * FROM cot_detalle_insumos 

                                            WHERE IDDETALLECOT='$iddetallecot' 

                                            AND IDINSUMO='$idinsumo'");

        if ($existe) {

            $sql = "UPDATE cot_detalle_insumos 

                    SET CANTIDAD='$cantidad', PRECIO='$precio', TOTAL='$total' 

                    WHERE IDDETALLECOT='$iddetallecot' AND IDINSUMO='$idinsumo'";

        } else {

            $sql = "INSERT INTO cot_detalle_insumos (IDDETALLECOT, IDINSUMO, CANTIDAD, PRECIO, TOTAL) 

                    VALUES ('$iddetallecot','$idinsumo','$cantidad','$precio','$total')";

        }

        echo ejecutarConsulta($sql) ? "Guardado" : "Error";

    break;



    case 'eliminarInsumo':

        $iddetallecot = $_POST['iddetallecot'];

        $idinsumo = $_POST['idinsumo'];

        $sql = "DELETE FROM cot_detalle_insumos 

                WHERE IDDETALLECOT='$iddetallecot' AND IDINSUMO='$idinsumo'";

        echo ejecutarConsulta($sql) ? "Eliminado" : "Error";

    break;

    

}

?>