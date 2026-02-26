<?php
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador', 'administrador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    if (mysqli_stmt_execute($stmt)) {
        session_destroy();
        echo "Cuenta eliminada correctamente. <a href='login.php'>Volver al inicio</a>";
        exit;
    } else {
        echo "Error al eliminar la cuenta.";
    }
}
?>

<h2>Eliminar Cuenta</h2>
<p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
<form method="post">
    <button type="submit">Sí, eliminar mi cuenta</button>
</form>
<a href="menu_usuario.php">Cancelar</a>
