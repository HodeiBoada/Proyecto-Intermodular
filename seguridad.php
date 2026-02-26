<?php
function verificarRol($rolEsperado) {
    session_start();
    if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== $rolEsperado) {
        header("Location: login.php");
        exit;
    }
}

function verificarRoles($rolesPermitidos) {
    session_start();
    if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
        header("Location: login.php");
        exit;
    }
}
