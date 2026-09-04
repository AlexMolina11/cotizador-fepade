<?php
require_once "global.php";

$conexion=new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
mysqli_query($conexion,'SET NAMES "'.DB_ENCODE.'"');
//Si tenemos un error en la conexiÃ³n lo mostramos
if(mysqli_connect_error()){
    printf("Fallo la conexiÃ³n a la base de datos:%s \n",mysqli_connect_error());
    exit();
}

if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql){
        global $conexion;
        $query=$conexion->query($sql);
        return $query;
    }
}

function ejecutarConsultaMatriz($sql) {
    global $conexion;
    $query = $conexion->query($sql);

    if (!$query) {
        die("Error en la consulta: " . $conexion->error);
    }

    return $query;
}

function ejecutarConsultaSimpleFila($sql){
    global $conexion;
    $query=$conexion->query($sql);
    $row=$query->fetch_assoc();
    return $row;
}

function ejecutarConsulta_retornaID($sql){
    global $conexion;
    $query=$conexion->query($sql);
    return $conexion->insert_id;
}

function limpiarCadena($str){
    global $conexion;
    $str=mysqli_real_escape_string($conexion,trim($str));
    return htmlspecialchars($str);
}

function ejecutarProcAlmacenado($sql){
    global $conexion;
    $query = mysqli_query($conexion, $sql);
    return $query;
}

function obtenerUltimoIDInsertado() {
    // Establece tu conexi¨®n a la base de datos, asegur¨¢ndote de que est¨¦ activa y configurada correctamente
    global $conexion;
    // Realiza la consulta para obtener el ¨²ltimo ID insertado
    $ultimoID = mysqli_insert_id($conexion); // Reemplaza $tuConexion con tu instancia de conexi¨®n a la base de datos

    return $ultimoID;
}
?>