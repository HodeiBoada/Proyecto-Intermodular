<?php
include 'seguridad.php';
verificarRol('usuario');
?>

<h2>Bienvenido Usuario</h2>
<ul>
    <li><a href="ver_rutina_usuario.php">Mi Rutina</a></li>
    <li><a href="ver_dieta_usuario.php">Mi Dieta</a></li>
    <li><a href="index.php">Videollamada</a></li>
    <li><a href="perfil.php">Mi Perfil</a></li>
    <li><a href="logout.php">Cerrar Sesión</a></li>
</ul>
