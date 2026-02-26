<?php
include 'seguridad.php';
verificarRol('entrenador');
?>

<h2>Bienvenido Entrenador</h2>
<ul>
    <li><a href="biblioteca_ejercicios.php">Ejercicios</a></li>
    <li><a href="crear_rutina.php">Crear Rutina</a></li>
    <li><a href="asignar_rutina.php">Asignar Rutina</a></li>
    <li><a href="biblioteca_comidas.php">Comidas</a></li>
    <li><a href="crear_dieta.php">Crear Dieta</a></li>
    <li><a href="asignar_dieta.php">Asignar Dieta</a></li>
    <li><a href="clientes.php">Clientes</a></li>
    <li><a href="logout.php">Cerrar Sesión</a></li>
</ul>
