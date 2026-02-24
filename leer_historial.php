<?php
header('Content-Type: application/json');

$archivo = 'historial_llamadas.json';

if (!file_exists($archivo)) {
  echo json_encode([]);
  exit;
}

$llamadas = json_decode(file_get_contents($archivo), true);
echo json_encode($llamadas);
