<?php
session_start();
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

header('Content-Type: application/json');

$id_sesion = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['fecha'], $data['fin'], $data['duracion'], $data['sala'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$fecha_inicio = date('Y-m-d H:i:s', strtotime($data['fecha']));
$fecha_fin = date('Y-m-d H:i:s', strtotime($data['fin']));
$duracion = (int)$data['duracion'];
$sala = $data['sala'];

// Extraer IDs desde el nombre de la sala
$partes = explode('-', $sala);
if (count($partes) === 3 && $partes[0] === 'sala') {
    $id_entrenador = intval($partes[1]);
    $id_usuario = intval($partes[2]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Formato de sala inválido']);
    exit;
}

// Validación adicional: asegurar que el usuario actual pertenece a esta sala
if (
    ($rol === 'usuario' && $id_sesion !== $id_usuario) ||
    ($rol === 'entrenador' && $id_sesion !== $id_entrenador)
) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para registrar esta llamada']);
    exit;
}

// Guardar llamada
$sql = "INSERT INTO historial_llamadas (id_usuario, id_entrenador, sala, fecha_inicio, fecha_fin, duracion) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "iisssi", $id_usuario, $id_entrenador, $sala, $fecha_inicio, $fecha_fin, $duracion);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar llamada']);
}
