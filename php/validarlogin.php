<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Añadimos el campo 'activo' a la consulta para verificar el estado
$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

if ($usuario && password_verify($clave, $usuario['password_hash'])) {
    
    // VERIFICACIÓN DE BORRADO LÓGICO
    if ($usuario['activo'] == 0) {
        // Si el usuario está desactivado, lo mandamos de vuelta con un mensaje específico
        header("Location: login.php?error=cuenta_desactivada");
        exit();
    }

    // Si el usuario existe, la clave es correcta y está ACTIVO, creamos la sesión
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['id_entrenador'] = $usuario['id_entrenador'];
    
    header("Location: menu_" . $usuario['rol'] . ".php");
    exit();

} else {
    // Si no existe el correo o la contraseña no coincide
    header("Location: login.php?error=invalid_credentials");
    exit();
}