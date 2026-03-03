<?php
$conexion = mysqli_connect("localhost", "root", "", "fitnessgymbd");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
