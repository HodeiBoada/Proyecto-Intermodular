<?php
// Desactivamos reporte de errores visuales para no ensuciar el JSON
error_reporting(0);
ob_start(); // Iniciamos un buffer de salida

include 'seguridad.php';
verificarRoles(['usuario', 'entrenador']);
include 'conexion.php';

// Limpiamos cualquier espacio o aviso previo
ob_clean();
header('Content-Type: application/json');

$id_usuario_sesion = $_SESSION['id_usuario'];
$sala = $_POST['sala'] ?? '';
$partes = explode('-', $sala);

// Validar formato de sala (sala-ENTRENADOR-USUARIO)
if (count($partes) === 3 && $partes[0] === 'sala') {
    $id_entrenador = intval($partes[1]);
    $id_usuario_destino = intval($partes[2]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Formato de sala inválido: ' . $sala]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $archivo = $_FILES['file'];
    $nombreOriginal = basename($archivo['name']);
    
    // Validación más robusta de PDF (algunos navegadores no envían bien el mime_type)
    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        echo json_encode(['status' => 'error', 'message' => 'Solo se permiten archivos PDF']);
        exit;
    }

    // Crear carpeta si no existe
    if (!file_exists('../../uploads')) {
        mkdir('../../uploads', 0777, true);
    }

    $nombreSeguro = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $nombreOriginal);
    $destino = '../../uploads/' . $nombreSeguro;

    if (move_uploaded_file($archivo['tmp_name'], $destino)) {
        
        // 1. Guardar en archivos_sala
        $sql = "INSERT INTO archivos_sala (id_usuario, id_entrenador, nombre_archivo, ruta_archivo) 
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $id_usuario_destino, $id_entrenador, $nombreSeguro, $destino);
        $res1 = mysqli_stmt_execute($stmt);

        // 2. Crear notificación (esto es lo que lee el polling del JS)
        $sql2 = "INSERT INTO notificaciones_sala (id_usuario, id_entrenador, tipo, contenido) 
                 VALUES (?, ?, 'archivo', ?)";
        $stmt2 = mysqli_prepare($conexion, $sql2);
        mysqli_stmt_bind_param($stmt2, "iis", $id_usuario_destino, $id_entrenador, $nombreSeguro);
        $res2 = mysqli_stmt_execute($stmt2);

        if ($res1 && $res2) {
            echo json_encode(['status' => 'ok', 'archivo' => $nombreSeguro]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en base de datos: ' . mysqli_error($conexion)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo mover el archivo a la carpeta uploads. Revisa permisos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió ningún archivo']);
}

?>