<?php
include './utilidades/seguridad.php';
verificarRoles(['usuario', 'entrenador', 'administrador']);
include './utilidades/conexion.php';

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
<?php include './utilidades/navbar.php'; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estilo_global.css">
    <link rel="icon" type="image/x-icon" href="../img/LogoProyecto.ico">
</head>
<body>
    <div class="container">
    <h2>Eliminar Cuenta</h2>
    <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
    <form method="post">
        <button class="btn btn-secondary" type="submit">Sí, eliminar mi cuenta</button>
    </form>
    <a href="menu_usuario.php" class="btn btn-secondary">Cancelar</a>
    </div>
</body>
</html>

