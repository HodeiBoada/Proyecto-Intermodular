<?php
file_put_contents('debug.txt', "Método: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
file_put_contents('debug.txt', print_r($_FILES, true), FILE_APPEND);

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $archivo = $_FILES['file'];
    $nombre = basename($archivo['name']);
    $destino = 'uploads/' . $nombre;

    if (move_uploaded_file($archivo['tmp_name'], $destino)) {
        // Guardar notificación
        $jsonFile = 'notificaciones.json';
        $notificaciones = [];

        if (file_exists($jsonFile)) {
            $json = file_get_contents($jsonFile);
            $notificaciones = json_decode($json, true);
        }

        $notificaciones[] = [
            'tipo' => 'archivo',
            'contenido' => $nombre,
            'timestamp' => time()
        ];

        file_put_contents($jsonFile, json_encode($notificaciones, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'ok', 'archivo' => $nombre]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo subir el archivo']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió archivo']);
}
?>
