<?php
include 'seguridad.php';
verificarRoles(['usuario', 'entrenador', 'administrador']);
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT u.*, e.nombre AS nombre_entrenador
        FROM usuarios u
        LEFT JOIN usuarios e ON u.id_entrenador = e.id_usuario
        WHERE u.id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
?>

<h2>Mi Perfil</h2>
<p><strong>Nombre:</strong> <?= $usuario['nombre'] . ' ' . $usuario['apellido1'] . ' ' . $usuario['apellido2'] ?></p>
<p><strong>Correo:</strong> <?= $usuario['correo'] ?></p>
<p><strong>Teléfono:</strong> <?= $usuario['telefono'] ?></p>
<p><strong>Rol:</strong> <?= ucfirst($usuario['rol']) ?></p>
<p><strong>Suscripción:</strong> <?= $usuario['suscrito'] ? 'Activa hasta ' . $usuario['fecha_fin_suscripcion'] : 'No suscrito' ?></p>
<?php if ($usuario['rol'] === 'usuario' && $usuario['nombre_entrenador']): ?>
    <p><strong>Entrenador asignado:</strong> <?= $usuario['nombre_entrenador'] ?></p>
<?php endif; ?>
<a href="logout.php">Cerrar sesión</a>
