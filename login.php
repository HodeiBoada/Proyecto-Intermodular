<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header("Location: menu_" . $_SESSION['rol'] . ".php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Acceso a FitnessGym</h2>
    <form action="validarlogin.php" method="post">
        <input type="email" name="correo" placeholder="Correo" required><br>
        <input type="password" name="clave" placeholder="Contraseña" required><br>
        <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
</body>
</html>
