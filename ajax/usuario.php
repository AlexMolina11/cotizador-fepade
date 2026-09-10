<?php
if(strlen(session_id())<1)
session_start();
require_once "../modelos/Usuario.php";

$usuario= new Usuario();

$idusuariosusu=isset($_POST["idusuariosusu"])?limpiarCadena($_POST["idusuariosusu"]):"";
$idrol=isset($_POST["idrol"])?limpiarCadena($_POST["idrol"]):"";
$nombreusu=isset($_POST["nombreusu"])?limpiarCadena($_POST["nombreusu"]):"";
$login=isset($_POST["login"])?limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])?limpiarCadena($_POST["clave"]):"";
$email=isset($_POST["email"])?limpiarCadena($_POST["email"]):"";
$telusu=isset($_POST["telusu"])?limpiarCadena($_POST["telusu"]):"";
$extensionusu=isset($_POST["extensionusu"])?limpiarCadena($_POST["extensionusu"]):"";
$orgejecutora=isset($_POST["orgejecutora"])?limpiarCadena($_POST["orgejecutora"]):"";
/*para claves de todos usuarios*/
$idusuario=isset($_POST["idusuario"])?limpiarCadena($_POST["idusuario"]):"";
$passw=isset($_POST["passw"])?limpiarCadena($_POST["passw"]):"";
/**para usuario propietario */
$ipusu = $_SERVER["REMOTE_ADDR"];
$regusu = $_SESSION["login"] ?? "";
$modusu = $_SESSION["login"] ?? "";

