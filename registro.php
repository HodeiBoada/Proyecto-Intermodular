<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    $rol = 'usuario'; // Por defecto
    $suscrito = isset($_POST['suscrito']) ? 1 : 0;
    $fecha_fin_suscripcion = !empty($_POST['fecha_fin_suscripcion']) ? $_POST['fecha_fin_suscripcion'] : null;
    $id_entrenador = !empty($_POST['id_entrenador']) ? $_POST['id_entrenador'] : null;

    $sql = "INSERT INTO usuarios 
        (nombre, apellido1, apellido2, telefono, correo, password_hash, rol, suscrito, fecha_fin_suscripcion, id_entrenador) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssi", 
        $nombre, $apellido1, $apellido2, $telefono, $correo, $clave, $rol, $suscrito, $fecha_fin_suscripcion, $id_entrenador);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error al registrar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <input type="text" name="apellido1" placeholder="Primer Apellido" required><br>
        <input type="text" name="apellido2" placeholder="Segundo Apellido"><br>
        <input type="text" name="telefono" placeholder="Teléfono"><br>
        <input type="email" name="correo" placeholder="Correo electrónico" required><br>
        <input type="password" name="clave" placeholder="Contraseña" required><br>
        <label><input type="checkbox" name="suscrito"> ¿Suscribirse?</label><br>
        <label>Fecha fin de suscripción: <input type="date" name="fecha_fin_suscripcion"></label><br>
        <label>ID de Entrenador Asignado (opcional): <input type="number" name="id_entrenador"></label><br>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
