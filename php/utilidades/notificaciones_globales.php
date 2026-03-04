<?php
include 'seguridad.php'; 
include 'conexion.php';

// Ajuste de zona horaria
date_default_timezone_set('Europe/Madrid');
mysqli_query($conexion, "SET time_zone = '+01:00'");

header('Content-Type: application/json');

// Verificamos que sea entrenador
if (!isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'entrenador') {
    echo json_encode(['hay_ayuda' => false]);
    exit;
}

$id_entrenador_sesion = $_SESSION['id_usuario'];

// CAMBIO CLAVE: Ahora filtramos por estado = 'pendiente'
// Quitamos la restricción de los 5 minutos porque el 'estado' ya controla qué es nuevo y qué no
$sql = "SELECT n.id, u.nombre AS cliente, n.id_usuario 
        FROM notificaciones_sala n
        JOIN usuarios u ON n.id_usuario = u.id_usuario
        WHERE n.id_entrenador = ? 
          AND n.tipo = 'ayuda' 
          AND n.estado = 'pendiente' 
        ORDER BY n.timestamp DESC LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_entrenador_sesion);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($res)) {
    echo json_encode([
        'hay_ayuda' => true,
        'mensaje' => "El cliente " . $row['cliente'] . " solicita ayuda.",
        'url_sala' => "index.php?id_usuario=" . $row['id_usuario']
    ]);
} else {
    echo json_encode(['hay_ayuda' => false]);
}

mysqli_close($conexion);
?>