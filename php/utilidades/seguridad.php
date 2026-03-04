<?php

// 1. Forzar que la cookie de sesión expire al cerrar el navegador
ini_set('session.cookie_lifetime', 0);

// 2. Iniciar la sesión DESPUÉS de configurar la cookie
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verificarRol($rolEsperado) {
    if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== $rolEsperado) {
        header("Location: login.php");
        exit;
    }
}

function verificarRoles($rolesPermitidos) {
    if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
        header("Location: login.php");
        exit;
    }
}
?>