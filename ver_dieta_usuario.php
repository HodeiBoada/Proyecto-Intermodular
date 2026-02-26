<?php
include 'seguridad.php';
verificarRol('usuario');
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT d.nombre AS nombre_dieta, d.descripcion, c.nombre AS nombre_comida, c.descripcion AS descripcion_comida, 
               dc.dia_semana, dc.momento, dc.orden_momento
        FROM usuarios u
        JOIN dietas d ON u.id_dieta_activa = d.id_dieta
        JOIN dieta_comida dc ON d.id_dieta = dc.id_dieta
        JOIN comidas c ON dc.id_comida = c.id_comida
        WHERE u.id_usuario = ?
        ORDER BY 
            FIELD(dc.dia_semana, 'lunes','martes','miércoles','jueves','viernes','sábado','domingo'),
            FIELD(dc.momento, 'mañana','mediodía','tarde','noche'),
            dc.orden_momento";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    $primera = true;
    $dia_actual = '';
    echo "<h2>Dieta Activa</h2>";
    while ($fila = mysqli_fetch_assoc($resultado)) {
        if ($primera) {
            echo "<p><strong>Nombre:</strong> " . htmlspecialchars($fila['nombre_dieta']) . "</p>";
            echo "<p><strong>Descripción:</strong> " . htmlspecialchars($fila['descripcion']) . "</p>";
            $primera = false;
        }
        if ($dia_actual !== $fila['dia_semana']) {
            $dia_actual = $fila['dia_semana'];
            echo "<h3>" . ucfirst($dia_actual) . "</h3>";
        }
        echo "<p><strong>" . ucfirst($fila['momento']) . ":</strong> " . htmlspecialchars($fila['nombre_comida']) . " - " . htmlspecialchars($fila['descripcion_comida']) . "</p>";
    }
} else {
    echo "<p>No tienes una dieta activa asignada.</p>";
}
?>
<a href="menu_usuario.php">Volver al menú</a>
