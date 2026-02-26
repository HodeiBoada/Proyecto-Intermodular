<?php
session_start();
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

header('Content-Type: application/json');

$sala = $_GET['sala'] ?? null;

if (!$sala) {
    echo json_encode([]);
    exit;
}

$partes = explode('-', $sala);
if (count($partes) === 3 && $partes[0] === 'sala') {
    $id_entrenador = intval($partes[1]);
    $id_usuario = intval($partes[2]);
} else {
    echo json_encode([]);
    exit;
}

$sql = "SELECT fecha_inicio, duracion 
        FROM historial_llamadas 
        WHERE id_usuario = ? AND id_entrenador = ?
        ORDER BY fecha_inicio DESC";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error preparando la consulta']);
    exit;
}

mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_entrenador);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$llamadas = [];
while ($row = mysqli_fetch_assoc($res)) {
    $llamadas[] = [
        'fecha' => $row['fecha_inicio'],
        'duracion' => $row['duracion']
    ];
}

echo json_encode($llamadas);
