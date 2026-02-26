<?php
include 'seguridad.php';
verificarRol('entrenador');
include 'conexion.php';

$id_entrenador = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $id_dieta = $_POST['id_dieta'];

    $sql = "UPDATE usuarios SET id_dieta_activa = ? WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_dieta, $id_usuario);
    mysqli_stmt_execute($stmt);

    $sql2 = "INSERT INTO dieta_usuario (id_usuario, id_dieta) VALUES (?, ?)";
    $stmt2 = mysqli_prepare($conexion, $sql2);
    mysqli_stmt_bind_param($stmt2, "ii", $id_usuario, $id_dieta);
    mysqli_stmt_execute($stmt2);

    echo "Dieta asignada correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Dieta</title>
</head>
<body>
<h2>Asignar Dieta a Usuario</h2>
<form method="post">
    <label>Usuario:
        <select name="id_usuario" required>
            <?php
            $res = mysqli_query($conexion, "SELECT id_usuario, nombre FROM usuarios WHERE rol = 'usuario'");
            while ($u = mysqli_fetch_assoc($res)) {
                echo "<option value='{$u['id_usuario']}'>{$u['nombre']}</option>";
            }
            ?>
        </select>
    </label><br><br>

    <label>Dieta:
        <select name="id_dieta" required>
            <?php
            $res = mysqli_query($conexion, "SELECT id_dieta, nombre FROM dietas WHERE creada_por = $id_entrenador");
            while ($d = mysqli_fetch_assoc($res)) {
                echo "<option value='{$d['id_dieta']}'>{$d['nombre']}</option>";
            }
            ?>
        </select>
    </label><br><br>

    <button type="submit">Asignar Dieta</button>
</form>
<a href="menu_entrenador.php">← Volver al menú</a>
</body>
</html>
