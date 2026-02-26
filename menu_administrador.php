<?php
include 'seguridad.php';
verificarRol('administrador');
?>

<h2>Panel de Administración</h2>
<ul>
    <li><a href="perfil.php">Mi Perfil</a></li>
    <li><a href="logout.php">Cerrar Sesión</a></li>
</ul>
