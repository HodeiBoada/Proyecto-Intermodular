<?php
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

$sala = $_POST['sala'] ?? '';
$partes = explode('-', $sala);

// Validar formato de sala
if (count($partes) === 3 && $partes[0] === 'sala') {
    $id_entrenador = intval($partes[1]);
    $id_usuario_destino = intval($partes[2]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Formato de sala inválido']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $archivo = $_FILES['file'];
    $nombre = basename($archivo['name']);
    $tipo = mime_content_type($archivo['tmp_name']);

    if ($tipo !== 'application/pdf') {
        echo json_encode(['status' => 'error', 'message' => 'Solo se permiten archivos PDF']);
        exit;
    }

    $nombreSeguro = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $nombre);
    $destino = 'uploads/' . $nombreSeguro;

    if (move_uploaded_file($archivo['tmp_name'], $destino)) {
        // Guardar archivo
        $sql = "INSERT INTO archivos_sala (id_usuario, id_entrenador, nombre_archivo, ruta_archivo) 
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $id_usuario_destino, $id_entrenador, $nombre, $destino);
        mysqli_stmt_execute($stmt);

        // Crear notificación
        $sql2 = "INSERT INTO notificaciones_sala (id_usuario, id_entrenador, tipo, contenido) 
                 VALUES (?, ?, 'archivo', ?)";
        $stmt2 = mysqli_prepare($conexion, $sql2);
        mysqli_stmt_bind_param($stmt2, "iis", $id_usuario_destino, $id_entrenador, $nombre);
        mysqli_stmt_execute($stmt2);

        echo json_encode(['status' => 'ok', 'archivo' => $nombre]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el archivo']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió archivo']);
}
