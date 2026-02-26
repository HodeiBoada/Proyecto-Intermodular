<?php
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

$sala = $_GET['sala'] ?? null;

if (!$sala) {
    echo json_encode([]);
    exit;
}

$partes = explode('-', $sala);
if (count($partes) === 3 && $partes[0] === 'sala') {
    $id_entrenador = intval($partes[1]);
    $id_usuario_destino = intval($partes[2]);
} else {
    echo json_encode([]);
    exit;
}

$sql = "SELECT tipo, contenido, timestamp 
        FROM notificaciones_sala 
        WHERE id_usuario = ? AND id_entrenador = ?
        ORDER BY timestamp DESC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id_usuario_destino, $id_entrenador);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$notificaciones = [];
while ($row = mysqli_fetch_assoc($res)) {
    $notificaciones[] = [
        'tipo' => $row['tipo'],
        'contenido' => $row['contenido'],
        'timestamp' => strtotime($row['timestamp'])
    ];
}

echo json_encode($notificaciones);
