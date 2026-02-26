<?php
session_start();
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

$tipo = $_POST['tipo'] ?? null;
$contenido = $_POST['contenido'] ?? '';
$sala = $_POST['sala'] ?? '';

if (!$tipo || !$sala) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    exit;
}

// Extraer IDs desde el nombre de la sala
$partes = explode('-', $sala);
if (count($partes) === 3 && $partes[0] === 'sala') {
    $id_entrenador = intval($partes[1]);
    $id_usuario_destino = intval($partes[2]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Formato de sala inválido']);
    exit;
}

// Insertar notificación
$sql = "INSERT INTO notificaciones_sala (id_usuario, id_entrenador, tipo, contenido) 
        VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "iiss", $id_usuario_destino, $id_entrenador, $tipo, $contenido);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar notificación']);
}
