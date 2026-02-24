<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Leer el cuerpo JSON enviado por sendBeacon
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar que los datos estén presentes
if (!$data || !isset($data['fecha'], $data['duracion'])) {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
  exit;
}

$archivo = 'historial_llamadas.json';
$llamadas = [];

if (file_exists($archivo)) {
  $json = file_get_contents($archivo);
  $llamadas = json_decode($json, true) ?: [];
}

// Agregar nueva llamada
$llamadas[] = [
  'fecha' => $data['fecha'],
  'duracion' => $data['duracion'],
  'entrenador' => 'Entrenador Demo'
];

// Guardar de nuevo el archivo
file_put_contents($archivo, json_encode($llamadas, JSON_PRETTY_PRINT));

// Respuesta (aunque sendBeacon no la usará)
echo json_encode(['status' => 'ok']);
