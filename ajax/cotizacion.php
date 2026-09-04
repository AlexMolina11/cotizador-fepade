<?php



ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);



session_start();



use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

// Librería para validación real de teléfonos internacionales

use libphonenumber\PhoneNumberUtil;

use libphonenumber\PhoneNumberFormat;

use libphonenumber\NumberParseException;



require_once "../librerias/PHPMailer/src/Exception.php";

require_once "../librerias/PHPMailer/src/PHPMailer.php";

require_once "../librerias/PHPMailer/src/SMTP.php";



// Cargar autoload de Composer para libphonenumber

require_once "../vendor/autoload.php";



require_once "../modelos/Cotizacion.php";



$cotizacion= new Cotizacion();





function normalizarTelefonoEntrada($telefono) {

    $telefono = trim((string)$telefono);



    // Quita espacios, guiones, paréntesis y puntos

    $telefono = preg_replace('/[\s\-\(\)\.]+/', '', $telefono);



    // Convierte 00 inicial en +

    if (strpos($telefono, '00') === 0) {

        $telefono = '+' . substr($telefono, 2);

    }



    // Deja solo un + al inicio y números en el resto

    if (strpos($telefono, '+') === 0) {

        $telefono = '+' . preg_replace('/\D+/', '', substr($telefono, 1));

    } else {

        $telefono = preg_replace('/\D+/', '', $telefono);

    }



    return $telefono;

}



function validarYFormatearTelefonoInternacional($telefono) {

    $telefono = normalizarTelefonoEntrada($telefono);



    // Para validación real por país sin selector de país,

    // exigimos prefijo internacional con +

    if ($telefono === '' || strpos($telefono, '+') !== 0) {

        return [

            'ok' => false,

            'mensaje' => 'Debe ingresar el teléfono en formato internacional, por ejemplo: +50371234567'

        ];

    }



    try {

        $phoneUtil = PhoneNumberUtil::getInstance();

        $numeroProto = $phoneUtil->parse($telefono, null);



        if (!$phoneUtil->isValidNumber($numeroProto)) {

            return [

                'ok' => false,

                'mensaje' => 'El número de teléfono no es válido para su país.'

            ];

        }



        // Guardamos en formato E.164

        $telefonoFormateado = $phoneUtil->format($numeroProto, PhoneNumberFormat::E164);



        return [

            'ok' => true,

            'telefono' => $telefonoFormateado

        ];

    } catch (NumberParseException $e) {

        return [

            'ok' => false,

            'mensaje' => 'No se pudo interpretar el número de teléfono. Use formato internacional, por ejemplo: +50371234567'

        ];

    } catch (Exception $e) {

        return [

            'ok' => false,

            'mensaje' => 'Ocurrió un error al validar el teléfono.'

        ];

    }

}





//Variables del form



$idcotizacion	        =isset($_POST["idcotizacion"])?limpiarCadena($_POST["idcotizacion"]):"";



$codreferencia		=isset($_POST["codreferencia"])?limpiarCadena($_POST["codreferencia"]):"";



$exentacot = isset($_POST["exentacot"]) ? limpiarCadena($_POST["exentacot"]) : "0";



$cli_nrc            =isset($_POST["cli_nrc"])?limpiarCadena($_POST["cli_nrc"]):"";



$cli_empresa	    =isset($_POST["cli_empresa"])?limpiarCadena($_POST["cli_empresa"]):"";



$cli_contacto       =isset($_POST["cli_contacto"])?limpiarCadena($_POST["cli_contacto"]):""; 



$cli_telefono       =isset($_POST["cli_telefono"])?limpiarCadena($_POST["cli_telefono"]):""; 



$cli_email		    =isset($_POST["cli_email"])?limpiarCadena($_POST["cli_email"]):"";



$tipoevento	        =isset($_POST["tipoevento"])?limpiarCadena($_POST["tipoevento"]):"";



$tipoalquiler       =isset($_POST["tipoalquiler"])?limpiarCadena($_POST["tipoalquiler"]):""; 



$descripcion_cot            =isset($_POST["descripcion_cot"])?limpiarCadena($_POST["descripcion_cot"]):"";







//Variables de sesión



$usuario           =$_SESSION["login"];



$permisorol     = $_SESSION["permiso"];



$idorgeje       = $_SESSION["idorgeje"];



$ip= $_SERVER["REMOTE_ADDR"];







//validando Acción Agregar detalle



if(isset($_SESSION["128AGR44"])&&$_SESSION["128AGR44"]==1){



    $btn_stl_agdet = "style='display: block;'"; 



} else {



    $btn_stl_agdet = "style='display: none;'"; 



}







//validando Acción Editar