//validando Acción Editar
if(isset($_SESSION["093EDI33"])&&$_SESSION["093EDI33"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Cambiar Contraseña
if(isset($_SESSION["094CAM33"])&&$_SESSION["094CAM33"]==1){
    $btn_stl_cambcontra = "style='display: block;'"; 
} else {
    $btn_stl_cambcontra = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["095ACT33"])&&$_SESSION["095ACT33"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["096DES33"])&&$_SESSION["096DES33"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':
       
        //Hash SHA256 en la contraseña
        $clavehash= hash("SHA256", $clave);
       
        if (empty($idusuariosusu)) {
            //valida que el login sea único
            $rs=$usuario->unico($login);
            $reg = $rs->fetch_object();
            if($reg->cantidad ==0){
                //$rspta=$usuario->insertar($login,$idrol,$nombreusu,$clavehash,$email,$regusu,$ipusu);
                $rspta=$usuario->insertar($login,$idrol,$nombreusu,$clavehash,$email,$telusu,$extensionusu,$regusu,$ipusu,$orgejecutora);
                echo $rspta? "Usuario Registrado":"No se pudieron registrar todos los datos del usuario";
            }else{echo 'El usuario ya existe';}    
        }else {
            //$rspta=$usuario->editar($idusuariosusu,$login,$idrol,$nombreusu,$email,$regusu);
            $rspta=$usuario->editar($idusuariosusu,$login,$idrol,$nombreusu,$email,$telusu,$extensionusu,$orgejecutora,$modusu);
           echo $rspta? "Usuario Actualizado":"Usuario no se pudo actualizar";
        }
    
    break;

    case 'desactivar':
        $rspta=$usuario->desactivar($idusuariosusu);
        echo $rspta?"Usuario Desactivado":"Usuario no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$usuario->activar($idusuariosusu);
        echo $rspta?"Usuario Activado":"Usuario no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$usuario->mostrar($idusuariosusu);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$usuario->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            if($reg->NOMBREUSU!=$_SESSION['nombre']){
                    $op='<button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDUSUARIOSUSU.')" data-toggle="tooltip" data-placement="top" title="Desactivar Usuario"><i class="fa fa-close"></i></button>';
                }else{$op="";}
            $data[]= array(
                
                "0"=>$reg->NOMBREUSU,
                "1"=>$reg->EMAIL,
                "2"=>$reg->USERUSU,
                "3"=>($reg->ESTADOUSU)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desacativado</span>',
                "4"=>($reg->ESTADOUSU)?'<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDUSUARIOSUSU.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                    '<button '.$btn_stl_cambcontra.' class="btn btn btn-info" onclick="clave('.$reg->IDUSUARIOSUSU.')" data-toggle="tooltip" data-placement="top" title="Editar Clave"><i class="fa fa-pencil-square-o"></i></button>'.
                    $op.'</div>':
                    '<div class="btn-group btn-group-sm" style="display:flex;">
                    <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDUSUARIOSUSU.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                    '<button '.$btn_stl_cambcontra.' class="btn btn btn-info" onclick="clave('.$reg->IDUSUARIOSUSU.')" data-toggle="tooltip" data-placement="top" title="Editar Clave"><i class="fa fa-pencil-square-o"></i></button>'.
                    ' <button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDUSUARIOSUSU.')" data-toggle="tooltip" data-placement="top" title="Activar Usuario"><i class="fa fa-check"></i></button></div>'
            ); 
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'selectRoles':
        $opciones='<option value="0">Seleccione rol</option>';
        $rspta=$usuario->roles();
        while ($reg = $rspta->fetch_object()) {
            $opciones.= '<option value='.$reg->IDROL.'>'.$reg->NOMBREROL.'</option>';
        }
        echo $opciones;
    break;
    
    case 'verificar':
        $logina=$_POST['logina'];
        $clavea=$_POST['clavea'];
        
        //Hash SHA256 en la contraseña
        $clavehash= hash("SHA256", $clavea);
        
        $rspta=$usuario->verificar($logina, $clavehash);
        
        $fetch=$rspta->fetch_object();
        
        if(isset($fetch)){
            //Declaramos las variables de sesión
            $_SESSION['idusuariosusu']=$fetch->IDUSUARIOSUSU;
            $_SESSION['nombre']=$fetch->NOMBREUSU;
            $_SESSION['login']=$fetch->USERUSU;
            $_SESSION['rol']=$fetch->NOMBREROL;
            $_SESSION['idrol']=$fetch->IDROL;
            $_SESSION['permiso']=$fetch->PERMISOROL;
            $_SESSION['idorgeje']=$fetch->IDORGEJE;
            $_SESSION['orgeje']=$fetch->NOMBREORGEJE;
            $_SESSION['areares']=$fetch->PERMISOAREARES;
            
            //--------------------Creamos Variables de SESION PARA PERMISOS ---------------------------------
            //Obtener los permisos marcados por el Rol
            $marcados=$usuario->listarmarcados($fetch->IDROL);			
            //Declaramos el array para almacenar los permisos marcados
            $valores=array();
            //Almacenamos los permisos marcados en el array
            while ($per=$marcados->fetch_object()){
                array_push($valores, $per->IDMODULO); 			
            }
            
            //Creamos variables de sesión por todos los módulos existentes. 
            //FUNCION PARA LLAMAR AL TOTAL DE MODULOS
            $totmod=$usuario->totalmodulos();
            $tm=$totmod->fetch_object();
            
            //Creamos ciclo for para que recorra cada modulo en base a su id
            for($i = 1; $i <= $tm->TOTALMOD; $i++){
                //Guardamos los códigos de cada modulo
                $codeMod=$usuario->codigoModulo($i);
                $codmod=$codeMod->fetch_object();
                //Creamos las variables de sesión por cada modulo.
                //Si existe, se le agrega 1, sino 0.
                in_array($i, $valores)?$_SESSION[$codmod->MODULOCODE]=1:$_SESSION[$codmod->MODULOCODE]=0; 
            }

            
            //--------------------Creamos Variables de SESION PARA ACCIONEs ---------------------------------
            //Obtener los permisos marcados por el Rol
            $marcados_acc=$usuario->listaraccionesmarcados($fetch->IDROL);			
            //Declaramos el array para almacenar los permisos marcados
            $valores_acc=array();
            //Almacenamos los permisos marcados en el array
            while ($per_acc=$marcados_acc->fetch_object()){
                array_push($valores_acc, $per_acc->IDACCION); 			
            }
            
            //Creamos variables de sesión por todos las acciones existentes. 
            //FUNCION PARA LLAMAR AL TOTAL DE ACCIONES
            $totacc=$usuario->totalacciones();
            $tacc=$totacc->fetch_object();
            
            //Creamos ciclo for para que recorra cada acción en base a su id
            for($j = 1; $j <= $tacc->TOTALACC; $j++){
                //Guardamos los códigos de cada acción
                $codeAcc=$usuario->codigoAccion($j);
                $codacc=$codeAcc->fetch_object();
                //Creamos las variables de sesión por cada Acción.
                //Si existe, se le agrega 1, sino 0.
                in_array($j, $valores_acc)?$_SESSION[$codacc->ACCIONCODE]=1:$_SESSION[$codacc->ACCIONCODE]=0; 
            }

            // REDIRECCIÓN POST LOGIN
            /*if (!empty($_SESSION['redirect_after_login'])) {
                $fetch->redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
            } else {
                $fetch->redirect = "vistas/escritorio.php";
            }*/
            
        }
        echo json_encode($fetch);
    break;  

    case 'cambiarclave':
    
            $usuariosusu        = $_REQUEST["usuariosusu"];    
            $newclave           = $_REQUEST["newclave"];
            //Hash SHA256 en la contraseña
            $newclavehash= hash("SHA256", $newclave);
           
               $rspta=$usuario->editarClave($usuariosusu,$newclavehash);
               echo $rspta? "Clave Actualizada con Exito":"La Clave no se pudo actualizar";
       
    break;

    case 'editarclave':

        //Hash SHA256 en la contraseña
        $passhash= hash("SHA256", $passw);
        
            $rspta=$usuario->editaClave($idusuario,$passhash);
            echo $rspta? "Clave del usuario fue Actualizada con Exito":"La Clave del usuario no se pudo actualizar";
    
    break;

    case 'salir':
            //Limpiamos todas las variables de session
            session_unset();
            //Destrimos la sessión
            session_destroy();
            //Redireccionamos al login
            header("Location:../index.php");
    break;

    case 'selectOrganizacionEjecutora':
        $rspta=$usuario->selectOrganizacionEjecutora();
		echo '<option value="">--Seleccione una Organización--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDORGEJE.'>'.$reg->NOMBREORGEJE.'</option>';
        }
	break;
}
?>
