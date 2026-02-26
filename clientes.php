<?php
include 'seguridad.php';
verificarRoles(['entrenador']);
include 'conexion.php';

$id_entrenador = $_SESSION['id_usuario'];

// Obtener lista de clientes asignados a este entrenador
$sql = "SELECT id_usuario, nombre FROM usuarios WHERE id_entrenador = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_entrenador);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$clientes = [];
while ($row = mysqli_fetch_assoc($res)) {
    $clientes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Clientes</title>
</head>
<body>
  <h1>Bienvenido, entrenador</h1>
  <h2>Selecciona un cliente para iniciar sesión privada:</h2>
  <ul>
    <?php foreach ($clientes as $cliente): ?>
      <li>
        <?= htmlspecialchars($cliente['nombre']) ?>
        — <a href="index.php?id_usuario=<?= $cliente['id_usuario'] ?>">Iniciar sesión</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
