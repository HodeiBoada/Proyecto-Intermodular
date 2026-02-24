<?php
$archivo = 'notificaciones.json';

if (file_exists($archivo)) {
    $json = file_get_contents($archivo);
    echo $json;
} else {
    echo json_encode([]);
}
?>