if(isset($_SESSION["129EDI44"])&&$_SESSION["129EDI44"]==1){



    $btn_stl_edi = "style='display: block;'"; 



} else {



    $btn_stl_edi = "style='display: none;'"; 



}







//validando Acción Ver 



if(isset($_SESSION["126VER44"])&&$_SESSION["126VER44"]==1){



    $btn_stl_ver = "style='display: block;'"; 



} else {



    $btn_stl_ver = "style='display: none;'"; 



}



//validando Acción cambiar estado



if(isset($_SESSION["130CAM44"])&&$_SESSION["130CAM44"]==1){



    $btn_stl_camb_estado = "style='display: block;'"; 



} else {



    $btn_stl_camb_estado = "style='display: none;'"; 



}



//validando Acción ver pipeline (timeline)

if(isset($_SESSION["137VER44"]) && $_SESSION["137VER44"]==1){

    $btn_stl_pipeline = "style='display: block;'";

} else {

    $btn_stl_pipeline = "style='display: none;'";

}





switch ($_GET["op"]) {







    case 'generarCodigoReferencia':



        // Año actual que se debe mostrar en el código

        $year = date("Y");



        // Obtener el número REF[#] más alto

        $sql = "

            SELECT 

                MAX(

                    CAST(REPLACE(SUBSTRING_INDEX(CODREFERENCIA, '-', 1), 'REF', '') AS UNSIGNED)

                ) AS ultimo_ref

            FROM cot_cotizaciones

        ";



        $result = $conexion->query($sql);

        $row = $result->fetch_assoc();



        // Si no hay registros aún, empezar en 1

        $correlativo = ($row['ultimo_ref']) ? $row['ultimo_ref'] + 1 : 1;



        // Guardamos correlativo provisional

        $_SESSION['correlativo_cotizacion'] = $correlativo;



        // Iniciales del usuario logueado

        $usuario = $_SESSION['login'];

        $iniciales = strtoupper(substr($usuario, 0, 2));



        // Código referencia base (esperando tipo de alquiler)

        $codigo = "REF".$correlativo."-".$iniciales."-".$year."-AL";



        echo $codigo;



    break;



    



    case 'guardaryeditar':



        $validacionTelefono = validarYFormatearTelefonoInternacional($cli_telefono);



        if (!$validacionTelefono['ok']) {

            echo $validacionTelefono['mensaje'];

            break;

        }



        // Reemplazamos el valor original por el valor ya validado y normalizado

        $cli_telefono = $validacionTelefono['telefono'];



        if (empty($idcotizacion)) {



            $rspta = $cotizacion->insertar(

                $idcotizacion,

                $codreferencia,

                $cli_nrc,

                $cli_empresa,

                $cli_contacto,

                $cli_telefono,

                $cli_email,

                $tipoevento,

                $tipoalquiler,

                $exentacot,

                $descripcion_cot,

                $usuario,

                $ip,

                $idorgeje

            );



            echo $rspta;



        } else {



            $rspta = $cotizacion->editar(

                $idcotizacion,

                $codreferencia,

                $cli_nrc,

                $cli_empresa,

                $cli_contacto,

                $cli_telefono,

                $cli_email,

                $tipoevento,

                $tipoalquiler,

                $exentacot,

                $descripcion_cot

            );



            if ($rspta) {

                echo "Cotizacion Actualizada";

            } else {

                echo "La cotizacion no se pudo actualizar";

            }

        }



    break;







    case 'eliminar':



        $rspta=$cotizacion->eliminar($idcotizacion);



        echo $rspta?"¡cotizacion Eliminada!":"cotizacion no se pudo eliminar";



    break;



        



    case 'mostrar':



        $rspta=$cotizacion->mostrar($idcotizacion);



        //Codificar el resultado utilizando json



        echo json_encode($rspta);		



    break;







    case 'listar':



        $rspta=$cotizacion->listar();



        //Vamos a declarar un array



        $data= Array();



        



        while ($reg = $rspta->fetch_object()) {            



            $data[]= array(



                "0"=>$reg->IDCOTIZACION,



                "1"=>$reg->CODREFERENCIA,



                "2"=>$reg->EMPRESA,



                "3"=>$reg->NOMBRECONTACTO,



                "4"=>'<a href="callto:'.$reg->TELCONTACTO.'">'.$reg->TELCONTACTO.'</a>',



                "5"=>'<a href="mailto:'.$reg->CORREOCONTACTO.'">'.$reg->CORREOCONTACTO.'</a>',



                "6"=>$reg->NOMBRETIPOEVENTO,



                "7"=>$reg->NOMBRETIPOALQUILER,



                "8"=>$reg->USURECOT,



                "9"=>date("d-m-Y", strtotime($reg->FECHAREGCOT)),



                "10"=>($reg->NOMBREESTADOCOT),



                "11"=>'<div class="btn-group btn-group-sm" style="display:flex;">



                <button '.$btn_stl_agdet.' class="btn btn-info" onclick="agregardetalle('.$reg->IDCOTIZACION.')" data-toggle="tooltip" data-placement="top" title="Agregar Detalle"><i class="fa fa-plus"></i></button>'.    



                '<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDCOTIZACION.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.



                '<button '.$btn_stl_camb_estado.' class="btn btn-primary" onclick="cambiarestado('.$reg->IDCOTIZACION.', '.$reg->ESTADOCOT.')" data-toggle="tooltip" title="Cambiar Estado"><i class="fa fa-exchange"></i></button>'.



                '<button '.$btn_stl_pipeline.' class="btn btn-link" onclick="verTimeline('.$reg->IDCOTIZACION.')" data-toggle="tooltip" title="Pipeline de estados"><i class="fa fa-commenting"></i></button>'.



                '<button '.$btn_stl_ver.' class="btn btn-success" onclick="vercotizacion('.$reg->IDCOTIZACION.')" data-toggle="tooltip" data-placement="top" title="Ver cotización"><i class="fa fa-eye"></i></button></div>'

                

            );



        }



        $results = array("sEcho" => 1, //Información para el datatables



                         "iTotalRecords" => count($data),//enviamos el total registros al datatable



                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar



                         "aaData" =>$data);



        echo json_encode($results);                 



    break;







    case 'listar_estados':



        $rspta = $cotizacion->listarEstados();



        $data = array();







        while ($reg = $rspta->fetch_object()) {



            $data[] = array(



                "id" => $reg->IDESTADOCOT,



                "nombre" => $reg->NOMBREESTADOCOT



            );



        }



        echo json_encode($data);



    break;







    case 'cambiarestado':



        $idcotizacion = isset($_POST['idcotizacion']) ? intval($_POST['idcotizacion']) : 0;

        $estadoNuevo  = isset($_POST['estado']) ? intval($_POST['estado']) : 0;



        $usuario = isset($_SESSION["login"]) ? $_SESSION["login"] : null;

        $rol = isset($_SESSION["idrol"]) ? intval($_SESSION["idrol"]) : 0;



        //Agregamos campos de Observación

        $observaciones = $_POST['observaciones'] ?? null; 

        $idobservacion = isset($_POST['idobservacion']) ? intval($_POST['idobservacion']) : 0;

        $obsTexto = isset($_POST['observacion_texto']) ? trim($_POST['observacion_texto']) : null;



        if ($idcotizacion <= 0 || $estadoNuevo <= 0) {

            echo "Parámetros inválidos";

            exit;

        }



        // 1) Obtener estado actual desde BD (no confiar en el front)

        $row = $cotizacion->obtenerEstadoActual($idcotizacion);

        if (!$row) {

            echo "Cotización no encontrada";

            exit;

        }

        $estadoActual = intval($row["ESTADOCOT"]);



        // 2) Validar transición permitida (backend)

        if (!$cotizacion->transicionPermitida($estadoActual, $estadoNuevo, $rol)) {

            echo "Transición de estado no permitida";

            exit;

        }



        // Observaciones (puede venir null)

        $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;



        // Si el estado nuevo es Denegada/Suspendida, la observación es obligatoria

        if (in_array(intval($estadoNuevo), [5,6,7], true)) {



            if ($idobservacion <= 0) {

                echo "Debe seleccionar un motivo de observación.";

                exit;

            }



            $esOtros = $cotizacion->esObservacionOtros($idobservacion);

            if ($esOtros && ($obsTexto === null || $obsTexto === '')) {

                echo "Debe escribir el detalle cuando selecciona 'Otros'.";

                exit;

            }



        } else {

            $idobservacion = null;

            $obsTexto = null;

        }



        // 3) Actualizar estado actual

        $rspta = $cotizacion->cambiarEstado($idcotizacion, $estadoNuevo, null, $idobservacion, $obsTexto);

        if (!$rspta) {

            echo "No se pudo cambiar el estado";

            exit;

        }



        // 4) Insertar historial con fecha real y usuario real

        $okHist = $cotizacion->insertarHistorialEstado($idcotizacion, $estadoNuevo, $usuario);

        if (!$okHist) {

            // No detengo el flujo: ya cambió el estado. Solo log.

            error_log("No se pudo insertar historial: cot=$idcotizacion estado=$estadoNuevo user=$usuario");

        }



        // 5) Lógica de correos (la dejas igual)

        if ($estadoNuevo == 2) {

            require_once "../modelos/Usuario.php";

            require_once "../config/EmailHelper.php";



            $data = $cotizacion->obtenerCodReferencia($idcotizacion);

            $codreferencia = $data['CODREFERENCIA'] ?? '';



            $usuarioM = new Usuario();

            $rolesCorreo = [1, 3];



            $usuarios = $usuarioM->listarPorRoles($rolesCorreo);



            while ($u = $usuarios->fetch_assoc()) {

                $ok = enviarCorreoSupervisor($u['EMAIL'], $idcotizacion, $codreferencia);

                if (!$ok) {

                    error_log("No se pudo enviar correo a: " . $u['EMAIL']);

                }

            }

        }

        if ($estadoNuevo == 3) {

            require_once "../modelos/Usuario.php";
            require_once "../config/EmailHelper.php";

            $vendedor = $cotizacion->obtenerCorreoVendedor($idcotizacion);

            if (!empty($vendedor['EMAIL'])) {

                enviarCorreoVendedor(
                    $vendedor['EMAIL'],
                    $idcotizacion,
                    $vendedor['CODREFERENCIA']
                );

            }

            $usuarioM = new Usuario();

            $rolesCorreo = [1]; // Administradores

            $usuariosAdmin = $usuarioM->listarPorRoles($rolesCorreo);

            while ($admin = $usuariosAdmin->fetch_assoc()) {

                if (!empty($admin['EMAIL'])) {

                    enviarCorreoVendedor(
                        $admin['EMAIL'],
                        $idcotizacion,
                        $vendedor['CODREFERENCIA']
                    );

                }

            }
        }



        echo "Estado actualizado correctamente";

    break;



    case 'listar_observaciones':



        // Si quieres validar permiso para ver/usar observaciones, aquí puedes hacerlo.

        // Normalmente no hace falta, porque solo se usa en el modal de denegación/suspensión.

        // if(!isset($_SESSION["XXX"]) || $_SESSION["XXX"] != 1){ ... }



        $rspta = $cotizacion->listarObservaciones();



        $data = array();

        while ($reg = $rspta->fetch_object()) {

            $data[] = array(

                "id"    => intval($reg->IDOBSERVACION),

                "nombre"=> $reg->NOMBREOBSERVACION,

                "otros" => intval($reg->ES_OTROS)

            );

        }



        echo json_encode($data);

    break;



    case 'timeline':



        $idcotizacion = intval($_GET['idcotizacion'] ?? 0);



        // validar permiso del botón

        if(!isset($_SESSION["137VER44"]) || $_SESSION["137VER44"] != 1){

            echo json_encode(["success" => false, "message" => "No tiene permisos para ver el pipeline."]);

            exit;

        }



        if ($idcotizacion <= 0) {

            echo json_encode(["success" => false, "message" => "Parámetros inválidos"]);

            exit;

        }



        $rspta = $cotizacion->obtenerTimeline($idcotizacion);



        $data = [];

        while ($row = $rspta->fetch_assoc()){

            $data[] = $row;

        }



        echo json_encode(["success" => true, "data" => $data]);

    break;



	



	case 'selectTipoEvento':



        $rspta=$cotizacion->selectTipoEvento();



		echo '<option value="">--Seleccione un tipo--</option>';



        while ($reg = $rspta->fetch_object()) {



            echo '<option value='.$reg->IDTIPOEVENTO.'>'.$reg->NOMBRETIPOEVENTO.'</option>';



        }



	break;







    case 'selTipoAlquiler':



        $rspta=$cotizacion->selTipoAlquiler();



		echo '<option value="">--Seleccione una tipo--</option>';



        while ($reg = $rspta->fetch_object()) {



            echo '<option value='.$reg->IDTIPOALQUILER.'>'.$reg->NOMBRETIPOALQUILER.'</option>';



        }



	break;







    case 'vercotizacion':



        $idcotizacion = $_POST['idcotizacion'];







        // Obtener el tipo de alquiler de la cotización



        $rspta = $cotizacion->obtenerTipoAlquiler($idcotizacion);







        if ($rspta) {



            $tipoalquiler = $rspta['IDTIPOALQUILER'];







            // Definimos la URL según el tipo



            if ($tipoalquiler == 1) {



                $url = "../vistas/reportes/cotizacion_auditorio_final_preview.php?idcotizacion=".$idcotizacion;



            } elseif ($tipoalquiler == 2) {



                $url = "../vistas/reportes/cotizacion_sala_final_preview.php?idcotizacion=".$idcotizacion;



            } else {



                $url = ""; // No aplica



            }







            echo json_encode(["success" => true, "url" => $url]);



        } else {



            echo json_encode(["success" => false, "message" => "No se encontró la cotización."]);



        }



    break;



}



?>