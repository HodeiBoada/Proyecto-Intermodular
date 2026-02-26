<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'];
$clave = $_POST['clave'];

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

if ($usuario && password_verify($clave, $usuario['password_hash'])) {
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['id_entrenador'] = $usuario['id_entrenador'];
    header("Location: menu_" . $usuario['rol'] . ".php");
} else {
    echo "Correo o contraseña incorrectos.";
}
