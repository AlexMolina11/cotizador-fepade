<?php
require_once "../modelos/Asignargrupo.php";

$asignargrupo= new Asignargrupo();
//Creamos objeto para Insertar y Editar grupoAsignado
$idgrupo_as                 =isset($_POST["grupoasignar"])?limpiarCadena($_POST["grupoasignar"]):"";
$idparticipante_as          =isset($_POST["participanteasignar"])?limpiarCadena($_POST["participanteasignar"]):"";
switch ($_GET["op"]) {
    case 'asignaragrupo':   
        $rspta=$asignargrupo->insertar($idgrupo_as,$idparticipante_as);
        echo $rspta? 
            "Participante ha sido creado y asignado Correctamente":
            "Participante ha sido creado Correctamente";         
    break;
    
}
?>