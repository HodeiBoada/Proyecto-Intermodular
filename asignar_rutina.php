<?php
include 'seguridad.php';
verificarRol('entrenador');
include 'conexion.php';

$id_entrenador = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $id_rutina = $_POST['id_rutina'];

    $sql = "UPDATE usuarios SET id_rutina_activa = ? WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_rutina, $id_usuario);
    mysqli_stmt_execute($stmt);

    $sql2 = "INSERT INTO rutina_usuario (id_usuario, id_rutina) VALUES (?, ?)";
    $stmt2 = mysqli_prepare($conexion, $sql2);
    mysqli_stmt_bind_param($stmt2, "ii", $id_usuario, $id_rutina);
    mysqli_stmt_execute($stmt2);

    echo "Rutina asignada correctamente.";
}
?>

<h2>Asignar Rutina a Usuario</h2>
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

    <label>Rutina:
        <select name="id_rutina" required>
            <?php
            $res = mysqli_query($conexion, "SELECT id_rutina, nombre FROM rutinas WHERE creada_por = $id_entrenador");
            while ($r = mysqli_fetch_assoc($res)) {
                echo "<option value='{$r['id_rutina']}'>{$r['nombre']}</option>";
            }
            ?>
        </select>
    </label><br><br>

    <button type="submit">Asignar Rutina</button>
</form>
<a href="menu_entrenador.php">Volver al menú</a>
