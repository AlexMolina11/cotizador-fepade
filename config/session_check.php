<?php
session_start();

// Define el tiempo máximo de inactividad (en segundos)
//$tiempo_max_inactividad = 1200; // 20 minutos
$tiempo_max_inactividad = 60; // 1 minuto

// Verifica si la sesión existe
if (isset($_SESSION['ultima_actividad'])) {
    // Verifica si el tiempo de inactividad ha excedido el límite
    if (time() - $_SESSION['ultima_actividad'] > $tiempo_max_inactividad) {
        // Si ha excedido el tiempo de inactividad, destruye la sesión
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'timeout']);
    } else {
        // Si no ha excedido, actualiza la hora de la última actividad
        $_SESSION['ultima_actividad'] = time();
        echo json_encode(['status' => 'active']);
    }
} else {
    echo json_encode(['status' => 'no_session']);
}
?>