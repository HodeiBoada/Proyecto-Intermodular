<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';

    $archivo = 'notificaciones.json';
    $notificaciones = [];

    if (file_exists($archivo)) {
        $json = file_get_contents($archivo);
        $notificaciones = json_decode($json, true);
    }

    $notificaciones[] = [
        'tipo' => $tipo,
        'contenido' => $contenido,
        'timestamp' => time()
    ];

    file_put_contents($archivo, json_encode($notificaciones, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>
